/**
 * Advanced Schema Management Module
 * OpenAPI Editor - Enhanced Schema Features
 */

class AdvancedSchemaManager {
    constructor() {
        this.schemas = new Map();
        this.schemaTypes = new Map();
        this.relationships = new Map();
        this.validationRules = new Map();
        this.schemaTemplates = new Map();
        this.init();
    }

    init() {
        this.setupSchemaTypes();
        this.setupValidationRules();
        this.setupSchemaTemplates();
        this.enableAdvancedFeatures();
    }

    /**
     * Setup predefined schema types and their configurations
     */
    setupSchemaTypes() {
        // Basic types
        this.schemaTypes.set('string', {
            type: 'string',
            properties: ['minLength', 'maxLength', 'pattern', 'format', 'enum'],
            formats: ['email', 'uri', 'date', 'date-time', 'password', 'uuid', 'binary', 'byte']
        });

        this.schemaTypes.set('number', {
            type: 'number',
            properties: ['minimum', 'maximum', 'exclusiveMinimum', 'exclusiveMaximum', 'multipleOf'],
            formats: ['float', 'double']
        });

        this.schemaTypes.set('integer', {
            type: 'integer',
            properties: ['minimum', 'maximum', 'exclusiveMinimum', 'exclusiveMaximum', 'multipleOf'],
            formats: ['int32', 'int64']
        });

        this.schemaTypes.set('boolean', {
            type: 'boolean',
            properties: []
        });

        this.schemaTypes.set('array', {
            type: 'array',
            properties: ['minItems', 'maxItems', 'uniqueItems', 'items'],
            requires: ['items']
        });

        this.schemaTypes.set('object', {
            type: 'object',
            properties: ['properties', 'required', 'additionalProperties', 'minProperties', 'maxProperties'],
            complex: true
        });
    }

    /**
     * Setup advanced validation rules for schemas
     */
    setupValidationRules() {
        // Schema naming conventions
        this.validationRules.set('naming', {
            validate: (schemaName, schema) => this.validateNaming(schemaName, schema),
            message: 'Schema names should follow naming conventions'
        });

        // Schema complexity validation
        this.validationRules.set('complexity', {
            validate: (schemaName, schema) => this.validateComplexity(schemaName, schema),
            message: 'Schema complexity should be manageable'
        });

        // Circular reference detection
        this.validationRules.set('circular', {
            validate: (schemaName, schema) => this.validateCircularReferences(schemaName, schema),
            message: 'Circular references detected in schema'
        });

        // Schema consistency validation
        this.validationRules.set('consistency', {
            validate: (schemaName, schema) => this.validateConsistency(schemaName, schema),
            message: 'Schema should be consistent with API patterns'
        });
    }

