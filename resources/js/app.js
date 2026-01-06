import './bootstrap';

// Import Bootstrap JS
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

// Import and start Alpine BEFORE anything else
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
window.Alpine = Alpine;

// Register Alpine plugins
Alpine.plugin(collapse);

// Create global store for cart state with enhanced modal management
Alpine.store('cart', {
    open: false,
    
    toggle() {
        this.open = !this.open;
        // Close other modals when cart opens
        if (this.open) {
            const nav = document.querySelector('nav');
            if (nav && nav.__x && nav.__x.$data) {
                nav.__x.$data.open = false;
                nav.__x.$data.searchOpen = false;
            }
        }
    },
    
    close() {
        this.open = false;
    },
    
    openCart() {
        this.open = true;
        // Close other modals when cart opens
        const nav = document.querySelector('nav');
        if (nav && nav.__x && nav.__x.$data) {
            nav.__x.$data.open = false;
            nav.__x.$data.searchOpen = false;
        }
    }
});

// Alert Dialog Component Data
Alpine.data('alertDialog', () => ({
    isOpen: false,
    dialogType: 'info', // success, warning, danger, info
    dialogTitle: '',
    dialogMessage: '',
    confirmText: 'OK',
    cancelText: 'Cancel',
    showCancel: true,
    onConfirm: null,
    onCancel: null,
    
    show(options = {}) {
        this.dialogType = options.type || 'info';
        this.dialogTitle = options.title || 'Confirmation';
        this.dialogMessage = options.message || '';
        this.confirmText = options.confirmText || 'OK';
        this.cancelText = options.cancelText || 'Cancel';
        this.showCancel = options.showCancel !== false;
        this.onConfirm = options.onConfirm || null;
        this.onCancel = options.onCancel || null;
        this.isOpen = true;
        document.body.style.overflow = 'hidden';
    },
    
    close() {
        this.isOpen = false;
        document.body.style.overflow = '';
    },
    
    confirm() {
        if (this.onConfirm && typeof this.onConfirm === 'function') {
            this.onConfirm();
        }
        this.close();
    },
    
    cancel() {
        if (this.onCancel && typeof this.onCancel === 'function') {
            this.onCancel();
        }
        this.close();
    }
}));

// Global helper function to show alert dialog
window.showAlertDialog = function(options) {
    // Find the alert dialog component
    const dialogElement = document.querySelector('[x-data*="alertDialog"]');
    if (dialogElement && dialogElement.__x) {
        dialogElement.__x.$data.show(options);
    }
};

Alpine.start();

// Fade In Animation on Scroll
document.addEventListener('DOMContentLoaded', function() {
    const fadeElements = document.querySelectorAll('.animate-fade-in-up');
    
    const fadeObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                fadeObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    
    fadeElements.forEach(element => {
        fadeObserver.observe(element);
    });
});
