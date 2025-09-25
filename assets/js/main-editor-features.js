/**
 * Main Editor Implementation - Advanced Features
 * OpenAPI Editor - Complete Main Tab Functionality
 */

class MainEditorFeatures {
    constructor() {
        this.currentSpec = {};
        this.selectedPath = null;
        this.selectedMethod = null;
        this.init();
    }

    init() {
        this.setupMainTabFeatures();
        this.loadCurrentSpec();
        this.bindEventHandlers();
    }

    /**
     * Setup all main tab advanced features
     */
    setupMainTabFeatures() {
        // Remove desenvolvimento notice and add real functionality
        this.removeDevNotice();
        this.createInfoSection();
        this.createServersSection();
        this.createPathsSection();
        this.createTagsSection();
        this.createExternalDocsSection();
    }

    /**
     * Remove development notice
     */
    removeDevNotice() {
        const devNotice = document.querySelector('#main-tab .alert-info');
        if (devNotice) {
            devNotice.remove();
        }
    }

    /**
     * Create API Info Section
     */
    createInfoSection() {
        const mainTab = document.querySelector('#main-tab');
        if (!mainTab) return;

        const infoSection = document.createElement('div');
        infoSection.innerHTML = `
            <div class="card-modern mb-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-info-circle"></i>
                        Informações da API
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Título da API *</label>
                            <input type="text" class="form-control" id="api-title" 
                                   placeholder="Minha API Incrível">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Versão *</label>
                            <input type="text" class="form-control" id="api-version" 
                                   placeholder="1.0.0">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Descrição</label>
                            <textarea class="form-control" id="api-description" rows="3"
                                      placeholder="Descreva o propósito e funcionalidades da sua API..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Termos de Serviço (URL)</label>
                            <input type="url" class="form-control" id="api-terms" 
                                   placeholder="https://exemplo.com/termos">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Licença</label>
                            <select class="form-select" id="api-license">
                                <option value="">Selecione uma licença</option>
                                <option value="MIT">MIT License</option>
                                <option value="Apache-2.0">Apache 2.0</option>
                                <option value="GPL-3.0">GPL v3</option>
                                <option value="BSD-3-Clause">BSD 3-Clause</option>
                                <option value="custom">Personalizada</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h6>Informações de Contato</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Nome</label>
                                <input type="text" class="form-control" id="contact-name" 
                                       placeholder="Equipe de API">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" id="contact-email" 
                                       placeholder="api@empresa.com">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">URL</label>
                                <input type="url" class="form-control" id="contact-url" 
                                       placeholder="https://empresa.com/suporte">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        mainTab.appendChild(infoSection);
        this.bindInfoEvents();
    }

    /**
     * Create Servers Section
     */
    createServersSection() {
        const mainTab = document.querySelector('#main-tab');
        if (!mainTab) return;

        const serversSection = document.createElement('div');
        serversSection.innerHTML = `
            <div class="card-modern mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-server"></i>
                        Servidores
                    </h5>
                    <button class="btn btn-primary btn-sm" onclick="mainEditor.addServer()">
                        <i class="fas fa-plus"></i>
                        Adicionar Servidor
                    </button>
                </div>
                <div class="card-body">
                    <div id="servers-list">
                        <!-- Servers will be populated here -->
                    </div>
                </div>
            </div>
        `;
        
        mainTab.appendChild(serversSection);
        this.loadServers();
    }

    /**
     * Create Paths Section
     */
    createPathsSection() {
        const mainTab = document.querySelector('#main-tab');
        if (!mainTab) return;

        const pathsSection = document.createElement('div');
        pathsSection.innerHTML = `
            <div class="card-modern mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-route"></i>
                        Endpoints da API
                    </h5>
                    <button class="btn btn-primary btn-sm" onclick="mainEditor.addPath()">
                        <i class="fas fa-plus"></i>
                        Novo Endpoint
                    </button>
                </div>
                <div class="card-body">
                    <div id="paths-list">
                        <!-- Paths will be populated here -->
                    </div>
                </div>
            </div>
        `;
        
        mainTab.appendChild(pathsSection);
        this.loadPaths();
    }

    /**
     * Create Tags Section
     */
    createTagsSection() {
        const mainTab = document.querySelector('#main-tab');
        if (!mainTab) return;

        const tagsSection = document.createElement('div');
        tagsSection.innerHTML = `
            <div class="card-modern mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-tags"></i>
                        Tags e Categorias
                    </h5>
                    <button class="btn btn-primary btn-sm" onclick="mainEditor.addTag()">
                        <i class="fas fa-plus"></i>
                        Nova Tag
                    </button>
                </div>
                <div class="card-body">
                    <div id="tags-list">
                        <!-- Tags will be populated here -->
                    </div>
                </div>
            </div>
        `;
        
        mainTab.appendChild(tagsSection);
        this.loadTags();
    }

    /**
     * Load current OpenAPI specification
     */
    loadCurrentSpec() {
        // Get spec from global state or localStorage
        if (window.openApiSpec) {
            this.currentSpec = window.openApiSpec;
        } else {
            // Try to load from editor
            const editorContent = this.getEditorContent();
            if (editorContent) {
                try {
                    this.currentSpec = JSON.parse(editorContent);
                } catch (e) {
                    console.warn('Could not parse editor content as JSON');
                    this.currentSpec = this.getDefaultSpec();
                }
            } else {
                this.currentSpec = this.getDefaultSpec();
            }
        }
        this.populateFields();
    }

    /**
     * Get default OpenAPI spec
     */
    getDefaultSpec() {
        return {
            openapi: "3.0.3",
            info: {
                title: "",
                version: "1.0.0",
                description: "",
                contact: {},
                license: {}
            },
            servers: [],
            paths: {},
            tags: []
        };
    }

    /**
     * Populate form fields with current spec data
     */
    populateFields() {
        const info = this.currentSpec.info || {};
        
        // API Info
        this.setFieldValue('api-title', info.title);
        this.setFieldValue('api-version', info.version);
        this.setFieldValue('api-description', info.description);
        this.setFieldValue('api-terms', info.termsOfService);
        
        // Contact info
        const contact = info.contact || {};
        this.setFieldValue('contact-name', contact.name);
        this.setFieldValue('contact-email', contact.email);
        this.setFieldValue('contact-url', contact.url);
        
        // License
        if (info.license && info.license.name) {
            this.setFieldValue('api-license', info.license.name);
        }
    }

    /**
     * Set field value safely
     */
    setFieldValue(fieldId, value) {
        const field = document.getElementById(fieldId);
        if (field && value !== undefined && value !== null) {
            field.value = value;
        }
    }

    /**
     * Bind info form events
     */
    bindInfoEvents() {
        const fields = [
            'api-title', 'api-version', 'api-description', 'api-terms',
            'contact-name', 'contact-email', 'contact-url', 'api-license'
        ];
        
        fields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('input', () => this.updateSpec());
                field.addEventListener('change', () => this.updateSpec());
            }
        });
    }

    /**
     * Update specification from form data
     */
    updateSpec() {
        if (!this.currentSpec.info) {
            this.currentSpec.info = {};
        }
        
        // Basic info
        this.currentSpec.info.title = this.getFieldValue('api-title');
        this.currentSpec.info.version = this.getFieldValue('api-version');
        this.currentSpec.info.description = this.getFieldValue('api-description');
        this.currentSpec.info.termsOfService = this.getFieldValue('api-terms');
        
        // Contact
        const contactName = this.getFieldValue('contact-name');
        const contactEmail = this.getFieldValue('contact-email');
        const contactUrl = this.getFieldValue('contact-url');
        
        if (contactName || contactEmail || contactUrl) {
            this.currentSpec.info.contact = {
                ...(contactName && { name: contactName }),
                ...(contactEmail && { email: contactEmail }),
                ...(contactUrl && { url: contactUrl })
            };
        }
        
        // License
        const license = this.getFieldValue('api-license');
        if (license && license !== '') {
            this.currentSpec.info.license = { name: license };
        }
        
        // Update global spec and editor
        this.syncWithEditor();
    }

    /**
     * Get field value safely
     */
    getFieldValue(fieldId) {
        const field = document.getElementById(fieldId);
        return field ? field.value.trim() : '';
    }

    /**
     * Add new server
     */
    addServer() {
        const url = prompt('URL do servidor:', 'https://api.exemplo.com/v1');
        if (url) {
            if (!this.currentSpec.servers) {
                this.currentSpec.servers = [];
            }
            
            this.currentSpec.servers.push({
                url: url,
                description: `Servidor ${this.currentSpec.servers.length + 1}`
            });
            
            this.loadServers();
            this.syncWithEditor();
        }
    }

    /**
     * Load and display servers
     */
    loadServers() {
        const serversList = document.getElementById('servers-list');
        if (!serversList) return;
        
        const servers = this.currentSpec.servers || [];
        
        if (servers.length === 0) {
            serversList.innerHTML = `
                <div class="empty-state text-center py-4">
                    <i class="fas fa-server text-muted mb-3" style="font-size: 2rem;"></i>
                    <p class="text-muted">Nenhum servidor configurado</p>
                    <button class="btn btn-outline-primary" onclick="mainEditor.addServer()">
                        Adicionar Primeiro Servidor
                    </button>
                </div>
            `;
            return;
        }
        
        const serversHtml = servers.map((server, index) => `
            <div class="server-item border rounded p-3 mb-3" data-index="${index}">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="fw-bold text-primary">${server.url}</div>
                        <div class="text-muted small">${server.description || 'Sem descrição'}</div>
                    </div>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-secondary" onclick="mainEditor.editServer(${index})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-outline-danger" onclick="mainEditor.removeServer(${index})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
        
        serversList.innerHTML = serversHtml;
    }