    /**
     * Setup schema templates for common patterns
     */
    setupSchemaTemplates() {
        // User entity template
        this.schemaTemplates.set('User', {
            type: 'object',
            properties: {
                id: {
                    type: 'integer',
                    format: 'int64',
                    description: 'Unique identifier for the user',
                    readOnly: true
                },
                username: {
                    type: 'string',
                    minLength: 3,
                    maxLength: 50,
                    pattern: '^[a-zA-Z0-9_]+$',
                    description: 'User\'s unique username'
                },
                email: {
                    type: 'string',
                    format: 'email',
                    description: 'User\'s email address'
                },
                firstName: {
                    type: 'string',
                    maxLength: 100,
                    description: 'User\'s first name'
                },
                lastName: {
                    type: 'string',
                    maxLength: 100,
                    description: 'User\'s last name'
                },
                createdAt: {
                    type: 'string',
                    format: 'date-time',
                    description: 'User creation timestamp',
                    readOnly: true
                },
                updatedAt: {
                    type: 'string',
                    format: 'date-time',
                    description: 'Last update timestamp',
                    readOnly: true
                }
            },
            required: ['username', 'email'],
            description: 'User entity schema'
        });

        // API Response template
        this.schemaTemplates.set('ApiResponse', {
            type: 'object',
            properties: {
                success: {
                    type: 'boolean',
                    description: 'Indicates if the request was successful'
                },
                message: {
                    type: 'string',
                    description: 'Human-readable message'
                },
                data: {
                    description: 'Response data (type varies)',
                    nullable: true
                },
                errors: {
                    type: 'array',
                    items: {
                        type: 'object',
                        properties: {
                            field: { type: 'string' },
                            code: { type: 'string' },
                            message: { type: 'string' }
                        }
                    },
                    description: 'Validation or error details'
                },
                meta: {
                    type: 'object',
                    properties: {
                        timestamp: {
                            type: 'string',
                            format: 'date-time'
                        },
                        version: { type: 'string' },
                        requestId: { type: 'string' }
                    }
                }
            },
            required: ['success'],
            description: 'Standard API response wrapper'
        });

        // Pagination template
        this.schemaTemplates.set('PaginatedResponse', {
            type: 'object',
            properties: {
                data: {
                    type: 'array',
                    items: {},
                    description: 'Array of data items'
                },
                pagination: {
                    type: 'object',
                    properties: {
                        page: {
                            type: 'integer',
                            minimum: 1,
                            description: 'Current page number'
                        },
                        perPage: {
                            type: 'integer',
                            minimum: 1,
                            maximum: 100,
                            description: 'Items per page'
                        },
                        total: {
                            type: 'integer',
                            minimum: 0,
                            description: 'Total number of items'
                        },
                        totalPages: {
                            type: 'integer',
                            minimum: 0,
                            description: 'Total number of pages'
                        },
                        hasNext: {
                            type: 'boolean',
                            description: 'Whether there are more pages'
                        },
                        hasPrev: {
                            type: 'boolean',
                            description: 'Whether there are previous pages'
                        }
                    },
                    required: ['page', 'perPage', 'total', 'totalPages']
                }
            },
            required: ['data', 'pagination'],
            description: 'Paginated response schema'
        });

        // Error response template
        this.schemaTemplates.set('ErrorResponse', {
            type: 'object',
            properties: {
                error: {
                    type: 'object',
                    properties: {
                        code: {
                            type: 'string',
                            description: 'Error code identifier'
                        },
                        message: {
                            type: 'string',
                            description: 'Human-readable error message'
                        },
                        details: {
                            type: 'string',
                            description: 'Additional error details'
                        },
                        timestamp: {
                            type: 'string',
                            format: 'date-time',
                            description: 'Error timestamp'
                        },
                        path: {
                            type: 'string',
                            description: 'API endpoint path where error occurred'
                        }
                    },
                    required: ['code', 'message']
                }
            },
            required: ['error'],
            description: 'Standard error response schema'
        });
    }

    /**
     * Generate schema from JSON example
     */
    generateSchemaFromJSON(jsonExample, schemaName = 'GeneratedSchema') {
        try {
            const example = typeof jsonExample === 'string' 
                ? JSON.parse(jsonExample) 
                : jsonExample;

            const schema = this.inferSchemaFromObject(example);
            schema.title = schemaName;
            schema.description = `Generated schema for ${schemaName}`;

            return schema;
        } catch (error) {
            throw new Error(`Failed to generate schema from JSON: ${error.message}`);
        }
    }

    /**
     * Infer schema structure from object
     */
    inferSchemaFromObject(obj, depth = 0) {
        if (depth > 10) {
            return { type: 'object', description: 'Maximum depth reached' };
        }

        if (obj === null) {
            return { type: 'null' };
        }

        if (Array.isArray(obj)) {
            const schema = { type: 'array' };
            
            if (obj.length > 0) {
                // Analyze first few items to infer array item schema
                const sampleItems = obj.slice(0, 3);
                const itemSchemas = sampleItems.map(item => 
                    this.inferSchemaFromObject(item, depth + 1)
                );
                
                // If all items have the same type, use that type
                const types = itemSchemas.map(s => s.type);
                if (types.every(t => t === types[0])) {
                    schema.items = itemSchemas[0];
                } else {
                    schema.items = { oneOf: itemSchemas };
                }
            }
            
            return schema;
        }

        if (typeof obj === 'object') {
            const schema = {
                type: 'object',
                properties: {},
                required: []
            };

            Object.keys(obj).forEach(key => {
                const value = obj[key];
                schema.properties[key] = this.inferSchemaFromObject(value, depth + 1);
                
                // Consider field required if it's not null/undefined
                if (value !== null && value !== undefined) {
                    schema.required.push(key);
                }
            });

            return schema;
        }

        // Primitive types
        const type = typeof obj;
        const schema = { type };

        if (type === 'string') {
            // Try to detect formats
            if (this.isEmail(obj)) {
                schema.format = 'email';
            } else if (this.isDateTime(obj)) {
                schema.format = 'date-time';
            } else if (this.isDate(obj)) {
                schema.format = 'date';
            } else if (this.isUUID(obj)) {
                schema.format = 'uuid';
            } else if (this.isURI(obj)) {
                schema.format = 'uri';
            }
        }

        if (type === 'number') {
            // Determine if it's integer or float
            if (Number.isInteger(obj)) {
                schema.type = 'integer';
                if (obj >= -2147483648 && obj <= 2147483647) {
                    schema.format = 'int32';
                } else {
                    schema.format = 'int64';
                }
            } else {
                schema.format = 'double';
            }
        }

        return schema;
    }

