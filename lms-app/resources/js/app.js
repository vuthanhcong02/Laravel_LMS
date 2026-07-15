import './bootstrap';
import AOS from 'aos';
import 'aos/dist/aos.css';
import '@fortawesome/fontawesome-free/js/all.min.js';
import Alpine from 'alpinejs';

import './quiz-builder.js';
import './components/games/index.js';

AOS.init();
window.Alpine = Alpine;

Alpine.start();
