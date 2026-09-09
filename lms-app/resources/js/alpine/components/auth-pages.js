/**
 * Alpine Components cho các trang Xác thực độc lập (Auth Pages)
 */

export const authLoginPage = () => ({
    langOpen: false,
    currentLang: 'Việt Nam',
    darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
    showPassword: false
});

export const authRegisterPage = () => ({
    langOpen: false,
    currentLang: 'Việt Nam',
    darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
    showPassword: false,
    showConfirmPassword: false
});

export const authForgotPasswordPage = () => ({
    langOpen: false,
    currentLang: 'Việt Nam',
    darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
});

export const authResetPasswordPage = () => ({
    langOpen: false,
    currentLang: 'Việt Nam',
    darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
    showPassword: false,
    showConfirmPassword: false
});

export const authVerifyEmailPage = () => ({
    langOpen: false,
    currentLang: 'Việt Nam',
    darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
    showPassword: false,
    showConfirmPassword: false
});

export default {
    authLoginPage,
    authRegisterPage,
    authForgotPasswordPage,
    authResetPasswordPage,
    authVerifyEmailPage
};
