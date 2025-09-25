/**
 * Advanced OpenAPI Editor Features
 * Main editor functionality with enhanced capabilities
 */

class AdvancedOpenAPIEditor {
    constructor() {
        this.spec = {};
        this.history = [];
        this.historyIndex = -1;
        this.autoSave = true;
        this.validators = new Map();
        this.plugins = new Map();
        this.init();
    }

    init() {
        this.setupValidators();
        this.setupAutoComplete();
        this.setupLivePreview();
        this.setupCollaboration();
        this.enableAdvancedFeatures();
    }

    /**
     * Setup advanced validators for OpenAPI spec
     */
    setupValidators() {
        // OpenAPI 3.0.3 structure validator
        this.validators.set('structure', {
            validate: (spec) => this.validateOpenAPIStructure(spec),
            severity: 'error'
        });

        // Schema consistency validator
        this.validators.set('schema', {
            validate: (spec) => this.validateSchemaConsistency(spec),
            severity: 'warning'
        });

        // Business logic validator
        this.validators.set('business', {
            validate: (spec) => this.validateBusinessLogic(spec),
            severity: 'info'
        });

        // Performance validator
        this.validators.set('performance', {
            validate: (spec) => this.validatePerformance(spec),
            severity: 'warning'
        });
    }

    /**
     * Validate OpenAPI structure compliance
     */
    validateOpenAPIStructure(spec) {
        const errors = [];
        
        // Check required root fields
        const requiredFields = ['openapi', 'info', 'paths'];
        requiredFields.forEach(field => {
            if (!spec[field]) {
                errors.push({
                    path: field,
                    message: `Required field '${field}' is missing`,
                    type: 'structure'
                });
            }
        });

        // Validate OpenAPI version
        if (spec.openapi && !spec.openapi.startsWith('3.0')) {
            errors.push({
                path: 'openapi',
                message: 'OpenAPI version should be 3.0.x',
                type: 'structure'
            });
        }

        // Validate info object
        if (spec.info) {
            if (!spec.info.title) {
                errors.push({
                    path: 'info.title',
                    message: 'API title is required',
                    type: 'structure'
                });
            }
            if (!spec.info.version) {
                errors.push({
                    path: 'info.version',
                    message: 'API version is required',
                    type: 'structure'
                });
            }
        }

        return errors;
    }

    /**
     * Validate schema consistency across the API
     */
    validateSchemaConsistency(spec) {
        const warnings = [];
        const schemas = spec.components?.schemas || {};
        const usedSchemas = new Set();
        const definedSchemas = new Set(Object.keys(schemas));

        // Find schema references in paths
        this.findSchemaReferences(spec.paths || {}, usedSchemas);

        // Check for unused schemas
        definedSchemas.forEach(schema => {
            if (!usedSchemas.has(schema)) {
                warnings.push({
                    path: `components.schemas.${schema}`,
                    message: `Schema '${schema}' is defined but never used`,
                    type: 'consistency',
                    suggestion: 'Remove unused schema or add reference'
                });
            }
        });

        // Check for missing schema definitions
        usedSchemas.forEach(schema => {
            if (!definedSchemas.has(schema)) {
                warnings.push({
                    path: `components.schemas.${schema}`,
                    message: `Schema '${schema}' is referenced but not defined`,
                    type: 'consistency',
                    suggestion: 'Define the missing schema'
                });
            }
        });

        return warnings;
    }

