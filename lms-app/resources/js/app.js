import './bootstrap';
import AOS from 'aos';
import 'aos/dist/aos.css';
import '@fortawesome/fontawesome-free/js/all.min.js';
import Alpine from 'alpinejs';

AOS.init();
window.Alpine = Alpine;

Alpine.start();