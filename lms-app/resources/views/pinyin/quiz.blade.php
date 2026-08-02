@extends('layouts.app')

@section('title', 'Luyện Nghe Pinyin - Phản Xạ Tiếng Trung')

@section('breadcrumb', 'Luyện Nghe Pinyin')
@section('breadcrumb_desc', 'Rèn luyện phản xạ nghe phân biệt thanh điệu, âm bật hơi và âm uốn lưỡi Tiếng Trung đồng bộ thương hiệu.')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-[#0b1120] text-slate-900 dark:text-slate-100 font-sans py-8 px-4 sm:px-6"
     x-data="pinyinQuizApp()"
     x-init="initQuiz()">

    <div class="max-w-3xl mx-auto space-y-6">

        <!-- Top Header Controls & Stats Bar -->
        <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur-md rounded-3xl p-4 sm:p-5 border border-slate-200/80 dark:border-slate-700/80 shadow-lg flex items-center justify-between gap-4 flex-wrap">
            
            <!-- Category Mode Dropdown -->
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px] text-primary">tune</span>
                <select x-model="selectedCategory" @change="restartQuiz()" class="bg-slate-100 dark:bg-slate-700/80 border-0 rounded-2xl px-3.5 py-2 text-xs font-bold text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-primary cursor-pointer shadow-inner">
                    <option value="all">🎯 Tất cả 1,572 Âm Pinyin</option>
                    <option value="tones">🎵 Phân biệt 4 Thanh điệu</option>
                    <option value="aspirated">💨 Âm Bật hơi (p, t, k, q, ch, c)</option>
                    <option value="retroflex">🌀 Âm Uốn lưỡi (zh, ch, sh, r)</option>
                    <option value="labial">👄 Âm Môi (b, p, m, f)</option>
                </select>
            </div>

            <!-- Score & Streak Badges -->
            <div class="flex items-center gap-3">
                <!-- Streak Badge -->
                <div class="flex items-center gap-1.5 px-3.5 py-2 rounded-2xl bg-amber-50 dark:bg-amber-950/60 border border-amber-200/80 dark:border-amber-900/60 shadow-sm">
                    <span class="material-symbols-outlined text-[20px] text-amber-500 animate-pulse">local_fire_department</span>
                    <span class="text-xs font-bold text-amber-600 dark:text-amber-400">Streak:</span>
                    <span class="text-sm font-black text-amber-700 dark:text-amber-300" x-text="streak">0</span>
                </div>

                <!-- Total Score Badge -->
                <div class="flex items-center gap-1.5 px-3.5 py-2 rounded-2xl bg-primary/10 dark:bg-primary/20 border border-primary/30 shadow-sm">
                    <span class="material-symbols-outlined text-[20px] text-primary">emoji_events</span>
                    <span class="text-xs font-bold text-primary">Điểm:</span>
                    <span class="text-sm font-black text-primary" x-text="score">0</span>
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="space-y-1.5 px-1">
            <div class="flex items-center justify-between text-xs font-bold text-slate-400 dark:text-slate-500">
                <span>Tiến độ lượt luyện tập</span>
                <span><span x-text="questionInRound">1</span> / 10</span>
            </div>
            <div class="w-full h-3 bg-slate-200 dark:bg-slate-700/60 rounded-full overflow-hidden p-0.5">
                <div class="h-full bg-gradient-to-r from-primary via-orange-400 to-emerald-500 rounded-full transition-all duration-300 shadow-sm"
                     :style="`width: ${(questionInRound / 10) * 100}%`"></div>
            </div>
        </div>

        <!-- Main Gamified Claymorphic Quiz Card -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-10 border border-slate-200/80 dark:border-slate-700/80 shadow-2xl space-y-8 relative">
            
            <!-- Header Prompt -->
            <div class="text-center space-y-1.5">
                <span class="px-3.5 py-1 rounded-full bg-primary/10 text-primary border border-primary/20 font-extrabold text-[11px] uppercase tracking-wider">
                    Luyện Phản Xạ Âm Thanh
                </span>
                <p class="text-base sm:text-lg font-bold text-slate-700 dark:text-slate-200">
                    Nghe phát âm & chọn đáp án Pinyin chuẩn
                </p>
            </div>

            <!-- Claymorphic Speaker Play Button with Primary Brand Color -->
            <div class="flex flex-col items-center justify-center space-y-3 py-3">
                <button @click="playAudio()"
                        :class="isPlaying ? 'shadow-none translate-y-1.5 bg-primary/80 ring-4 ring-primary/30' : 'shadow-[0_8px_0_0_#c87058] hover:shadow-[0_5px_0_0_#c87058] hover:translate-y-0.5 bg-primary'"
                        class="w-32 h-32 sm:w-36 sm:h-36 rounded-full text-white flex flex-col items-center justify-center transition-all duration-150 active:translate-y-2 active:shadow-none cursor-pointer group relative overflow-hidden">
                    
                    <!-- Sound Wave Pulse Lines -->
                    <div x-show="isPlaying" class="flex items-center gap-1 absolute bottom-4">
                        <span class="w-1 h-3.5 bg-white/80 rounded-full animate-bounce"></span>
                        <span class="w-1 h-6 bg-white rounded-full animate-bounce [animation-delay:0.15s]"></span>
                        <span class="w-1 h-7 bg-white/90 rounded-full animate-bounce [animation-delay:0.3s]"></span>
                        <span class="w-1 h-4 bg-white/80 rounded-full animate-bounce [animation-delay:0.45s]"></span>
                    </div>

                    <span class="material-symbols-outlined text-[52px] sm:text-[58px] group-hover:scale-110 transition-transform">volume_up</span>
                    <span class="text-[11px] font-black uppercase tracking-wider mt-1" x-text="isPlaying ? 'Đang phát...' : 'Bấm để nghe 🔊'">Bấm để nghe 🔊</span>
                </button>
                <p class="text-xs font-medium text-slate-400 dark:text-slate-500">Chạm vào loa để nghe phát âm</p>
            </div>

            <!-- Option Buttons Grid (2x2) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <template x-for="(opt, idx) in currentOptions" :key="idx">
                    <button @click="selectAnswer(opt)"
                            :disabled="answered"
                            :class="getOptionClass(opt)"
                            class="px-3.5 py-2.5 rounded-xl text-left flex items-center justify-between transition-all duration-150 cursor-pointer relative group">
                        
                        <div class="flex items-center gap-3">
                            <!-- Option Badge (A, B, C, D) -->
                            <span :class="getBadgeClass(opt)"
                                  class="w-7 h-7 rounded-lg flex items-center justify-center font-bold text-xs shadow-sm transition-colors"
                                  x-text="['A', 'B', 'C', 'D'][idx]"></span>
                            
                            <!-- Pinyin Text Display -->
                            <span class="text-sm sm:text-base font-bold tracking-wide text-slate-800 dark:text-slate-100" x-text="opt.display"></span>
                        </div>

                        <!-- Result Icon Indicator -->
                        <template x-if="answered && opt.id === targetTone.id">
                            <span class="material-symbols-outlined text-emerald-500 text-xl font-black animate-bounce">check_circle</span>
                        </template>
                        <template x-if="answered && selectedOpt && selectedOpt.id === opt.id && opt.id !== targetTone.id">
                            <span class="material-symbols-outlined text-rose-500 text-xl font-black">cancel</span>
                        </template>
                    </button>
                </template>
            </div>

            <!-- Result Banner Footer -->
            <div x-show="answered" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-3"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="pt-4 border-t border-slate-100 dark:border-slate-700/80 flex items-center justify-between flex-wrap gap-4">
                
                <div class="flex items-center gap-3">
                    <div :class="isCorrect ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-200' : 'bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-200'"
                         class="px-4 py-2 rounded-2xl text-xs font-black flex items-center gap-2 shadow-sm">
                        <span class="material-symbols-outlined text-[20px]" x-text="isCorrect ? 'task_alt' : 'error'"></span>
                        <span x-text="isCorrect ? 'Chính xác! 🎉' : 'Chưa đúng! ❌'"></span>
                    </div>
                    <span class="text-xs text-slate-500 dark:text-slate-400 font-semibold">
                        Đáp án đúng: <strong class="text-slate-800 dark:text-slate-100 font-extrabold text-sm" x-text="targetTone ? targetTone.display : ''"></strong>
                    </span>
                </div>

                <button @click="handleNextStep()" 
                        class="px-6 py-3 rounded-2xl bg-primary hover:bg-primary/90 text-white font-black text-xs flex items-center gap-2 shadow-[0_4px_0_0_#c87058] active:translate-y-1 active:shadow-none transition-all cursor-pointer">
                    <span>Câu tiếp theo</span>
                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                </button>
            </div>

        </div>

    </div>

    <!-- Celebration Modal for Round Completion (10 Questions) -->
    <div x-show="showSummaryModal" 
         x-transition.opacity
         style="display: none;" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-md p-4">
        
        <div x-show="showSummaryModal"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-90 translate-y-8"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             class="bg-white dark:bg-slate-800 rounded-3xl p-8 max-w-md w-full border border-slate-100 dark:border-slate-700 shadow-2xl text-center space-y-6">
            
            <div class="w-20 h-20 rounded-full bg-amber-100 dark:bg-amber-950/80 text-amber-500 flex items-center justify-center mx-auto shadow-inner">
                <span class="material-symbols-outlined text-[48px] animate-bounce">emoji_events</span>
            </div>

            <div class="space-y-2">
                <h3 class="text-2xl font-black text-slate-800 dark:text-slate-100">Hoàn Thành Lượt Luyện Tập! 🎉</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Bạn đã hoàn thành 10 câu phản xạ nghe Pinyin</p>
            </div>

            <div class="grid grid-cols-2 gap-3 p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200/60 dark:border-slate-700/60">
                <div class="flex flex-col items-center">
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Tổng điểm</span>
                    <span class="text-xl font-black text-primary" x-text="score"></span>
                </div>
                <div class="flex flex-col items-center">
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Streak cao nhất</span>
                    <span class="text-xl font-black text-amber-500" x-text="maxStreak"></span>
                </div>
            </div>

            <button @click="showSummaryModal = false; restartQuiz()" 
                    class="w-full py-3.5 rounded-2xl bg-primary hover:bg-primary/90 text-white font-black text-sm shadow-[0_5px_0_0_#c87058] active:translate-y-1 active:shadow-none transition-all cursor-pointer flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[20px]">refresh</span>
                <span>Bắt đầu lượt mới</span>
            </button>
        </div>
    </div>

    <!-- Hidden Audio Player -->
    <audio x-ref="audioPlayer" class="hidden"></audio>
