<div x-data="audioRecorder('{{ $name ?? 'audio_file' }}')" class="space-y-3">
    <!-- Hidden input to store the audio file -->
    <input type="file" :name="inputName" x-ref="audioInput" class="hidden" accept="audio/*">

    <div class="flex items-center gap-4 p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-700 transition-all">
        <!-- Record Button -->
        <button type="button" 
                x-show="state === 'idle'" 
                @click="startRecording"
                class="size-12 rounded-full bg-red-500 hover:bg-red-600 text-white flex items-center justify-center shadow-lg shadow-red-200 dark:shadow-none transition-all pulse-animation">
            <span class="material-symbols-outlined text-2xl">mic</span>
        </button>

        <!-- Stop Button -->
        <button type="button" 
                x-show="state === 'recording'" 
                @click="stopRecording"
                class="size-12 rounded-full bg-slate-800 dark:bg-slate-200 text-white dark:text-slate-800 flex items-center justify-center shadow-lg transition-all animate-pulse">
            <span class="material-symbols-outlined text-2xl">stop</span>
        </button>

        <!-- Recording Indicator -->
        <div x-show="state === 'recording'" class="flex-1 flex items-center gap-3">
            <div class="flex gap-1 h-4 items-center">
                <template x-for="i in 4">
                    <div class="w-1 bg-red-500 rounded-full animate-bounce" :style="`animation-delay: ${i * 0.1}s; height: ${Math.random() * 100 + 50}%` "></div>
                </template>
            </div>
            <span class="text-sm font-black text-red-500 tabular-nums" x-text="formatTime(seconds)">00:00</span>
            <span class="text-xs font-bold text-slate-400 animate-pulse">Đang ghi âm...</span>
        </div>

        <!-- Preview State -->
        <div x-show="state === 'preview'" class="flex-1 flex items-center gap-3">
            <div class="flex-1">
                <audio x-ref="audioPreview" controls class="w-full h-8 custom-audio-player"></audio>
            </div>
            <button type="button" 
                    @click="reset"
                    class="size-10 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-500 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 transition-all flex items-center justify-center"
                    title="Xóa và ghi lại">
                <span class="material-symbols-outlined text-xl">delete</span>
            </button>
        </div>

        <!-- Idle Text -->
        <div x-show="state === 'idle'" class="flex-1">
            <p class="text-sm font-bold text-slate-600 dark:text-slate-400">Nhấn để bắt đầu ghi âm</p>
            <p class="text-[10px] text-slate-400 font-medium">Sử dụng micrô trên thiết bị của bạn</p>
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
        
        startRecording() {
            navigator.mediaDevices.getUserMedia({ audio: true })
                .then(stream => {
                    this.state = 'recording';
                    this.seconds = 0;
                    this.audioChunks = [];
                    
                    this.mediaRecorder = new MediaRecorder(stream);
                    this.mediaRecorder.ondataavailable = (event) => {
                        this.audioChunks.push(event.data);
                    };
                    
                    this.mediaRecorder.onstop = () => {
                        const audioBlob = new Blob(this.audioChunks, { type: 'audio/webm' });
                        const audioUrl = URL.createObjectURL(audioBlob);
                        this.$refs.audioPreview.src = audioUrl;
                        
                        // Create a file object to put in the hidden input
                        const file = new File([audioBlob], `recording_${Date.now()}.webm`, { type: 'audio/webm' });
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        this.$refs.audioInput.files = dataTransfer.files;
                        
                        this.state = 'preview';
                        
                        // Stop all tracks to release microphone
                        stream.getTracks().forEach(track => track.stop());
                    };
                    
                    this.mediaRecorder.start();
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
            this.audioChunks = [];
            if (this.$refs.audioPreview) {
                this.$refs.audioPreview.src = '';
            }
            if (this.$refs.audioInput) {
                this.$refs.audioInput.value = '';
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
