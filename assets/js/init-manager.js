/**
 * OpenAPI Editor - Initialization Manager
 * Manages proper initialization order and prevents conflicts
 */

// Global namespace for the application
window.OpenAPIEditor = window.OpenAPIEditor || {
    initialized: false,
    components: {},
    config: {
        appName: 'OpenAPI Editor',
        version: '1.0.0',
        environment: 'development',
        debug: true
    }
};

// Initialization Manager
class InitializationManager {
    constructor() {
        this.initQueue = [];
        this.initialized = new Set();
        this.dependencies = new Map();
        this.startInitialization();
    }

    /**
     * Register a component for initialization
     */
    register(name, initFunction, dependencies = []) {
        this.initQueue.push({
            name,
            initFunction,
            dependencies
        });
        this.dependencies.set(name, dependencies);
    }

    /**
     * Start the initialization process
     */
    startInitialization() {
        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                this.processQueue();
            });
        } else {
            this.processQueue();
        }
    }

    /**
     * Process initialization queue
     */
    processQueue() {
        let processed = true;
        
        while (processed && this.initQueue.length > 0) {
            processed = false;
            
            for (let i = 0; i < this.initQueue.length; i++) {
                const item = this.initQueue[i];
                
                // Check if all dependencies are met
                if (this.canInitialize(item)) {
                    try {
                        console.log(`🔧 Initializing ${item.name}...`);
                        item.initFunction();
                        this.initialized.add(item.name);
                        window.OpenAPIEditor.components[item.name] = true;
                        this.initQueue.splice(i, 1);
                        processed = true;
                        break;
                    } catch (error) {
                        console.error(`❌ Failed to initialize ${item.name}:`, error);
                        this.initQueue.splice(i, 1);
                        break;
                    }
                }
            }
        }
        
        // Log final status
        if (this.initQueue.length === 0) {
            console.log('✅ All components initialized successfully!');
            window.OpenAPIEditor.initialized = true;
            this.dispatchReadyEvent();
        } else {
            console.warn('⚠️ Some components could not be initialized:', 
                this.initQueue.map(item => item.name));
        }
    }

    /**
     * Check if component can be initialized
     */
    canInitialize(item) {
        return item.dependencies.every(dep => this.initialized.has(dep));
    }

    /**
     * Dispatch application ready event
     */
    dispatchReadyEvent() {
        window.dispatchEvent(new CustomEvent('openapi-editor-ready', {
            detail: {
                components: Array.from(this.initialized),
                config: window.OpenAPIEditor.config
            }
        }));
    }
}

// Create global initialization manager
const initManager = new InitializationManager();

// Safe component initialization helper
window.OpenAPIEditor.safeInit = function(name, initFunction, dependencies = []) {
    initManager.register(name, initFunction, dependencies);
};

// Utility functions
window.OpenAPIEditor.utils = {
    /**
     * Show notification
     */
    showNotification(message, type = 'info', duration = 3000) {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <div class="notification-icon">
                    <i class="fas fa-${this.getNotificationIcon(type)}"></i>
                </div>
                <div class="notification-message">${message}</div>
                <button class="notification-close" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Show notification
        setTimeout(() => notification.classList.add('show'), 10);
        
        // Auto hide
        if (duration > 0) {
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.parentElement.removeChild(notification);
                    }
                }, 300);
            }, duration);
        }
    },

    /**
     * Get notification icon based on type
     */
    getNotificationIcon(type) {
        const icons = {
            success: 'check-circle',
            error: 'exclamation-triangle',
            warning: 'exclamation-circle',
            info: 'info-circle'
        };
        return icons[type] || 'info-circle';
    },

    /**
     * Debounce function
     */
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    /**
     * Throttle function
     */
    throttle(func, limit) {
        let lastFunc;
        let lastRan;
        return function(...args) {
            if (!lastRan) {
                func.apply(this, args);
                lastRan = Date.now();
            } else {
                clearTimeout(lastFunc);
                lastFunc = setTimeout(() => {
                    if ((Date.now() - lastRan) >= limit) {
                        func.apply(this, args);
                        lastRan = Date.now();
                    }
                }, limit - (Date.now() - lastRan));
            }
        }
    },

    /**
     * Deep clone object
     */
    deepClone(obj) {
        if (obj === null || typeof obj !== 'object') return obj;
        if (obj instanceof Date) return new Date(obj);
        if (obj instanceof Array) return obj.map(item => this.deepClone(item));
        if (typeof obj === 'object') {
            const cloned = {};
            Object.keys(obj).forEach(key => {
                cloned[key] = this.deepClone(obj[key]);
            });
            return cloned;
        }
    }
};

// Enhanced error handling
window.addEventListener('error', function(event) {
    console.error('🚨 JavaScript Error:', {
        message: event.message,
        filename: event.filename,
        line: event.lineno,
        column: event.colno,
        error: event.error
    });
    
    if (window.OpenAPIEditor.config.debug) {
        window.OpenAPIEditor.utils.showNotification(
            `Error: ${event.message}`,
            'error',
            5000
        );
    }
});

// Enhanced unhandled promise rejection handling
window.addEventListener('unhandledrejection', function(event) {
    console.error('🚨 Unhandled Promise Rejection:', event.reason);
    
    if (window.OpenAPIEditor.config.debug) {
        window.OpenAPIEditor.utils.showNotification(
            `Promise Rejection: ${event.reason}`,
            'error',
            5000
        );
    }
});

// Application ready handler
window.addEventListener('openapi-editor-ready', function(event) {
    console.log('🚀 OpenAPI Editor is ready!', event.detail);
    
    // Initialize theme from localStorage
    const savedTheme = localStorage.getItem('openapi-editor-theme') || 'light';
    document.documentElement.setAttribute('data-bs-theme', savedTheme);
    
    // Initialize any post-load features
    if (typeof window.ultraModernFeatures === 'undefined' && typeof UltraModernFeatures === 'function') {
        window.ultraModernFeatures = new UltraModernFeatures();
    }
    
    // Initialize auto-save if available
    if (typeof window.autoSave === 'undefined' && typeof AutoSave === 'function') {
        window.autoSave = new AutoSave();
    }
});

console.log('🔧 OpenAPI Editor Initialization Manager loaded');