    /**
     * Find all schema references in the specification
     */
    findSchemaReferences(obj, usedSchemas, path = '') {
        if (typeof obj === 'object' && obj !== null) {
            if (obj.$ref && typeof obj.$ref === 'string') {
                const match = obj.$ref.match(/#\/components\/schemas\/(.+)/);
                if (match) {
                    usedSchemas.add(match[1]);
                }
            }
            
            Object.keys(obj).forEach(key => {
                this.findSchemaReferences(obj[key], usedSchemas, `${path}.${key}`);
            });
        } else if (Array.isArray(obj)) {
            obj.forEach((item, index) => {
                this.findSchemaReferences(item, usedSchemas, `${path}[${index}]`);
            });
        }
    }

    /**
     * Validate business logic patterns
     */
    validateBusinessLogic(spec) {
        const suggestions = [];
        const paths = spec.paths || {};

        Object.keys(paths).forEach(path => {
            const pathObj = paths[path];
            
            // Check for CRUD operations
            const methods = Object.keys(pathObj).filter(key => 
                ['get', 'post', 'put', 'delete', 'patch'].includes(key)
            );

            // Suggest missing CRUD operations
            if (methods.includes('post') && !methods.includes('get')) {
                suggestions.push({
                    path: `paths.${path}`,
                    message: 'Consider adding GET operation for created resources',
                    type: 'business-logic'
                });
            }

            if (methods.includes('post') && !methods.includes('put') && !methods.includes('patch')) {
                suggestions.push({
                    path: `paths.${path}`,
                    message: 'Consider adding UPDATE operation (PUT/PATCH)',
                    type: 'business-logic'
                });
            }

            // Check response codes consistency
            methods.forEach(method => {
                const operation = pathObj[method];
                const responses = operation.responses || {};
                
                if (method === 'post' && !responses['201']) {
                    suggestions.push({
                        path: `paths.${path}.${method}`,
                        message: 'POST operations should typically return 201 Created',
                        type: 'business-logic'
                    });
                }

                if (method === 'delete' && !responses['204']) {
                    suggestions.push({
                        path: `paths.${path}.${method}`,
                        message: 'DELETE operations should typically return 204 No Content',
                        type: 'business-logic'
                    });
                }
            });
        });

        return suggestions;
    }

    /**
     * Validate performance considerations
     */
    validatePerformance(spec) {
        const warnings = [];
        const paths = spec.paths || {};

        Object.keys(paths).forEach(path => {
            const pathObj = paths[path];
            
            Object.keys(pathObj).forEach(method => {
                if (['get', 'post', 'put', 'delete', 'patch'].includes(method)) {
                    const operation = pathObj[method];
                    
                    // Check for pagination in list endpoints
                    if (method === 'get' && path.includes('s') && !this.hasPagination(operation)) {
                        warnings.push({
                            path: `paths.${path}.${method}`,
                            message: 'List endpoints should implement pagination',
                            type: 'performance',
                            suggestion: 'Add limit, offset, or cursor-based pagination'
                        });
                    }

                    // Check for filtering capabilities
                    if (method === 'get' && !this.hasFiltering(operation)) {
                        warnings.push({
                            path: `paths.${path}.${method}`,
                            message: 'Consider adding filtering parameters',
                            type: 'performance',
                            suggestion: 'Add query parameters for filtering results'
                        });
                    }

                    // Check for caching headers
                    const responses = operation.responses || {};
                    Object.keys(responses).forEach(code => {
                        if (code.startsWith('2') && method === 'get') {
                            const response = responses[code];
                            const headers = response.headers || {};
                            
                            if (!headers['Cache-Control'] && !headers['ETag']) {
                                warnings.push({
                                    path: `paths.${path}.${method}.responses.${code}`,
                                    message: 'Consider adding caching headers for GET responses',
                                    type: 'performance',
                                    suggestion: 'Add Cache-Control or ETag headers'
                                });
                            }
                        }
                    });
                }
            });
        });

        return warnings;
    }

    /**
     * Check if operation has pagination parameters
     */
    hasPagination(operation) {
        const parameters = operation.parameters || [];
        const paginationParams = ['limit', 'offset', 'page', 'size', 'cursor'];
        
        return parameters.some(param => 
            paginationParams.includes(param.name?.toLowerCase())
        );
    }

    /**
     * Check if operation has filtering parameters
     */
    hasFiltering(operation) {
        const parameters = operation.parameters || [];
        return parameters.some(param => 
            param.in === 'query' && 
            !['limit', 'offset', 'page', 'size', 'cursor'].includes(param.name?.toLowerCase())
        );
    }

    /**
     * Setup intelligent auto-complete
     */
    setupAutoComplete() {
        this.autoCompleteRules = {
            paths: {
                suggestions: ['/users', '/api/v1/users', '/products', '/orders'],
                patterns: ['/api/v{version}/{resource}', '/{resource}/{id}']
            },
            methods: ['get', 'post', 'put', 'delete', 'patch', 'head', 'options'],
            responsesCodes: ['200', '201', '204', '400', '401', '403', '404', '422', '500'],
            mediaTypes: ['application/json', 'application/xml', 'text/plain', 'multipart/form-data'],
            securitySchemes: ['apiKey', 'http', 'oauth2', 'openIdConnect']
        };
    }

    /**
     * Setup live preview functionality
     */
    setupLivePreview() {
        this.previewMode = 'swagger-ui';
        this.previewUpdateDelay = 500; // milliseconds
        this.previewTimer = null;

        // Setup preview update debouncing
        this.schedulePreviewUpdate = () => {
            if (this.previewTimer) {
                clearTimeout(this.previewTimer);
            }
            
            this.previewTimer = setTimeout(() => {
                this.updatePreview();
            }, this.previewUpdateDelay);
        };
    }

    /**
     * Update live preview
     */
    updatePreview() {
        try {
            const validatedSpec = this.validateAndCleanSpec(this.spec);
            
            // Update Swagger UI
            if (window.SwaggerUIBundle && this.previewMode === 'swagger-ui') {
                this.updateSwaggerUI(validatedSpec);
            }

            // Update Redoc
            if (window.Redoc && this.previewMode === 'redoc') {
                this.updateRedoc(validatedSpec);
            }

            // Emit preview update event
            document.dispatchEvent(new CustomEvent('previewUpdated', {
                detail: { spec: validatedSpec }
            }));

        } catch (error) {
            console.error('Preview update failed:', error);
            this.showPreviewError(error);
        }
    }

    /**
     * Setup real-time collaboration features
     */
    setupCollaboration() {
        this.collaborationEnabled = false;
        this.collaborators = new Map();
        this.conflictResolution = 'last-writer-wins';
        
        // Setup WebSocket connection for real-time collaboration
        // Note: This would need a backend WebSocket server
        this.initializeCollaborationSocket();
    }

    /**
     * Initialize collaboration WebSocket
     */
    initializeCollaborationSocket() {
        // Placeholder for WebSocket initialization
        // In a real implementation, this would connect to a WebSocket server
        console.log('Collaboration features initialized (requires WebSocket server)');
    }

    /**
     * Enable advanced editor features
     */
    enableAdvancedFeatures() {
        // Code folding
        this.enableCodeFolding();
        
        // Smart indentation
        this.enableSmartIndentation();
        
        // Bracket matching
        this.enableBracketMatching();
        
        // Multi-cursor editing
        this.enableMultiCursor();
        
        // Advanced search and replace
        this.enableAdvancedSearch();
    }

    /**
     * Import/Export functionality
     */
    exportSpec(format = 'json') {
        const formats = {
            json: () => JSON.stringify(this.spec, null, 2),
            yaml: () => this.convertToYAML(this.spec),
            postman: () => this.convertToPostman(this.spec),
            insomnia: () => this.convertToInsomnia(this.spec)
        };

        if (formats[format]) {
            const content = formats[format]();
            this.downloadFile(content, `openapi-spec.${format}`);
        } else {
            throw new Error(`Unsupported export format: ${format}`);
        }
    }

    /**
     * Import specification from various formats
     */
    async importSpec(file, format = 'auto') {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            
            reader.onload = (e) => {
                try {
                    let content = e.target.result;
                    let spec;

                    if (format === 'auto') {
                        format = this.detectFormat(file.name, content);
                    }

                    switch (format) {
                        case 'json':
                            spec = JSON.parse(content);
                            break;
                        case 'yaml':
                        case 'yml':
                            spec = this.parseYAML(content);
                            break;
                        case 'postman':
                            spec = this.convertFromPostman(content);
                            break;
                        default:
                            throw new Error(`Unsupported import format: ${format}`);
                    }

                    this.loadSpec(spec);
                    resolve(spec);
                    
                } catch (error) {
                    reject(error);
                }
            };

            reader.readAsText(file);
        });
    }

    /**
     * Auto-detect file format
     */
    detectFormat(filename, content) {
        const extension = filename.split('.').pop().toLowerCase();
        
        if (['yaml', 'yml'].includes(extension)) {
            return 'yaml';
        }
        
        if (extension === 'json') {
            try {
                const parsed = JSON.parse(content);
                if (parsed.info && parsed.info._postman_id) {
                    return 'postman';
                }
                return 'json';
            } catch {
                return 'json';
            }
        }
        
        // Try to detect by content
        if (content.trim().startsWith('{')) {
            return 'json';
        }
        
        return 'yaml';
    }

    /**
     * Generate API client code
     */
    generateClientCode(language, options = {}) {
        const generators = {
            javascript: () => this.generateJavaScriptClient(options),
            python: () => this.generatePythonClient(options),
            php: () => this.generatePHPClient(options),
            curl: () => this.generateCurlCommands(options)
        };

        if (generators[language]) {
            return generators[language]();
        } else {
            throw new Error(`Unsupported client language: ${language}`);
        }
    }

    /**
     * Generate API documentation
     */
    generateDocumentation(format = 'html', options = {}) {
        const generators = {
            html: () => this.generateHTMLDocs(options),
            markdown: () => this.generateMarkdownDocs(options),
            pdf: () => this.generatePDFDocs(options)
        };

        if (generators[format]) {
            return generators[format]();
        } else {
            throw new Error(`Unsupported documentation format: ${format}`);
        }
    }

    /**
     * Placeholder methods for advanced features
     * These would be fully implemented based on specific requirements
     */
    enableCodeFolding() { console.log('Code folding enabled'); }
    enableSmartIndentation() { console.log('Smart indentation enabled'); }
    enableBracketMatching() { console.log('Bracket matching enabled'); }
    enableMultiCursor() { console.log('Multi-cursor editing enabled'); }
    enableAdvancedSearch() { console.log('Advanced search enabled'); }
    
    convertToYAML(spec) { return 'yaml conversion not implemented'; }
    convertToPostman(spec) { return 'postman conversion not implemented'; }
    convertToInsomnia(spec) { return 'insomnia conversion not implemented'; }
    convertFromPostman(content) { return {}; }
    parseYAML(content) { return {}; }
    
    generateJavaScriptClient(options) { return 'js client generation not implemented'; }
    generatePythonClient(options) { return 'python client generation not implemented'; }
    generatePHPClient(options) { return 'php client generation not implemented'; }
    generateCurlCommands(options) { return 'curl generation not implemented'; }
    
    generateHTMLDocs(options) { return 'html docs generation not implemented'; }
    generateMarkdownDocs(options) { return 'markdown docs generation not implemented'; }
    generatePDFDocs(options) { return 'pdf docs generation not implemented'; }

    /**
     * Utility method to download generated content
     */
    downloadFile(content, filename) {
        const blob = new Blob([content], { type: 'text/plain' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }
}

// Initialize advanced editor only if not already initialized
if (typeof window.advancedEditor === 'undefined') {
    const advancedEditor = new AdvancedOpenAPIEditor();
    window.advancedEditor = advancedEditor;
}