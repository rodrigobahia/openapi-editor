/**
 * Security Management Module
 * OpenAPI Editor - Enhanced Security Features
 */

class SecurityManager {
    constructor() {
        this.securityRules = [];
        this.validationSchemas = new Map();
        this.auditLog = [];
        this.init();
    }

    init() {
        this.loadSecurityRules();
        this.setupValidationSchemas();
        this.enableSecurityMonitoring();
    }

    /**
     * Load and validate security rules for OpenAPI spec
     */
    loadSecurityRules() {
        this.securityRules = [
            {
                id: 'auth_required',
                name: 'Authentication Required',
                description: 'All endpoints must have authentication',
                severity: 'high',
                check: this.checkAuthenticationRequired.bind(this)
            },
            {
                id: 'https_only',
                name: 'HTTPS Only',
                description: 'All servers must use HTTPS',
                severity: 'critical',
                check: this.checkHttpsOnly.bind(this)
            },
            {
                id: 'rate_limiting',
                name: 'Rate Limiting',
                description: 'Endpoints should have rate limiting headers',
                severity: 'medium',
                check: this.checkRateLimiting.bind(this)
            },
            {
                id: 'input_validation',
                name: 'Input Validation',
                description: 'All parameters must have validation rules',
                severity: 'high',
                check: this.checkInputValidation.bind(this)
            },
            {
                id: 'sensitive_data',
                name: 'Sensitive Data Protection',
                description: 'Sensitive fields must be properly marked',
                severity: 'critical',
                check: this.checkSensitiveData.bind(this)
            }
        ];
    }

    /**
     * Setup validation schemas for different security contexts
     */
    setupValidationSchemas() {
        // API Key validation schema
        this.validationSchemas.set('apiKey', {
            type: 'object',
            properties: {
                name: { type: 'string', minLength: 1 },
                in: { enum: ['header', 'query', 'cookie'] }
            },
            required: ['name', 'in']
        });

        // OAuth2 validation schema
        this.validationSchemas.set('oauth2', {
            type: 'object',
            properties: {
                flows: { type: 'object' },
                scopes: { type: 'object' }
            },
            required: ['flows']
        });

        // JWT Bearer validation schema
        this.validationSchemas.set('http', {
            type: 'object',
            properties: {
                scheme: { enum: ['bearer', 'basic'] },
                bearerFormat: { type: 'string' }
            },
            required: ['scheme']
        });
    }

    /**
     * Check if all endpoints require authentication
     */
    checkAuthenticationRequired(spec) {
        const issues = [];
        const paths = spec.paths || {};

        Object.keys(paths).forEach(path => {
            const pathObj = paths[path];
            Object.keys(pathObj).forEach(method => {
                if (['get', 'post', 'put', 'delete', 'patch'].includes(method)) {
                    const operation = pathObj[method];
                    if (!operation.security || operation.security.length === 0) {
                        issues.push({
                            path: `${method.toUpperCase()} ${path}`,
                            message: 'Endpoint lacks security requirements',
                            suggestion: 'Add security scheme to this endpoint'
                        });
                    }
                }
            });
        });

        return issues;
    }

    /**
     * Check if all servers use HTTPS
     */
    checkHttpsOnly(spec) {
        const issues = [];
        const servers = spec.servers || [];

        servers.forEach((server, index) => {
            if (!server.url.startsWith('https://')) {
                issues.push({
                    path: `servers[${index}]`,
                    message: `Server URL uses insecure protocol: ${server.url}`,
                    suggestion: 'Use HTTPS for all server URLs'
                });
            }
        });

        return issues;
    }

    /**
     * Check for rate limiting considerations
     */
    checkRateLimiting(spec) {
        const issues = [];
        const paths = spec.paths || {};

        Object.keys(paths).forEach(path => {
            const pathObj = paths[path];
            Object.keys(pathObj).forEach(method => {
                if (['post', 'put', 'delete', 'patch'].includes(method)) {
                    const operation = pathObj[method];
                    const responses = operation.responses || {};
                    
                    if (!responses['429']) {
                        issues.push({
                            path: `${method.toUpperCase()} ${path}`,
                            message: 'Missing rate limiting response (429)',
                            suggestion: 'Add 429 Too Many Requests response'
                        });
                    }
                }
            });
        });

        return issues;
    }

    /**
     * Check input validation schemas
     */
    checkInputValidation(spec) {
        const issues = [];
        const paths = spec.paths || {};

        Object.keys(paths).forEach(path => {
            const pathObj = paths[path];
            Object.keys(pathObj).forEach(method => {
                if (['get', 'post', 'put', 'delete', 'patch'].includes(method)) {
                    const operation = pathObj[method];
                    const parameters = operation.parameters || [];
                    
                    parameters.forEach((param, index) => {
                        if (!param.schema || !param.schema.type) {
                            issues.push({
                                path: `${method.toUpperCase()} ${path} - parameter[${index}]`,
                                message: `Parameter '${param.name}' lacks validation schema`,
                                suggestion: 'Add proper schema validation for this parameter'
                            });
                        }
                    });
                }
            });
        });

        return issues;
    }

