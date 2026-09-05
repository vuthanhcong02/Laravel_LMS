window.toneToUnicode = function (pinyin) {
    if (!pinyin) return '';
    const toneMap = {
        'a': ['ā', 'á', 'ǎ', 'à'], 'A': ['Ā', 'Á', 'Ǎ', 'À'],
        'e': ['ē', 'é', 'ě', 'è'], 'E': ['Ē', 'É', 'Ě', 'È'],
        'i': ['ī', 'í', 'ǐ', 'ì'], 'I': ['Ī', 'Í', 'Ǐ', 'Ì'],
        'o': ['ō', 'ó', 'ǒ', 'ò'], 'O': ['Ō', 'Ó', 'Ǒ', 'Ò'],
        'u': ['ū', 'ú', 'ǔ', 'ù'], 'U': ['Ū', 'Ú', 'Ǔ', 'Ù'],
        'ü': ['ǖ', 'ǘ', 'ǚ', 'ǜ'], 'Ü': ['Ǖ', 'Ǘ', 'Ǚ', 'Ǜ'],
    };

    let str = String(pinyin).trim();
    str = str.replace(/uue/gi, 'üe').replace(/uun/gi, 'ün').replace(/uu/gi, 'ü');
    str = str.replace(/v/g, 'ü').replace(/V/g, 'Ü');

    const match = str.match(/^(.*?)([1-5])$/);
    if (!match) {
        return str;
    }

    const base = match[1];
    const toneNum = parseInt(match[2], 10) - 1;
    if (toneNum < 0 || toneNum > 3) return base;

    const lowerBase = base.toLowerCase();
    
    // 1. Nếu có nguyên âm 'a', đánh dấu trên 'a'
    let idx = lowerBase.indexOf('a');
    if (idx !== -1) {
        const char = base[idx];
        return base.substring(0, idx) + toneMap[char][toneNum] + base.substring(idx + 1);
    }

    // 2. Nếu có nguyên âm 'e', đánh dấu trên 'e'
    idx = lowerBase.indexOf('e');
    if (idx !== -1) {
        const char = base[idx];
        return base.substring(0, idx) + toneMap[char][toneNum] + base.substring(idx + 1);
    }

    // 3. Nếu có cụm 'ou', đánh dấu trên 'o'
    idx = lowerBase.indexOf('ou');
    if (idx !== -1) {
        const char = base[idx];
        return base.substring(0, idx) + toneMap[char][toneNum] + base.substring(idx + 1);
    }

    // 4. Các trường hợp còn lại (ui, iu, ü...), đánh dấu trên nguyên âm xuất hiện sau cùng
    const vowels = ['a', 'e', 'i', 'o', 'u', 'ü'];
    let lastVowelIdx = -1;
    for (let i = 0; i < base.length; i++) {
        if (vowels.includes(lowerBase[i])) {
            lastVowelIdx = i;
        }
    }

    if (lastVowelIdx !== -1) {
        const char = base[lastVowelIdx];
        if (toneMap[char]) {
            return base.substring(0, lastVowelIdx) + toneMap[char][toneNum] + base.substring(lastVowelIdx + 1);
        }
    }

    return base;
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
