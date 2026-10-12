/**
 * Alpine.js component: Copy link to clipboard
 *
 * @param {Object} config
 * @param {string} config.url
 * @returns {Object}
 */
export default function copyLinkButton(config = {}) {
    return {
        url: config.url || '',
        copied: false,
        timeoutId: null,

        async copy() {
            if (!this.url) return;

            try {
                if (navigator.clipboard && window.isSecureContext) {
                    await navigator.clipboard.writeText(this.url);
                } else {
                    // Fallback for non-HTTPS or legacy browsers
                    const textArea = document.createElement('textarea');
                    textArea.value = this.url;
                    textArea.style.position = 'fixed';
                    textArea.style.left = '-999999px';
                    textArea.style.top = '-999999px';
                    document.body.appendChild(textArea);
                    textArea.focus();
                    textArea.select();
                    document.execCommand('copy');
                    textArea.remove();
                }

                this.copied = true;
                if (this.timeoutId) clearTimeout(this.timeoutId);
                this.timeoutId = setTimeout(() => {
                    this.copied = false;
                }, 2000);
            } catch (err) {
                console.error('Failed to copy link: ', err);
            }
        }
    };
}