</div>

<script>
function pinyinQuizApp() {
    return {
        allTones: {!! $quizTonesJson !!},
        filteredTones: [],
        targetTone: null,
        currentOptions: [],
        selectedCategory: 'all',
        selectedOpt: null,
        answered: false,
        isCorrect: false,
        score: 0,
        streak: 0,
        maxStreak: 0,
        questionInRound: 1,
        showSummaryModal: false,
        isPlaying: false,

        initQuiz() {
            this.filterTones();
            this.nextQuestion(false); // Không tự động phát âm thanh khi vừa truy cập trang
        },

        filterTones() {
            if (this.selectedCategory === 'tones') {
                this.filteredTones = this.allTones.filter(t => t.tone_number >= 1 && t.tone_number <= 4);
            } else if (this.selectedCategory === 'aspirated') {
                const aspInitials = ['p', 't', 'k', 'q', 'ch', 'c'];
                this.filteredTones = this.allTones.filter(t => aspInitials.includes(t.initial));
            } else if (this.selectedCategory === 'retroflex') {
                const retInitials = ['zh', 'ch', 'sh', 'r'];
                this.filteredTones = this.allTones.filter(t => retInitials.includes(t.initial));
            } else if (this.selectedCategory === 'labial') {
                const labInitials = ['b', 'p', 'm', 'f'];
                this.filteredTones = this.allTones.filter(t => labInitials.includes(t.initial));
            } else {
                this.filteredTones = [...this.allTones];
            }

            if (this.filteredTones.length < 4) {
                this.filteredTones = [...this.allTones];
            }
        },

        restartQuiz() {
            this.score = 0;
            this.streak = 0;
            this.maxStreak = 0;
            this.questionInRound = 1;
            this.showSummaryModal = false;
            this.filterTones();
            this.nextQuestion(false); // Không tự động phát khi đổi danh mục
        },

        handleNextStep() {
            if (this.questionInRound >= 10) {
                this.showSummaryModal = true;
            } else {
                this.questionInRound++;
                this.nextQuestion(true); // Tự động phát khi người dùng bấm sang câu tiếp theo
            }
        },

        nextQuestion(autoPlay = true) {
            this.answered = false;
            this.selectedOpt = null;
            this.isCorrect = false;

            const pool = (this.filteredTones && this.filteredTones.length >= 4) ? this.filteredTones : this.allTones;

            // Pick random target tone from filtered pool
            const randIdx = Math.floor(Math.random() * pool.length);
            this.targetTone = pool[randIdx];

            // Pick 3 distractors using Intelligent Phonetic Confusion Algorithm
            const distractors = this.getSmartDistractors(this.targetTone, pool);

            // Shuffle options
            this.currentOptions = [this.targetTone, ...distractors].sort(() => 0.5 - Math.random());

            // Chỉ tự động phát nếu autoPlay là true (khi người dùng bấm câu tiếp theo)
            if (autoPlay) {
                setTimeout(() => {
                    this.playAudio();
                }, 300);
            }
        },

        getSmartDistractors(target, pool) {
            const distractors = [];
            const addedIds = new Set([target.id]);

            // MODE 1: If explicitly selected 'tones' mode, fill all slots with same-syllable tones
            if (this.selectedCategory === 'tones') {
                const sameSyllableTones = pool.filter(t => !addedIds.has(t.id) && t.pinyin_id === target.pinyin_id);
                this.shuffleArray(sameSyllableTones);
                for (let t of sameSyllableTones) {
                    if (distractors.length >= 3) break;
                    distractors.push(t);
                    addedIds.add(t.id);
                }
            } else {
                // MODE 2: BALANCED MIXED STRATEGY FOR ALL OTHER MODES
                // 1. Pick 1 same-syllable different tone (Distractor 1: Tone practice)
                const sameSyllableTones = pool.filter(t => !addedIds.has(t.id) && t.pinyin_id === target.pinyin_id);
                if (sameSyllableTones.length > 0) {
                    this.shuffleArray(sameSyllableTones);
                    distractors.push(sameSyllableTones[0]);
                    addedIds.add(sameSyllableTones[0].id);
                }

                // 2. Pick 1 minimal pair initial (Distractor 2: Initial practice e.g. b-p, d-t, g-k)
                const SIMILAR_INITIALS = {
                    'b': ['p', 'm'], 'p': ['b', 'f'], 'm': ['b', 'n'], 'f': ['p', 'h'],
                    'd': ['t', 'n'], 't': ['d', 'l'], 'n': ['l', 'm'], 'l': ['n', 'r'],
                    'g': ['k', 'h'], 'k': ['g', 'h'], 'h': ['k', 'f'],
                    'j': ['q', 'x', 'z'], 'q': ['j', 'x', 'c'], 'x': ['j', 'q', 's'],
                    'zh': ['ch', 'sh', 'z'], 'ch': ['zh', 'sh', 'c'], 'sh': ['zh', 'ch', 's', 'r'], 'r': ['l', 'sh'],
                    'z': ['c', 's', 'zh'], 'c': ['z', 's', 'ch'], 's': ['z', 'c', 'sh']
                };

                const targetInitial = target.initial || '';
                const simInitials = SIMILAR_INITIALS[targetInitial] || [];

                if (simInitials.length > 0) {
                    const simInitialTones = pool.filter(t => !addedIds.has(t.id) && simInitials.includes(t.initial));
                    if (simInitialTones.length > 0) {
                        this.shuffleArray(simInitialTones);
                        distractors.push(simInitialTones[0]);
                        addedIds.add(simInitialTones[0].id);
                    }
                }

                // 3. Pick 1 minimal pair final (Distractor 3: Final practice e.g. an-ang, en-eng)
                const SIMILAR_FINALS = {
                    'an': ['ang', 'en'], 'ang': ['an', 'ong'],
                    'en': ['eng', 'an'], 'eng': ['en', 'ong'],
                    'in': ['ing', 'en'], 'ing': ['in', 'eng'],
                    'ian': ['iang', 'an'], 'iang': ['ian', 'ang'],
                    'u': ['ü', 'ou'], 'ü': ['u', 'iu']
                };

                const targetFinal = target.final || '';
                const simFinals = SIMILAR_FINALS[targetFinal] || [];

                if (simFinals.length > 0) {
                    const simFinalTones = pool.filter(t => !addedIds.has(t.id) && simFinals.includes(t.final));
                    if (simFinalTones.length > 0) {
                        this.shuffleArray(simFinalTones);
                        distractors.push(simFinalTones[0]);
                        addedIds.add(simFinalTones[0].id);
                    }
                }
            }

            // Fallback: Fill any remaining slots from the pool
            if (distractors.length < 3) {
                const remaining = pool.filter(t => !addedIds.has(t.id));
                this.shuffleArray(remaining);
                for (let t of remaining) {
                    if (distractors.length >= 3) break;
                    distractors.push(t);
                    addedIds.add(t.id);
                }
            }

            return distractors;
        },

        shuffleArray(arr) {
            for (let i = arr.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [arr[i], arr[j]] = [arr[j], arr[i]];
            }
        },

        playAudio() {
            if (!this.targetTone || !this.targetTone.audio_path) return;
            
            const player = this.$refs.audioPlayer;
            player.src = '/storage/audio/pinyin/' + this.targetTone.audio_path;
            this.isPlaying = true;
            
            player.play().catch(e => {
                console.log('Audio autoplay prevented or error:', e);
            });

            player.onended = () => {
                this.isPlaying = false;
            };
        },

        selectAnswer(opt) {
            if (this.answered) return;

            this.selectedOpt = opt;
            this.answered = true;

            if (opt.id === this.targetTone.id) {
                this.isCorrect = true;
                this.score += 10;
                this.streak += 1;
                if (this.streak > this.maxStreak) {
                    this.maxStreak = this.streak;
                }
                this.playSynthSound('correct');

                // Auto advance to next step after 1.3 seconds
                setTimeout(() => {
                    if (this.answered && this.isCorrect) {
                        this.handleNextStep();
                    }
                }, 1300);
            } else {
                this.isCorrect = false;
                this.streak = 0;
                this.playSynthSound('wrong');
            }
        },

        getOptionClass(opt) {
            if (!this.answered) {
                return 'bg-slate-50 dark:bg-slate-900/50 border-2 border-slate-200 dark:border-slate-700/80 hover:border-primary dark:hover:border-primary shadow-[0_4px_0_0_#cbd5e1] dark:shadow-[0_4px_0_0_#334155] active:translate-y-1 active:shadow-none hover:bg-white dark:hover:bg-slate-800';
            }
            if (opt.id === this.targetTone.id) {
                return 'bg-emerald-50 dark:bg-emerald-950/40 border-2 border-emerald-500 shadow-[0_4px_0_0_#10b981]';
            }
            if (this.selectedOpt && this.selectedOpt.id === opt.id) {
                return 'bg-rose-50 dark:bg-rose-950/40 border-2 border-rose-500 shadow-[0_4px_0_0_#f43f5e]';
            }
            return 'bg-slate-50 dark:bg-slate-900/30 border-2 border-slate-200/40 dark:border-slate-800/40 opacity-50';
        },

        getBadgeClass(opt) {
            if (!this.answered) {
                return 'bg-primary/10 text-primary group-hover:bg-primary group-hover:text-white';
            }
            if (opt.id === this.targetTone.id) {
                return 'bg-emerald-600 text-white';
            }
            if (this.selectedOpt && this.selectedOpt.id === opt.id) {
                return 'bg-rose-600 text-white';
            }
            return 'bg-slate-200 dark:bg-slate-700 text-slate-500';
        },

        // Web Audio API Synthesis for Sound Effects
        playSynthSound(type) {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();

                osc.connect(gain);
                gain.connect(ctx.destination);

                if (type === 'correct') {
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(523.25, ctx.currentTime); // C5
                    osc.frequency.setValueAtTime(659.25, ctx.currentTime + 0.1); // E5
                    gain.gain.setValueAtTime(0.35, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.35);
                    osc.start(ctx.currentTime);
                    osc.stop(ctx.currentTime + 0.35);
                } else {
                    osc.type = 'sawtooth';
                    osc.frequency.setValueAtTime(180, ctx.currentTime);
                    osc.frequency.setValueAtTime(130, ctx.currentTime + 0.15);
                    gain.gain.setValueAtTime(0.35, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.35);
                    osc.start(ctx.currentTime);
                    osc.stop(ctx.currentTime + 0.35);
                }
            } catch(e) {
                console.log('Web Audio API error:', e);
            }
        }
    }
}
</script>
@endsection
