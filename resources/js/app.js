import './bootstrap';
import Alpine from 'alpinejs';
import habitTracker from './habitTracker';

Alpine.data('habitTracker', habitTracker);
window.Alpine = Alpine;
Alpine.start();