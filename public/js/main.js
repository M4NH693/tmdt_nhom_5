/**
 * BookStore - Main JavaScript
 * Optimally refactored using ES Modules
 */

import { initUI } from './modules/ui.js';
import { initAlerts } from './modules/alerts.js';
import { initPayment } from './modules/payment.js';
import { initAnimations } from './modules/animations.js';
import { initSearch } from './modules/search.js';
import { initProduct } from './modules/product.js';
import { initCart } from './modules/cart.js';

document.addEventListener('DOMContentLoaded', function () {
    // Initialize all modules
    initUI();
    initAlerts();
    initPayment();
    initAnimations();
    initSearch();
    initProduct();
    initCart();
});
