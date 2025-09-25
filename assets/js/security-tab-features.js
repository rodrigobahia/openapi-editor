/**
 * Security Tab Implementation - Complete Security Features
 * OpenAPI Editor - Advanced Security Management
 */

class SecurityTabFeatures {
    constructor() {
        this.securitySchemes = new Map();
        this.currentSpec = {};
        this.init();
    }

    init() {
        this.setupSecurityTab();
        this.loadCurrentSpec();
        this.bindEventHandlers();
    }

    /**
     * Setup complete security tab functionality
     */
    setupSecurityTab() {
        this.removeDevNotice();
        this.createSecuritySchemesSection();
        this.createGlobalSecuritySection();
        this.createSecurityAuditSection();
        this.createBestPracticesSection();
    }

    /**
     * Remove development notice
     */
    removeDevNotice() {
        const devNotice = document.querySelector('#security-tab .alert-info');
        if (devNotice) {
            devNotice.remove();
        }
    }

    /**
     * Create Security Schemes Management Section
     */
    createSecuritySchemesSection() {
        const securityTab = document.querySelector('#security-tab');
        if (!securityTab) return;

        const schemesSection = document.createElement('div');
        schemesSection.innerHTML = `
            <div class="card-modern mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-key"></i>
                        Esquemas de Segurança
                    </h5>
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-plus"></i>
                            Adicionar Esquema
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="securityTab.addSecurityScheme('apiKey')">
                                <i class="fas fa-key me-2"></i>API Key
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="securityTab.addSecurityScheme('oauth2')">
                                <i class="fas fa-shield-alt me-2"></i>OAuth 2.0
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="securityTab.addSecurityScheme('http')">
                                <i class="fas fa-lock me-2"></i>HTTP Authentication
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="securityTab.addSecurityScheme('openIdConnect')">
                                <i class="fas fa-id-badge me-2"></i>OpenID Connect
                            </a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div id="security-schemes-list">
                        <!-- Security schemes will be populated here -->
                    </div>
                </div>
            </div>
        `;
        
        securityTab.appendChild(schemesSection);
        this.loadSecuritySchemes();
    }

    /**
     * Create Global Security Requirements Section
     */
    createGlobalSecuritySection() {
        const securityTab = document.querySelector('#security-tab');
        if (!securityTab) return;

        const globalSection = document.createElement('div');
        globalSection.innerHTML = `
            <div class="card-modern mb-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-globe"></i>
                        Segurança Global da API
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">
                        Configure os requisitos de segurança que se aplicam a toda a API por padrão.
                    </p>
                    <div id="global-security-list">
                        <!-- Global security requirements will be populated here -->
                    </div>
                    <button class="btn btn-outline-primary" onclick="securityTab.addGlobalSecurity()">
                        <i class="fas fa-plus me-2"></i>
                        Adicionar Requisito Global
                    </button>
                </div>
            </div>
        `;
        
        securityTab.appendChild(globalSection);
        this.loadGlobalSecurity();
    }

    /**
     * Create Security Audit Section
     */
    createSecurityAuditSection() {
        const securityTab = document.querySelector('#security-tab');
        if (!securityTab) return;

        const auditSection = document.createElement('div');
        auditSection.innerHTML = `
            <div class="card-modern card-status warning mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-shield-virus"></i>
                        Auditoria de Segurança
                    </h5>
                    <button class="btn btn-warning" onclick="securityTab.runSecurityAudit()">
                        <i class="fas fa-search me-2"></i>
                        Executar Auditoria
                    </button>
                </div>
                <div class="card-body">
                    <div id="security-audit-results">
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-shield-check" style="font-size: 2rem;"></i>
                            <p class="mt-2">Execute uma auditoria para verificar a segurança da sua API</p>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        securityTab.appendChild(auditSection);
    }

    /**
     * Create Security Best Practices Section
     */
    createBestPracticesSection() {
        const securityTab = document.querySelector('#security-tab');
        if (!securityTab) return;

        const practicesSection = document.createElement('div');
        practicesSection.innerHTML = `
            <div class="card-modern mb-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-lightbulb"></i>
                        Boas Práticas de Segurança
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="best-practice-item">
                                <h6><i class="fas fa-check-circle text-success me-2"></i>HTTPS Obrigatório</h6>
                                <p class="text-muted small">Sempre use HTTPS em produção para proteger dados em trânsito.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="best-practice-item">
                                <h6><i class="fas fa-check-circle text-success me-2"></i>Autenticação Forte</h6>
                                <p class="text-muted small">Implemente OAuth 2.0 ou JWT para autenticação robusta.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="best-practice-item">
                                <h6><i class="fas fa-check-circle text-success me-2"></i>Rate Limiting</h6>
                                <p class="text-muted small">Configure limites de taxa para prevenir abuso da API.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="best-practice-item">
                                <h6><i class="fas fa-check-circle text-success me-2"></i>Validação de Input</h6>
                                <p class="text-muted small">Valide todos os inputs para prevenir ataques de injeção.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h6>Verificações Automáticas</h6>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="auto-https-check" checked>
                            <label class="form-check-label" for="auto-https-check">
                                Verificar HTTPS em servidores automaticamente
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="auto-auth-check" checked>
                            <label class="form-check-label" for="auto-auth-check">
                                Alertar sobre endpoints sem autenticação
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="auto-sensitive-check" checked>
                            <label class="form-check-label" for="auto-sensitive-check">
                                Detectar campos sensíveis não protegidos
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        securityTab.appendChild(practicesSection);
    }

