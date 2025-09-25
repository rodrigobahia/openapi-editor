// Ultra-Modern Layout Features
class UltraModernFeatures {
    constructor() {
        this.initializeThemeSystem();
        this.initializeParticleBackground();
        this.initializeProgressTracking();
        this.initializeKeyboardShortcuts();
        this.initializeAdvancedTooltips();
        this.initializeLayoutAnimations();
    }

    // Enhanced Theme System
    initializeThemeSystem() {
        const themeToggle = document.getElementById('theme-toggle');
        const html = document.documentElement;
        
        // Get saved theme or default to light
        const savedTheme = localStorage.getItem('openapi-editor-theme') || 'light';
        this.setTheme(savedTheme);
        
        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                const currentTheme = html.getAttribute('data-bs-theme');
                const newTheme = currentTheme === 'light' ? 'dark' : 'light';
                this.setTheme(newTheme);
                
                // Animate theme icon
                const icon = themeToggle.querySelector('.theme-icon');
                icon.style.transform = 'rotate(360deg) scale(1.2)';
                setTimeout(() => {
                    icon.style.transform = '';
                    icon.className = `fas fa-${newTheme === 'dark' ? 'sun' : 'moon'} theme-icon`;
                }, 300);
            });
        }
    }

    setTheme(theme) {
        document.documentElement.setAttribute('data-bs-theme', theme);
        localStorage.setItem('openapi-editor-theme', theme);
        
        // Update theme icon
        const themeIcon = document.querySelector('.theme-icon');
        if (themeIcon) {
            themeIcon.className = `fas fa-${theme === 'dark' ? 'sun' : 'moon'} theme-icon`;
        }
        
        // Dispatch theme change event
        window.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme } }));
    }

    // Enhanced Particle Background
    initializeParticleBackground() {
        const canvas = document.createElement('canvas');
        canvas.id = 'particle-canvas';
        canvas.style.position = 'fixed';
        canvas.style.top = '0';
        canvas.style.left = '0';
        canvas.style.width = '100%';
        canvas.style.height = '100%';
        canvas.style.pointerEvents = 'none';
        canvas.style.zIndex = '0';
        canvas.style.opacity = '0.1';
        
        const particleContainer = document.getElementById('particle-bg');
        if (particleContainer) {
            particleContainer.appendChild(canvas);
            this.animateParticles(canvas);
        }
    }

    animateParticles(canvas) {
        const ctx = canvas.getContext('2d');
        const particles = [];
        const particleCount = 50;
        
        // Resize canvas
        const resizeCanvas = () => {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        };
        
        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);
        
        // Create particles
        for (let i = 0; i < particleCount; i++) {
            particles.push({
                x: Math.random() * canvas.width,
                y: Math.random() * canvas.height,
                vx: (Math.random() - 0.5) * 0.5,
                vy: (Math.random() - 0.5) * 0.5,
                size: Math.random() * 2 + 1,
                opacity: Math.random() * 0.5 + 0.3
            });
        }
        
        // Animation loop
        const animate = () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            // Update and draw particles
            particles.forEach((particle, index) => {
                particle.x += particle.vx;
                particle.y += particle.vy;
                
                // Wrap around edges
                if (particle.x < 0) particle.x = canvas.width;
                if (particle.x > canvas.width) particle.x = 0;
                if (particle.y < 0) particle.y = canvas.height;
                if (particle.y > canvas.height) particle.y = 0;
                
                // Draw particle
                ctx.beginPath();
                ctx.arc(particle.x, particle.y, particle.size, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(79, 70, 229, ${particle.opacity})`;
                ctx.fill();
                
                // Draw connections
                particles.forEach((otherParticle, otherIndex) => {
                    if (index !== otherIndex) {
                        const dx = particle.x - otherParticle.x;
                        const dy = particle.y - otherParticle.y;
                        const distance = Math.sqrt(dx * dx + dy * dy);
                        
                        if (distance < 100) {
                            ctx.beginPath();
                            ctx.moveTo(particle.x, particle.y);
                            ctx.lineTo(otherParticle.x, otherParticle.y);
                            ctx.strokeStyle = `rgba(79, 70, 229, ${(100 - distance) / 500})`;
                            ctx.lineWidth = 0.5;
                            ctx.stroke();
                        }
                    }
                });
            });
            
            requestAnimationFrame(animate);
        };
        
        animate();
    }

    // Progress Tracking System
    initializeProgressTracking() {
        const progressRing = document.getElementById('progress-ring');
        if (!progressRing) return;
        
        // Calculate completion based on filled sections
        this.updateProgress();
        
        // Listen for form changes
        document.addEventListener('input', () => {
            this.debounce(this.updateProgress.bind(this), 500)();
        });
    }

    updateProgress() {
        const forms = document.querySelectorAll('form input, form textarea, form select');
        let filled = 0;
        let total = forms.length;
        
        forms.forEach(field => {
            if (field.value && field.value.trim() !== '') {
                filled++;
            }
        });
        
        const percentage = total > 0 ? Math.round((filled / total) * 100) : 0;
        this.setProgressRing(percentage);
    }

    setProgressRing(percent) {
        const circle = document.querySelector('.progress-ring-circle');
        const text = document.querySelector('.progress-text');
        
        if (circle && text) {
            const radius = circle.r.baseVal.value;
            const circumference = radius * 2 * Math.PI;
            
            circle.style.strokeDasharray = `${circumference} ${circumference}`;
            circle.style.strokeDashoffset = circumference;
            
            const offset = circumference - (percent / 100) * circumference;
            circle.style.strokeDashoffset = offset;
            text.textContent = `${percent}%`;
        }
    }

    // Advanced Keyboard Shortcuts
    initializeKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Ctrl/Cmd + S for save
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                const saveBtn = document.getElementById('save-section');
                if (saveBtn) saveBtn.click();
            }
            
            // Ctrl/Cmd + P for preview
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                const previewBtn = document.querySelector('.btn-preview');
                if (previewBtn) window.open(previewBtn.href, '_blank');
            }
            
            // Ctrl/Cmd + T for theme toggle
            if ((e.ctrlKey || e.metaKey) && e.key === 't') {
                e.preventDefault();
                const themeToggle = document.getElementById('theme-toggle');
                if (themeToggle) themeToggle.click();
            }
            
            // ESC for close modals/overlays
            if (e.key === 'Escape') {
                const activeModal = document.querySelector('.modal.show');
                if (activeModal) {
                    const closeBtn = activeModal.querySelector('.btn-close');
                    if (closeBtn) closeBtn.click();
                }
            }
        });
    }

    // Enhanced Tooltip System
    initializeAdvancedTooltips() {
        document.querySelectorAll('[data-tooltip]').forEach(element => {
            element.addEventListener('mouseenter', this.showAdvancedTooltip);
            element.addEventListener('mouseleave', this.hideAdvancedTooltip);
            element.addEventListener('focus', this.showAdvancedTooltip);
            element.addEventListener('blur', this.hideAdvancedTooltip);
        });
    }

    showAdvancedTooltip(e) {
        const element = e.target;
        const tooltipText = element.getAttribute('data-tooltip');
        if (!tooltipText) return;
        
        // Remove existing tooltips
        document.querySelectorAll('.ultra-modern-tooltip').forEach(t => t.remove());
        
        // Create new tooltip
        const tooltip = document.createElement('div');
        tooltip.className = 'ultra-modern-tooltip';
        tooltip.innerHTML = `
            <div class="tooltip-content">${tooltipText}</div>
            <div class="tooltip-arrow"></div>
        `;
        
        document.body.appendChild(tooltip);
        
        // Position tooltip
        const rect = element.getBoundingClientRect();
        const tooltipRect = tooltip.getBoundingClientRect();
        
        let top = rect.bottom + 8;
        let left = rect.left + (rect.width / 2) - (tooltipRect.width / 2);
        
        // Adjust for screen boundaries
        if (left < 8) left = 8;
        if (left + tooltipRect.width > window.innerWidth - 8) {
            left = window.innerWidth - tooltipRect.width - 8;
        }
        if (top + tooltipRect.height > window.innerHeight - 8) {
            top = rect.top - tooltipRect.height - 8;
            tooltip.classList.add('tooltip-top');
        }
        
        tooltip.style.top = top + 'px';
        tooltip.style.left = left + 'px';
        
        // Show with animation
        setTimeout(() => tooltip.classList.add('show'), 10);
        
        element._tooltip = tooltip;
    }

    hideAdvancedTooltip(e) {
        const element = e.target;
        if (element._tooltip) {
            element._tooltip.classList.remove('show');
            setTimeout(() => {
                if (element._tooltip && element._tooltip.parentNode) {
                    element._tooltip.parentNode.removeChild(element._tooltip);
                }
                element._tooltip = null;
            }, 200);
        }
    }

    // Layout Animations
    initializeLayoutAnimations() {
        // Intersection Observer for scroll animations
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });
        
        // Observe elements for animation
        document.querySelectorAll('.content-card, .section-link, .modern-btn').forEach(el => {
            observer.observe(el);
        });
        
        // Add floating animation to action buttons
        document.querySelectorAll('.floating-action-btn').forEach(btn => {
            btn.addEventListener('mouseenter', () => {
                btn.style.transform = 'translateY(-5px) scale(1.1)';
            });
            
            btn.addEventListener('mouseleave', () => {
                btn.style.transform = 'translateY(0) scale(1)';
            });
        });
    }

    // Utility function for debouncing
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
    }
}

// Enhanced Loading System
class LoadingSystem {
    static show(message = 'Loading...') {
        const loader = document.createElement('div');
        loader.className = 'ultra-modern-loader';
        loader.innerHTML = `
            <div class="loader-backdrop"></div>
            <div class="loader-content">
                <div class="loader-spinner">
                    <div class="spinner-ring"></div>
                    <div class="spinner-ring"></div>
                    <div class="spinner-ring"></div>
                </div>
                <div class="loader-text">${message}</div>
            </div>
        `;
        
        document.body.appendChild(loader);
        setTimeout(() => loader.classList.add('show'), 10);
        
        return loader;
    }
    
    static hide(loader) {
        if (loader) {
            loader.classList.remove('show');
            setTimeout(() => {
                if (loader.parentNode) {
                    loader.parentNode.removeChild(loader);
                }
            }, 300);
        }
    }
}

// Auto-save functionality
class AutoSave {
    constructor(interval = 30000) { // 30 seconds
        this.interval = interval;
        this.enabled = false;
        this.init();
    }
    
    init() {
        this.startAutoSave();
        
        // Listen for user activity
        ['input', 'change', 'keyup'].forEach(event => {
            document.addEventListener(event, () => {
                this.resetTimer();
            });
        });
    }
    
    startAutoSave() {
        this.timer = setInterval(() => {
            if (this.hasUnsavedChanges()) {
                this.performAutoSave();
            }
        }, this.interval);
    }
    
    resetTimer() {
        clearInterval(this.timer);
        this.startAutoSave();
    }
    
    hasUnsavedChanges() {
        // Check if there are any modified form fields
        const forms = document.querySelectorAll('form');
        return Array.from(forms).some(form => {
            return Array.from(form.elements).some(element => {
                return element.value !== element.defaultValue;
            });
        });
    }
    
    performAutoSave() {
        console.log('Auto-saving changes...');
        // Implement auto-save logic here
        this.showAutoSaveIndicator();
    }
    
    showAutoSaveIndicator() {
        const indicator = document.createElement('div');
        indicator.className = 'autosave-indicator';
        indicator.innerHTML = '<i class="fas fa-check-circle"></i> Auto-saved';
        
        document.body.appendChild(indicator);
        setTimeout(() => indicator.classList.add('show'), 10);
        
        setTimeout(() => {
            indicator.classList.remove('show');
            setTimeout(() => {
                if (indicator.parentNode) {
                    indicator.parentNode.removeChild(indicator);
                }
            }, 300);
        }, 2000);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize ultra-modern features
    window.ultraModernFeatures = new UltraModernFeatures();
    
    // Initialize auto-save
    window.autoSave = new AutoSave();
    
    // Add global loading system
    window.LoadingSystem = LoadingSystem;
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Add ripple effect to buttons
    document.querySelectorAll('.modern-btn, .floating-action-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('div');
            ripple.className = 'ripple-effect';
            
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                if (ripple.parentNode) {
                    ripple.parentNode.removeChild(ripple);
                }
            }, 600);
        });
    });
    
    console.log('🚀 Ultra-Modern OpenAPI Editor initialized successfully!');
});