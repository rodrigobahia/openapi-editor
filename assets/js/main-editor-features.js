// Função global para serializar dados de qualquer seção antes do submit
function serializeDataBeforeSubmit(form) {
    const section = form.querySelector('input[name="section"]')?.value;
    console.log('=== Serializando dados da seção:', section, '===');
    
    try {
        switch (section) {
            case 'header':
                return serializeHeaderData(form);
            case 'tags':
                return serializeTagsData(form);
            case 'main':
                return serializePathsData(form);
            case 'servers':
                return serializeServersData(form);
            case 'security':
                return serializeSecurityData(form);
            default:
                console.log('Seção não reconhecida, permitindo submit padrão');
                return true;
        }
    } catch (e) {
        console.error('Erro na serialização:', e);
        alert('Erro ao preparar dados para salvamento: ' + e.message);
        return false;
    }
}

// Função específica para serializar endpoints (antiga função)
function serializePathsBeforeSubmit() {
    try {
        console.log('=== Iniciando serialização dos endpoints ===');
        console.log('window.mainEditor:', window.mainEditor);
        console.log('window.openApiSpec:', window.openApiSpec);
        
        let paths;
        if (window.mainEditor && typeof window.mainEditor.getCurrentPaths === 'function') {
            paths = window.mainEditor.getCurrentPaths();
            console.log('Paths do mainEditor:', paths);
        } else if (window.openApiSpec && window.openApiSpec.paths) {
            paths = window.openApiSpec.paths;
            console.log('Paths do openApiSpec global:', paths);
        } else {
            console.error('Não foi possível encontrar os endpoints para serializar.');
            console.log('Tentando buscar paths de outras fontes...');
            // Tentar buscar do DOM como fallback
            paths = {};
            const endpointElements = document.querySelectorAll('.endpoint-group');
            console.log('Elementos de endpoint encontrados no DOM:', endpointElements.length);
            endpointElements.forEach(elem => {
                const path = elem.getAttribute('data-path');
                const method = elem.getAttribute('data-method');
                if (path && method) {
                    if (!paths[path]) paths[path] = {};
                    paths[path][method] = {
                        summary: elem.querySelector('.text-muted.small').textContent || 'Endpoint criado',
                        responses: { "200": { "description": "Successful response" } }
                    };
                }
            });
            console.log('Paths extraídos do DOM:', paths);
        }
        
        const pathsField = document.getElementById('paths-json');
        if (!pathsField) {
            console.error('Campo oculto paths-json não encontrado no formulário.');
            alert('Erro interno: campo de dados não encontrado.');
            return false;
        }
        
        const pathsJson = JSON.stringify(paths);
        pathsField.value = pathsJson;
        console.log('JSON serializado:', pathsJson);
        console.log('Campo paths-json preenchido com:', pathsField.value);
        console.log('=== Serialização concluída com sucesso ===');
        return true;
    } catch (e) {
        console.error('Erro ao serializar endpoints:', e);
        alert('Erro ao salvar endpoints: ' + e.message);
        return false;
    }
}

// Funções de serialização por seção
function serializeHeaderData(form) {
    console.log('Serializando dados do header');
    // Os dados do header já são enviados pelos campos do formulário
    // Não precisa de serialização especial
    return true;
}

function serializeTagsData(form) {
    console.log('=== Serializando dados das tags ===');
    
    // Debug: verificar elementos encontrados
    const tagItems = document.querySelectorAll('.tag-item');
    console.log('Tag items encontrados:', tagItems.length);
    
    const tags = [];
    tagItems.forEach((item, index) => {
        console.log(`Processando tag item ${index + 1}:`);
        
        // Buscar inputs de forma mais específica
        const nameInput = item.querySelector('input[name*="[name]"]') || item.querySelector('input[type="text"]');
        const descInput = item.querySelector('input[name*="[description]"]');
        const urlInput = item.querySelector('input[name*="[externalDocs][url]"]') || item.querySelector('input[type="url"]');
        const docDescInput = item.querySelector('input[name*="[externalDocs][description]"]:last-of-type');
        
        console.log('Campos encontrados:', {
            nameInput: nameInput?.name,
            nameValue: nameInput?.value,
            descInput: descInput?.name,
            descValue: descInput?.value
        });
        
        const name = nameInput?.value?.trim();
        if (name) {
            const tag = { name };
            
            const description = descInput?.value?.trim();
            if (description) {
                tag.description = description;
            }
            
            const url = urlInput?.value?.trim();
            const docDesc = docDescInput?.value?.trim();
            if (url || docDesc) {
                tag.externalDocs = {};
                if (url) tag.externalDocs.url = url;
                if (docDesc) tag.externalDocs.description = docDesc;
            }
            
            tags.push(tag);
            console.log(`Tag ${index + 1} adicionada:`, tag);
        } else {
            console.log(`Tag ${index + 1} ignorada - nome vazio`);
        }
    });
    
    let tagsField = form.querySelector('input[name="tags_data"]');
    if (!tagsField) {
        tagsField = document.createElement('input');
        tagsField.type = 'hidden';
        tagsField.name = 'tags_data';
        form.appendChild(tagsField);
        console.log('Campo oculto tags_data criado');
    }
    
    const tagsJson = JSON.stringify(tags);
    tagsField.value = tagsJson;
    
    console.log('JSON final das tags:', tagsJson);
    console.log('Campo tags_data preenchido:', tagsField.value);
    console.log('=== Serialização de tags concluída ===');
    
    return true;
}

function serializePathsData(form) {
    console.log('Serializando dados dos paths');
    return serializePathsBeforeSubmit();
}

function serializeServersData(form) {
    console.log('Serializando dados dos servers');
    // Coletar servidores da interface e serializar
    const servers = [];
    document.querySelectorAll('.server-item').forEach(item => {
        const url = item.querySelector('[data-field="url"]')?.textContent || item.querySelector('[data-field="url"]')?.value;
        const description = item.querySelector('[data-field="description"]')?.textContent || item.querySelector('[data-field="description"]')?.value;
        if (url) {
            servers.push({ url, description: description || '' });
        }
    });
    
    let serversField = form.querySelector('input[name="servers_data"]');
    if (!serversField) {
        serversField = document.createElement('input');
        serversField.type = 'hidden';
        serversField.name = 'servers_data';
        form.appendChild(serversField);
    }
    serversField.value = JSON.stringify(servers);
    console.log('Servers serializados:', servers);
    return true;
}

function serializeSecurityData(form) {
    console.log('Serializando dados de segurança');
    // Implementar quando necessário
    return true;
}
/**
 * Main Editor Implementation - Advanced Features
 * OpenAPI Editor - Complete Main Tab Functionality
 */

class MainEditorFeatures {
    // ...existing code...
    getCurrentPaths() {
        return this.currentSpec.paths || {};
    }
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