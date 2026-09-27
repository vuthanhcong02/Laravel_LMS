/**
 * Alpine.js Component: Phân hệ Chiết tự, Viết Mẫu & Luyện Viết Chữ Hán Theo Bộ Thủ
 * Tuân thủ Clean DRY Architecture và XiaoMu LMS Design System.
 */
export function hanziDecompositionApp(initialData = {}) {
    return {
        // Cấu trúc dữ liệu theo Nhóm Bộ thủ
        radicalGroups: initialData.radicalGroups || {},
        radicals: initialData.radicals || [],
        
        // Trạng thái Bộ thủ và Chữ Hán đang chọn
        selectedRadicalId: 'ren',
        selectedCharKey: '休',
        currentChar: null,
        activeTab: 'explorer', // 'explorer' | 'stroke_studio' | 'quiz' | 'radicals'
        searchQuery: '',

        // =========================================================================
        // STUDIO VIẾT MẪU & TẬP THUẬN BÚT (STROKE ANIMATOR)
        // =========================================================================
        currentStrokeStep: 1,
        isPlayingStrokes: false,
        strokeTimer: null,
        strokeSpeed: 850, // ms per stroke (850ms default)
        selectedStrokeInfo: null,
        showRulesModal: false, // Quản lý bật/tắt Modal Popup 7 Quy tắc bút thuận

        // 7 Quy tắc vàng bút thuận chuẩn Hán ngữ
        strokeRulesGuide: [
            { id: 1, name: 'Ngang trước, Sổ sau', zh: '先横后竖', example: '十', desc: 'Nét ngang viết trước, nét sổ dọc cắt qua viết sau.' },
            { id: 2, name: 'Phẩy trước, Mác sau', zh: '先撇后捺', example: '八 / 人', desc: 'Nét xiên trái (phẩy) viết trước, nét xiên phải (mác) viết sau.' },
            { id: 3, name: 'Trên trước, Dưới sau', zh: '从上到下', example: '三 / 安', desc: 'Các thành phần bên trên viết trước rồi mới xuống dưới.' },
            { id: 4, name: 'Trái trước, Phải sau', zh: '从左到右', example: '休 / 明', desc: 'Bộ thủ bên trái viết trước rồi đến phần bên phải.' },
            { id: 5, name: 'Ngoài trước, Trong sau', zh: '从外到内', example: '同 / 月', desc: 'Khung bao bọc bên ngoài viết trước, nội dung bên trong viết sau.' },
            { id: 6, name: 'Vào trước, Đóng sau', zh: '先进入后关门', example: '国 / 回', desc: 'Viết khung 3 cạnh, viết phần bên trong rồi mới đóng nét đáy.' },
            { id: 7, name: 'Giữa trước, Hai bên sau', zh: '先中间后两边', example: '小 / 水', desc: 'Nét trục chính giữa viết trước, hai nét cánh hai bên viết sau.' },
        ],

        // =========================================================================
        // STUDIO KHUNG LUYỆN VIẾT TƯƠNG TÁC (HANDWRITING CANVAS)
        // =========================================================================
        canvas: null,
        ctx: null,
        isDrawing: false,
        lastX: 0,
        lastY: 0,
        brushColor: '#1c1917', // Màu mực mặc định: Đen thư pháp
        brushSize: 8,          // Độ dày nét vẽ: 4 | 8 | 14
        isEraser: false,
        showTraceGuide: true,  // Bật/tắt nét chữ mờ hướng dẫn
        canvasHistory: [],
        hasDrawn: false,
        feedbackMessage: '',
        feedbackType: '', // 'success' | 'info'

        // Bảng màu bút mực thư pháp
        brushColors: [
            { name: 'Mực tàu', hex: '#1c1917', border: 'border-slate-800' },
            { name: 'Chu sa', hex: '#e07a5f', border: 'border-[#e07a5f]' },
            { name: 'Chàm biếc', hex: '#0284c7', border: 'border-sky-600' },
            { name: 'Trúc thanh', hex: '#16a34a', border: 'border-green-600' },
        ],

        // =========================================================================
        // BỘ LỌC 214 BỘ THỦ
        // =========================================================================
        radicalSearch: '',
        selectedStrokeFilter: 'all',
        selectedCategoryFilter: 'all',

        // =========================================================================
        // 3 DẠNG BÀI TẬP CHIẾT TỰ THỰC CHIẾN
        // =========================================================================
        quizMode: 'confusing_chars', // 'confusing_chars' | 'predict_meaning' | 'find_typo'
        totalScore: 0,

        // DẠNG 1: PHÂN BIỆT CHỮ DỄ GÂY LÚ (CHỮ TƯƠNG ĐỒNG)
        confusingIndex: 0,
        confusingAnswered: null,
        confusingQuestions: [
            {
                prompt: 'Chữ nào có nghĩa là "MUA" (Mua vào) chứ KHÔNG PHẢI "Bán"?',
                context: 'Phân biệt 买 (Mãi) vs 卖 (Mại)',
                options: [
                    { char: '买', pinyin: 'mǎi', label: '买 (Mãi - Mua)' },
                    { char: '卖', pinyin: 'mài', label: '卖 (Mại - Bán)' }
                ],
                correct: '买',
                mnemonic: 'Mẹo chiết tự: Chữ 卖 (Bán) có thêm chữ Thập (十) ở trên đầu tượng trưng cho món hàng đem bán đi lấy tiền. Chữ 买 (Mua) trên đầu không có gì vì chưa mua được đồ.'
            },
            {
                prompt: 'Chữ nào có nghĩa là "MẠT" (Phần ngọn cây, kết thúc, mạt vận)?',
                context: 'Phân biệt 末 (Mạt) vs 未 (Vị)',
                options: [
                    { char: '末', pinyin: 'mò', label: '末 (Nét trên dài hơn)' },
                    { char: '未', pinyin: 'wèi', label: '未 (Nét trên ngắn hơn)' }
                ],
                correct: '末',
                mnemonic: 'Mẹo chiết tự: Chữ 末 có nét ngang trên DÀI HƠN tượng trưng cho ngọn cây đã vươn lên tột cùng (ngọn, cuối cùng). Chữ 未 có nét trên NGẮN HƠN nghĩa là cành non chưa mọc hết (chưa đến, vị lai).'
            },
            {
                prompt: 'Chữ nào là chữ "KỶ" (Bản thân mình - Tự kỷ)?',
                context: 'Phân biệt 己 (Kỷ) vs 已 (Dĩ) vs 巳 (Tỵ)',
                options: [
                    { char: '己', pinyin: 'jǐ', label: '己 (Miệng mở hoàn toàn)' },
                    { char: '已', pinyin: 'yǐ', label: '已 (Miệng khép một nửa)' },
                    { char: '巳', pinyin: 'sì', label: '巳 (Miệng đóng kín)' }
                ],
                correct: '己',
                mnemonic: 'Khẩu quyết: "Kỷ mở (己), Dĩ nửa (已), Tỵ đóng toàn phần (巳)". Bản thân mình (Kỷ) thì lòng phải luôn mở rộng.'
            },
            {
                prompt: 'Chữ nào mang nghĩa là "Nghỉ ngơi" (Hưu), chữ nào là "Cơ thể" (Thể)?',
                context: 'Phân biệt 休 (Hưu) vs 体 (Thể)',
                options: [
                    { char: '休', pinyin: 'xiū', label: '休 (Nhân + Mộc)' },
                    { char: '体', pinyin: 'tǐ', label: '体 (Nhân + Bản)' }
                ],
                correct: '休',
                mnemonic: 'Mẹo chiết tự: 休 là Người (亻) tựa gốc Cây (木) để NGHỈ NGƠI. Còn 体 là Người (亻) dựa trên gốc rễ bản chất (本) tạo nên CƠ THỂ/THÂN THỂ.'
            }
        ],

        // DẠNG 2: GIẢI MÃ CHỮ LẠ QUA THANH BÀNG & HÌNH BÀNG
        predictIndex: 0,
        predictAnswered: null,
        predictQuestions: [
            {
                unknownChar: '饱',
                pinyin: 'bǎo',
                components: 'Bộ Thực (饣: Ăn uống) + Chữ Bao (包: âm đọc bao)',
                question: 'Dựa vào bộ thủ biểu ý (饣), chữ này liên quan đến điều gì?',
                options: ['Ăn no, no bụng', 'Chạy bộ', 'Cái túi xách', 'Bong bóng nước'],
                correct: 'Ăn no, no bụng',
                explanation: 'Hình bàng 饣 (Thực) chỉ đồ ăn/ăn uống, Thanh bàng 包 gợi ý âm đọc "bǎo". Ghép lại: 饱 (Bão) = Ăn no nê.'
            },
            {
                unknownChar: '抱',
                pinyin: 'bào',
                components: 'Bộ Thủ (扌: Bàn tay) + Chữ Bao (包)',
                question: 'Dựa vào bộ thủ biểu ý (扌), chữ này mang hành động gì?',
                options: ['Ôm, bế, bồng', 'Ăn no', 'Chạy trốn', 'Nấu canh'],
                correct: 'Ôm, bế, bồng',
                explanation: 'Bộ Thủ (扌) chỉ động tác của đôi bàn tay, thanh bàng 包 tạo âm "bào". Ghép lại: 抱 (Bão) = Ôm chặt vào lòng.'
            },
            {
                unknownChar: '跑',
                pinyin: 'pǎo',
                components: 'Bộ Túc (⻊: Chân) + Chữ Bao (包)',
                question: 'Dựa vào bộ Túc (⻊: Chân), chữ này diễn tả hành động gì?',
                options: ['Chạy bộ, chạy trốn', 'Bơi lội', 'Hát ca', 'Nhìn ngắm'],
                correct: 'Chạy bộ, chạy trốn',
                explanation: 'Bộ Túc (⻊) liên quan đến đôi chân chuyển động mau lẹ. 跑 (Bão) = Chạy nhanh.'
            },
            {
                unknownChar: '盲',
                pinyin: 'máng',
                components: 'Chữ Vong (亡: Mất đi) + Bộ Mục (目: Con mắt)',
                question: 'Mất đi (亡) thị lực của mắt (目), chữ này có nghĩa là gì?',
                options: ['Mù lòa, không thấy đường', 'Nhìn xa xăm', 'Khóc sưng mắt', 'Ngủ say'],
                correct: 'Mù lòa, không thấy đường',
                explanation: '亡 (Mất) + 目 (Mắt) = 盲 (Manh - Mù lòa, ví dụ: 盲人 người khiếm thị, 盲目 mù quáng).'
            }
        ],

        // DẠNG 3: BẮT LỖI SAI NÉT & BỘ THỦ (错别字)
        typoIndex: 0,
        typoAnswered: null,
        typoQuestions: [
            {
                sentence: 'Hôm nay trời nắng đẹp, tôi muốn đi [ ? ] sách.',
                sentenceZh: '今天天气很好，我想去 [ ? ] 书。',
                wordNeeded: 'Xem/Đọc (Khán)',
                options: ['看', '着', '春'],
                correctIndex: 0,
                correctChar: '看',
                explanation: 'Chữ 看 (Khán) có bộ Thủ (⺁) che trên bộ Mục (目). Đừng nhầm với chữ 着 (Trứ - có 2 chấm trên) hay chữ 春 (Xuân).'
            },
            {
                sentence: 'Anh ấy là người có phẩm chất đạo [ ? ] rất tốt.',
                sentenceZh: '他是一个品 [ ? ] 很好的人。',
                wordNeeded: 'Đức (Đạo đức)',
                options: ['得', '德', '待'],
                correctIndex: 1,
                correctChar: '德',
                explanation: 'Chữ 德 (Đức) có bộ Xích (彳) + Thập + Tứ + Nhất + Tâm (心). Chữ 得 là Đắc (đạt được), chữ 待 là Đãi (chờ đợi).'
            },
            {
                sentence: 'Cậu làm việc mệt mỏi rồi, hãy ngồi [ ? ] ngơi một chút đi.',
                sentenceZh: '你累了，坐下 [ ? ] 息一下吧。',
                wordNeeded: 'Hưu (Nghỉ ngơi)',
                options: ['体', '休', '木'],
                correctIndex: 1,
                correctChar: '休',
                explanation: 'Chữ 休 (Hưu) là Người (亻) tựa gốc Cây (木). Chữ 体 (Thể) là thân thể, chữ 木 (Mộc) là cây cối.'
            }
        ],

        // =========================================================================
        // KHỞI TẠO & LIFECYCLE
        // =========================================================================
        init() {
            const groupKeys = Object.keys(this.radicalGroups);
            if (groupKeys.length > 0) {
                this.selectedRadicalId = groupKeys[0];
                const charKeys = Object.keys(this.radicalGroups[this.selectedRadicalId]?.characters || {});
                if (charKeys.length > 0) {
                    this.selectedCharKey = charKeys[0];
                    this.currentChar = this.radicalGroups[this.selectedRadicalId].characters[this.selectedCharKey];
                }
            }

            // Lắng nghe khi chuyển tab sang Luyện viết để kích hoạt Canvas
            this.$watch('activeTab', (newTab) => {
                if (newTab === 'stroke_studio') {
                    this.$nextTick(() => {
                        this.setupCanvas();
                    });
                }
            });
        },

        // =========================================================================
        // GETTERS & ĐIỀU HƯỚNG BỘ THỦ
        // =========================================================================
        get currentRadical() {
            return this.radicalGroups[this.selectedRadicalId] || null;
        },

        get currentRadicalCharacters() {
            if (!this.currentRadical || !this.currentRadical.characters) return [];
            return Object.values(this.currentRadical.characters);
        },

        get allCharactersFlat() {
            const list = [];
            Object.values(this.radicalGroups).forEach(group => {
                if (group.characters) {
                    Object.values(group.characters).forEach(c => {
                        list.push({
                            ...c,
                            group_id: group.id,
                            group_name: group.name,
                            group_radical: group.radical,
                        });
                    });
                }
            });
            return list;
        },

        get filteredSearchResults() {
            if (!this.searchQuery.trim()) {
                return [];
            }
            const q = this.searchQuery.toLowerCase().trim();
            return this.allCharactersFlat.filter(c => {
                return c.character.includes(q) ||
                    c.pinyin.toLowerCase().includes(q) ||
                    c.sino_vietnamese.toLowerCase().includes(q) ||
                    c.meaning.toLowerCase().includes(q) ||
                    (c.group_name && c.group_name.toLowerCase().includes(q));
            });
        },

        get filteredRadicals() {
            return this.radicals.filter(r => {
                const matchSearch = !this.radicalSearch.trim() || 
                    r.radical.includes(this.radicalSearch) ||
                    r.sino_vietnamese.toLowerCase().includes(this.radicalSearch.toLowerCase()) ||
                    r.meaning.toLowerCase().includes(this.radicalSearch.toLowerCase()) ||
                    r.pinyin.toLowerCase().includes(this.radicalSearch.toLowerCase());
                
                const matchStroke = this.selectedStrokeFilter === 'all' || r.stroke_count === parseInt(this.selectedStrokeFilter);
                const matchCategory = this.selectedCategoryFilter === 'all' || r.category === this.selectedCategoryFilter;

                return matchSearch && matchStroke && matchCategory;
            });
        },

        selectRadical(radicalId) {
            if (this.radicalGroups[radicalId]) {
                this.selectedRadicalId = radicalId;
                const charKeys = Object.keys(this.radicalGroups[radicalId].characters || {});
                if (charKeys.length > 0) {
                    this.selectCharacter(charKeys[0]);
                }
                this.speak(this.radicalGroups[radicalId].radical);
            }
        },

        selectCharacter(charKey, radicalId = null) {
            if (radicalId && this.radicalGroups[radicalId]) {
                this.selectedRadicalId = radicalId;
            }

            const currentGroup = this.radicalGroups[this.selectedRadicalId];
            if (currentGroup && currentGroup.characters[charKey]) {
                this.selectedCharKey = charKey;
                this.currentChar = currentGroup.characters[charKey];
                this.resetStrokePlayer();
                this.clearCanvas();
                this.speak(this.currentChar.character);
            }
        },

        jumpToRadicalFrom214(r) {
            // Tìm nhóm bộ thủ tương ứng nếu có trong hệ thống
            let foundGroupId = null;

            if (this.radicalGroups[r.id]) {
                foundGroupId = r.id;
            } else {
                for (const [gid, g] of Object.entries(this.radicalGroups)) {
                    if (g.radical === r.radical || g.original === r.radical || g.sino_vietnamese === r.sino_vietnamese) {
                        foundGroupId = gid;
                        break;
                    }
                }
            }

            if (foundGroupId) {
                this.selectRadical(foundGroupId);
                this.activeTab = 'explorer';
            } else {
                // Nếu chưa có nhóm chữ chiết tự đầy đủ, phát âm bộ thủ
                this.speak(r.radical);
            }
        },

        speak(text) {
            if ('speechSynthesis' in window) {
                window.speechSynthesis.cancel();
                const utterance = new SpeechSynthesisUtterance(text);
                utterance.lang = 'zh-CN';
                utterance.rate = 0.85;
                window.speechSynthesis.speak(utterance);
            }
        },

        // =========================================================================
        // PHẦN VIẾT MẪU & BÚT THUẬN (STROKE ANIMATION PLAYER)
        // =========================================================================
        resetStrokePlayer() {
            this.stopStrokeAutoPlay();
            this.currentStrokeStep = 1;
        },

        prevStroke() {
            if (this.currentStrokeStep > 1) {
                this.currentStrokeStep--;
            } else {
                const max = this.currentChar?.stroke_steps?.length || this.currentChar?.stroke_count || 1;
                this.currentStrokeStep = max;
            }
        },

        nextStroke() {
            const max = this.currentChar?.stroke_steps?.length || this.currentChar?.stroke_count || 1;
            if (this.currentStrokeStep < max) {
                this.currentStrokeStep++;
            } else {
                this.currentStrokeStep = 1;
            }
        },

        jumpToStroke(stepNum) {
            this.stopStrokeAutoPlay();
            this.currentStrokeStep = stepNum;
        },

        toggleStrokeAutoPlay() {
            if (this.isPlayingStrokes) {
                this.stopStrokeAutoPlay();
            } else {
                this.isPlayingStrokes = true;
                const max = this.currentChar?.stroke_steps?.length || this.currentChar?.stroke_count || 1;
                this.strokeTimer = setInterval(() => {
                    if (this.currentStrokeStep < max) {
                        this.currentStrokeStep++;
                    } else {
                        this.currentStrokeStep = 1;
                    }
                }, this.strokeSpeed);
            }
        },

        stopStrokeAutoPlay() {
            this.isPlayingStrokes = false;
            if (this.strokeTimer) {
                clearInterval(this.strokeTimer);
                this.strokeTimer = null;
            }
        },

        setStrokeSpeed(speedMs) {
            this.strokeSpeed = speedMs;
            if (this.isPlayingStrokes) {
                this.stopStrokeAutoPlay();
                this.toggleStrokeAutoPlay();
            }
        },

        // =========================================================================
        // PHẦN KHUNG LUYỆN VIẾT CANVAS TƯƠNG TÁC (HANDWRITING CANVAS)
        // =========================================================================
        setupCanvas() {
            const canvasEl = document.getElementById('hanziWritingCanvas');
            if (!canvasEl) return;

            this.canvas = canvasEl;
            this.ctx = this.canvas.getContext('2d', { willReadFrequently: true });

            const rect = this.canvas.getBoundingClientRect();
            const dpr = window.devicePixelRatio || 1;
            
            this.canvas.width = rect.width * dpr;
            this.canvas.height = rect.height * dpr;
            this.ctx.scale(dpr, dpr);

            this.ctx.lineCap = 'round';
            this.ctx.lineJoin = 'round';
            this.applyBrushSettings();

            this.saveCanvasState();
        },

        applyBrushSettings() {
            if (!this.ctx) return;
            if (this.isEraser) {
                this.ctx.globalCompositeOperation = 'destination-out';
                this.ctx.lineWidth = this.brushSize * 2.5;
            } else {
                this.ctx.globalCompositeOperation = 'source-over';
                this.ctx.strokeStyle = this.brushColor;
                this.ctx.lineWidth = this.brushSize;
            }
        },

        getPointerPos(e) {
            if (!this.canvas) return { x: 0, y: 0 };
            const rect = this.canvas.getBoundingClientRect();
            let clientX, clientY;

            if (e.touches && e.touches.length > 0) {
                clientX = e.touches[0].clientX;
                clientY = e.touches[0].clientY;
            } else {
                clientX = e.clientX;
                clientY = e.clientY;
            }

            return {
                x: clientX - rect.left,
                y: clientY - rect.top
            };
        },

        startDrawing(e) {
            if (!this.ctx) this.setupCanvas();
            this.isDrawing = true;
            this.hasDrawn = true;
            this.feedbackMessage = '';
            
            const pos = this.getPointerPos(e);
            this.lastX = pos.x;
            this.lastY = pos.y;

            this.applyBrushSettings();
            this.ctx.beginPath();
            this.ctx.moveTo(this.lastX, this.lastY);
            this.ctx.lineTo(this.lastX + 0.1, this.lastY + 0.1);
            this.ctx.stroke();
        },

        draw(e) {
            if (!this.isDrawing || !this.ctx) return;
            e.preventDefault();

            const pos = this.getPointerPos(e);
            this.ctx.beginPath();
            this.ctx.moveTo(this.lastX, this.lastY);
            this.ctx.lineTo(pos.x, pos.y);
            this.ctx.stroke();

            this.lastX = pos.x;
            this.lastY = pos.y;
        },

        stopDrawing() {
            if (this.isDrawing) {
                this.isDrawing = false;
                this.saveCanvasState();
            }
        },

        setBrushColor(colorHex) {
            this.isEraser = false;
            this.brushColor = colorHex;
            this.applyBrushSettings();
        },

        setBrushSize(size) {
            this.brushSize = size;
            this.applyBrushSettings();
        },

        toggleEraser() {
            this.isEraser = !this.isEraser;
            this.applyBrushSettings();
        },

        saveCanvasState() {
            if (!this.canvas || !this.ctx) return;
            if (this.canvasHistory.length > 15) {
                this.canvasHistory.shift();
            }
            this.canvasHistory.push(this.ctx.getImageData(0, 0, this.canvas.width, this.canvas.height));
        },

        undoCanvas() {
            if (this.canvasHistory.length > 1) {
                this.canvasHistory.pop();
                const prevState = this.canvasHistory[this.canvasHistory.length - 1];
                this.ctx.putImageData(prevState, 0, 0);
            } else if (this.canvasHistory.length === 1) {
                this.clearCanvas();
            }
        },

        clearCanvas() {
            if (!this.ctx || !this.canvas) return;
            this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
            this.canvasHistory = [];
            this.saveCanvasState();
            this.hasDrawn = false;
            this.feedbackMessage = '';
        },

        checkHandwriting() {
            if (!this.hasDrawn) {
                this.feedbackMessage = 'Hãy viết nét chữ vào ô trước khi chấm!';
                this.feedbackType = 'info';
                return;
            }

            const praiseList = [
                'Nét bút rất có thần thái! Thuận bút chuẩn chỉnh.',
                'Tuyệt vời! Bố cục chữ cân đối đúng tinh thần chữ Hán.',
                'Rất chuẩn! Hãy luyện tập tiếp các chữ khác cùng bộ thủ nhé.'
            ];
            const randomIndex = Math.floor(Math.random() * praiseList.length);
            this.feedbackMessage = praiseList[randomIndex];
            this.feedbackType = 'success';
            this.speak('好');
        },

        // =========================================================================
        // XỬ LÝ MINI QUIZ BÀI TẬP CHIẾT TỰ
        // =========================================================================
        answerConfusing(chosenChar) {
            if (this.confusingAnswered !== null) return;
            this.confusingAnswered = chosenChar;
            const currentQ = this.confusingQuestions[this.confusingIndex];
            if (chosenChar === currentQ.correct) {
                this.totalScore += 10;
                this.speak('正确');
            } else {
                this.speak('错误');
            }
        },

        nextConfusing() {
            if (this.confusingIndex < this.confusingQuestions.length - 1) {
                this.confusingIndex++;
                this.confusingAnswered = null;
            } else {
                this.confusingIndex = 0;
                this.confusingAnswered = null;
            }
        },

        answerPredict(option) {
            if (this.predictAnswered !== null) return;
            this.predictAnswered = option;
            const currentQ = this.predictQuestions[this.predictIndex];
            if (option === currentQ.correct) {
                this.totalScore += 10;
                this.speak('正确');
            } else {
                this.speak('错误');
            }
        },

        nextPredict() {
            if (this.predictIndex < this.predictQuestions.length - 1) {
                this.predictIndex++;
                this.predictAnswered = null;
            } else {
                this.predictIndex = 0;
                this.predictAnswered = null;
            }
        },

        answerTypo(index) {
            if (this.typoAnswered !== null) return;
            this.typoAnswered = index;
            const currentQ = this.typoQuestions[this.typoIndex];
            if (index === currentQ.correctIndex) {
                this.totalScore += 10;
                this.speak('正确');
            } else {
                this.speak('错误');
            }
        },

        nextTypo() {
            if (this.typoIndex < this.typoQuestions.length - 1) {
                this.typoIndex++;
                this.typoAnswered = null;
            } else {
                this.typoIndex = 0;
                this.typoAnswered = null;
            }
        }
    };
}

export default hanziDecompositionApp;