    /**
     * Check for sensitive data exposure
     */
    checkSensitiveData(spec) {
        const issues = [];
        const sensitiveFields = ['password', 'token', 'secret', 'key', 'auth', 'credential'];
        
        const checkSchema = (schema, path) => {
            if (schema.properties) {
                Object.keys(schema.properties).forEach(prop => {
                    if (sensitiveFields.some(field => prop.toLowerCase().includes(field))) {
                        const propSchema = schema.properties[prop];
                        if (!propSchema.writeOnly && !propSchema.format === 'password') {
                            issues.push({
                                path: `${path}.${prop}`,
                                message: `Sensitive field '${prop}' not properly protected`,
                                suggestion: 'Mark as writeOnly or use password format'
                            });
                        }
                    }
                });
            }
        };

        // Check components schemas
        const components = spec.components || {};
        const schemas = components.schemas || {};
        
        Object.keys(schemas).forEach(schemaName => {
            checkSchema(schemas[schemaName], `components.schemas.${schemaName}`);
        });

        return issues;
    }

    /**
     * Run complete security audit
     */
    runSecurityAudit(spec) {
        const auditResults = {
            timestamp: new Date().toISOString(),
            summary: {
                total: 0,
                critical: 0,
                high: 0,
                medium: 0,
                low: 0
            },
            issues: []
        };

        this.securityRules.forEach(rule => {
            const ruleIssues = rule.check(spec);
            ruleIssues.forEach(issue => {
                auditResults.issues.push({
                    ...issue,
                    rule: rule.id,
                    ruleName: rule.name,
                    severity: rule.severity,
                    description: rule.description
                });
                
                auditResults.summary.total++;
                auditResults.summary[rule.severity]++;
            });
        });

        this.auditLog.push(auditResults);
        return auditResults;
    }

    /**
     * Generate security report
     */
    generateSecurityReport(auditResults) {
        const report = {
            title: 'OpenAPI Security Audit Report',
            generated: new Date().toISOString(),
            summary: auditResults.summary,
            recommendations: this.getSecurityRecommendations(auditResults),
            details: auditResults.issues
        };

        return report;
    }

    /**
     * Get security recommendations based on audit results
     */
    getSecurityRecommendations(auditResults) {
        const recommendations = [];

        if (auditResults.summary.critical > 0) {
            recommendations.push({
                priority: 'critical',
                title: 'Critical Security Issues Found',
                description: 'Address critical security vulnerabilities immediately before deployment',
                actions: ['Fix HTTPS configuration', 'Protect sensitive data fields']
            });
        }

        if (auditResults.summary.high > 0) {
            recommendations.push({
                priority: 'high',
                title: 'High Priority Security Improvements',
                description: 'Implement authentication and input validation',
                actions: ['Add security schemes', 'Validate all inputs', 'Implement proper error handling']
            });
        }

        if (auditResults.summary.medium > 0) {
            recommendations.push({
                priority: 'medium',
                title: 'Security Best Practices',
                description: 'Implement additional security measures',
                actions: ['Add rate limiting', 'Implement request logging', 'Add security headers']
            });
        }

        return recommendations;
    }

    /**
     * Enable real-time security monitoring
     */
    enableSecurityMonitoring() {
        // Monitor for potential security issues in real-time
        document.addEventListener('specChanged', (event) => {
            const spec = event.detail;
            const quickAudit = this.runQuickSecurityCheck(spec);
            
            if (quickAudit.criticalIssues > 0) {
                this.showSecurityWarning(quickAudit);
            }
        });
    }

    /**
     * Quick security check for real-time monitoring
     */
    runQuickSecurityCheck(spec) {
        const criticalChecks = this.securityRules.filter(rule => 
            rule.severity === 'critical'
        );
        
        let criticalIssues = 0;
        criticalChecks.forEach(rule => {
            const issues = rule.check(spec);
            criticalIssues += issues.length;
        });

        return { criticalIssues };
    }

    /**
     * Show security warning in UI
     */
    showSecurityWarning(auditResults) {
        const alertHtml = `
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-shield-alt me-2"></i>
                <strong>Security Warning!</strong> 
                Critical security issues detected (${auditResults.criticalIssues} issues). 
                <a href="#" onclick="securityManager.showSecurityPanel()">Review now</a>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        const alertContainer = document.getElementById('security-alerts');
        if (alertContainer) {
            alertContainer.innerHTML = alertHtml;
        }
    }

    /**
     * Show security panel with detailed results
     */
    showSecurityPanel() {
        const modal = document.getElementById('securityModal');
        if (modal) {
            const modalInstance = new bootstrap.Modal(modal);
            modalInstance.show();
        }
    }

    /**
     * Export audit results
     */
    exportAuditResults(format = 'json') {
        const results = this.auditLog[this.auditLog.length - 1];
        
        if (format === 'json') {
            const blob = new Blob([JSON.stringify(results, null, 2)], {
                type: 'application/json'
            });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `security-audit-${new Date().toISOString().split('T')[0]}.json`;
            a.click();
            URL.revokeObjectURL(url);
        }
    }
}

// Initialize security manager only if not already initialized
if (typeof window.securityManager === 'undefined') {
    const securityManager = new SecurityManager();
    window.securityManager = securityManager;
}

// Export for global access
window.securityManager = securityManager;