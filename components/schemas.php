<?php
// Componente para edição de esquemas da API
$schemas = $openApiData['components']['schemas'] ?? [];

// Lista de tipos de dados suportados
$dataTypes = [
    'string' => 'String (texto)',
    'integer' => 'Integer (número inteiro)', 
    'number' => 'Number (número decimal)',
    'boolean' => 'Boolean (verdadeiro/falso)',
    'array' => 'Array (lista)',
    'object' => 'Object (objeto)',
    'null' => 'Null (nulo)'
];

// Formatos específicos para strings
$stringFormats = [
    '' => 'Nenhum',
    'date' => 'Date (YYYY-MM-DD)',
    'date-time' => 'Date-Time (ISO 8601)',
    'email' => 'Email',
    'uri' => 'URI/URL',
    'uuid' => 'UUID',
    'password' => 'Password',
    'binary' => 'Binary',
    'byte' => 'Byte (base64)'
];

// Função para gerar exemplo baseado no tipo
function generateExample($type, $format = null, $properties = null) {
    switch ($type) {
        case 'string':
            switch ($format) {
                case 'date': return date('Y-m-d');
                case 'date-time': return date('c');
                case 'email': return 'user@example.com';
                case 'uri': return 'https://example.com';
                case 'uuid': return '550e8400-e29b-41d4-a716-446655440000';
                case 'password': return '********';
                default: return 'string';
            }
        case 'integer': return 42;
        case 'number': return 3.14;
        case 'boolean': return true;
        case 'array': return ['item1', 'item2'];
        case 'object': 
            if ($properties) {
                $example = [];
                foreach ($properties as $prop => $propData) {
                    $example[$prop] = generateExample($propData['type'] ?? 'string', $propData['format'] ?? null);
                }
                return $example;
            }
            return ['key' => 'value'];
        case 'null': return null;
        default: return 'example';
    }
}