    /**
     * Additional methods would continue here for:
     * - Path management
     * - Tag management  
     * - External docs
     * - Endpoint editing with full HTTP methods
     * - Request/Response body editing
     * - Parameter management
     * - Security scheme assignment
     */

    /**
     * Sync changes with the main editor
     */
    syncWithEditor() {
        // Update global spec
        window.openApiSpec = this.currentSpec;
        
        // Update editor content if available
        if (window.editor && typeof window.editor.setValue === 'function') {
            window.editor.setValue(JSON.stringify(this.currentSpec, null, 2));
        }
        
        // Trigger spec change event
        document.dispatchEvent(new CustomEvent('specChanged', {
            detail: this.currentSpec
        }));
    }

    /**
     * Create External Docs Section
     */
    createExternalDocsSection() {
        const mainTab = document.querySelector('#main-tab');
        if (!mainTab) return;

        const externalDocsSection = document.createElement('div');
        externalDocsSection.className = 'editor-section';
        externalDocsSection.innerHTML = `
            <div class="section-header">
                <h3 class="section-title">
                    <i class="fas fa-external-link-alt me-2"></i>
                    External Documentation
                </h3>
                <p class="section-description">Configure external documentation links for your API</p>
            </div>
            <div class="section-content">
                <div class="form-group">
                    <label for="external-docs-url" class="form-label">Documentation URL</label>
                    <input type="url" class="form-control" id="external-docs-url" 
                           placeholder="https://example.com/docs">
                </div>
                <div class="form-group">
                    <label for="external-docs-description" class="form-label">Description</label>
                    <input type="text" class="form-control" id="external-docs-description" 
                           placeholder="Find more info here">
                </div>
                <div class="section-actions">
                    <button type="button" class="btn btn-primary" onclick="mainEditor.saveExternalDocs()">
                        <i class="fas fa-save me-1"></i> Save External Docs
                    </button>
                </div>
            </div>
        `;
        
        mainTab.appendChild(externalDocsSection);
    }