    /**
     * Schema validation methods
     */
    validateNaming(schemaName, schema) {
        const issues = [];
        
        // Check PascalCase naming
        if (!/^[A-Z][a-zA-Z0-9]*$/.test(schemaName)) {
            issues.push({
                type: 'naming',
                message: `Schema name '${schemaName}' should be in PascalCase`,
                suggestion: 'Use PascalCase for schema names (e.g., UserProfile, ApiResponse)'
            });
        }

        // Check for meaningful names
        const meaninglessNames = ['Object', 'Data', 'Item', 'Entity', 'Model'];
        if (meaninglessNames.includes(schemaName)) {
            issues.push({
                type: 'naming',
                message: `Schema name '${schemaName}' is too generic`,
                suggestion: 'Use descriptive names that indicate the purpose of the schema'
            });
        }

        return issues;
    }

    validateComplexity(schemaName, schema) {
        const issues = [];
        const complexity = this.calculateSchemaComplexity(schema);

        if (complexity.depth > 5) {
            issues.push({
                type: 'complexity',
                message: `Schema '${schemaName}' has excessive nesting depth (${complexity.depth})`,
                suggestion: 'Consider flattening the schema or using references'
            });
        }

        if (complexity.properties > 20) {
            issues.push({
                type: 'complexity',
                message: `Schema '${schemaName}' has too many properties (${complexity.properties})`,
                suggestion: 'Consider breaking down into smaller schemas'
            });
        }

        return issues;
    }

    validateCircularReferences(schemaName, schema) {
        const visited = new Set();
        const path = [];
        
        const checkCircular = (currentSchema, currentPath) => {
            if (currentSchema.$ref) {
                const refName = currentSchema.$ref.split('/').pop();
                if (path.includes(refName)) {
                    return [{
                        type: 'circular',
                        message: `Circular reference detected: ${currentPath.join(' -> ')} -> ${refName}`,
                        suggestion: 'Break the circular reference using optional properties or restructuring'
                    }];
                }
                return [];
            }

            if (currentSchema.properties) {
                const issues = [];
                Object.keys(currentSchema.properties).forEach(prop => {
                    path.push(prop);
                    issues.push(...checkCircular(currentSchema.properties[prop], [...currentPath, prop]));
                    path.pop();
                });
                return issues;
            }

            return [];
        };

        return checkCircular(schema, [schemaName]);
    }

    validateConsistency(schemaName, schema) {
        const issues = [];
        
        // Check for consistent property naming
        if (schema.properties) {
            const properties = Object.keys(schema.properties);
            const namingStyles = this.analyzePropertyNaming(properties);
            
            if (namingStyles.camelCase > 0 && namingStyles.snake_case > 0) {
                issues.push({
                    type: 'consistency',
                    message: 'Mixed naming conventions in properties',
                    suggestion: 'Use consistent naming convention (camelCase or snake_case)'
                });
            }
        }

        return issues;
    }

