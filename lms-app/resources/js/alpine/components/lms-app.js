/**
 * Component quản lý thông báo Toast
 */
export const lmsToast = (duration = 5000) => ({
    showToast: true,
    init() {
        setTimeout(() => {
            this.showToast = false;
        }, duration);
    }
});

/**
 * Component quản lý toàn cục giao diện LMS (Sidebar, Dark Mode, Auth Modal, Language Dropdown)
 */
export const lmsApp = (initialState = {}) => ({
    sidebarOpen: false,
    sidebarCollapsed: false,
    isLoggedIn: initialState.isLoggedIn || false,
    langOpen: false,
    currentLang: 'Việt Nam',
    darkMode: localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
    socialDockExpanded: true,
    searchKeyword: '',
    authModalOpen: initialState.authModalOpen || false,
    authModalTab: initialState.authModalTab || 'login',
    authRedirectUrl: '',
    contactModalOpen: false,
    authEmail: '',
    authPassword: '',
    authFirstName: '',
    authLastName: '',
    authRemember: false,
    authShowPassword: false,
    authLoading: false,

    init() {
        this.$watch('darkMode', val => {
            localStorage.setItem('darkMode', val);
            if (val) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        });

        const preloader = document.getElementById('global-preloader');
        if (preloader) {
            setTimeout(() => {
                preloader.style.opacity = '0';
                setTimeout(() => preloader.remove(), 300);
            }, 100);
        }
    },

    openAuthModal(tab = 'login', redirect = '') {
        this.authModalOpen = true;
        if (tab) this.authModalTab = tab;
        if (redirect) this.authRedirectUrl = redirect;
    }
});

export default { lmsToast, lmsApp };
