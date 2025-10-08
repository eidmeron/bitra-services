import './bootstrap';
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
import persist from '@alpinejs/persist';

// Import Alpine components
import formBuilder from './alpine/form-builder';
import priceCalculator from './alpine/price-calculator';

// Register Alpine plugins
Alpine.plugin(focus);
Alpine.plugin(persist);

// Register Alpine components
Alpine.data('formBuilder', formBuilder);
Alpine.data('priceCalculator', priceCalculator);

// Start Alpine
window.Alpine = Alpine;
Alpine.start();
