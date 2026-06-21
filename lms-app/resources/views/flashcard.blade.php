@extends('layouts.app')

@section('title', 'Flashcard Từ Vựng HSK 1 - HSK 9')
@section('breadcrumb', 'Flashcard HSK 1 - HSK 9')

@section('content')

    <!-- Main Workspace Section -->
    <section id="flashcard-section" class="pt-8 lg:pt-12 pb-24 bg-transparent"
        x-data="{
            activeLevel: 1,
            currentIndex: 0,
            flipped: false,
            autoplayAudio: false,
            isShuffled: false,
            shuffledWordsList: [],
            levels: [1, 2, 3, 4, 5, 6, 7, 8, 9],
            rememberedWords: JSON.parse(localStorage.getItem('remembered_hsk_words') || '[]'),
            vocabularies: {
                1: [
                    { word: '爱', pinyin: 'ài', meaning: 'Yêu, thích', example: '我爱学汉语。', example_pinyin: 'Wǒ ài xué hànyǔ.', example_meaning: 'Tôi thích học tiếng Trung.' },
                    { word: '谢谢', pinyin: 'xièxie', meaning: 'Cảm ơn', example: '谢谢你帮我。', example_pinyin: 'Xièxie nǐ bāng wǒ.', example_meaning: 'Cảm ơn bạn đã giúp tôi.' },
                    { word: '医生', pinyin: 'yīshēng', meaning: 'Bác sĩ', example: '他是我们的医生。', example_pinyin: 'Tā shì wǒmen de yīshēng.', example_meaning: 'Ông ấy là bác sĩ của chúng tôi.' }
                ],
                2: [
                    { word: '唱歌', pinyin: 'chànggē', meaning: 'Hát, ca hát', example: 'She 很喜欢唱歌。', example: '她很喜欢唱歌。', example_pinyin: 'Tā hěn xǐhuān chànggē.', example_meaning: 'Cô ấy rất thích hát.' },
                    { word: '运动', pinyin: 'yùndòng', meaning: 'Vận động, thể thao', example: 'Chúng tôi vận động mỗi ngày。', example: '我们每天都运动。', example_pinyin: 'Wǒmen měitiān dōu yùndòng.', example_meaning: 'Chúng tôi vận động mỗi ngày.' },
                    { word: '旅游', pinyin: 'lǚyóu', meaning: 'Du lịch', example: 'Tôi muốn đi du lịch Trung Quốc。', example: '我想去中国旅游。', example_pinyin: 'Wǒ xiǎng qù Zhōngguó lǚyóu.', example_meaning: 'Tôi muốn đi du lịch Trung Quốc.' }
                ],
                3: [
                    { word: '办法', pinyin: 'bànfǎ', meaning: 'Biện pháp, cách giải quyết', example: 'Đây thực sự là một cách hay！', example: '这真是一个好办法！', example_pinyin: 'Zhè zhēn shì yí gè hǎo bànfǎ!', example_meaning: 'Đây thực sự là một cách hay!' },
                    { word: '简单', pinyin: 'jiǎndān', meaning: 'Đơn giản, dễ dàng', example: 'Câu hỏi này rất đơn giản。', example: '这个问题很简单。', example_pinyin: 'Zhè gè wèntí hěn jiǎndān.', example_meaning: 'Câu hỏi này rất đơn giản.' },
                    { word: '影响', pinyin: 'yǐngxiǎng', meaning: 'Ảnh hưởng, tác động', example: 'Đừng ảnh hưởng tới việc học của người khác。', example: '别影响别人学习。', example_pinyin: 'Bié yǐngxiǎng biérén xuéxí.', example_meaning: 'Đừng ảnh hưởng tới việc học của người khác.' }
                ],
                4: [
                    { word: '关键', pinyin: 'guānjiàn', meaning: 'Mấu chốt, then chốt', example: 'Thái độ là chìa khóa của thành công。', example: '态度是成功的关键。', example_pinyin: 'Tàidù shì chénggōng de guānjiàn.', example_meaning: 'Thái độ là chìa khóa của thành công.' },
                    { word: '幽默', pinyin: 'yōumò', meaning: 'Hài hước, hóm hỉnh', example: 'Anh ấy là một người hài hước。', example: '他是一个幽默的人。', example_pinyin: 'Tā shì yí gè yōumò de rén.', example_meaning: 'Anh ấy là một người hài hước.' },
                    { word: '积极', pinyin: 'jījí', meaning: 'Tích cực, chủ động', example: 'Tích cực đối mặt với cuộc sống。', example: '积极面对生活。', example_pinyin: 'Jījí miànduì shēnghuó.', example_meaning: 'Tích cực đối mặt với cuộc sống.' }
                ],
                5: [
                    { word: '彼此', pinyin: 'bǐcǐ', meaning: 'Lẫn nhau, cả hai bên', example: 'Chúng ta nên tin tưởng lẫn nhau。', example: '我们应该彼此信任。', example_pinyin: 'Wǒmen yīnggāi bǐcǐ xìnrèn.', example_meaning: 'Chúng ta nên tin tưởng lẫn nhau.' },
                    { word: '诚实', pinyin: 'chéngshí', meaning: 'Thành thật, trung thực', example: 'Người trung thực là đáng yêu nhất。', example: '诚实的人最可爱。', example_pinyin: 'Chéngshí de rén zuì kě’ài.', example_meaning: 'Người trung thực là đáng yêu nhất.' },
                    { word: '贡献', pinyin: 'gòngxiàn', meaning: 'Cống hiến, đóng góp', example: 'Đóng góp cho xã hội。', example: '为社会做出贡献。', example_pinyin: 'Wèi shèhuì zuò chū gòngxiàn.', example_meaning: 'Đóng góp cho xã hội.' }
                ],
                6: [
                    { word: '忽略', pinyin: 'hūlüè', meaning: 'Bỏ qua, lơ là', example: 'Chi tiết không thể bị bỏ qua。', example: '细节不能被忽略。', example_pinyin: 'Xìjié bù néng bèi hūlüè.', example_meaning: 'Chi tiết không thể bị bỏ qua.' },
                    { word: '艰巨', pinyin: 'jiānjù', meaning: 'Gian khổ, khó khăn lớn', example: 'Đây là một nhiệm vụ gian khổ。', example: '这是一个艰巨的任务。', example_pinyin: 'Zhè shì yí gè jiānjù de rènwu.', example_meaning: 'Đây là một nhiệm vụ gian khổ.' },
                    { word: '偶尔', pinyin: 'ǒu\'ěr', meaning: 'Thỉnh thoảng, thi thoảng', example: 'Thỉnh thoảng tôi mới đến thư viện。', example: '我偶尔去图书馆。', example_pinyin: 'Wǒ ǒu\'ěr qù túshūguǎn.', example_meaning: 'Thỉnh thoảng tôi mới đến thư viện.' }
                ],
                7: [
                    { word: '阐述', pinyin: 'chǎnshù', meaning: 'Trình bày, làm rõ', example: 'Trình bày rõ quan điểm học thuật。', example: '阐述学术观点。', example_pinyin: 'Chǎnshù xuéshù guāndiǎn.', example_meaning: 'Trình bày rõ quan điểm học thuật.' },
                    { word: '磋商', pinyin: 'cuōshāng', meaning: 'Thương lượng, đàm phán', example: 'Hai bên đang đàm phán về sự hợp tác。', example: '双方正就合作进行磋商。', example_pinyin: 'Shuāngfāng zhèng jiù hézuò jìnxíng cuōshāng.', example_meaning: 'Hai bên đang đàm phán về sự hợp tác.' },
                    { word: '拓宽', pinyin: 'tuòkuān', meaning: 'Mở rộng, phát triển rộng', example: 'Mở rộng tầm nhìn quốc tế。', example: '拓宽国际视野。', example_pinyin: 'Tuòkuān guójì shìyě.', example_meaning: 'Mở rộng tầm nhìn quốc tế.' }
                ],
                8: [
                    { word: '宏观', pinyin: 'hóngguān', meaning: 'Vĩ mô', example: 'Điều tiết kinh tế vĩ mô。', example: '宏观经济调控。', example_pinyin: 'Hóngguān jīngjì tiáokòng.', example_meaning: 'Điều tiết kinh tế vĩ mô.' },
                    { word: '弊端', pinyin: 'bìduān', meaning: 'Mặt hại, tệ nạn, khuyết tật', example: 'Loại bỏ những mặt hại của thể chế。', example: '消除体制弊端。', example_pinyin: 'Xiāochú tǐzhì bìduān.', example_meaning: 'Loại bỏ những mặt hại của thể chế.' },
                    { word: '协调', pinyin: 'xiétiáo', meaning: 'Phối hợp, hài hòa', example: 'Phối hợp lợi ích các bên。', example: '协调各方利益。', example_pinyin: 'Xiétiáo gèfāng lìyì.', example_meaning: 'Phối hợp lợi ích các bên.' }
                ],
                9: [
                    { word: '阐明', pinyin: 'chǎnmíng', meaning: 'Làm sáng tỏ, giải thích rõ', example: 'Làm sáng tỏ sự thật lịch sử。', example: '阐明历史事实。', example_pinyin: 'Chǎnmíng lìshǐ shìshí.', example_meaning: 'Làm sáng tỏ sự thật lịch sử.' },
                    { word: '瞻望', pinyin: 'zhānwàng', meaning: 'Hướng về tương lai, triển vọng', example: 'Hướng về tương lai tốt đẹp。', example: '瞻望美好未来。', example_pinyin: 'Zhānwàng měihǎo wèilái.', example_meaning: 'Hướng về tương lai tốt đẹp.' },
                    { word: '融洽', pinyin: 'róngqià', meaning: 'Hòa hợp, thân thiết, hòa mục', example: 'Quan hệ vô cùng hòa hợp。', example: '关系非常融洽。', example_pinyin: 'Guānxì fēicháng / Róngqià.', example_pinyin: 'Guānxì fēicháng róngqià.', example_meaning: 'Quan hệ vô cùng hòa hợp.' }
                ]
            },
            currentWords() {
                let allWords = this.vocabularies[this.activeLevel] || [];
                let unremembered = allWords.filter(w => !this.rememberedWords.includes(w.word));
                if (this.isShuffled) {
                    return this.shuffledWordsList.filter(w => !this.rememberedWords.includes(w.word));
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
            flipCard() {
                if (this.currentWords().length === 0) return;
                this.flipped = !this.flipped;
            },
            shuffle() {
                this.flipped = false;
                setTimeout(() => {
                    if (this.isShuffled) {
                        this.isShuffled = false;
                        this.shuffledWordsList = [];
                        this.currentIndex = 0;
                    } else {
                        let words = (this.vocabularies[this.activeLevel] || []).filter(w => !this.rememberedWords.includes(w.word));
                        if (words.length <= 1) return;
                        // Fisher-Yates shuffle
                        for (let i = words.length - 1; i > 0; i--) {
                            const j = Math.floor(Math.random() * (i + 1));
                            [words[i], words[j]] = [words[j], words[i]];
                        }
                        this.shuffledWordsList = words;
                        this.isShuffled = true;
                        this.currentIndex = 0;
                    }
                    if (this.autoplayAudio && this.currentWords().length > 0) {
                        setTimeout(() => { this.speak(); }, 300);
                    }
                }, 150);
            },
            nextWord() {
                if (this.currentWords().length === 0) return;
                this.flipped = false;
                setTimeout(() => {
                    this.currentIndex = (this.currentIndex + 1) % this.currentWords().length;
                    if (this.autoplayAudio) {
                        setTimeout(() => { this.speak(); }, 300);
                    }
                }, 150);
            },
            prevWord() {
                if (this.currentWords().length === 0) return;
                this.flipped = false;
                setTimeout(() => {
                    this.currentIndex = (this.currentIndex - 1 + this.currentWords().length) % this.currentWords().length;
                    if (this.autoplayAudio) {
                        setTimeout(() => { this.speak(); }, 300);
                    }
                }, 150);
            },
            changeLevel(level) {
                this.activeLevel = level;
                this.currentIndex = 0;
                this.flipped = false;
                this.isShuffled = false;
                this.shuffledWordsList = [];
                if (this.autoplayAudio && this.currentWords().length > 0) {
                    setTimeout(() => { this.speak(); }, 400);
                }
            },
            markAsRemembered(word) {
                if (!this.rememberedWords.includes(word)) {
                    this.rememberedWords.push(word);
                    localStorage.setItem('remembered_hsk_words', JSON.stringify(this.rememberedWords));
                    
                    this.flipped = false;
                    setTimeout(() => {
                        if (this.currentIndex >= this.currentWords().length) {
                            this.currentIndex = Math.max(0, this.currentWords().length - 1);
                        }
                        if (this.autoplayAudio && this.currentWords().length > 0) {
                            this.speak();
                        }
                    }, 150);
                }
            },
            resetRemembered() {
                let levelWords = this.vocabularies[this.activeLevel].map(w => w.word);
                this.rememberedWords = this.rememberedWords.filter(w => !levelWords.includes(w));
                localStorage.setItem('remembered_hsk_words', JSON.stringify(this.rememberedWords));
                
                this.currentIndex = 0;
                this.flipped = false;
                this.isShuffled = false;
                this.shuffledWordsList = [];
                if (this.autoplayAudio) {
                    setTimeout(() => { this.speak(); }, 300);
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
                    let zhVoice = voices.find(voice => voice.lang.includes('zh') || voice.lang.includes('ZH'));
                    if (zhVoice) {
                        utterance.voice = zhVoice;
                    }
                    utterance.rate = 0.85;
                    window.speechSynthesis.speak(utterance);
                }
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
        }"
    >
        <div class="mx-auto max-w-7xl px-6">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">
                
                <!-- LEFT COLUMN: Sidebar Filter HSK (1/4 Width) -->
                <div class="lg:col-span-1 bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/50 dark:border-slate-700/60 shadow-xl shadow-slate-100/30 dark:shadow-none p-5 relative z-10">
                    <div class="px-2 pb-4 mb-4 border-b border-slate-100 dark:border-slate-700/50">
                        <span class="text-xs font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">layers</span>
                            CẤP ĐỘ HSK 1 - 9
                        </span>
                    </div>

                    <!-- Desktop vertical list, mobile horizontal slide -->
                    <div class="flex lg:flex-col gap-3 overflow-x-auto lg:overflow-x-visible no-scrollbar pb-2 lg:pb-0">
                        <template x-for="level in levels" :key="level">
                            <button 
                                @click="changeLevel(level)"
                                class="w-72 lg:w-full flex-shrink-0 lg:flex-shrink-1 flex items-center justify-between gap-4 px-4 py-3.5 rounded-2xl text-sm font-bold transition-all duration-300 border hover:scale-[1.01] active:scale-[0.98]"
                                :class="activeLevel === level 
                                    ? 'bg-gradient-to-r from-primary to-primary/95 text-white border-primary shadow-lg shadow-primary/20 scale-[1.02]' 
                                    : 'bg-transparent text-slate-700 dark:text-slate-300 border-slate-100 dark:border-slate-800 hover:border-primary/40 hover:bg-primary/5'"
                            >
                                <div class="flex items-center gap-3">
                                    <!-- Dynamic Badge for Numbers -->
                                    <div 
                                        class="flex h-8 w-8 items-center justify-center rounded-xl text-xs font-black shadow-sm"
                                        :class="activeLevel === level 
                                            ? 'bg-white text-primary shadow-inner' 
                                            : (level <= 3 ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-500' 
                                              : (level <= 6 ? 'bg-amber-50 dark:bg-amber-500/10 text-amber-500' 
                                              : 'bg-rose-50 dark:bg-rose-500/10 text-rose-500'))"
                                        x-text="level"
                                    ></div>
                                    <div class="text-left">
                                        <p class="font-bold text-sm" :class="activeLevel === level ? 'text-white' : 'text-slate-800 dark:text-white'" x-text="'HSK Cấp ' + level"></p>
                                        <p class="text-[10px] font-medium leading-tight mt-0.5" :class="activeLevel === level ? 'text-white/80' : 'text-slate-400 dark:text-slate-500'" x-text="getLevelDesc(level)"></p>
                                    </div>
                                </div>
                                <span 
                                    class="text-[9px] px-2 py-0.5 rounded-lg font-extrabold uppercase tracking-wide hidden sm:inline"
                                    :class="activeLevel === level 
                                        ? 'bg-white/20 text-white' 
                                        : (level <= 3 ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' 
                                          : (level <= 6 ? 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400' 
                                          : 'bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400'))"
                                    x-text="getLevelLabel(level)"
                                ></span>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- RIGHT COLUMN: Flashcard Panel (3/4 Width) -->
                <div class="lg:col-span-3 flex flex-col items-center justify-center gap-4 relative z-10 w-full">
                    
                    <!-- If NOT completed HSK level words -->
                    <template x-if="currentWords().length > 0">
                        <div class="w-full flex flex-col items-center justify-center gap-4">
                            <!-- Progress Info & Settings Bar -->
                            <div class="w-full max-w-3xl flex flex-wrap gap-4 justify-between items-center bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700/50 px-5 py-2.5 rounded-2xl shadow-sm text-xs text-slate-500 dark:text-slate-400">
                                <span class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-primary text-[18px]">keyboard</span>
                                    <span>Nhấn <kbd class="px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-sans font-bold">Space</kbd> để lật, phím <kbd class="px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-sans font-bold">←</kbd> / <kbd class="px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-sans font-bold">→</kbd> để chuyển từ.</span>
                                </span>
                                <div class="flex items-center gap-4">
                                    <!-- Shuffle Button (Trộn thẻ) -->
                                    <button 
                                        @click="shuffle()" 
                                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl border transition-all duration-300 font-bold hover:scale-[1.03] active:scale-[0.97]"
                                        :class="isShuffled 
                                            ? 'bg-primary/10 border-primary/30 text-primary dark:bg-primary/20' 
                                            : 'bg-slate-50 border-slate-100 hover:bg-slate-100 hover:border-slate-200 text-slate-500 dark:bg-slate-900 dark:border-slate-800 dark:hover:bg-slate-850'"
                                        title="Trộn ngẫu nhiên thứ tự các thẻ học"
                                    >
                                        <span class="material-symbols-outlined text-[16px]">shuffle</span>
                                        <span x-text="isShuffled ? 'Đang trộn' : 'Trộn thẻ'"></span>
                                    </button>

                                    <!-- Autoplay Switcher -->
                                    <div class="flex items-center gap-2 border-l border-r border-slate-200 dark:border-slate-700 px-4">
                                        <span class="text-xs font-bold text-slate-500 dark:text-slate-400 flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[16px]">volume_up</span>
                                            Tự động phát âm
                                        </span>
                                        <button 
                                            @click="autoplayAudio = !autoplayAudio" 
                                            class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                            :class="autoplayAudio ? 'bg-primary' : 'bg-slate-200 dark:bg-slate-700'"
                                        >
                                            <span 
                                                class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                                :class="autoplayAudio ? 'translate-x-4' : 'translate-x-0'"
                                            ></span>
                                        </button>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="h-2 w-2 rounded-full bg-primary animate-ping"></span>
                                        <span class="font-extrabold text-primary" x-text="'Học thử HSK ' + activeLevel"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- 3D Card Container (Phóng to theo yêu cầu) -->
                            <div class="w-full max-w-3xl h-[420px] perspective">
                                <div 
                                    class="w-full h-full preserve-3d transition-transform duration-500 relative"
                                    :class="flipped ? 'rotate-y-180' : ''"
                                    @click="flipCard()"
                                >
                                    <!-- CARD FRONT (Chinese characters - Pure & Clean Focus) -->
                                    <div class="backface-hidden w-full h-full bg-white dark:bg-slate-800 rounded-3xl border border-primary/20 dark:border-slate-700 shadow-xl dark:shadow-none flex flex-col justify-between p-6 absolute top-0 left-0 hover:border-primary transition-all duration-300">
                                        <!-- Card Header Info -->
                                        <div class="flex justify-between items-center w-full">
                                            <!-- Mark Learned Button (Premium Minimalist Check) -->
                                            <button 
                                                @click.stop="markAsRemembered(currentWord().word)" 
                                                class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-50 hover:bg-green-50 dark:bg-slate-900 dark:hover:bg-green-950/30 text-slate-400 hover:text-green-600 dark:text-slate-500 dark:hover:text-green-400 border border-slate-100/80 dark:border-slate-700/80 hover:border-green-200 dark:hover:border-green-900/50 shadow-sm transition-all duration-300"
                                                title="Đánh dấu đã thuộc từ này (sẽ ẩn đi)"
                                            >
                                                <span class="material-symbols-outlined text-[16px] font-bold">check</span>
                                            </button>
                                            
                                            <div class="px-3 py-0.5 rounded-full bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 text-slate-400 dark:text-slate-500 text-xs font-bold" x-text="(currentIndex + 1) + ' / ' + currentWords().length"></div>
                                        </div>

                                        <!-- Main Word -->
                                        <div class="flex flex-col items-center justify-center my-auto">
                                            <h3 class="text-7xl lg:text-8xl font-black text-slate-800 dark:text-white tracking-wide" x-text="currentWord().word"></h3>
                                        </div>

                                        <!-- Bottom indicator hints -->
                                        <div class="flex items-center justify-center gap-1.5 text-xs text-slate-400 dark:text-slate-500">
                                            <span class="material-symbols-outlined text-[16px] animate-pulse">flip</span>
                                            <span>Nhấp vào thẻ để lật xem ý nghĩa</span>
                                        </div>
                                    </div>

                                    <!-- CARD BACK (Pinyin & Definition & Example - Với vùng cuộn thông minh) -->
                                    <div class="backface-hidden rotate-y-180 w-full h-full bg-white dark:bg-slate-800 rounded-3xl border border-primary/20 dark:border-slate-700 shadow-xl dark:shadow-none flex flex-col justify-between p-6 absolute top-0 left-0 hover:border-primary transition-all duration-300">
                                        <!-- Card Header Info -->
                                        <div class="flex justify-between items-center w-full">
                                            <!-- Mark Learned Button (Premium Minimalist Check) -->
                                            <button 
                                                @click.stop="markAsRemembered(currentWord().word)" 
                                                class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-50 hover:bg-green-50 dark:bg-slate-900 dark:hover:bg-green-950/30 text-slate-400 hover:text-green-600 dark:text-slate-500 dark:hover:text-green-400 border border-slate-100/80 dark:border-slate-700/80 hover:border-green-200 dark:hover:border-green-900/50 shadow-sm transition-all duration-300"
                                                title="Đánh dấu đã thuộc từ này (sẽ ẩn đi)"
                                            >
                                                <span class="material-symbols-outlined text-[16px] font-bold">check</span>
                                            </button>
                                            
                                            <button 
                                                @click.stop="speak()" 
                                                class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-50 hover:bg-primary/10 dark:bg-slate-900 dark:hover:bg-primary/20 text-slate-400 hover:text-primary dark:text-slate-500 dark:hover:text-primary border border-slate-100/80 dark:border-slate-700/80 hover:border-primary/30 shadow-sm transition-all duration-300"
                                                title="Phát âm từ này"
                                            >
                                                <span class="material-symbols-outlined text-[16px] font-bold">volume_up</span>
                                            </button>
                                        </div>

                                        <!-- Word Details & Scrollable Example Section -->
                                        <div class="flex flex-col gap-4 my-auto text-left w-full overflow-y-auto max-h-[260px] pr-1.5 no-scrollbar">
                                            <div class="flex items-baseline gap-3">
                                                <h4 class="text-3xl font-extrabold text-primary" x-text="currentWord().word"></h4>
                                                <p class="text-lg font-bold text-slate-400 dark:text-slate-500 tracking-wide" x-text="'[' + currentWord().pinyin + ']'"></p>
                                            </div>
                                            
                                            <!-- Meaning -->
                                            <div class="bg-primary/5 dark:bg-primary/10 px-4 py-2.5 rounded-xl border-l-4 border-primary">
                                                <p class="text-base font-bold text-slate-800 dark:text-white" x-text="currentWord().meaning"></p>
                                            </div>

                                            <!-- Example Section -->
                                            <template x-if="currentWord().example">
                                                <div class="bg-slate-50 dark:bg-slate-900/50 p-3 rounded-xl border border-slate-100 dark:border-slate-800">
                                                    <div class="flex items-center justify-between mb-1">
                                                        <p class="text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Ví dụ minh họa:</p>
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
                                                            class="flex h-5 w-5 items-center justify-center rounded bg-white dark:bg-slate-800 text-slate-400 hover:text-primary border border-slate-100 dark:border-slate-700/80 transition-all"
                                                            title="Phát âm câu ví dụ"
                                                        >
                                                            <span class="material-symbols-outlined text-[12px]">volume_up</span>
                                                        </button>
                                                    </div>
                                                    <p class="text-sm font-bold text-slate-800 dark:text-white tracking-wide" x-text="currentWord().example"></p>
                                                    <p class="text-[10px] text-slate-400 dark:text-slate-500 font-medium italic mt-0.5" x-text="currentWord().example_pinyin"></p>
                                                    <div class="h-[1px] bg-slate-100/80 dark:bg-slate-750 my-1"></div>
                                                    <p class="text-xs text-slate-600 dark:text-slate-300" x-text="currentWord().example_meaning"></p>
                                                </div>
                                            </template>
                                        </div>

                                        <!-- Footer Flipback action -->
                                        <div class="flex justify-center w-full border-t border-slate-100 dark:border-slate-700/50 pt-3">
                                            <span class="flex items-center gap-1 text-[11px] text-slate-400">
                                                <span class="material-symbols-outlined text-[14px]">reply</span>
                                                Click để quay lại mặt trước
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Navigation Controls (Optimized size) -->
                            <div class="flex items-center justify-center gap-4 w-full max-w-2xl">
                                <!-- Prev button -->
                                <button 
                                    @click="prevWord()" 
                                    class="flex h-11 w-11 items-center justify-center rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-primary hover:text-primary hover:scale-105 active:scale-95 transition-all duration-200 shadow-sm"
                                    title="Từ trước đó (Phím ←)"
                                >
                                    <span class="material-symbols-outlined text-xl">chevron_left</span>
                                </button>

                                <!-- Play Audio Button -->
                                <button 
                                    @click="speak()" 
                                    class="flex-1 max-w-[200px] h-11 flex items-center justify-center gap-2 rounded-2xl bg-primary text-white hover:bg-primary/90 hover:scale-105 active:scale-95 transition-all duration-200 shadow-md shadow-primary/20 font-bold text-sm"
                                >
                                    <span class="material-symbols-outlined text-lg">volume_up</span>
                                    Phát âm Hán ngữ
                                </button>

                                <!-- Next button -->
                                <button 
                                    @click="nextWord()" 
                                    class="flex h-11 w-11 items-center justify-center rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-primary hover:text-primary hover:scale-105 active:scale-95 transition-all duration-200 shadow-sm"
                                    title="Từ tiếp theo (Phím →)"
                                >
                                    <span class="material-symbols-outlined text-xl">chevron_right</span>
                                </button>
                            </div>
                        </div>
                    </template>

                    <!-- If ALL words are completed -->
                    <template x-if="currentWords().length === 0">
                        <div class="w-full max-w-2xl bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/50 dark:border-slate-700/60 shadow-lg p-12 text-center flex flex-col items-center justify-center gap-6 relative z-10 my-12">
                            <div class="h-24 w-24 bg-yellow-100 dark:bg-yellow-500/10 rounded-full flex items-center justify-center text-yellow-500 animate-bounce">
                                <span class="material-symbols-outlined text-5xl">workspace_premium</span>
                            </div>
                            <div>
                                <h3 class="text-2xl font-extrabold text-slate-800 dark:text-white" x-text="'Chúc mừng! Bạn đã thuộc hết từ vựng HSK ' + activeLevel"></h3>
                                <p class="text-slate-500 dark:text-slate-400 text-sm mt-2">Tuyệt vời! Bạn đã ghi nhớ toàn bộ từ vựng ở cấp độ này.</p>
                            </div>
                            <button 
                                @click="resetRemembered()" 
                                class="flex items-center gap-2 px-6 py-3 rounded-2xl bg-primary text-white hover:bg-primary/90 hover:scale-105 active:scale-95 transition-all duration-200 font-bold text-sm shadow-lg shadow-primary/20"
                            >
                                <span class="material-symbols-outlined text-lg">replay</span>
                                Học lại cấp độ này từ đầu
                            </button>
                        </div>
                    </template>

                </div>

            </div>
        </div>
    </section>
@endsection