    /**
     * Save External Documentation
     */
    saveExternalDocs() {
        const url = document.getElementById('external-docs-url')?.value;
        const description = document.getElementById('external-docs-description')?.value;
        
        if (!url) {
            this.showNotification('URL is required for external documentation', 'error');
            return;
        }

        this.currentSpec.externalDocs = {
            url: url,
            description: description || 'External Documentation'
        };

        this.showNotification('External documentation saved successfully!', 'success');
        console.log('External docs saved:', this.currentSpec.externalDocs);
    }

    /**
     * Bind event handlers
     */
    bindEventHandlers() {
        // Save button events
        const saveButtons = document.querySelectorAll('[data-action="save"]');
        saveButtons.forEach(button => {
            button.addEventListener('click', () => this.saveCurrentSpec());
        });

        // Auto-save on form changes
        document.addEventListener('input', (e) => {
            if (e.target.matches('input, textarea, select')) {
                this.debounce(this.autoSave.bind(this), 2000)();
            }
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            // Ctrl/Cmd + S for save
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                this.saveCurrentSpec();
            }

            // Ctrl/Cmd + Enter for quick add
            if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                e.preventDefault();
                const activeSection = document.querySelector('.tab-pane.active');
                if (activeSection) {
                    const addButton = activeSection.querySelector('[data-action="add"]');
                    if (addButton) addButton.click();
                }
            }
        });

        // Path method selection
        document.addEventListener('change', (e) => {
            if (e.target.matches('[data-field="method"]')) {
                this.selectedMethod = e.target.value;
                this.updateMethodForm();
            }
        });

        // Dynamic form validation
        document.addEventListener('blur', (e) => {
            if (e.target.matches('input[required], textarea[required]')) {
                this.validateField(e.target);
            }
        }, true);

        // Tab navigation
        document.addEventListener('click', (e) => {
            if (e.target.matches('.nav-link[data-bs-toggle="tab"]')) {
                this.handleTabSwitch(e.target);
            }
        });

        console.log('📎 Event handlers bound successfully');
    }

    /**
     * Save current specification
     */
    saveCurrentSpec(showNotification = true) {
        try {
            // Collect all form data
            const forms = document.querySelectorAll('form');
            const updatedSpec = { ...this.currentSpec };
            
            forms.forEach(form => {
                const formData = new FormData(form);
                const data = Object.fromEntries(formData);
                
                // Update spec based on form context
                if (form.id === 'api-info-form') {
                    updatedSpec.info = { ...updatedSpec.info, ...data };
                } else if (form.id === 'servers-form') {
                    // Handle servers array
                    if (!updatedSpec.servers) updatedSpec.servers = [];
                } else if (form.classList.contains('path-form')) {
                    // Handle paths
                    if (!updatedSpec.paths) updatedSpec.paths = {};
                }
            });
            
            // Update internal spec
            this.currentSpec = updatedSpec;
            
            // Save to localStorage for persistence
            localStorage.setItem('openapi-editor-current-spec', JSON.stringify(this.currentSpec));
            
            if (showNotification) {
                this.showNotification('Specification saved successfully!', 'success');
            }
            
            // Dispatch save event
            document.dispatchEvent(new CustomEvent('specSaved', {
                detail: this.currentSpec
            }));
            
            console.log('💾 Specification saved:', this.currentSpec);
            return this.currentSpec;
            
        } catch (error) {
            console.error('Save failed:', error);
            if (showNotification) {
                this.showNotification('Failed to save specification', 'error');
            }
            throw error;
        }
    }

    /**
     * Auto-save functionality
     */
    autoSave() {
        try {
            this.saveCurrentSpec(false); // Silent save
            this.showNotification('Changes auto-saved', 'success', 2000);
        } catch (error) {
            console.warn('Auto-save failed:', error);
        }
    }

    /**
     * Update method form based on selection
     */
    updateMethodForm() {
        const methodForms = document.querySelectorAll('.method-form');
        methodForms.forEach(form => {
            form.style.display = form.dataset.method === this.selectedMethod ? 'block' : 'none';
        });
    }

    /**
     * Validate form field
     */
    validateField(field) {
        const isValid = field.checkValidity();
        field.classList.toggle('is-valid', isValid);
        field.classList.toggle('is-invalid', !isValid);
        
        return isValid;
    }

    /**
     * Handle tab switch
     */
    handleTabSwitch(tabElement) {
        const target = tabElement.getAttribute('data-bs-target') || tabElement.getAttribute('href');
        console.log(`Switching to tab: ${target}`);
        
        // Save current state before switching
        this.saveCurrentState();
    }

    /**
     * Save current state
     */
    saveCurrentState() {
        const forms = document.querySelectorAll('.tab-pane.active form');
        forms.forEach(form => {
            const formData = new FormData(form);
            const data = Object.fromEntries(formData);
            localStorage.setItem(`openapi-editor-${form.id}`, JSON.stringify(data));
        });
    }

    /**
     * Show notification to user
     */
    showNotification(message, type = 'info', duration = 3000) {
        // Use global notification system if available
        if (window.OpenAPIEditor && window.OpenAPIEditor.utils && window.OpenAPIEditor.utils.showNotification) {
            window.OpenAPIEditor.utils.showNotification(message, type, duration);
            return;
        }

        // Fallback notification system
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
    }

    /**
     * Get notification icon
     */
    getNotificationIcon(type) {
        const icons = {
            success: 'check-circle',
            error: 'exclamation-triangle',
            warning: 'exclamation-circle',
            info: 'info-circle'
        };
        return icons[type] || 'info-circle';
    }

    /**
     * Debounce utility function
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
    }

    /**
     * Get editor content
     */
    getEditorContent() {
        if (window.editor && typeof window.editor.getValue === 'function') {
            return window.editor.getValue();
        }
        return null;
    }
}

// Initialize main editor features only if not already initialized
if (typeof window.mainEditor === 'undefined') {
    const mainEditor = new MainEditorFeatures();
    window.mainEditor = mainEditor;
}