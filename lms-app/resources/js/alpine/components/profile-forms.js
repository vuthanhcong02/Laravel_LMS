/**
 * Alpine Components cho Quản lý Trang Cá Nhân (Profile Forms)
 */

export const userProfilePage = (tab = 'profile', name = '', avatar = '') => ({
    activeTab: tab,
    userName: name,
    userAvatar: avatar
});

export const passwordUpdateForm = (updateUrl = '/password') => ({
    showCurrent: false, 
    showNew: false, 
    showConfirm: false,
    loading: false,
    errors: {},
    successMessage: '',
    init() {
        this.$watch('successMessage', val => {
            if (val) setTimeout(() => this.successMessage = '', 3000);
        });
    },
    async submitPasswordForm() {
        this.loading = true;
        this.errors = {};
        this.successMessage = '';
        const form = document.getElementById('passwordForm');
        if (!form) return;
        const targetUrl = form.getAttribute('action') || updateUrl;
        const formData = new FormData(form);
        try {
            const response = await fetch(targetUrl, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            });
            const data = await response.json();
            if (response.ok && data.success) {
                this.successMessage = data.message;
                form.reset();
            } else if (response.status === 422) {
                for (const key in data.errors) {
                    this.errors[key] = data.errors[key][0];
                }
            } else {
                this.errors.general = data.message || 'Có lỗi xảy ra khi đổi mật khẩu.';
            }
        } catch (error) {
            console.error(error);
            this.errors.general = 'Lỗi kết nối mạng, vui lòng thử lại.';
        } finally {
            this.loading = false;
        }
    }
});

export const avatarUpload = (config = {}) => {
    const defaultUrl = typeof config === 'string' ? config : (config.defaultUrl || 'https://ui-avatars.com/api/?name=User&background=fdeae3&color=e07a5f');
    const updateUrl = typeof config === 'object' && config.updateUrl ? config.updateUrl : '/ho-so-ca-nhan';

    return {
        imageUrl: defaultUrl,
        hasNewImage: false,
        loading: false,
        errors: {},
        successMessage: '',
        init() {
            this.$watch('successMessage', val => {
                if (val) setTimeout(() => this.successMessage = '', 3000);
            });
        },
        fileChosen(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imageUrl = e.target.result;
                    this.hasNewImage = true;
                };
                reader.readAsDataURL(file);
            }
        },
        async submitProfileForm() {
            this.loading = true;
            this.errors = {};
            this.successMessage = '';
            const form = document.getElementById('profileForm');
            if (!form) return;
            const targetUrl = form.getAttribute('action') || updateUrl;
            const formData = new FormData(form);
            try {
                const response = await fetch(targetUrl, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                const data = await response.json();
                if (response.ok && data.success) {
                    this.successMessage = data.message;
                    this.hasNewImage = false;
                    if (data.user && data.user.avatar_url) {
                        this.imageUrl = data.user.avatar_url;
                    }
                    const fullName = data.user ? (data.user.first_name + ' ' + data.user.last_name).trim() : (formData.get('first_name') + ' ' + formData.get('last_name')).trim();
                    const newAvatar = (data.user && data.user.avatar_url) ? data.user.avatar_url : this.imageUrl;
                    window.dispatchEvent(new CustomEvent('profile-updated', {
                        detail: {
                            name: fullName,
                            avatar: newAvatar
                        }
                    }));
                } else if (response.status === 422) {
                    for (const key in data.errors) {
                        this.errors[key] = data.errors[key][0];
                    }
                } else {
                    this.errors.general = data.message || 'Có lỗi xảy ra khi lưu thông tin.';
                }
            } catch (error) {
                console.error(error);
                this.errors.general = 'Lỗi kết nối mạng, vui lòng thử lại.';
            } finally {
                this.loading = false;
            }
        }
    };
};

export default {
    userProfilePage,
    passwordUpdateForm,
    avatarUpload
};
