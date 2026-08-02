@extends('layouts.app')

@section('title', 'Flashcard Từ Vựng HSK 1 - HSK 9')
@section('breadcrumb', 'Flashcard HSK 1 - HSK 9')
@section('breadcrumb_desc', 'Luyện nhớ chữ Hán, phiên âm Pinyin và câu ví dụ thông qua hệ thống thẻ ghi nhớ 3D thông
    minh.')

@section('content')
    <script>
        window.isLoggedIn = @json(auth()->check());
        window.hskVocabularies = @json($vocabularies);
        window.hskTopics = @json($topics ?? []);

        document.addEventListener('alpine:init', () => {
            Alpine.data('flashcardComponent', () => ({
                vocabularies: window.hskVocabularies || {},
                topics: window.hskTopics || {},
                studyMode: 'level',
                activeLevel: 1,
                activeTopic: Object.keys(window.hskTopics || {})[0] || '',
                currentIndex: 0,
                flipped: false,
                autoplayAudio: false,
                isShuffled: false,
                isShuffling: false,
                shuffledWordsList: [],
                levels: [1, 2, 3, 4, 5, 6, 7, 8, 9],
                rememberedWords: JSON.parse(localStorage.getItem('remembered_hsk_words') || '[]'),
                isLeaving: false,
                isFilterDrawerOpen: false,

                currentWords() {
                    let allWords = [];
                    if (this.studyMode === 'level') {
                        allWords = this.vocabularies[this.activeLevel] || [];
                    } else {
                        allWords = this.topics[this.activeTopic] || [];
                    }
                    let unremembered = allWords.filter(w => !this.rememberedWords.includes(w.word));
                    if (this.isShuffled) {
                        return this.shuffledWordsList.filter(w => !this.rememberedWords.includes(w
                            .word));
                    }
                    return unremembered;
                },
                currentWord() {
                    return this.currentWords()[this.currentIndex] || {};
                },
                getLevelLabel(level) {
                    if (level <= 3) return 'Sơ cấp';
                    if (level <= 6) return 'Trung cấp';
                    return 'Cao cấp';
                },
                getLevelDesc(level) {
                    const descs = {
                        1: 'Nhập môn cơ bản',
                        2: 'Từ vựng phổ thông',
                        3: 'Giao tiếp đời sống',
                        4: 'Giao tiếp xã hội',
                        5: 'Báo chí & Đọc hiểu',
                        6: 'Thảo luận chuyên sâu',
                        7: 'Biên dịch học thuật',
                        8: 'Phân tích vĩ mô',
                        9: 'Học thuật cao cấp'
                    };
                    return descs[level] || '';
                },
                getTopicIcon(topic) {
                    const icons = {
                        'Gia đình': 'family_restroom',
                        'Ăn uống': 'restaurant',
                        'Du lịch & Địa điểm': 'explore',
                        'Thời gian & Thời tiết': 'wb_sunny',
                        'Học tập & Công việc': 'school',
                        'Giao tiếp hàng ngày': 'forum'
                    };
                    return icons[topic] || 'category';
                },
                flipCard() {
                    if (this.currentWords().length === 0) return;
                    this.flipped = !this.flipped;
                },
                shuffle() {
                    this.flipped = false;
                    if (this.isShuffled) {
                        this.isShuffled = false;
                        this.shuffledWordsList = [];
                        this.currentIndex = 0;
                    } else {
                        let words = [];
                        if (this.studyMode === 'level') {
                            words = (this.vocabularies[this.activeLevel] || []).filter(w => !this
                                .rememberedWords.includes(w.word));
                        } else {
                            words = (this.topics[this.activeTopic] || []).filter(w => !this
                                .rememberedWords.includes(w.word));
                        }
                        if (words.length <= 1) return;

                        this.isShuffling = true;
                        setTimeout(() => {
                            for (let i = words.length - 1; i > 0; i--) {
                                const j = Math.floor(Math.random() * (i + 1));
                                [words[i], words[j]] = [words[j], words[i]];
                            }
                            this.shuffledWordsList = words;
                            this.isShuffled = true;
                            this.currentIndex = 0;
                            this.isShuffling = false;
                            if (this.autoplayAudio && this.currentWords().length > 0) {
                                setTimeout(() => {
                                    this.speak();
                                }, 300);
                            }
                        }, 200);
                    }
                },
                nextWord() {
                    if (this.currentWords().length === 0) return;
                    this.flipped = false;
                    setTimeout(() => {
                        this.currentIndex = (this.currentIndex + 1) % this.currentWords()
                        .length;
                        if (this.autoplayAudio) {
                            setTimeout(() => {
                                this.speak();
                            }, 300);
                        }
                    }, 150);
                },
                prevWord() {
                    if (this.currentWords().length === 0) return;
                    this.flipped = false;
                    setTimeout(() => {
                        this.currentIndex = (this.currentIndex - 1 + this.currentWords()
                            .length) % this.currentWords().length;
                        if (this.autoplayAudio) {
                            setTimeout(() => {
                                this.speak();
                            }, 300);
                        }
                    }, 150);
                },
                changeLevel(level) {
                    this.studyMode = 'level';
                    this.activeLevel = level;
                    this.currentIndex = 0;
                    this.flipped = false;
                    this.isShuffled = false;
                    this.shuffledWordsList = [];
                    if (this.autoplayAudio && this.currentWords().length > 0) {
                        setTimeout(() => {
                            this.speak();
                        }, 400);
                    }
                },
                changeTopic(topic) {
                    this.studyMode = 'topic';
                    this.activeTopic = topic;
                    this.currentIndex = 0;
                    this.flipped = false;
                    this.isShuffled = false;
                    this.shuffledWordsList = [];
                    if (this.autoplayAudio && this.currentWords().length > 0) {
                        setTimeout(() => {
                            this.speak();
                        }, 400);
                    }
                },
                markAsRemembered(word, id) {
                    if (this.isLeaving) return;
                    if (!this.rememberedWords.includes(word)) {
                        this.isLeaving = true;

                        // Wait for transition swipe out to complete (250ms)
                        setTimeout(() => {
                            this.rememberedWords.push(word);

                            // Save to localStorage only if user is not logged in (Guest)
                            if (!window.isLoggedIn) {
                                localStorage.setItem('remembered_hsk_words', JSON.stringify(this
                                    .rememberedWords));
                            } else {
                                // If logged in, call API to save to database
                                fetch('/flashcards/remember', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector(
                                                'meta[name="csrf-token"]').getAttribute(
                                                'content')
                                        },
                                        body: JSON.stringify({
                                            vocabulary_id: id
                                        })
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (!data.success) {
                                            console.error('API Error:', data.message);
                                        }
                                    })
                                    .catch(error => console.error('Connection Error:', error));
                            }

                            this.flipped = false;
                            this.isLeaving = false;

                            // Autoplay audio for next word if enabled
                            setTimeout(() => {
                                if (this.autoplayAudio && this.currentWords().length >
                                    0) {
                                    this.speak();
                                }
                            }, 150);
                        }, 250);
                    }
                },
                speak() {
                    if (this.currentWords().length === 0) return;
                    if ('speechSynthesis' in window) {
                        window.speechSynthesis.cancel();
                        let word = this.currentWord().word;
                        let utterance = new SpeechSynthesisUtterance(word);
                        utterance.lang = 'zh-CN';
                        let voices = window.speechSynthesis.getVoices();
                        let zhVoice = voices.find(voice => voice.lang.includes('zh') || voice.lang
                            .includes('ZH'));
                        if (zhVoice) {
                            utterance.voice = zhVoice;
                        }
                        utterance.rate = 0.85;
                        window.speechSynthesis.speak(utterance);
                    }
                },
                getProgressPercentage() {
                    let allWords = [];
                    if (this.studyMode === 'level') {
                        allWords = this.vocabularies[this.activeLevel] || [];
                    } else {
                        allWords = this.topics[this.activeTopic] || [];
                    }
                    if (allWords.length === 0) return 0;

                    // Count words marked as remembered within the active filter scope
                    let wordStrings = allWords.map(w => w.word);
                    let rememberedInCurrent = this.rememberedWords.filter(w => wordStrings.includes(w))
                        .length;

                    return Math.round((rememberedInCurrent / allWords.length) * 100);
                },
                init() {
                    window.addEventListener('keydown', (e) => {
                        let section = document.getElementById('flashcard-section');
                        if (!section) return;
                        let rect = section.getBoundingClientRect();
                        let isVisible = (rect.top < window.innerHeight && rect.bottom >= 0);
                        if (!isVisible) return;

                        if (e.code === 'Space') {
                            e.preventDefault();
                            this.flipCard();
                        } else if (e.code === 'ArrowRight') {
                            e.preventDefault();
                            this.nextWord();
                        } else if (e.code === 'ArrowLeft') {
                            e.preventDefault();
                            this.prevWord();
                        }
                    });
                }
            }));
        });
    </script>

    <!-- Main Workspace Section -->
    <section id="flashcard-section" class="pt-8 lg:pt-12 pb-24 bg-transparent" x-data="flashcardComponent()">
        <div class="mx-auto max-w-7xl px-6">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">

                <!-- LEFT COLUMN: Sidebar Filter HSK (1/4 Width) - Sticky on desktop, hidden on mobile -->
                <div
                    class="hidden lg:flex lg:col-span-1 bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/50 dark:border-slate-700/60 shadow-xl shadow-slate-100/30 dark:shadow-none p-5 relative z-10 flex-col gap-6">

                    <!-- Section 1: HSK Levels -->
                    <div>
                        <h4
                            class="text-xs font-black uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-3.5 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px]">layers</span>
                            <span>Cấp Độ HSK</span>
                        </h4>
                        <div
                            class="flex lg:grid lg:grid-cols-3 gap-2 overflow-x-auto lg:overflow-x-visible no-scrollbar pb-2 lg:pb-0">
                            <template x-for="level in levels" :key="level">
                                <button @click="changeLevel(level)"
                                    class="w-14 h-11 flex-shrink-0 lg:w-full lg:flex-shrink-1 flex flex-col items-center justify-center rounded-2xl text-sm font-black transition-all duration-300 border hover:-translate-y-0.5 hover:shadow-sm active:translate-y-0 active:scale-[0.95]"
                                    :class="studyMode === 'level' && activeLevel === level ?
                                        'bg-gradient-to-br from-primary to-primary/90 text-white border-primary shadow-md shadow-primary/20 scale-[1.03]' :
                                        'bg-slate-50 dark:bg-slate-900 text-slate-700 dark:text-slate-350 border-slate-100 dark:border-slate-800 hover:border-primary/45 hover:bg-primary/5'">
                                    <span x-text="level"></span>
                                    <span class="text-[8px] font-medium leading-none opacity-80 mt-0.5"
                                        x-text="'HSK ' + level"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Elegant Divider Line -->
                    <div class="h-[1px] bg-slate-100 dark:bg-slate-700/60"></div>

                    <!-- Section 2: Topics -->
                    <div>
                        <h4
                            class="text-xs font-black uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-3.5 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px]">widgets</span>
                            <span>Chủ Đề Học Tập</span>
                        </h4>
                        <div
                            class="flex lg:flex-col gap-2.5 overflow-x-auto lg:overflow-y-auto lg:max-h-[350px] no-scrollbar pb-2 lg:pb-0 pr-1">
                            <template x-for="(words, topicName) in topics" :key="topicName">
                                <button @click="changeTopic(topicName)"
                                    class="w-56 lg:w-full flex-shrink-0 lg:flex-shrink-1 flex items-center justify-between gap-3 px-3.5 py-3 rounded-2xl text-xs font-bold transition-all duration-300 border hover:-translate-y-0.5 hover:shadow-md active:translate-y-0 active:scale-[0.99]"
                                    :class="studyMode === 'topic' && activeTopic === topicName ?
                                        'bg-gradient-to-r from-primary to-primary/95 text-white border-primary shadow-lg shadow-primary/20' :
                                        'bg-transparent text-slate-700 dark:text-slate-350 border-slate-100 dark:border-slate-800/80 hover:border-primary/30 hover:bg-primary/5'">
                                    <div class="flex items-center gap-3">
                                        <!-- Dynamic Circular Badge for Topics -->
                                        <div class="flex h-7 w-7 items-center justify-center rounded-full text-xs shadow-sm transition-colors duration-300"
                                            :class="studyMode === 'topic' && activeTopic === topicName ?
                                                'bg-white text-primary' :
                                                'bg-primary/5 dark:bg-primary/10 text-primary'">
                                            <span class="material-symbols-outlined text-[16px] font-bold"
                                                x-text="getTopicIcon(topicName)"></span>
                                        </div>
                                        <div class="text-left">
                                            <p class="font-bold text-xs tracking-wide"
                                                :class="studyMode === 'topic' && activeTopic === topicName ? 'text-white' :
                                                    'text-slate-800 dark:text-white'"
                                                x-text="topicName"></p>
                                            <p class="text-[9px] font-medium leading-none mt-0.5 opacity-80"
                                                :class="studyMode === 'topic' && activeTopic === topicName ?
                                                    'text-white/80' : 'text-slate-400 dark:text-slate-500'"
                                                x-text="words.length + ' từ vựng'"></p>
                                        </div>
                                    </div>
                                    <span class="material-symbols-outlined text-[14px] transition-transform duration-300"
                                        :class="studyMode === 'topic' && activeTopic === topicName ?
                                            'text-white translate-x-0.5' : 'text-slate-400 dark:text-slate-500'">chevron_right</span>
                                </button>
                            </template>
                        </div>
                    </div>

                </div>

                <!-- RIGHT COLUMN: Flashcard Panel (3/4 Width) -->
                <div class="col-span-1 lg:col-span-3 flex flex-col items-center justify-center gap-4 relative z-10 w-full">

                    <!-- Mobile Filter Trigger Button (Visible only on mobile/tablet) -->
                    <div
                        class="w-full max-w-3xl flex lg:hidden justify-between items-center bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/60 px-5 py-3 rounded-2xl shadow-sm mb-1">
                        <span class="flex items-center gap-1.5">
                            <span class="h-2 w-2 rounded-full bg-primary animate-ping"></span>
                            <span class="font-extrabold text-xs text-primary"
                                x-text="studyMode === 'level' ? 'HSK ' + activeLevel : activeTopic"></span>
                        </span>
                        <button @click="isFilterDrawerOpen = true"
                            class="flex items-center gap-2 px-3 py-1.5 bg-primary/5 hover:bg-primary/10 border border-primary/20 rounded-xl text-xs font-black text-primary transition-all active:scale-95">
                            <span class="material-symbols-outlined text-[16px] font-bold">tune</span>
                            <span>Chọn Cấp độ / Chủ đề</span>
                        </button>
                    </div>

                    <!-- If NOT completed current words -->
                    <template x-if="currentWords().length > 0">
                        <div class="w-full flex flex-col items-center justify-center gap-4">
                            <!-- Progress Info & Settings Bar -->
                            <div
                                class="w-full max-w-3xl flex flex-wrap gap-4 justify-between items-center bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700/50 px-5 py-2.5 rounded-2xl shadow-sm text-xs text-slate-500 dark:text-slate-400">
                                <!-- Keyboard hint - Hidden on mobile/tablet devices -->
                                <span class="hidden md:flex items-center gap-2">
                                    <span class="material-symbols-outlined text-primary text-[18px]">keyboard</span>
                                    <span>Nhấn <kbd
                                            class="px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-sans font-bold">Space</kbd>
                                        để lật, phím <kbd
                                            class="px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-sans font-bold">←</kbd>
                                        / <kbd
                                            class="px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-sans font-bold">→</kbd>
                                        để chuyển từ.</span>
                                </span>
                                <div class="flex items-center gap-4 w-full md:w-auto justify-between md:justify-end">
                                    <!-- Shuffle Button -->
                                    <button @click="shuffle()"
                                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl border transition-all duration-300 font-bold hover:scale-[1.03] active:scale-[0.97]"
                                        :class="isShuffled
                                            ?
                                            'bg-primary/10 border-primary/30 text-primary dark:bg-primary/20' :
                                            'bg-slate-50 border-slate-100 hover:bg-slate-100 hover:border-slate-200 text-slate-500 dark:bg-slate-900 dark:border-slate-800 dark:hover:bg-slate-850'"
                                        title="Trộn ngẫu nhiên thứ tự các thẻ học">
                                        <span class="material-symbols-outlined text-[16px]">shuffle</span>
                                        <span
                                            x-text="isShuffling ? 'Đang trộn...' : (isShuffled ? 'Bỏ trộn' : 'Trộn thẻ')"></span>
                                    </button>

                                    <!-- Autoplay Switcher -->
                                    <div
                                        class="flex items-center gap-2 border-l border-r border-slate-200 dark:border-slate-700 px-4">
                                        <span
                                            class="text-xs font-bold text-slate-500 dark:text-slate-400 flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[16px]">volume_up</span>
                                            Tự động phát âm
                                        </span>
                                        <button @click="autoplayAudio = !autoplayAudio"
                                            class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                            :class="autoplayAudio ? 'bg-primary' : 'bg-slate-200 dark:bg-slate-700'">
                                            <span
                                                class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                                :class="autoplayAudio ? 'translate-x-4' : 'translate-x-0'"></span>
                                        </button>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="h-2 w-2 rounded-full bg-primary animate-ping"></span>
                                        <span class="font-extrabold text-primary"
                                            x-text="studyMode === 'level' ? 'Học thử HSK ' + activeLevel : 'Chủ đề: ' + activeTopic"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Progress Bar (Visual representation of memorization status) -->
                            <div class="w-full max-w-3xl flex flex-col gap-1.5 mb-1 px-1">
                                <div
                                    class="flex justify-between items-center text-[9px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                    <span>Tiến độ ghi nhớ</span>
                                    <span class="text-primary font-black" x-text="getProgressPercentage() + '%'"></span>
                                </div>
                                <div
                                    class="w-full h-1.5 bg-slate-100 dark:bg-slate-900 border border-slate-200/10 dark:border-slate-800/80 rounded-full overflow-hidden shadow-inner">
                                    <div class="h-full bg-gradient-to-r from-primary via-primary/95 to-primary/80 rounded-full transition-all duration-500 ease-out"
                                        :style="'width: ' + getProgressPercentage() + '%'"></div>
                                </div>
                            </div>

                            <!-- 3D Card Container (Responsive height and premium animations) -->
                            <div class="w-full max-w-3xl h-[380px] md:h-[420px] perspective">
                                <div class="w-full h-full preserve-3d transition-all duration-300 relative cursor-pointer"
                                    :class="{
                                        'rotate-y-180': flipped,
                                        'translate-x-full opacity-0 scale-95 rotate-6 pointer-events-none': isLeaving
                                    }"
                                    @click="flipCard()">
                                    <!-- CARD FRONT (Chinese characters - Pure & Clean Focus) -->
                                    <div
                                        class="backface-hidden w-full h-full bg-white/95 dark:bg-slate-800/95 backdrop-blur-md rounded-[32px] border border-primary/10 dark:border-slate-700/60 shadow-xl hover:shadow-2xl hover:shadow-primary/5 hover:border-primary/50 dark:hover:border-primary/40 transition-all duration-300 flex flex-col justify-between p-6 absolute top-0 left-0">
                                        <!-- Card Header Info -->
                                        <div class="flex justify-between items-center w-full">
                                            <!-- Mark Learned Button (Premium Minimalist Check) -->
                                            <div class="relative group">
                                                <button @click.stop="markAsRemembered(currentWord().word, currentWord().id)"
                                                    class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-50 hover:bg-green-50 dark:bg-slate-900 dark:hover:bg-green-950/30 text-slate-400 hover:text-green-600 dark:text-slate-500 dark:hover:text-green-400 border border-slate-100/80 dark:border-slate-700/80 hover:border-green-200 dark:hover:border-green-900/50 shadow-sm transition-all duration-300">
                                                    <span
                                                        class="material-symbols-outlined text-[16px] font-bold">check</span>
                                                </button>
                                                <!-- Custom Tooltip -->
                                                <div
                                                    class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-48 scale-0 opacity-0 group-hover:scale-100 group-hover:opacity-100 transition-all duration-200 origin-bottom pointer-events-none z-50">
                                                    <div
                                                        class="bg-slate-900 dark:bg-slate-750 text-white text-[10px] font-bold py-1.5 px-3 rounded-lg shadow-md text-center leading-normal">
                                                        Đánh dấu đã thuộc (từ này sẽ ẩn và không xuất hiện lại nữa)
                                                    </div>
                                                    <div
                                                        class="w-2 h-2 bg-slate-900 dark:bg-slate-750 rotate-45 absolute top-full left-1/2 -translate-x-1/2 -translate-y-1/2">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="px-3 py-0.5 rounded-full bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-slate-400 dark:text-slate-500 text-xs font-bold"
                                                x-text="(currentIndex + 1) + ' / ' + currentWords().length"></div>
                                        </div>

                                        <!-- Main Word -->
                                        <div class="flex flex-col items-center justify-center my-auto">
                                            <h3 class="text-7xl lg:text-8xl font-black text-slate-800 dark:text-white tracking-wide"
                                                x-text="currentWord().word"></h3>
                                        </div>

                                        <!-- Bottom indicator hints -->
                                        <div
                                            class="flex items-center justify-center gap-1.5 text-xs text-slate-400 dark:text-slate-500">
                                            <span class="material-symbols-outlined text-[16px] animate-pulse">flip</span>
                                            <span>Nhấp vào thẻ để lật xem ý nghĩa</span>
                                        </div>
                                    </div>

                                    <!-- CARD BACK (Pinyin & Definition & Example - With smart scroll area) -->
                                    <div
                                        class="backface-hidden rotate-y-180 w-full h-full bg-white/95 dark:bg-slate-800/95 backdrop-blur-md rounded-[32px] border border-primary/10 dark:border-slate-700/60 shadow-xl hover:shadow-2xl hover:shadow-primary/5 hover:border-primary/50 dark:hover:border-primary/40 transition-all duration-300 flex flex-col justify-between p-6 absolute top-0 left-0">
                                        <!-- Card Header Info -->
                                        <div class="flex justify-between items-center w-full">
                                            <!-- Mark Learned Button (Premium Minimalist Check) -->
                                            <div class="relative group">
                                                <button @click.stop="markAsRemembered(currentWord().word, currentWord().id)"
                                                    class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-50 hover:bg-green-50 dark:bg-slate-900 dark:hover:bg-green-950/30 text-slate-400 hover:text-green-600 dark:text-slate-500 dark:hover:text-green-400 border border-slate-100/80 dark:border-slate-700/80 hover:border-green-200 dark:hover:border-green-900/50 shadow-sm transition-all duration-300">
                                                    <span
                                                        class="material-symbols-outlined text-[16px] font-bold">check</span>
                                                </button>
                                                <!-- Custom Tooltip -->
                                                <div
                                                    class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-48 scale-0 opacity-0 group-hover:scale-100 group-hover:opacity-100 transition-all duration-200 origin-bottom pointer-events-none z-50">
                                                    <div
                                                        class="bg-slate-900 dark:bg-slate-750 text-white text-[10px] font-bold py-1.5 px-3 rounded-lg shadow-md text-center leading-normal">
                                                        Đánh dấu đã thuộc (từ này sẽ ẩn và không xuất hiện lại nữa)
                                                    </div>
                                                    <div
                                                        class="w-2 h-2 bg-slate-900 dark:bg-slate-750 rotate-45 absolute top-full left-1/2 -translate-x-1/2 -translate-y-1/2">
                                                    </div>
                                                </div>
                                            </div>

                                            <button @click.stop="speak()"
                                                class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-50 hover:bg-primary/10 dark:bg-slate-900 dark:hover:bg-primary/20 text-slate-400 hover:text-primary dark:text-slate-500 dark:hover:text-primary border border-slate-100/80 dark:border-slate-700/80 hover:border-primary/30 shadow-sm transition-all duration-300"
                                                title="Phát âm từ này">
                                                <span
                                                    class="material-symbols-outlined text-[16px] font-bold">volume_up</span>
                                            </button>
                                        </div>

                                        <!-- Word Details & Scrollable Example Section -->
                                        <div
                                            class="flex flex-col gap-4 my-auto text-left w-full overflow-y-auto max-h-[240px] md:max-h-[260px] pr-1.5 no-scrollbar">
                                            <div class="flex items-baseline gap-3">
                                                <h4 class="text-2xl md:text-3xl font-extrabold text-primary"
                                                    x-text="currentWord().word"></h4>
                                                <p class="text-base md:text-lg font-bold text-slate-400 dark:text-slate-500 tracking-wide"
                                                    x-text="'[' + currentWord().pinyin + ']'"></p>
                                            </div>

                                            <!-- Meaning -->
                                            <div
                                                class="bg-primary/10 dark:bg-primary/20 px-4 py-2.5 rounded-2xl border-l-4 border-primary shadow-sm">
                                                <p class="text-sm md:text-base font-bold text-slate-800 dark:text-white"
                                                    x-text="currentWord().meaning"></p>
                                            </div>

                                            <!-- Example Section -->
                                            <template x-if="currentWord().example">
                                                <div
                                                    class="bg-slate-50/50 dark:bg-slate-900/40 p-4 rounded-2xl border border-slate-100/85 dark:border-slate-800/80 flex flex-col gap-3">
                                                    <div class="flex items-center justify-between mb-0.5">
                                                        <p
                                                            class="text-[9px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                                                            Ví dụ minh họa:</p>
                                                        <button
                                                            @click.stop="
                                                                if ('speechSynthesis' in window) {
                                                                    window.speechSynthesis.cancel();
                                                                    let utterance = new SpeechSynthesisUtterance(currentWord().example);
                                                                    utterance.lang = 'zh-CN';
                                                                    let voices = window.speechSynthesis.getVoices();
                                                                    let zhVoice = voices.find(v => v.lang.includes('zh') || v.lang.includes('ZH'));
                                                                    if (zhVoice) utterance.voice = zhVoice;
                                                                    utterance.rate = 0.8;
                                                                    window.speechSynthesis.speak(utterance);
                                                                }
                                                            "
                                                            class="flex h-5 w-5 items-center justify-center rounded bg-white dark:bg-slate-800 text-slate-400 hover:text-primary border border-slate-100 dark:border-slate-700/80 transition-all shadow-sm active:scale-90"
                                                            title="Phát âm câu ví dụ">
                                                            <span
                                                                class="material-symbols-outlined text-[12px]">volume_up</span>
                                                        </button>
                                                    </div>
                                                    <template x-for="(ex, index) in currentWord().example.split('\n')"
                                                        :key="index">
                                                        <div
                                                            class="flex flex-col gap-1 border-b last:border-b-0 border-slate-100/80 dark:border-slate-800/60 pb-2 last:pb-0">
                                                            <p class="text-xs md:text-sm font-bold text-slate-800 dark:text-white tracking-wide"
                                                                x-text="(index + 1) + '. ' + ex"></p>
                                                            <p class="text-[11px] md:text-xs text-slate-500 dark:text-slate-400"
                                                                x-text="currentWord().example_meaning.split('\n')[index] || ''">
                                                            </p>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>

                                        <!-- Footer Flipback action -->
                                        <div
                                            class="flex justify-center w-full border-t border-slate-100 dark:border-slate-700/50 pt-3">
                                            <span
                                                class="flex items-center gap-1 text-[10px] md:text-[11px] text-slate-400">
                                                <span class="material-symbols-outlined text-[14px]">reply</span>
                                                Click để quay lại mặt trước
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Navigation Controls (Optimized size and micro-interactions) -->
                            <div class="flex items-center justify-center gap-4 w-full max-w-2xl mt-1">
                                <!-- Prev button -->
                                <button @click="prevWord()"
                                    class="flex h-11 w-11 items-center justify-center rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-350 hover:border-primary hover:text-primary hover:scale-105 active:scale-95 hover:-translate-x-1 transition-all duration-200 shadow-sm"
                                    title="Từ trước đó (Phím ←)">
                                    <span class="material-symbols-outlined text-xl">chevron_left</span>
                                </button>

                                <!-- Play Audio Button -->
                                <button @click="speak()"
                                    class="flex-1 max-w-[200px] h-11 flex items-center justify-center gap-2 rounded-2xl bg-primary text-white hover:bg-primary/95 hover:scale-105 active:scale-95 transition-all duration-200 shadow-md shadow-primary/20 font-bold text-xs md:text-sm">
                                    <span class="material-symbols-outlined text-lg">volume_up</span>
                                    Phát âm Hán ngữ
                                </button>

                                <!-- Next button -->
                                <button @click="nextWord()"
                                    class="flex h-11 w-11 items-center justify-center rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-350 hover:border-primary hover:text-primary hover:scale-105 active:scale-95 hover:translate-x-1 transition-all duration-200 shadow-sm"
                                    title="Từ tiếp theo (Phím →)">
                                    <span class="material-symbols-outlined text-xl">chevron_right</span>
                                </button>
                            </div>
                        </div>
                    </template>

                    <!-- If ALL words are completed -->
                    <template x-if="currentWords().length === 0">
                        <div
                            class="w-full max-w-2xl bg-white dark:bg-slate-800 rounded-[32px] border border-slate-200/50 dark:border-slate-700/60 shadow-lg p-12 text-center flex flex-col items-center justify-center gap-6 relative z-10 my-12">
                            <div
                                class="h-20 w-20 bg-yellow-100 dark:bg-yellow-500/10 rounded-full flex items-center justify-center text-yellow-500 animate-bounce">
                                <span class="material-symbols-outlined text-4xl">workspace_premium</span>
                            </div>
                            <div>
                                <h3 class="text-xl md:text-2xl font-extrabold text-slate-800 dark:text-white"
                                    x-text="studyMode === 'level' ? 'Chúc mừng! Bạn đã thuộc hết từ vựng HSK ' + activeLevel : 'Chúc mừng! Bạn đã thuộc hết từ vựng chủ đề ' + activeTopic">
                                </h3>
                                <p class="text-slate-500 dark:text-slate-400 text-xs md:text-sm mt-2">Tuyệt vời! Bạn đã ghi
                                    nhớ toàn bộ từ vựng ở phần này.</p>
                            </div>
                        </div>
                    </template>

                </div>

            </div>
        </div>

        <!-- Mobile Filter Slide-over Drawer (Responsive UI component) -->
        <div x-show="isFilterDrawerOpen" class="fixed inset-0 z-50 lg:hidden" x-cloak>
            <!-- Backdrop Overlay with blur effect -->
            <div x-show="isFilterDrawerOpen" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" @click="isFilterDrawerOpen = false"
                class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm"></div>

            <!-- Drawer Panel sliding up from the bottom -->
            <div x-show="isFilterDrawerOpen" x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                x-transition:leave="transition ease-in duration-250 transform" x-transition:leave-start="translate-y-0"
                x-transition:leave-end="translate-y-full"
                class="fixed inset-x-0 bottom-0 max-h-[85vh] bg-white dark:bg-slate-800 rounded-t-[32px] border-t border-slate-100 dark:border-slate-700 shadow-2xl flex flex-col p-6 overflow-y-auto no-scrollbar z-50">
                <!-- Drawer Header -->
                <div
                    class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-700/80 mb-6">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-xl">tune</span>
                        <h3 class="text-base font-black text-slate-800 dark:text-white">Bộ lọc học tập</h3>
                    </div>
                    <button @click="isFilterDrawerOpen = false"
                        class="h-8 w-8 flex items-center justify-center rounded-full bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white transition-colors">
                        <span class="material-symbols-outlined text-base">close</span>
                    </button>
                </div>

                <!-- Drawer Content List -->
                <div class="flex flex-col gap-6 pb-8">
                    <!-- HSK Levels filter group -->
                    <div>
                        <h4
                            class="text-xs font-black uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-3.5 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px]">layers</span>
                            <span>Cấp Độ HSK</span>
                        </h4>
                        <div class="grid grid-cols-3 gap-2">
                            <template x-for="level in levels" :key="level">
                                <button @click="changeLevel(level); isFilterDrawerOpen = false"
                                    class="h-11 flex flex-col items-center justify-center rounded-2xl text-sm font-black transition-all border"
                                    :class="studyMode === 'level' && activeLevel === level ?
                                        'bg-gradient-to-br from-primary to-primary/90 text-white border-primary shadow-md' :
                                        'bg-slate-50 dark:bg-slate-900 text-slate-700 dark:text-slate-355 border-slate-100 dark:border-slate-800'">
                                    <span x-text="level"></span>
                                    <span class="text-[8px] font-medium opacity-80" x-text="'HSK ' + level"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Topics filter group -->
                    <div>
                        <h4
                            class="text-xs font-black uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-3.5 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px]">widgets</span>
                            <span>Chủ Đề Học Tập</span>
                        </h4>
                        <div class="flex flex-col gap-2.5">
                            <template x-for="(words, topicName) in topics" :key="topicName">
                                <button @click="changeTopic(topicName); isFilterDrawerOpen = false"
                                    class="w-full flex items-center justify-between gap-3 px-3.5 py-3 rounded-2xl text-xs font-bold transition-all border"
                                    :class="studyMode === 'topic' && activeTopic === topicName ?
                                        'bg-gradient-to-r from-primary to-primary/95 text-white border-primary shadow-lg' :
                                        'bg-transparent text-slate-700 dark:text-slate-355 border-slate-100 dark:border-slate-800/80'">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-7 w-7 items-center justify-center rounded-full text-xs shadow-sm"
                                            :class="studyMode === 'topic' && activeTopic === topicName ?
                                                'bg-white text-primary' :
                                                'bg-primary/5 dark:bg-primary/10 text-primary'">
                                            <span class="material-symbols-outlined text-[16px] font-bold"
                                                x-text="getTopicIcon(topicName)"></span>
                                        </div>
                                        <div class="text-left">
                                            <p class="font-bold text-xs"
                                                :class="studyMode === 'topic' && activeTopic === topicName ? 'text-white' :
                                                    'text-slate-800 dark:text-white'"
                                                x-text="topicName"></p>
                                            <p class="text-[9px] font-medium opacity-80"
                                                :class="studyMode === 'topic' && activeTopic === topicName ?
                                                    'text-white/80' : 'text-slate-400 dark:text-slate-500'"
                                                x-text="words.length + ' từ vựng'"></p>
                                        </div>
                                    </div>
                                    <span class="material-symbols-outlined text-[14px]"
                                        :class="studyMode === 'topic' && activeTopic === topicName ? 'text-white' :
                                            'text-slate-400 dark:text-slate-500'">chevron_right</span>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
