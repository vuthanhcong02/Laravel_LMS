window.toneToUnicode = function (pinyin) {
    if (!pinyin) return pinyin;
    const toneMap = {
        'a': ['ā', 'á', 'ǎ', 'à'],
        'e': ['ē', 'é', 'ě', 'è'],
        'i': ['ī', 'í', 'ǐ', 'ì'],
        'o': ['ō', 'ó', 'ǒ', 'ò'],
        'u': ['ū', 'ú', 'ǔ', 'ù'],
        'ü': ['ǖ', 'ǘ', 'ǚ', 'ǜ'],
        'v': ['ǖ', 'ǘ', 'ǚ', 'ǜ'],
    };
    const match = pinyin.match(/^(.*?)([1-4])$/);
    if (!match) return pinyin;
    const base = match[1];
    const toneNum = parseInt(match[2]) - 1;
    const priority = ['a', 'e', 'ou', 'o', 'u', 'i', 'ü', 'v'];
    for (let vowel of priority) {
        if (vowel === 'ou' && base.includes('ou')) {
            return base.replace('o', toneMap['o'][toneNum]) + '';
        }
        if (base.includes(vowel) && toneMap[vowel]) {
            return base.replace(vowel, toneMap[vowel][toneNum]);
        }
    }
    return pinyin;
};

// Global Audio Player Logic for XIAOMU LMS
let currentGlobalAudio = null;
let _globalSpeechTimer = null;

window.playWordAudio = function (word) {
    if (!word) return;
    let textToSpeak = String(word).trim();
    if ('speechSynthesis' in window) {
        let synth = window.speechSynthesis;
        if (synth.paused) {
            synth.resume();
        }
        synth.cancel();

        if (_globalSpeechTimer) {
            clearTimeout(_globalSpeechTimer);
        }

        _globalSpeechTimer = setTimeout(function () {
            if (synth.paused) {
                synth.resume();
            }

            let utterance = new SpeechSynthesisUtterance(textToSpeak);
            utterance.lang = 'zh-CN';
            utterance.rate = 0.85;

            let setVoiceAndSpeak = function () {
                let voices = synth.getVoices();
                let zhVoice = voices.find(v => v.lang === 'zh-CN' || v.lang === 'zh_CN' || v.lang
                    .startsWith('zh') || v.lang.startsWith('cmn'));
                if (zhVoice) utterance.voice = zhVoice;
                window._globalActiveUtterance = utterance;
                synth.speak(utterance);
            };

            if (synth.getVoices().length > 0) {
                setVoiceAndSpeak();
            } else {
                synth.onvoiceschanged = setVoiceAndSpeak;
                window._globalActiveUtterance = utterance;
                synth.speak(utterance);
            }
        }, 60);
    }
};

window.playAudio = function (urlOrText) {
    if (!urlOrText) return;

    let val = String(urlOrText).trim();
    // Check if it's a URL or audio path
    if (val.startsWith('http://') || val.startsWith('https://') || val.startsWith('/') || val.startsWith(
        'storage/') || val.startsWith('audio/')) {
        let src = val;
        if (src.startsWith('audio/')) {
            src = '/storage/hsk_media/' + src;
        } else if (src.startsWith('storage/')) {
            src = '/' + src;
        }

        if (currentGlobalAudio) {
            currentGlobalAudio.pause();
            currentGlobalAudio.currentTime = 0;
        }

        currentGlobalAudio = new Audio(src);

        let fallback = function () {
            try {
                let urlObj = new URL(src, window.location.origin);
                let word = urlObj.searchParams.get('audio') || urlObj.searchParams.get('text');
                if (word) {
                    window.playWordAudio(decodeURIComponent(word));
                    return;
                }
            } catch (err) { }
        };

        currentGlobalAudio.onerror = fallback;
        let playPromise = currentGlobalAudio.play();
        if (playPromise !== undefined) {
            playPromise.catch(function (e) {
                console.warn('Audio playback error, falling back:', e);
                fallback();
            });
        }
    } else {
        // Play text directly via SpeechSynthesis
        window.playWordAudio(val);
    }
};
