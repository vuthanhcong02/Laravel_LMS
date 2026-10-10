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
import headerStreakWidget from './alpine/components/header-streak.js';
import streakHeatmapWidget from './alpine/components/streak-heatmap.js';
import gamificationLeaderboard from './alpine/components/gamification-leaderboard.js';
import hskResultViewer from './alpine/components/hsk-result-viewer.js';
import sentenceBuilder from './alpine/components/sentence-builder.js';
import sentenceTopicSearch from './alpine/components/sentence-topic-search.js';
import hskIndex from './hsk-index.js';
import examTimer from './hsk-take.js';
import hskExamList from './alpine/components/hsk-exam-list.js';
import customFlashcardApp from './alpine/components/custom-flashcard.js';
import flashcardApp from './alpine/components/flashcard-app.js';
import teacherClassDetail from './alpine/components/teacher-class-detail.js';
import teacherQuizResults from './alpine/components/teacher-quiz-results.js';
import teacherScheduleModal from './alpine/components/teacher-schedule-modal.js';
import { filePreviewModal, fileUploadPreview } from './alpine/components/file-preview.js';

window.customFlashcardApp = customFlashcardApp;
window.flashcardApp = flashcardApp;
window.teacherClassDetail = teacherClassDetail;
window.teacherQuizResults = teacherQuizResults;
window.teacherScheduleModal = teacherScheduleModal;
window.headerStreakWidget = headerStreakWidget;
window.streakHeatmapWidget = streakHeatmapWidget;
window.gamificationLeaderboard = gamificationLeaderboard;
window.filePreviewModal = filePreviewModal;
window.fileUploadPreview = fileUploadPreview;


if (!window.Alpine) {
    window.Alpine = Alpine;
    
    // Register centralized Store
    Alpine.store('lesson', lessonStore);

    // Register Alpine components prior to starting Alpine to prevent race conditions
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
    Alpine.data('headerStreakWidget', headerStreakWidget);
    Alpine.data('streakHeatmapWidget', streakHeatmapWidget);
    Alpine.data('gamificationLeaderboard', gamificationLeaderboard);
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
    Alpine.data('hskResultViewer', hskResultViewer);
    Alpine.data('sentenceBuilder', sentenceBuilder);
    Alpine.data('sentenceTopicSearch', sentenceTopicSearch);
    Alpine.data('hskExamList', hskExamList);
    Alpine.data('customFlashcardApp', customFlashcardApp);
    Alpine.data('flashcardApp', flashcardApp);
    Alpine.data('teacherClassDetail', teacherClassDetail);
    Alpine.data('teacherQuizResults', teacherQuizResults);
    Alpine.data('teacherScheduleModal', teacherScheduleModal);
    Alpine.data('filePreviewModal', filePreviewModal);
    Alpine.data('fileUploadPreview', fileUploadPreview);

    Alpine.start();

}

import './lms.js';