    /**
     * Add new security scheme
     */
    addSecurityScheme(type) {
        let schemeConfig = {};
        
        switch (type) {
            case 'apiKey':
                schemeConfig = this.configureApiKeyScheme();
                break;
            case 'oauth2':
                schemeConfig = this.configureOAuth2Scheme();
                break;
            case 'http':
                schemeConfig = this.configureHttpScheme();
                break;
            case 'openIdConnect':
                schemeConfig = this.configureOpenIdConnectScheme();
                break;
        }
        
        if (schemeConfig) {
            const schemeName = prompt('Nome do esquema de segurança:', `${type}_auth`);
            if (schemeName) {
                this.saveSecurityScheme(schemeName, schemeConfig);
            }
        }
    }

    /**
     * Configure API Key security scheme
     */
    configureApiKeyScheme() {
        const name = prompt('Nome do parâmetro da API Key:', 'X-API-Key');
        const location = prompt('Localização (header, query, cookie):', 'header');
        
        if (name && ['header', 'query', 'cookie'].includes(location)) {
            return {
                type: 'apiKey',
                name: name,
                in: location,
                description: `API Key authentication via ${location}`
            };
        }
        
        return null;
    }

    /**
     * Configure OAuth2 security scheme
     */
    configureOAuth2Scheme() {
        const authUrl = prompt('URL de autorização:', 'https://auth.exemplo.com/oauth/authorize');
        const tokenUrl = prompt('URL do token:', 'https://auth.exemplo.com/oauth/token');
        
        if (authUrl && tokenUrl) {
            return {
                type: 'oauth2',
                description: 'OAuth 2.0 Authentication',
                flows: {
                    authorizationCode: {
                        authorizationUrl: authUrl,
                        tokenUrl: tokenUrl,
                        scopes: {
                            'read': 'Read access',
                            'write': 'Write access',
                            'admin': 'Administrative access'
                        }
                    }
                }
            };
        }
        
        return null;
    }

    /**
     * Configure HTTP authentication scheme
     */
    configureHttpScheme() {
        const scheme = prompt('Esquema HTTP (bearer, basic, digest):', 'bearer');
        
        if (['bearer', 'basic', 'digest'].includes(scheme)) {
            const config = {
                type: 'http',
                scheme: scheme,
                description: `HTTP ${scheme.charAt(0).toUpperCase() + scheme.slice(1)} authentication`
            };
            
            if (scheme === 'bearer') {
                const format = prompt('Formato do bearer (opcional, ex: JWT):', 'JWT');
                if (format) {
                    config.bearerFormat = format;
                }
            }
            
            return config;
        }
        
        return null;
    }

    /**
     * Configure OpenID Connect scheme
     */
    configureOpenIdConnectScheme() {
        const openIdConnectUrl = prompt('URL de descoberta OpenID Connect:', 
            'https://auth.exemplo.com/.well-known/openid_configuration');
        
        if (openIdConnectUrl) {
            return {
                type: 'openIdConnect',
                openIdConnectUrl: openIdConnectUrl,
                description: 'OpenID Connect authentication'
            };
        }
        
        return null;
    }

    /**
     * Save security scheme to specification
     */
    saveSecurityScheme(name, scheme) {
        if (!this.currentSpec.components) {
            this.currentSpec.components = {};
        }
        
        if (!this.currentSpec.components.securitySchemes) {
            this.currentSpec.components.securitySchemes = {};
        }
        
        this.currentSpec.components.securitySchemes[name] = scheme;
        this.securitySchemes.set(name, scheme);
        
        this.loadSecuritySchemes();
        this.syncWithEditor();
    }

