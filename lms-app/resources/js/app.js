import './bootstrap';
import AOS from 'aos';
import 'aos/dist/aos.css';
import '@fortawesome/fontawesome-free/js/all.min.js';
import Alpine from 'alpinejs';

import './quiz-builder.js';
import './components/games/index.js';

AOS.init();

// Alpine Stores
import lessonStore from './alpine/stores/lesson.js';

// Alpine Components
import { lmsToast, lmsApp } from './alpine/components/lms-app.js';
import lessonStudyApp from './alpine/components/lesson-study.js';
import homeDailyVocab from './alpine/components/home-vocab.js';
import {
    authLoginPage,
    authRegisterPage,
    authForgotPasswordPage,
    authResetPasswordPage,
    authVerifyEmailPage
} from './alpine/components/auth-pages.js';
import {
    lmsScrollTop,
    contactModalForm,
    authForgotForm,
    sidebarUserProfile
} from './alpine/components/lms-components.js';
import { pinyinBoardApp, pinyinDragScroll, pinyinCrosshair } from './alpine/components/pinyin-chart.js';
import { pinyinQuizApp } from './alpine/components/pinyin-quiz.js';
import { userProfilePage, passwordUpdateForm, avatarUpload } from './alpine/components/profile-forms.js';
import hskIndex from './hsk-index.js';
import examTimer from './hsk-take.js';

if (!window.Alpine) {
    window.Alpine = Alpine;
    
    // Đăng ký Store tập trung
    Alpine.store('lesson', lessonStore);

    // Đăng ký các component Alpine trước khi start để tránh race condition
    Alpine.data('lmsToast', lmsToast);
    Alpine.data('lmsApp', lmsApp);
    Alpine.data('lessonStudyApp', lessonStudyApp);
    Alpine.data('homeDailyVocab', homeDailyVocab);
    Alpine.data('lmsScrollTop', lmsScrollTop);
    Alpine.data('contactModalForm', contactModalForm);
    Alpine.data('authForgotForm', authForgotForm);
    Alpine.data('sidebarUserProfile', sidebarUserProfile);
    Alpine.data('pinyinBoardApp', pinyinBoardApp);
    Alpine.data('pinyinDragScroll', pinyinDragScroll);
    Alpine.data('pinyinCrosshair', pinyinCrosshair);
    Alpine.data('pinyinQuizApp', pinyinQuizApp);
    Alpine.data('userProfilePage', userProfilePage);
    Alpine.data('passwordUpdateForm', passwordUpdateForm);
    Alpine.data('avatarUpload', avatarUpload);

    // Auth components
    Alpine.data('authLoginPage', authLoginPage);
    Alpine.data('authRegisterPage', authRegisterPage);
    Alpine.data('authForgotPage', authForgotPasswordPage);
    Alpine.data('authForgotPasswordPage', authForgotPasswordPage);
    Alpine.data('authResetPasswordPage', authResetPasswordPage);
    Alpine.data('authVerifyEmailPage', authVerifyEmailPage);

    // HSK components
    Alpine.data('hskIndex', hskIndex);
    Alpine.data('examTimer', examTimer);

    Alpine.start();
}

import './lms.js';