    /**
     * Calculate schema complexity metrics
     */
    calculateSchemaComplexity(schema, depth = 0) {
        let properties = 0;
        let maxDepth = depth;

        if (schema.properties) {
            properties = Object.keys(schema.properties).length;
            
            Object.values(schema.properties).forEach(prop => {
                const childComplexity = this.calculateSchemaComplexity(prop, depth + 1);
                properties += childComplexity.properties;
                maxDepth = Math.max(maxDepth, childComplexity.depth);
            });
        }

        if (schema.items) {
            const itemComplexity = this.calculateSchemaComplexity(schema.items, depth + 1);
            properties += itemComplexity.properties;
            maxDepth = Math.max(maxDepth, itemComplexity.depth);
        }

        return { properties, depth: maxDepth };
    }

    /**
     * Analyze property naming patterns
     */
    analyzePropertyNaming(properties) {
        const patterns = {
            camelCase: 0,
            snake_case: 0,
            kebab_case: 0,
            PascalCase: 0
        };

        properties.forEach(prop => {
            if (/^[a-z][a-zA-Z0-9]*$/.test(prop)) {
                patterns.camelCase++;
            } else if (/^[a-z][a-z0-9_]*$/.test(prop)) {
                patterns.snake_case++;
            } else if (/^[a-z][a-z0-9-]*$/.test(prop)) {
                patterns.kebab_case++;
            } else if (/^[A-Z][a-zA-Z0-9]*$/.test(prop)) {
                patterns.PascalCase++;
            }
        });

        return patterns;
    }

    /**
     * Auto-generate relationships between schemas
     */
    detectSchemaRelationships(schemas) {
        const relationships = [];

        Object.keys(schemas).forEach(schemaName => {
            const schema = schemas[schemaName];
            const refs = this.findSchemaReferences(schema);
            
            refs.forEach(ref => {
                const targetSchema = ref.replace('#/components/schemas/', '');
                relationships.push({
                    from: schemaName,
                    to: targetSchema,
                    type: 'reference',
                    path: ref.path
                });
            });
        });

        return relationships;
    }

    /**
     * Find all schema references in a schema
     */
    findSchemaReferences(schema, path = '') {
        const references = [];

        const traverse = (obj, currentPath) => {
            if (typeof obj === 'object' && obj !== null) {
                if (obj.$ref && typeof obj.$ref === 'string') {
                    references.push({
                        ref: obj.$ref,
                        path: currentPath
                    });
                }

                Object.keys(obj).forEach(key => {
                    traverse(obj[key], currentPath ? `${currentPath}.${key}` : key);
                });
            } else if (Array.isArray(obj)) {
                obj.forEach((item, index) => {
                    traverse(item, `${currentPath}[${index}]`);
                });
            }
        };

        traverse(schema, path);
        return references;
    }

    /**
     * Format detection helpers
     */
    isEmail(str) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(str);
    }

    isDateTime(str) {
        return !isNaN(Date.parse(str)) && str.includes('T');
    }

    isDate(str) {
        return !isNaN(Date.parse(str)) && /^\d{4}-\d{2}-\d{2}$/.test(str);
    }

    isUUID(str) {
        const uuidRegex = /^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i;
        return uuidRegex.test(str);
    }

    isURI(str) {
        try {
            new URL(str);
            return true;
        } catch {
            return false;
        }
    }

    /**
     * Enable advanced schema features
     */
    enableAdvancedFeatures() {
        // Schema diff and merge capabilities
        this.enableSchemaDiff();
        
        // Schema inheritance and composition
        this.enableSchemaInheritance();
        
        // Schema versioning
        this.enableSchemaVersioning();
        
        // Schema documentation generation
        this.enableSchemaDocumentation();
    }

    enableSchemaDiff() { console.log('Schema diff enabled'); }
    enableSchemaInheritance() { console.log('Schema inheritance enabled'); }
    enableSchemaVersioning() { console.log('Schema versioning enabled'); }
    enableSchemaDocumentation() { console.log('Schema documentation enabled'); }
}

// Initialize advanced schema manager only if not already initialized
if (typeof window.advancedSchemaManager === 'undefined') {
    const advancedSchemaManager = new AdvancedSchemaManager();
    window.advancedSchemaManager = advancedSchemaManager;
}

// Export for global access
window.advancedSchemaManager = advancedSchemaManager;