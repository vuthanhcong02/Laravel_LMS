/**
 * Alpine Components cho các UI Component của LMS (ScrollTop, ContactModal, AuthForgotForm, SidebarUserProfile)
 */

export const lmsScrollTop = (threshold = 250, targetSelector = 'main > div.overflow-y-auto') => ({
    show: false,
    threshold: threshold,
    targetSelector: targetSelector,
    init() {
        const container = document.querySelector(this.targetSelector);
        if (container) {
            container.addEventListener('scroll', () => {
                this.show = container.scrollTop > this.threshold;
            }, { passive: true });
        }
        window.addEventListener('scroll', () => {
            const top = window.pageYOffset || document.documentElement.scrollTop;
            this.show = top > this.threshold;
        }, { passive: true });
    },
    scrollToTop() {
        const container = document.querySelector(this.targetSelector);
        if (container) {
            container.scrollTo({ top: 0, behavior: 'smooth' });
        }
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
});

export const contactModalForm = (config = {}) => {
    const initialName = typeof config === 'string' ? config : (config.initialName || '');
    const initialEmail = config.initialEmail || '';
    const submitUrl = config.submitUrl || '/lien-he';

    return {
        name: initialName,
        email: initialEmail,
        phone: '',
        topic: 'tu-van',
        message: '',
        website: '',
        loading: false,
        success: false,
        errorMessage: '',
        fieldErrors: {},

        resetForm() {
            this.name = initialName;
            this.email = initialEmail;
            this.phone = '';
            this.topic = 'tu-van';
            this.message = '';
            this.website = '';
            this.success = false;
            this.errorMessage = '';
            this.fieldErrors = {};
        },

        submitForm() {
            this.loading = true;
            this.errorMessage = '';
            this.fieldErrors = {};
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            fetch(submitUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify({
                    name: this.name,
                    email: this.email,
                    phone: this.phone,
                    topics: [this.topic],
                    message: this.message,
                    website: this.website
                })
            })
            .then(async response => {
                const data = await response.json();
                if (response.ok && data.success) {
                    this.success = true;
                    this.message = '';
                } else if (response.status === 429) {
                    this.errorMessage = 'Bạn đã gửi yêu cầu quá nhiều lần liên tiếp. Vui lòng chờ 1 phút trước khi thử lại.';
                } else if (response.status === 422) {
                    this.fieldErrors = data.errors || {};
                    this.errorMessage = 'Vui lòng kiểm tra lại thông tin nhập bên dưới.';
                } else {
                    this.errorMessage = data.message || 'Đã có lỗi xảy ra, vui lòng thử lại sau.';
                }
            })
            .catch(err => {
                console.error('Contact Form Error:', err);
                this.errorMessage = 'Không thể kết nối đến máy chủ. Vui lòng thử lại sau.';
            })
            .finally(() => {
                this.loading = false;
            });
        }
    };
};

export const authForgotForm = (submitUrl = '/forgot-password') => ({
    forgotLoading: false,
    forgotStatus: null,
    forgotError: null,
    async submitForgot() {
        if (this.forgotLoading) return;
        this.forgotLoading = true;
        this.forgotStatus = null;
        this.forgotError = null;
        try {
            const emailInput = this.authEmail || this.$root?.closest('[x-data]')?.__x?.$data?.authEmail || '';
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const response = await fetch(submitUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ email: emailInput })
            });
            const data = await response.json();
            if (response.ok) {
                this.forgotStatus = data.status || 'Đã gửi liên kết khôi phục.';
            } else {
                this.forgotError = data.message || (data.errors && data.errors.email ? data.errors.email[0] : 'Có lỗi xảy ra.');
            }
        } catch (e) {
            this.forgotError = 'Lỗi kết nối mạng, vui lòng thử lại.';
        }
        this.forgotLoading = false;
    }
});

export const sidebarUserProfile = (name = '', avatar = '') => ({
    userName: name,
    userAvatar: avatar
});

export default {
    lmsScrollTop,
    contactModalForm,
    authForgotForm,
    sidebarUserProfile
};