    /**
     * Load and display security schemes
     */
    loadSecuritySchemes() {
        const schemesList = document.getElementById('security-schemes-list');
        if (!schemesList) return;
        
        const schemes = this.currentSpec.components?.securitySchemes || {};
        
        if (Object.keys(schemes).length === 0) {
            schemesList.innerHTML = `
                <div class="empty-state text-center py-4">
                    <i class="fas fa-key text-muted mb-3" style="font-size: 2rem;"></i>
                    <p class="text-muted">Nenhum esquema de segurança configurado</p>
                    <p class="text-muted small">Adicione esquemas de autenticação para proteger sua API</p>
                </div>
            `;
            return;
        }
        
        const schemesHtml = Object.entries(schemes).map(([name, scheme]) => `
            <div class="security-scheme-item border rounded p-3 mb-3" data-scheme="${name}">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-primary">${scheme.type.toUpperCase()}</span>
                            <strong>${name}</strong>
                        </div>
                        <div class="text-muted small mb-2">
                            ${scheme.description || 'Sem descrição'}
                        </div>
                        ${this.getSchemeDetails(scheme)}
                    </div>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-secondary" onclick="securityTab.editSecurityScheme('${name}')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-outline-danger" onclick="securityTab.removeSecurityScheme('${name}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
        
        schemesList.innerHTML = schemesHtml;
    }

    /**
     * Get scheme-specific details HTML
     */
    getSchemeDetails(scheme) {
        switch (scheme.type) {
            case 'apiKey':
                return `<div class="small text-info">
                    <i class="fas fa-info-circle"></i>
                    Parâmetro: <code>${scheme.name}</code> em <code>${scheme.in}</code>
                </div>`;
            
            case 'oauth2':
                const scopes = scheme.flows?.authorizationCode?.scopes || {};
                return `<div class="small text-info">
                    <i class="fas fa-info-circle"></i>
                    OAuth 2.0 com ${Object.keys(scopes).length} escopo(s)
                </div>`;
            
            case 'http':
                return `<div class="small text-info">
                    <i class="fas fa-info-circle"></i>
                    HTTP ${scheme.scheme.toUpperCase()}${scheme.bearerFormat ? ` (${scheme.bearerFormat})` : ''}
                </div>`;
            
            case 'openIdConnect':
                return `<div class="small text-info">
                    <i class="fas fa-info-circle"></i>
                    OpenID Connect Discovery
                </div>`;
            
            default:
                return '';
        }
    }

    /**
     * Run security audit
     */
    runSecurityAudit() {
        if (window.securityManager) {
            const auditResults = window.securityManager.runSecurityAudit(this.currentSpec);
            this.displayAuditResults(auditResults);
        } else {
            console.error('Security Manager not available');
        }
    }

    /**
     * Display audit results
     */
    displayAuditResults(results) {
        const auditContainer = document.getElementById('security-audit-results');
        if (!auditContainer) return;

        const { summary, issues } = results;
        
        let severityClass = 'success';
        if (summary.critical > 0) severityClass = 'danger';
        else if (summary.high > 0) severityClass = 'warning';
        else if (summary.medium > 0) severityClass = 'info';

        const auditHtml = `
            <div class="audit-summary mb-3">
                <div class="row g-2">
                    <div class="col">
                        <div class="badge bg-danger">${summary.critical} Críticos</div>
                    </div>
                    <div class="col">
                        <div class="badge bg-warning">${summary.high} Altos</div>
                    </div>
                    <div class="col">
                        <div class="badge bg-info">${summary.medium} Médios</div>
                    </div>
                    <div class="col">
                        <div class="badge bg-secondary">${summary.low} Baixos</div>
                    </div>
                </div>
            </div>
            
            ${issues.length > 0 ? `
                <div class="audit-issues">
                    ${issues.slice(0, 5).map(issue => `
                        <div class="alert alert-${this.getSeverityClass(issue.severity)} py-2">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-${this.getSeverityIcon(issue.severity)} me-2 mt-1"></i>
                                <div>
                                    <strong>${issue.ruleName}</strong><br>
                                    <small>${issue.path}: ${issue.message}</small>
                                    ${issue.suggestion ? `<br><small class="text-muted">💡 ${issue.suggestion}</small>` : ''}
                                </div>
                            </div>
                        </div>
                    `).join('')}
                    
                    ${issues.length > 5 ? `
                        <div class="text-center">
                            <button class="btn btn-outline-secondary btn-sm" onclick="securityTab.showFullAuditReport()">
                                Ver todos os ${issues.length} problemas
                            </button>
                        </div>
                    ` : ''}
                </div>
            ` : `
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    Parabéns! Nenhum problema de segurança detectado.
                </div>
            `}
        `;
        
        auditContainer.innerHTML = auditHtml;
    }

    getSeverityClass(severity) {
        const classes = {
            'critical': 'danger',
            'high': 'warning', 
            'medium': 'info',
            'low': 'secondary'
        };
        return classes[severity] || 'secondary';
    }

    getSeverityIcon(severity) {
        const icons = {
            'critical': 'exclamation-triangle',
            'high': 'exclamation-circle',
            'medium': 'info-circle',
            'low': 'question-circle'
        };
        return icons[severity] || 'info-circle';
    }

    /**
     * Load current specification
     */
    loadCurrentSpec() {
        if (window.openApiSpec) {
            this.currentSpec = window.openApiSpec;
        } else if (window.mainEditor && window.mainEditor.currentSpec) {
            this.currentSpec = window.mainEditor.currentSpec;
        } else {
            this.currentSpec = { components: { securitySchemes: {} } };
        }
    }

    /**
     * Sync with editor
     */
    syncWithEditor() {
        window.openApiSpec = this.currentSpec;
        
        if (window.editor && typeof window.editor.setValue === 'function') {
            window.editor.setValue(JSON.stringify(this.currentSpec, null, 2));
        }

        document.dispatchEvent(new CustomEvent('specChanged', {
            detail: this.currentSpec
        }));
    }
}

// Initialize security tab features only if not already initialized
if (typeof window.securityTab === 'undefined') {
    const securityTab = new SecurityTabFeatures();
    window.securityTab = securityTab;
}

// Export for global access
window.securityTab = securityTab;