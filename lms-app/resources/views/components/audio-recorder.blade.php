<div x-data="audioRecorder('{{ $name ?? 'audio_file' }}')" class="space-y-3">
    <input type="file" name="{{ $name ?? 'audio_file' }}" x-ref="audioInput" class="hidden" accept="audio/*">
    <input type="file" x-ref="audioFileInput" class="hidden" accept="audio/*,.mp3,.wav,.ogg,.m4a,.webm,.weba,.aac" @change="handleFileUpload($event)">

    <div class="flex items-center gap-4 p-3 sm:p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-700 transition-all">
        <button type="button" 
                x-show="state === 'idle'" 
                @click="startRecording"
                class="size-11 sm:size-12 rounded-full bg-red-500 hover:bg-red-600 text-white flex items-center justify-center shadow-lg shadow-red-200 dark:shadow-none transition-all pulse-animation shrink-0 cursor-pointer">
            <span class="material-symbols-outlined text-2xl">mic</span>
        </button>

        <button type="button" 
                x-show="state === 'recording'" 
                @click="stopRecording"
                class="size-11 sm:size-12 rounded-full bg-slate-800 dark:bg-slate-200 text-white dark:text-slate-800 flex items-center justify-center shadow-lg transition-all animate-pulse shrink-0 cursor-pointer">
            <span class="material-symbols-outlined text-2xl">stop</span>
        </button>

        <div x-show="state === 'recording'" class="flex-1 flex items-center gap-3">
            <div class="flex gap-1 h-4 items-center">
                <template x-for="i in 4">
                    <div class="w-1 bg-red-500 rounded-full animate-bounce" :style="`animation-delay: ${i * 0.1}s; height: ${Math.random() * 100 + 50}%` "></div>
                </template>
            </div>
            <span class="text-sm font-black text-red-500 tabular-nums" x-text="formatTime(seconds)">00:00</span>
            <span class="text-xs font-bold text-slate-400 animate-pulse">Đang ghi âm...</span>
        </div>

        <div x-show="state === 'preview'" class="flex-1 flex items-center gap-3 min-w-0">
            <div class="flex-1 min-w-0">
                <p x-show="audioName" class="text-xs font-semibold text-slate-700 dark:text-slate-200 truncate mb-1" x-text="audioName"></p>
                <audio x-ref="audioPreview" controls class="w-full h-8 custom-audio-player"></audio>
            </div>
            <button type="button" 
                    @click="reset"
                    class="size-9 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-500 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 transition-all flex items-center justify-center shrink-0 cursor-pointer"
                    title="Xóa và chọn lại">
                <span class="material-symbols-outlined text-xl">delete</span>
            </button>
        </div>

        <div x-show="state === 'idle'" class="flex-1 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <p class="text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-300">Nhấn mic để ghi âm</p>
                <p class="text-[10px] text-slate-400 font-medium">Hoặc bạn có thể tải lên file âm thanh từ máy</p>
            </div>
            <button type="button" 
                    @click="$refs.audioFileInput.click()" 
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:text-primary hover:border-primary/50 transition-colors shadow-2xs shrink-0 self-start sm:self-auto cursor-pointer">
                <span class="material-symbols-outlined text-base text-primary">upload_file</span>
                <span>{{ __('Tải file audio') }}</span>
            </button>
        </div>
    </div>
</div>

<script>
function audioRecorder(name) {
    return {
        inputName: name,
        state: 'idle', // idle, recording, preview
        seconds: 0,
        timer: null,
        mediaRecorder: null,
        audioChunks: [],
        
        audioName: null,
        
        handleFileUpload(event) {
            const file = event.target.files && event.target.files[0];
            if (!file) return;

            if (file.size > 25 * 1024 * 1024) {
                alert('Dung lượng file audio không được vượt quá 25MB');
                event.target.value = '';
                return;
            }

            this.audioName = file.name;
            const audioUrl = URL.createObjectURL(file);
            if (this.$refs.audioPreview) {
                this.$refs.audioPreview.src = audioUrl;
            }

            try {
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                this.$refs.audioInput.files = dataTransfer.files;
            } catch (e) {
                console.error('DataTransfer assignment error:', e);
            }

            this.state = 'preview';
        },
        
        startRecording() {
            this.audioName = null;
            navigator.mediaDevices.getUserMedia({ audio: true })
                .then(stream => {
                    this.state = 'recording';
                    this.seconds = 0;
                    this.audioChunks = [];
                    
                    this.mediaRecorder = new MediaRecorder(stream);
                    this.mediaRecorder.ondataavailable = (event) => {
                        if (event.data && event.data.size > 0) {
                            this.audioChunks.push(event.data);
                        }
                    };
                    
                    this.mediaRecorder.onstop = () => {
                        const mimeType = (this.mediaRecorder.mimeType && this.mediaRecorder.mimeType.split(';')[0]) || 'audio/webm';
                        let ext = 'webm';
                        if (mimeType.includes('mp4') || mimeType.includes('aac')) {
                            ext = 'm4a';
                        } else if (mimeType.includes('ogg')) {
                            ext = 'ogg';
                        } else if (mimeType.includes('wav')) {
                            ext = 'wav';
                        }
                        
                        const audioBlob = new Blob(this.audioChunks, { type: mimeType });
                        const audioUrl = URL.createObjectURL(audioBlob);
                        if (this.$refs.audioPreview) {
                            this.$refs.audioPreview.src = audioUrl;
                        }
                        
                        // Create a file object to put in the hidden input
                        const file = new File([audioBlob], `recording_${Date.now()}.${ext}`, { type: mimeType });
                        try {
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(file);
                            this.$refs.audioInput.files = dataTransfer.files;
                        } catch (e) {
                            console.error('DataTransfer assignment error:', e);
                        }
                        
                        this.state = 'preview';
                        
                        // Stop all tracks to release microphone
                        stream.getTracks().forEach(track => track.stop());
                    };
                    
                    this.mediaRecorder.start(250);
                    this.startTimer();
                })
                .catch(err => {
                    alert('Không thể truy cập Microphone: ' + err.message);
                });
        },
        
        stopRecording() {
            if (this.mediaRecorder && this.state === 'recording') {
                this.mediaRecorder.stop();
                this.stopTimer();
            }
        },
        
        reset() {
            this.state = 'idle';
            this.seconds = 0;
            this.audioName = null;
            this.audioChunks = [];
            if (this.$refs.audioPreview) {
                this.$refs.audioPreview.src = '';
            }
            if (this.$refs.audioInput) {
                this.$refs.audioInput.value = '';
            }
            if (this.$refs.audioFileInput) {
                this.$refs.audioFileInput.value = '';
            }
        },
        
        startTimer() {
            this.stopTimer(); // Ensure no multiple timers
            this.timer = setInterval(() => {
                this.seconds++;
                if (this.seconds >= 300) { // Limit 5 minutes
                    this.stopRecording();
                }
            }, 1000);
        },
        
        stopTimer() {
            if (this.timer) {
                clearInterval(this.timer);
                this.timer = null;
            }
        },
        
        formatTime(sec) {
            const m = Math.floor(sec / 60);
            const s = sec % 60;
            return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
        }
    }
}
</script>

<style>
.pulse-animation:hover {
    box-shadow: 0 0 0 10px rgba(239, 68, 68, 0.1);
}
.custom-audio-player::-webkit-media-controls-enclosure {
    background-color: transparent;
}
</style>