// Função para renderizar um card de esquema
function renderSchemaCard($schemaName, $schemaData) {
    $type = $schemaData['type'] ?? 'object';
    $description = $schemaData['description'] ?? '';
    $properties = $schemaData['properties'] ?? [];
    $required = $schemaData['required'] ?? [];
    
    $propertiesCount = count($properties);
    $requiredCount = count($required);
    
    $html = '<div class="schema-item card mb-3" data-schema="' . htmlspecialchars($schemaName) . '">';
    $html .= '<div class="card-header">';
    $html .= '<div class="d-flex justify-content-between align-items-center">';
    $html .= '<div>';
    $html .= '<h6 class="mb-1">';
    $html .= '<i class="fas fa-cube me-2 text-primary"></i>';
    $html .= '<span class="fw-bold">' . htmlspecialchars($schemaName) . '</span>';
    $html .= '<span class="badge bg-secondary ms-2">' . ucfirst($type) . '</span>';
    $html .= '</h6>';
    if ($description) {
        $html .= '<p class="text-muted small mb-0">' . htmlspecialchars($description) . '</p>';
    }
    $html .= '</div>';
    $html .= '<div class="btn-group">';
    $html .= '<button type="button" class="btn btn-outline-primary btn-sm" onclick="editSchema(\'' . htmlspecialchars($schemaName) . '\')" title="Editar">';
    $html .= '<i class="fas fa-edit"></i>';
    $html .= '</button>';
    $html .= '<button type="button" class="btn btn-outline-info btn-sm" onclick="duplicateSchema(\'' . htmlspecialchars($schemaName) . '\')" title="Duplicar">';
    $html .= '<i class="fas fa-copy"></i>';
    $html .= '</button>';
    $html .= '<button type="button" class="btn btn-outline-success btn-sm" onclick="generateExample(\'' . htmlspecialchars($schemaName) . '\')" title="Ver Exemplo">';
    $html .= '<i class="fas fa-eye"></i>';
    $html .= '</button>';
    $html .= '<button type="button" class="btn btn-outline-danger btn-sm" onclick="removeSchema(\'' . htmlspecialchars($schemaName) . '\')" title="Remover">';
    $html .= '<i class="fas fa-trash"></i>';
    $html .= '</button>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    
    $html .= '<div class="card-body">';
    
    if ($type === 'object' && $properties) {
        $html .= '<div class="row">';
        $html .= '<div class="col-md-6">';
        $html .= '<h6><i class="fas fa-list me-1"></i>Propriedades (' . $propertiesCount . ')</h6>';
        $html .= '<div class="table-responsive">';
        $html .= '<table class="table table-sm">';
        $html .= '<thead><tr><th>Nome</th><th>Tipo</th><th>Obrigatório</th></tr></thead>';
        $html .= '<tbody>';
        
        foreach ($properties as $propName => $propData) {
            $propType = $propData['type'] ?? 'string';
            $propFormat = $propData['format'] ?? '';
            $isRequired = in_array($propName, $required);
            
            $html .= '<tr>';
            $html .= '<td>';
            $html .= '<code>' . htmlspecialchars($propName) . '</code>';
            if ($propData['description'] ?? false) {
                $html .= '<br><small class="text-muted">' . htmlspecialchars($propData['description']) . '</small>';
            }
            $html .= '</td>';
            $html .= '<td>';
            $html .= '<span class="badge bg-info">' . $propType . '</span>';
            if ($propFormat) {
                $html .= '<br><small class="text-muted">' . $propFormat . '</small>';
            }
            $html .= '</td>';
            $html .= '<td>';
            if ($isRequired) {
                $html .= '<span class="badge bg-warning">Obrigatório</span>';
            } else {
                $html .= '<span class="badge bg-secondary">Opcional</span>';
            }
            $html .= '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';
        $html .= '</div>';
        
        $html .= '<div class="col-md-6">';
        $html .= '<h6><i class="fas fa-code me-1"></i>Preview JSON</h6>';
        $html .= '<div class="bg-light p-3 rounded">';
        $html .= '<pre class="mb-0"><code>' . htmlspecialchars(json_encode($schemaData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) . '</code></pre>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
    } else {
        $html .= '<div class="bg-light p-3 rounded">';
        $html .= '<pre class="mb-0"><code>' . htmlspecialchars(json_encode($schemaData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) . '</code></pre>';
        $html .= '</div>';
    }
    
    $html .= '</div>';
    $html .= '</div>';
    
    return $html;
}
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header component-header-gradient d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-1">
                        <i class="fas fa-sitemap me-2"></i>
                        <?php echo t('schemas'); ?> - <?php echo t('schema_definition'); ?>
                    </h5>
                    <p class="mb-0"><?php echo t('schemas_description'); ?></p>
                </div>
                <button type="button" class="btn btn-light btn-sm" onclick="showSchemaModal()">
                    <i class="fas fa-plus me-2"></i>
                    <?php echo t('add_schema'); ?>
                </button>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <?php echo t('schemas_info'); ?>
                </div>
                
                <form method="POST" id="schemas-form">
                    <input type="hidden" name="save_section" value="1">
                    <input type="hidden" name="section" value="schemas">
                    
                    <div id="schemas-container">
                        <?php if (empty($schemas)): ?>
                            <div class="text-center py-5" id="empty-state">
                                <div class="mb-4">
                                    <i class="fas fa-sitemap display-1 text-muted"></i>
                                </div>
                                <h4 class="text-muted"><?php echo t('no_schemas_defined'); ?></h4>
                                <p class="text-muted mb-4"><?php echo t('schemas_help'); ?></p>
                                <button type="button" class="btn btn-primary btn-lg" onclick="showSchemaModal()">
                                    <i class="fas fa-plus me-2"></i>
                                    Criar Primeiro Esquema
                                </button>
                            </div>
                        <?php else: ?>
                            <?php foreach ($schemas as $schemaName => $schemaData): ?>
                                <?php echo renderSchemaCard($schemaName, $schemaData); ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mt-4 d-flex justify-content-end">
                        <button type="submit" class="btn btn-component-save">
                            <i class="fas fa-check me-2"></i>
                            Salvar Esquemas
                        </button>
                    </div>
                </form>

                <!-- Schema Usage Reference -->
                <div class="mt-4">
                    <div class="alert alert-success">
                        <h6><i class="fas fa-lightbulb me-2"></i>Como usar os esquemas</h6>
                        <p class="mb-2">Para referenciar um esquema em endpoints, use:</p>
                        <code>{"$ref": "#/components/schemas/NomeDoEsquema"}</code>
                        <p class="mt-2 mb-0">Isso garante reutilização e consistência nos seus dados!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Criação/Edição de Schema -->
<div class="modal fade" id="schemaModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen-xl-down modal-xl">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header modal-header-gradient text-white position-relative overflow-hidden">
                <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10">
                    <div class="bg-primary w-100 h-100 gradient-bg-purple"></div>
                </div>
                <div class="d-flex align-items-center position-relative z-index-2 w-100">
                    <div class="flex-grow-1">
                        <h5 class="modal-title mb-1 fw-bold">
                            <i class="fas fa-cube me-2"></i>
                            <span id="modal-title">Configurar Esquema</span>
                        </h5>
                        <small class="opacity-75">Defina as estruturas de dados da sua API</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
            </div>
            <div class="modal-body">
                <form id="schema-form">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nome do Esquema *</label>
                                <input type="text" class="form-control" id="schema-name" required>
                                <div class="form-text">Use PascalCase (ex: UserProfile, ProductData)</div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Tipo *</label>
                                <select class="form-select" id="schema-type" onchange="handleTypeChange()">
                                    <?php foreach ($dataTypes as $value => $label): ?>
                                        <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Descrição</label>
                                <textarea class="form-control" id="schema-description" rows="3" placeholder="Descreva o propósito deste esquema..."></textarea>
                            </div>
                            
                            <!-- Configurações específicas para string -->
                            <div id="string-options" class="schema-type-options">
                                <div class="mb-3">
                                    <label class="form-label">Formato</label>
                                    <select class="form-select" id="schema-format">
                                        <?php foreach ($stringFormats as $value => $label): ?>
                                            <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="row">
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label class="form-label">Tamanho Mínimo</label>
                                            <input type="number" class="form-control" id="schema-min-length" min="0">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label class="form-label">Tamanho Máximo</label>
                                            <input type="number" class="form-control" id="schema-max-length" min="0">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Padrão (Regex)</label>
                                    <input type="text" class="form-control" id="schema-pattern" placeholder="^[a-zA-Z0-9]+$">
                                </div>
                            </div>
                            
                            <!-- Configurações específicas para number/integer -->
                            <div id="number-options" class="schema-type-options">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label class="form-label">Valor Mínimo</label>
                                            <input type="number" class="form-control" id="schema-minimum" step="any">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label class="form-label">Valor Máximo</label>
                                            <input type="number" class="form-control" id="schema-maximum" step="any">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="schema-exclusive-minimum">
                                    <label class="form-check-label">Mínimo Exclusivo</label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="schema-exclusive-maximum">
                                    <label class="form-check-label">Máximo Exclusivo</label>
                                </div>
                            </div>
                            
                            <!-- Configurações específicas para array -->
                            <div id="array-options" class="schema-type-options">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label class="form-label">Itens Mínimos</label>
                                            <input type="number" class="form-control" id="schema-min-items" min="0">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label class="form-label">Itens Máximos</label>
                                            <input type="number" class="form-control" id="schema-max-items" min="0">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="schema-unique-items">
                                    <label class="form-check-label">Itens Únicos</label>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Tipo dos Itens</label>
                                    <select class="form-select" id="schema-items-type">
                                        <?php foreach ($dataTypes as $value => $label): ?>
                                            <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <!-- Propriedades para tipo object -->
                            <div id="object-properties">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6>Propriedades do Objeto</h6>
                                    <button type="button" class="btn btn-success btn-sm" onclick="addProperty()">
                                        <i class="fas fa-plus"></i>
                                        Adicionar Propriedade
                                    </button>
                                </div>
                                
                                <div id="properties-container">
                                    <!-- Propriedades serão adicionadas aqui -->
                                </div>
                            </div>
                            
                            <!-- Preview do Schema -->
                            <div class="mt-4">
                                <h6>Preview JSON</h6>
                                <div class="bg-light p-3 rounded">
                                    <pre id="schema-preview"><code>{}</code></pre>
                                </div>
                            </div>
                            
                            <!-- Exemplo Gerado -->
                            <div class="mt-3">
                                <h6>Exemplo de Dados</h6>
                                <div class="bg-success-subtle p-3 rounded">
                                    <pre id="schema-example"><code>{}</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 bg-light px-4 py-3 d-flex justify-content-end gap-3">
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>
                    Cancelar
                </button>
                <button type="button" class="btn btn-save" onclick="saveSchema()">
                    <i class="fas fa-check me-2"></i>
                    Salvar Esquema
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Visualizar Exemplo -->
<div class="modal fade" id="exampleModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen-xl-down modal-xl">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header modal-header-gradient text-white position-relative overflow-hidden">
                <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10">
                    <div class="bg-primary w-100 h-100 gradient-bg-purple"></div>
                </div>
                <div class="d-flex align-items-center position-relative z-index-2 w-100">
                    <div class="flex-grow-1">
                        <h5 class="modal-title mb-1 fw-bold">
                            <i class="fas fa-eye me-2"></i>
                            Exemplo de Dados
                        </h5>
                        <small class="opacity-75">Visualize como os dados ficam estruturados</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
            </div>
            <div class="modal-body">
                <div id="example-content"></div>
            </div>
            <div class="modal-footer border-0 bg-light px-4 py-3 d-flex justify-content-end gap-3">
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>
                    Fechar
                </button>
                <button type="button" class="btn btn-save" onclick="copyExample()">
                    <i class="fas fa-copy me-2"></i>
                    Copiar Exemplo
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentSchemaName = null;
let currentSchemas = <?php echo json_encode($schemas); ?>;
let propertyCounter = 0;

// Função para mostrar modal de schema
function showSchemaModal(schemaName = null) {
    currentSchemaName = schemaName;
    
    if (schemaName) {
        // Editar esquema existente
        document.getElementById('modal-title').textContent = 'Editar Esquema';
        loadSchemaData(schemaName);
    } else {
        // Criar novo esquema
        document.getElementById('modal-title').textContent = 'Criar Esquema';
        resetSchemaForm();
    }
    
    // Usar getOrCreateInstance para evitar conflitos
    const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('schemaModal'));
    modal.show();
    updatePreview();
}

// Função para carregar dados de um esquema existente
function loadSchemaData(schemaName) {
    const schema = currentSchemas[schemaName];
    if (!schema) return;
    
    document.getElementById('schema-name').value = schemaName;
    document.getElementById('schema-type').value = schema.type || 'object';
    document.getElementById('schema-description').value = schema.description || '';
    
    // Carregar configurações específicas do tipo
    if (schema.type === 'string') {
        document.getElementById('schema-format').value = schema.format || '';
        document.getElementById('schema-min-length').value = schema.minLength || '';
        document.getElementById('schema-max-length').value = schema.maxLength || '';
        document.getElementById('schema-pattern').value = schema.pattern || '';
    }
    
    if (schema.type === 'number' || schema.type === 'integer') {
        document.getElementById('schema-minimum').value = schema.minimum || '';
        document.getElementById('schema-maximum').value = schema.maximum || '';
        document.getElementById('schema-exclusive-minimum').checked = schema.exclusiveMinimum || false;
        document.getElementById('schema-exclusive-maximum').checked = schema.exclusiveMaximum || false;
    }
    
    if (schema.type === 'array') {
        document.getElementById('schema-min-items').value = schema.minItems || '';
        document.getElementById('schema-max-items').value = schema.maxItems || '';
        document.getElementById('schema-unique-items').checked = schema.uniqueItems || false;
        document.getElementById('schema-items-type').value = (schema.items && schema.items.type) || 'string';
    }
    
    // Carregar propriedades para objects
    if (schema.type === 'object' && schema.properties) {
        const container = document.getElementById('properties-container');
        container.innerHTML = '';
        propertyCounter = 0;
        
        Object.keys(schema.properties).forEach(propName => {
            const propData = schema.properties[propName];
            addProperty(propName, propData, (schema.required || []).includes(propName));
        });
    }
    
    handleTypeChange();
}

// Função para resetar o formulário
function resetSchemaForm() {
    document.getElementById('schema-form').reset();
    document.getElementById('properties-container').innerHTML = '';
    propertyCounter = 0;
    handleTypeChange();
}

// Função para lidar com mudança de tipo
function handleTypeChange() {
    const type = document.getElementById('schema-type').value;
    
    // Esconder todas as opções específicas
    document.getElementById('string-options').classList.remove('show');
    document.getElementById('number-options').classList.remove('show');
    document.getElementById('array-options').classList.remove('show');
    document.getElementById('object-properties').style.display = 'none';
    
    // Mostrar opções relevantes
    if (type === 'string') {
        document.getElementById('string-options').classList.add('show');
    } else if (type === 'number' || type === 'integer') {
        document.getElementById('number-options').classList.add('show');
    } else if (type === 'array') {
        document.getElementById('array-options').classList.add('show');
    } else if (type === 'object') {
        document.getElementById('object-properties').style.display = 'block';
    }
    
    updatePreview();
}

// Função para adicionar propriedade
function addProperty(name = '', propData = {}, required = false) {
    propertyCounter++;
    const container = document.getElementById('properties-container');
    
    const propertyDiv = document.createElement('div');
    propertyDiv.className = 'border rounded p-3 mb-3';
    propertyDiv.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0">Propriedade #${propertyCounter}</h6>
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeProperty(this)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Nome *</label>
                    <input type="text" class="form-control property-name" value="${name}" onchange="updatePreview()" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Tipo *</label>
                    <select class="form-select property-type" onchange="updatePreview()">
                        ${Object.keys(<?php echo json_encode($dataTypes); ?>).map(type => 
                            `<option value="${type}" ${propData.type === type ? 'selected' : ''}>${<?php echo json_encode($dataTypes); ?>[type]}</option>`
                        ).join('')}
                    </select>
                </div>
                
                <div class="form-check">
                    <input class="form-check-input property-required" type="checkbox" ${required ? 'checked' : ''} onchange="updatePreview()">
                    <label class="form-check-label">Obrigatório</label>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Descrição</label>
                    <textarea class="form-control property-description" rows="2" onchange="updatePreview()">${propData.description || ''}</textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Formato</label>
                    <select class="form-select property-format" onchange="updatePreview()">
                        ${Object.keys(<?php echo json_encode($stringFormats); ?>).map(format => 
                            `<option value="${format}" ${propData.format === format ? 'selected' : ''}>${<?php echo json_encode($stringFormats); ?>[format]}</option>`
                        ).join('')}
                    </select>
                </div>
            </div>
        </div>
    `;
    
    container.appendChild(propertyDiv);
    updatePreview();
}

// Função para remover propriedade
function removeProperty(button) {
    button.closest('.border').remove();
    updatePreview();
}

// Função para atualizar preview
function updatePreview() {
    const schema = buildSchemaObject();
    
    // Atualizar preview JSON
    document.getElementById('schema-preview').textContent = JSON.stringify(schema, null, 2);
    
    // Gerar exemplo
    const example = generateSchemaExample(schema);
    document.getElementById('schema-example').textContent = JSON.stringify(example, null, 2);
}

// Função para construir objeto do schema
function buildSchemaObject() {
    const type = document.getElementById('schema-type').value;
    const description = document.getElementById('schema-description').value;
    
    const schema = { type };
    
    if (description) {
        schema.description = description;
    }
    
    // Configurações específicas por tipo
    if (type === 'string') {
        const format = document.getElementById('schema-format').value;
        const minLength = document.getElementById('schema-min-length').value;
        const maxLength = document.getElementById('schema-max-length').value;
        const pattern = document.getElementById('schema-pattern').value;
        
        if (format) schema.format = format;
        if (minLength) schema.minLength = parseInt(minLength);
        if (maxLength) schema.maxLength = parseInt(maxLength);
        if (pattern) schema.pattern = pattern;
    }
    
    if (type === 'number' || type === 'integer') {
        const minimum = document.getElementById('schema-minimum').value;
        const maximum = document.getElementById('schema-maximum').value;
        const exclusiveMinimum = document.getElementById('schema-exclusive-minimum').checked;
        const exclusiveMaximum = document.getElementById('schema-exclusive-maximum').checked;
        
        if (minimum !== '') schema.minimum = parseFloat(minimum);
        if (maximum !== '') schema.maximum = parseFloat(maximum);
        if (exclusiveMinimum) schema.exclusiveMinimum = true;
        if (exclusiveMaximum) schema.exclusiveMaximum = true;
    }
    
    if (type === 'array') {
        const minItems = document.getElementById('schema-min-items').value;
        const maxItems = document.getElementById('schema-max-items').value;
        const uniqueItems = document.getElementById('schema-unique-items').checked;
        const itemsType = document.getElementById('schema-items-type').value;
        
        if (minItems) schema.minItems = parseInt(minItems);
        if (maxItems) schema.maxItems = parseInt(maxItems);
        if (uniqueItems) schema.uniqueItems = true;
        
        schema.items = { type: itemsType };
    }
    
    if (type === 'object') {
        const propertyElements = document.querySelectorAll('#properties-container .border');
        const properties = {};
        const required = [];
        
        propertyElements.forEach(element => {
            const name = element.querySelector('.property-name').value;
            const propType = element.querySelector('.property-type').value;
            const propDescription = element.querySelector('.property-description').value;
            const propFormat = element.querySelector('.property-format').value;
            const isRequired = element.querySelector('.property-required').checked;
            
            if (name) {
                properties[name] = { type: propType };
                
                if (propDescription) properties[name].description = propDescription;
                if (propFormat && propType === 'string') properties[name].format = propFormat;
                if (isRequired) required.push(name);
            }
        });
        
        if (Object.keys(properties).length > 0) {
            schema.properties = properties;
        }
        
        if (required.length > 0) {
            schema.required = required;
        }
    }
    
    return schema;
}

// Função para gerar exemplo do schema
function generateSchemaExample(schema) {
    const type = schema.type || 'string';
    
    switch (type) {
        case 'string':
            if (schema.format === 'date') return '2023-12-25';
            if (schema.format === 'date-time') return '2023-12-25T10:30:00Z';
            if (schema.format === 'email') return 'user@example.com';
            if (schema.format === 'uri') return 'https://example.com';
            if (schema.format === 'uuid') return '123e4567-e89b-12d3-a456-426614174000';
            return 'exemplo de texto';
            
        case 'integer':
            return schema.minimum ? Math.max(schema.minimum, 1) : 42;
            
        case 'number':
            return schema.minimum ? Math.max(schema.minimum, 1.0) : 3.14;
            
        case 'boolean':
            return true;
            
        case 'array':
            const itemType = schema.items?.type || 'string';
            const itemExample = generateSchemaExample({ type: itemType });
            return [itemExample, itemExample];
            
        case 'object':
            const example = {};
            if (schema.properties) {
                Object.keys(schema.properties).forEach(propName => {
                    example[propName] = generateSchemaExample(schema.properties[propName]);
                });
            }
            return example;
            
        case 'null':
            return null;
            
        default:
            return 'exemplo';
    }
}

// Função para salvar schema
function saveSchema() {
    const schemaName = document.getElementById('schema-name').value.trim();
    
    if (!schemaName) {
        alert('Por favor, informe o nome do esquema.');
        return;
    }
    
    // Verificar se o nome já existe (apenas para novos schemas)
    if (!currentSchemaName && currentSchemas[schemaName]) {
        if (!confirm(`Já existe um esquema com o nome "${schemaName}". Deseja substituí-lo?`)) {
            return;
        }
    }
    
    const schema = buildSchemaObject();
    
    // Atualizar esquemas
    if (currentSchemaName && currentSchemaName !== schemaName) {
        // Renomear esquema
        delete currentSchemas[currentSchemaName];
    }
    
    currentSchemas[schemaName] = schema;
    
    // Fechar modal
    const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('schemaModal'));
    modal.hide();
    
    // Recarregar interface
    refreshSchemasDisplay();
    
    // Mostrar mensagem de sucesso
    showNotification('success', `Esquema "${schemaName}" salvo com sucesso!`);
}

// Função para atualizar display dos schemas
function refreshSchemasDisplay() {
    const container = document.getElementById('schemas-container');
    const emptyState = document.getElementById('empty-state');
    
    if (Object.keys(currentSchemas).length === 0) {
        if (emptyState) {
            emptyState.style.display = 'block';
        }
        // Limpar container se não houver empty state
        container.querySelectorAll('.schema-item').forEach(item => item.remove());
    } else {
        if (emptyState) {
            emptyState.style.display = 'none';
        }
        
        // Recriar todos os cards
        container.innerHTML = '';
        Object.keys(currentSchemas).forEach(schemaName => {
            container.insertAdjacentHTML('beforeend', renderSchemaCardJS(schemaName, currentSchemas[schemaName]));
        });
    }
}

// Função para renderizar card do schema em JavaScript
function renderSchemaCardJS(schemaName, schemaData) {
    const type = schemaData.type || 'object';
    const description = schemaData.description || '';
    const properties = schemaData.properties || {};
    const required = schemaData.required || [];
    
    const propertiesCount = Object.keys(properties).length;
    const requiredCount = required.length;
    
    return `
        <div class="schema-item card mb-3" data-schema="${schemaName}">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">
                            <i class="fas fa-cube me-2 text-primary"></i>
                            <span class="fw-bold">${schemaName}</span>
                            <span class="badge bg-secondary ms-2">${type.charAt(0).toUpperCase() + type.slice(1)}</span>
                        </h6>
                        ${description ? `<p class="text-muted small mb-0">${description}</p>` : ''}
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="editSchema('${schemaName}')" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-outline-info btn-sm" onclick="duplicateSchema('${schemaName}')" title="Duplicar">
                            <i class="fas fa-copy"></i>
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm" onclick="showExample('${schemaName}')" title="Ver Exemplo">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeSchema('${schemaName}')" title="Remover">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                ${type === 'object' && propertiesCount > 0 ? `
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-list me-1"></i>Propriedades (${propertiesCount})</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead><tr><th>Nome</th><th>Tipo</th><th>Status</th></tr></thead>
                                    <tbody>
                                        ${Object.keys(properties).map(propName => {
                                            const propData = properties[propName];
                                            const propType = propData.type || 'string';
                                            const propFormat = propData.format || '';
                                            const isRequired = required.includes(propName);
                                            
                                            return `
                                                <tr>
                                                    <td>
                                                        <code>${propName}</code>
                                                        ${propData.description ? `<br><small class="text-muted">${propData.description}</small>` : ''}
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info">${propType}</span>
                                                        ${propFormat ? `<br><small class="text-muted">${propFormat}</small>` : ''}
                                                    </td>
                                                    <td>
                                                        ${isRequired ? 
                                                            '<span class="badge bg-warning">Obrigatório</span>' : 
                                                            '<span class="badge bg-secondary">Opcional</span>'
                                                        }
                                                    </td>
                                                </tr>
                                            `;
                                        }).join('')}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-code me-1"></i>Preview JSON</h6>
                            <div class="bg-light p-3 rounded">
                                <pre class="mb-0"><code>${JSON.stringify(schemaData, null, 2)}</code></pre>
                            </div>
                        </div>
                    </div>
                ` : `
                    <div class="bg-light p-3 rounded">
                        <pre class="mb-0"><code>${JSON.stringify(schemaData, null, 2)}</code></pre>
                    </div>
                `}
            </div>
        </div>
    `;
}

// Função para editar schema
function editSchema(schemaName) {
    showSchemaModal(schemaName);
}

// Função para duplicar schema
function duplicateSchema(schemaName) {
    const originalSchema = currentSchemas[schemaName];
    if (!originalSchema) return;
    
    let newName = `${schemaName}Copy`;
    let counter = 1;
    
    while (currentSchemas[newName]) {
        newName = `${schemaName}Copy${counter}`;
        counter++;
    }
    
    currentSchemas[newName] = JSON.parse(JSON.stringify(originalSchema));
    refreshSchemasDisplay();
    showNotification('success', `Esquema duplicado como "${newName}"`);
}

// Função para mostrar exemplo
function showExample(schemaName) {
    const schema = currentSchemas[schemaName];
    if (!schema) return;
    
    const example = generateSchemaExample(schema);
    
    const content = `
        <div class="mb-3">
            <h6>Esquema: ${schemaName}</h6>
            <div class="bg-light p-3 rounded">
                <pre><code>${JSON.stringify(schema, null, 2)}</code></pre>
            </div>
        </div>
        
        <div>
            <h6>Exemplo de Dados:</h6>
            <div class="bg-success-subtle p-3 rounded">
                <pre id="example-to-copy"><code>${JSON.stringify(example, null, 2)}</code></pre>
            </div>
        </div>
    `;
    
    document.getElementById('example-content').innerHTML = content;
    new bootstrap.Modal(document.getElementById('exampleModal')).show();
}

// Função para copiar exemplo
function copyExample() {
    const exampleText = document.querySelector('#example-to-copy code').textContent;
    
    navigator.clipboard.writeText(exampleText).then(() => {
        showNotification('success', 'Exemplo copiado para a área de transferência!');
    }).catch(() => {
        // Fallback para navegadores mais antigos
        const textArea = document.createElement('textarea');
        textArea.value = exampleText;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showNotification('success', 'Exemplo copiado para a área de transferência!');
    });
}

// Função para remover schema
function removeSchema(schemaName) {
    if (!confirm(`Tem certeza que deseja remover o esquema "${schemaName}"?`)) {
        return;
    }
    
    delete currentSchemas[schemaName];
    refreshSchemasDisplay();
    showNotification('success', `Esquema "${schemaName}" removido com sucesso!`);
}

// Função para mostrar notificações
function showNotification(type, message) {
    // Implementar sistema de notificações toast
    const toastHtml = `
        <div class="toast align-items-center text-bg-${type === 'success' ? 'success' : 'danger'} border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-${type === 'success' ? 'check' : 'exclamation-triangle'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    // Criar container de toasts se não existir
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '1080';
        document.body.appendChild(toastContainer);
    }
    
    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    
    const toastElement = toastContainer.lastElementChild;
    const toast = new bootstrap.Toast(toastElement);
    toast.show();
    
    // Remover o elemento após ser ocultado
    toastElement.addEventListener('hidden.bs.toast', () => {
        toastElement.remove();
    });
}

// Inicializar listeners
document.addEventListener('DOMContentLoaded', function() {
    // Listener para mudanças nos campos de preview
    document.addEventListener('input', function(e) {
        if (e.target.closest('#schemaModal')) {
            updatePreview();
        }
    });
    
    // Atualizar dados do formulário quando os schemas mudarem
    document.getElementById('schemas-form').addEventListener('submit', function(e) {
        // Criar input hidden com os schemas atualizados
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'schemas_data';
        input.value = JSON.stringify(currentSchemas);
        this.appendChild(input);
    });
});
</script>