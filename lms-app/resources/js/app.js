import './bootstrap';
import AOS from 'aos';
import 'aos/dist/aos.css';
import '@fortawesome/fontawesome-free/js/all.min.js';
import Alpine from 'alpinejs';

import './quiz-builder.js';
import './components/games/index.js';

AOS.init();

import hskIndex from './hsk-index.js';
import examTimer from './hsk-take.js';

if (!window.Alpine) {
    window.Alpine = Alpine;
    
    // Đăng ký các component Alpine trước khi start để tránh race condition
    Alpine.data('hskIndex', hskIndex);
    Alpine.data('examTimer', examTimer);

    Alpine.start();
}

import './lms.js';
