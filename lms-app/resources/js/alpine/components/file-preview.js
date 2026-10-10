/**
 * Attachment Preview & Live Upload Preview Alpine Component
 */

export function filePreviewModal() {
    return {
        isOpen: false,
        fileUrl: '',
        fileName: '',
        fileTypeLabel: '',

        openPreview(detail) {
            this.fileUrl = detail.url || '';
            this.fileName = detail.name || '';
            this.fileTypeLabel = this.getTypeLabel(this.fileName);
            this.isOpen = true;
            document.body.classList.add('overflow-hidden');
        },

        closePreview() {
            this.isOpen = false;
            if (this.$refs.audioPlayer) {
                this.$refs.audioPlayer.pause();
                this.$refs.audioPlayer.currentTime = 0;
            }
            document.body.classList.remove('overflow-hidden');
        },

        isImage(name) {
            if (!name) return false;
            const ext = name.split('.').pop().toLowerCase();
            return ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg', 'bmp'].includes(ext);
        },

        isAudio(name) {
            if (!name) return false;
            const ext = name.split('.').pop().toLowerCase();
            return ['mp3', 'wav', 'ogg', 'webm', 'm4a', 'weba', 'aac', 'flac'].includes(ext);
        },

        isPdf(name) {
            if (!name) return false;
            const ext = name.split('.').pop().toLowerCase();
            return ext === 'pdf';
        },

        getFileIcon(name) {
            if (!name) return 'description';
            const ext = name.split('.').pop().toLowerCase();
            if (['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg', 'bmp'].includes(ext)) return 'image';
            if (['mp3', 'wav', 'ogg', 'webm', 'm4a', 'weba', 'aac', 'flac'].includes(ext)) return 'audio_file';
            if (ext === 'pdf') return 'picture_as_pdf';
            if (['doc', 'docx'].includes(ext)) return 'article';
            if (['xls', 'xlsx'].includes(ext)) return 'table_view';
            if (['zip', 'rar', '7z', 'tar', 'gz'].includes(ext)) return 'folder_zip';
            return 'description';
        },

        getTypeLabel(name) {
            if (!name) return '';
            const ext = name.split('.').pop().toLowerCase();
            if (['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg', 'bmp'].includes(ext)) return 'Hình ảnh (' + ext.toUpperCase() + ')';
            if (['mp3', 'wav', 'ogg', 'webm', 'm4a', 'weba', 'aac', 'flac'].includes(ext)) return 'Tệp Âm thanh (' + ext.toUpperCase() + ')';
            if (ext === 'pdf') return 'Tài liệu PDF';
            if (['doc', 'docx'].includes(ext)) return 'Tài liệu Word (' + ext.toUpperCase() + ')';
            if (['xls', 'xlsx'].includes(ext)) return 'Bảng tính Excel (' + ext.toUpperCase() + ')';
            if (['zip', 'rar', '7z', 'tar', 'gz'].includes(ext)) return 'Tệp nén (' + ext.toUpperCase() + ')';
            return 'Tệp đính kèm (' + ext.toUpperCase() + ')';
        }
    };
}

export function fileUploadPreview(config = {}) {
    return {
        files: [],
        isDragging: false,
        maxFiles: config.maxFiles || 10,
        maxSizeMB: config.maxSizeMB || 20,

        handleFiles(fileList) {
            const incoming = Array.from(fileList);
            for (let f of incoming) {
                if (this.files.length >= this.maxFiles) {
                    window.dispatchEvent(new CustomEvent('notify', {
                        detail: { msg: `Chỉ được chọn tối đa ${this.maxFiles} tệp.`, type: 'warning' }
                    }));
                    break;
                }

                // Check file size (MB)
                const sizeMB = f.size / (1024 * 1024);
                if (sizeMB > this.maxSizeMB) {
                    window.dispatchEvent(new CustomEvent('notify', {
                        detail: { msg: `Tệp "${f.name}" vượt quá dung lượng tối đa (${this.maxSizeMB}MB).`, type: 'warning' }
                    }));
                    continue;
                }

                const ext = f.name.split('.').pop().toLowerCase();
                const isImg = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg', 'bmp'].includes(ext);
                const isAud = ['mp3', 'wav', 'ogg', 'webm', 'm4a', 'weba', 'aac', 'flac'].includes(ext);
                const isPdf = ext === 'pdf';
                const previewUrl = URL.createObjectURL(f);

                this.files.push({
                    file: f,
                    name: f.name,
                    sizeFormatted: this.formatSize(f.size),
                    ext: ext.toUpperCase(),
                    isImg,
                    isAud,
                    isPdf,
                    previewUrl,
                    icon: this.getIcon(ext)
                });
            }

            this.syncInputFiles();
        },

        removeFile(index) {
            if (this.files[index]?.previewUrl) {
                URL.revokeObjectURL(this.files[index].previewUrl);
            }
            this.files.splice(index, 1);
            this.syncInputFiles();
        },

        syncInputFiles() {
            const input = this.$refs.fileInput;
            if (!input) return;
            const dt = new DataTransfer();
            this.files.forEach(item => {
                if (item.file) {
                    dt.items.add(item.file);
                }
            });
            input.files = dt.files;
        },

        previewSelectedFile(item) {
            window.dispatchEvent(new CustomEvent('open-file-preview', {
                detail: {
                    url: item.previewUrl,
                    name: item.name
                }
            }));
        },

        formatSize(bytes) {
            if (!bytes || bytes === 0) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
        },

        getIcon(ext) {
            ext = ext.toLowerCase();
            if (['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg', 'bmp'].includes(ext)) return 'image';
            if (['mp3', 'wav', 'ogg', 'webm', 'm4a', 'weba', 'aac', 'flac'].includes(ext)) return 'audio_file';
            if (ext === 'pdf') return 'picture_as_pdf';
            if (['doc', 'docx'].includes(ext)) return 'article';
            if (['xls', 'xlsx'].includes(ext)) return 'table_view';
            if (['zip', 'rar', '7z'].includes(ext)) return 'folder_zip';
            return 'description';
        }
    };
}
