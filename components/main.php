<?php
// Componente para edição das rotas principais (paths) da API
$paths = $openApiData['paths'] ?? new stdClass();
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-code me-2"></i>
                    <?php echo t('main'); ?> - Endpoints da API
                </h5>
                <div class="d-flex gap-2">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-filter me-1"></i>
                            Filtrar por Tag
                        </button>
                        <ul class="dropdown-menu" id="tag-filter-menu">
                            <li><a class="dropdown-item" href="#" onclick="filterByTag('')">
                                <i class="fas fa-list me-2"></i>Todas as Tags
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <!-- Tags serão carregadas dinamicamente -->
                        </ul>
                    </div>
                    <button type="button" class="btn btn-success btn-sm" onclick="addEndpoint()" data-bs-toggle="modal" data-bs-target="#addEndpointModal">
                        <i class="fas fa-plus"></i>
                        Adicionar Endpoint
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Esta é a seção principal onde você define todos os endpoints da sua API. Cada endpoint representa uma operação que sua API pode realizar.
                </div>
                
                <form method="POST" id="paths-form" onsubmit="return serializeDataBeforeSubmit(this);">
                    <input type="hidden" name="save_section" value="1">
                    <input type="hidden" name="section" value="main">
                    <input type="hidden" name="paths" id="paths-json">
                    
                    <div id="endpoints-container">
                        <?php if (empty((array)$paths)): ?>
                            <div class="text-center py-4" id="empty-state">
                                <i class="fas fa-code display-4 text-muted"></i>
                                <p class="text-muted mt-2">Nenhum endpoint definido ainda. Comece adicionando seu primeiro endpoint!</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ((array)$paths as $pathUrl => $pathMethods): ?>
                                <?php foreach ((array)$pathMethods as $method => $operation): ?>
                                    <?php echo renderEndpointEditor($pathUrl, $method, $operation); ?>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>
                            Salvar Endpoints
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Adicionar/Editar Endpoint -->
<div class="modal fade" id="addEndpointModal" tabindex="-1" aria-labelledby="addEndpointModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEndpointModalLabel">
                    <i class="fas fa-plus me-2"></i>
                    Adicionar Novo Endpoint
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="endpoint-form">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="endpoint-method" class="form-label">Método HTTP</label>
                            <select class="form-select" id="endpoint-method" required>
                                <option value="">Selecione o método</option>
                                <option value="get" data-color="primary">GET</option>
                                <option value="post" data-color="success">POST</option>
                                <option value="put" data-color="warning">PUT</option>
                                <option value="patch" data-color="info">PATCH</option>
                                <option value="delete" data-color="danger">DELETE</option>
                                <option value="head" data-color="secondary">HEAD</option>
                                <option value="options" data-color="dark">OPTIONS</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label for="endpoint-path" class="form-label">Caminho do Endpoint</label>
                            <input type="text" class="form-control" id="endpoint-path" placeholder="/users/{id}" required>
                            <div class="form-text">Use {parametro} para parâmetros de caminho</div>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="endpoint-summary" class="form-label">Resumo</label>
                            <input type="text" class="form-control" id="endpoint-summary" placeholder="Obter usuário por ID" required>
                        </div>
                        <div class="col-md-6">
                            <label for="endpoint-tags" class="form-label">Tags</label>
                            <input type="text" class="form-control" id="endpoint-tags" placeholder="users, authentication">
                            <div class="form-text">Separar por vírgulas</div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <label for="endpoint-description" class="form-label">Descrição</label>
                        <textarea class="form-control" id="endpoint-description" rows="3" placeholder="Descrição detalhada do endpoint..."></textarea>
                    </div>
                    
                    <!-- Abas para diferentes seções -->
                    <div class="mt-4">
                        <ul class="nav nav-tabs" id="endpointTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="parameters-tab" data-bs-toggle="tab" data-bs-target="#parameters" type="button" role="tab">
                                    <i class="fas fa-cog me-1"></i>Parâmetros
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="request-tab" data-bs-toggle="tab" data-bs-target="#request" type="button" role="tab">
                                    <i class="fas fa-upload me-1"></i>Request Body
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="responses-tab" data-bs-toggle="tab" data-bs-target="#responses" type="button" role="tab">
                                    <i class="fas fa-download me-1"></i>Responses
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="headers-tab" data-bs-toggle="tab" data-bs-target="#headers" type="button" role="tab">
                                    <i class="fas fa-list me-1"></i>Headers
                                </button>
                            </li>
                        </ul>
                        
                        <div class="tab-content mt-3" id="endpointTabContent">
                            <!-- Tab Parâmetros -->
                            <div class="tab-pane fade show active" id="parameters" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">Parâmetros do Endpoint</h6>
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="addParameter()">
                                        <i class="fas fa-plus"></i> Adicionar Parâmetro
                                    </button>
                                </div>
                                <div id="parameters-container">
                                    <div class="text-muted text-center py-3">Nenhum parâmetro adicionado</div>
                                </div>
                            </div>
                            
                            <!-- Tab Request Body -->
                            <div class="tab-pane fade" id="request" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="request-content-type" class="form-label">Content-Type</label>
                                        <select class="form-select" id="request-content-type">
                                            <option value="">Nenhum body</option>
                                            <option value="application/json">application/json</option>
                                            <option value="application/xml">application/xml</option>
                                            <option value="application/x-www-form-urlencoded">application/x-www-form-urlencoded</option>
                                            <option value="multipart/form-data">multipart/form-data</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="request-required" class="form-label">Obrigatório</label>
                                        <select class="form-select" id="request-required">
                                            <option value="false">Não</option>
                                            <option value="true">Sim</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label for="request-schema" class="form-label">Schema (JSON)</label>
                                        <textarea class="form-control" id="request-schema" rows="8" placeholder='{"type": "object", "properties": {"name": {"type": "string"}}}'></textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="request-example" class="form-label">Exemplo de Dados (JSON)</label>
                                        <textarea class="form-control" id="request-example" rows="8" placeholder='{"name": "João Silva", "age": 30}'></textarea>
                                        <div class="form-text">Exemplo de dados que serão enviados no request body</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Tab Responses -->
                            <div class="tab-pane fade" id="responses" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">Respostas da API</h6>
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="addResponse()">
                                        <i class="fas fa-plus"></i> Adicionar Resposta
                                    </button>
                                </div>
                                <div id="responses-container">
                                    <div class="text-muted text-center py-3">Nenhuma resposta definida</div>
                                </div>
                            </div>
                            
                            <!-- Tab Headers -->
                            <div class="tab-pane fade" id="headers" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">Headers Personalizados</h6>
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="addHeader()">
                                        <i class="fas fa-plus"></i> Adicionar Header
                                    </button>
                                </div>
                                <div id="headers-container">
                                    <div class="text-muted text-center py-3">Nenhum header personalizado</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="saveEndpoint()">
                    <i class="fas fa-save me-1"></i>
                    Salvar Endpoint
                </button>
            </div>
        </div>
    </div>
</div>

<?php
function getMethodColor($method) {
    $colors = [
        'get' => 'primary',
        'post' => 'success', 
        'put' => 'warning',
        'patch' => 'info',
        'delete' => 'danger',
        'head' => 'secondary',
        'options' => 'dark'
    ];
    return $colors[strtolower($method)] ?? 'secondary';
}

function getParamLocationColor($location) {
    $colors = [
        'query' => 'info',
        'path' => 'primary',
        'header' => 'secondary',
        'cookie' => 'warning'
    ];
    return $colors[strtolower($location)] ?? 'info';
}

function getStatusCodeColor($statusCode) {
    $code = intval($statusCode);
    if ($code >= 200 && $code < 300) return 'success';
    if ($code >= 300 && $code < 400) return 'info';
    if ($code >= 400 && $code < 500) return 'warning';
    if ($code >= 500) return 'danger';
    return 'secondary';
}

function getStatusCodeName($statusCode) {
    $names = [
        '200' => 'OK',
        '201' => 'Created',
        '204' => 'No Content',
        '400' => 'Bad Request',
        '401' => 'Unauthorized',
        '403' => 'Forbidden',
        '404' => 'Not Found',
        '422' => 'Unprocessable Entity',
        '500' => 'Internal Server Error',
        'default' => 'Default Response'
    ];
    return $names[$statusCode] ?? 'Unknown';
}

function renderEndpointEditor($pathUrl, $method, $operation) {
    $methodColor = getMethodColor($method);
    $operationId = $pathUrl . '_' . $method;
    $summary = htmlspecialchars($operation['summary'] ?? 'Sem título');
    $description = htmlspecialchars($operation['description'] ?? 'Sem descrição');
    
    ob_start();
    ?>
    <div class="endpoint-group border rounded p-3 mb-4" data-path="<?php echo htmlspecialchars($pathUrl); ?>" data-method="<?php echo $method; ?>">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <h6 class="mb-1">
                    <span class="badge bg-<?php echo $methodColor; ?> me-2"><?php echo strtoupper($method); ?></span>
                    <code class="bg-light px-2 py-1 rounded"><?php echo htmlspecialchars($pathUrl); ?></code>
                </h6>
                <div class="text-muted small"><?php echo $summary; ?></div>
                <?php if (!empty($operation['description'])): ?>
                    <div class="text-muted small mt-1"><?php echo $description; ?></div>
                <?php endif; ?>
                <?php if (isset($operation['tags']) && !empty($operation['tags'])): ?>
                    <div class="mt-2">
                        <?php foreach ($operation['tags'] as $tag): ?>
                            <span class="badge bg-secondary me-1"><?php echo htmlspecialchars($tag); ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="editEndpoint(this)" title="Editar endpoint">
                    <i class="fas fa-edit"></i>
                </button>
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeEndpointGroup(this)" title="Remover endpoint">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
        
        <!-- Detalhes colapsáveis -->
        <div class="collapse" id="details-<?php echo md5($operationId); ?>">
            <div class="border-top pt-3">
                <div class="row">
                    <!-- Parâmetros -->
                    <?php if (isset($operation['parameters']) && !empty($operation['parameters'])): ?>
                        <div class="col-md-4">
                            <h6 class="text-muted mb-2">
                                <i class="fas fa-cog me-1"></i>
                                Parâmetros (<?php echo count($operation['parameters']); ?>)
                            </h6>
                            <?php foreach ($operation['parameters'] as $param): ?>
                                <div class="border rounded p-2 mb-2 small">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <code class="text-primary"><?php echo htmlspecialchars($param['name'] ?? 'unnamed'); ?></code>
                                        <div>
                                            <span class="badge bg-<?php echo getParamLocationColor($param['in'] ?? 'query'); ?> text-white">
                                                <?php echo strtoupper($param['in'] ?? 'query'); ?>
                                            </span>
                                            <?php if ($param['required'] ?? false): ?>
                                                <span class="badge bg-warning">required</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php if (!empty($param['description'])): ?>
                                        <div class="text-muted small mt-1"><?php echo htmlspecialchars($param['description']); ?></div>
                                    <?php endif; ?>
                                    <?php if (!empty($param['schema']['type'])): ?>
                                        <div class="text-info small">Tipo: <?php echo htmlspecialchars($param['schema']['type']); ?></div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Request Body -->
                    <?php if (isset($operation['requestBody']) && !empty($operation['requestBody'])): ?>
                        <div class="col-md-4">
                            <h6 class="text-muted mb-2">
                                <i class="fas fa-upload me-1"></i>
                                Request Body
                                <?php if ($operation['requestBody']['required'] ?? false): ?>
                                    <span class="badge bg-warning small">required</span>
                                <?php endif; ?>
                            </h6>
                            <?php if (!empty($operation['requestBody']['description'])): ?>
                                <p class="small text-muted mb-2"><?php echo htmlspecialchars($operation['requestBody']['description']); ?></p>
                            <?php endif; ?>
                            <?php if (isset($operation['requestBody']['content']) && !empty($operation['requestBody']['content'])): ?>
                                <?php foreach ($operation['requestBody']['content'] as $contentType => $contentData): ?>
                                    <div class="border rounded p-2 mb-2 small">
                                        <div class="fw-bold text-success"><?php echo htmlspecialchars($contentType); ?></div>
                                        <?php if (isset($contentData['schema']['$ref'])): ?>
                                            <div class="text-info small">Schema: <?php echo htmlspecialchars(basename($contentData['schema']['$ref'])); ?></div>
                                        <?php elseif (isset($contentData['schema']['type'])): ?>
                                            <div class="text-info small">Tipo: <?php echo htmlspecialchars($contentData['schema']['type']); ?></div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Responses -->
                    <?php if (isset($operation['responses']) && !empty($operation['responses'])): ?>
                        <div class="col-md-4">
                            <h6 class="text-muted mb-2">
                                <i class="fas fa-download me-1"></i>
                                Respostas (<?php echo count($operation['responses']); ?>)
                            </h6>
                            <?php foreach ($operation['responses'] as $statusCode => $response): ?>
                                <div class="border rounded p-2 mb-2 small">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <span class="badge bg-<?php echo getStatusCodeColor($statusCode); ?>">
                                            <?php echo $statusCode; ?>
                                        </span>
                                        <small class="text-muted"><?php echo getStatusCodeName($statusCode); ?></small>
                                    </div>
                                    <div class="fw-bold small"><?php echo htmlspecialchars($response['description'] ?? 'Sem descrição'); ?></div>
                                    <?php if (isset($response['content']) && !empty($response['content'])): ?>
                                        <div class="mt-1">
                                            <?php foreach ($response['content'] as $contentType => $contentData): ?>
                                                <div class="text-success small"><?php echo htmlspecialchars($contentType); ?></div>
                                                <?php if (isset($contentData['schema']['$ref'])): ?>
                                                    <div class="text-info small">Schema: <?php echo htmlspecialchars(basename($contentData['schema']['$ref'])); ?></div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (isset($response['headers']) && !empty($response['headers'])): ?>
                                        <div class="mt-1">
                                            <div class="text-muted small">Headers:</div>
                                            <?php foreach ($response['headers'] as $headerName => $headerData): ?>
                                                <div class="text-info small">• <?php echo htmlspecialchars($headerName); ?></div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="mt-2">
            <button class="btn btn-link btn-sm p-0 text-decoration-none" type="button" 
                    data-bs-toggle="collapse" data-bs-target="#details-<?php echo md5($operationId); ?>"
                    aria-expanded="false">
                <i class="fas fa-chevron-down me-1"></i>
                Ver detalhes
            </button>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
?>

<script>
let currentEndpoint = null;
let parameterCounter = 0;
let responseCounter = 0;
let headerCounter = 0;

// Disponibilizar dados do OpenAPI globalmente para JavaScript
const openApiData = <?php echo json_encode($openApiData); ?>;
let availableTags = new Set();
let currentFilter = '';

// Inicializar tags disponíveis
function initializeTags() {
    availableTags.clear();
    
    if (openApiData.paths) {
        Object.keys(openApiData.paths).forEach(path => {
            Object.keys(openApiData.paths[path]).forEach(method => {
                const operation = openApiData.paths[path][method];
                if (operation.tags && Array.isArray(operation.tags)) {
                    operation.tags.forEach(tag => availableTags.add(tag));
                }
            });
        });
    }
    
    updateTagFilterMenu();
}

// Atualizar menu de filtro de tags
function updateTagFilterMenu() {
    const menu = document.getElementById('tag-filter-menu');
    const tagsArray = Array.from(availableTags).sort();
    
    // Manter o "Todas as Tags" e o divider
    let menuHTML = `
        <li><a class="dropdown-item ${currentFilter === '' ? 'active' : ''}" href="#" onclick="filterByTag('')">
            <i class="fas fa-list me-2"></i>Todas as Tags
        </a></li>
        <li><hr class="dropdown-divider"></li>
    `;
    
    if (tagsArray.length === 0) {
        menuHTML += '<li><span class="dropdown-item-text text-muted">Nenhuma tag encontrada</span></li>';
    } else {
        tagsArray.forEach(tag => {
            menuHTML += `
                <li><a class="dropdown-item ${currentFilter === tag ? 'active' : ''}" href="#" onclick="filterByTag('${escapeHtml(tag)}')">
                    <i class="fas fa-tag me-2"></i>${escapeHtml(tag)}
                </a></li>
            `;
        });
    }
    
    menu.innerHTML = menuHTML;
}

// Função para filtrar endpoints por tag
function filterByTag(tag) {
    currentFilter = tag;
    const allEndpoints = document.querySelectorAll('.endpoint-group');
    let visibleCount = 0;
    
    allEndpoints.forEach(endpoint => {
        const tagBadges = endpoint.querySelectorAll('.badge.bg-secondary');
        const endpointTags = Array.from(tagBadges).map(badge => badge.textContent.trim());
        
        if (tag === '' || endpointTags.includes(tag)) {
            endpoint.style.display = 'block';
            visibleCount++;
        } else {
            endpoint.style.display = 'none';
        }
    });
    
    // Mostrar/esconder estado vazio
    const emptyState = document.getElementById('empty-state');
    const container = document.getElementById('endpoints-container');
    
    if (visibleCount === 0) {
        if (!emptyState) {
            const emptyHtml = tag === '' ? 
                `<div class="text-center py-4" id="empty-state">
                    <i class="fas fa-code display-4 text-muted"></i>
                    <p class="text-muted mt-2">Nenhum endpoint definido ainda. Comece adicionando seu primeiro endpoint!</p>
                </div>` :
                `<div class="text-center py-4" id="empty-state">
                    <i class="fas fa-filter display-4 text-muted"></i>
                    <p class="text-muted mt-2">Nenhum endpoint encontrado com a tag "${escapeHtml(tag)}".</p>
                </div>`;
            container.insertAdjacentHTML('afterbegin', emptyHtml);
        }
    } else if (emptyState) {
        emptyState.remove();
    }
    
    // Atualizar menu visual
    updateTagFilterMenu();
    
    // Mostrar notificação
    const message = tag === '' ? 
        'Exibindo todos os endpoints' : 
        `Filtrando por tag "${tag}" - ${visibleCount} endpoint(s) encontrado(s)`;
    
    showFilterNotification(message);
}

function addEndpoint() {
    currentEndpoint = null;
    document.getElementById('addEndpointModalLabel').innerHTML = '<i class="fas fa-plus me-2"></i>Adicionar Novo Endpoint';
    document.getElementById('endpoint-form').reset();
    clearAllContainers();
}

function editEndpoint(button) {
    const endpointGroup = button.closest('.endpoint-group');
    const path = endpointGroup.dataset.path;
    const method = endpointGroup.dataset.method;
    
    currentEndpoint = { path, method };
    document.getElementById('addEndpointModalLabel').innerHTML = '<i class="fas fa-edit me-2"></i>Editar Endpoint';
    
    // Limpar formulário primeiro
    document.getElementById('endpoint-form').reset();
    clearAllContainers();
    
    // Buscar dados do endpoint do PHP/JSON
    loadEndpointData(path, method);
    
    // Abrir modal
    const modal = new bootstrap.Modal(document.getElementById('addEndpointModal'));
    modal.show();
}

// Nova função para carregar dados do endpoint
function loadEndpointData(path, method) {
    // Acessar dados globais do PHP (se disponível)
    if (typeof openApiData !== 'undefined' && openApiData.paths && openApiData.paths[path] && openApiData.paths[path][method]) {
        const operation = openApiData.paths[path][method];
        
        // Preencher campos básicos
        document.getElementById('endpoint-method').value = method;
        document.getElementById('endpoint-path').value = path;
        document.getElementById('endpoint-summary').value = operation.summary || '';
        document.getElementById('endpoint-description').value = operation.description || '';
        
        // Preencher tags
        if (operation.tags && Array.isArray(operation.tags)) {
            document.getElementById('endpoint-tags').value = operation.tags.join(', ');
        }
        
        // Carregar parâmetros
        if (operation.parameters && Array.isArray(operation.parameters)) {
            loadParameters(operation.parameters);
        }
        
        // Carregar request body
        if (operation.requestBody) {
            loadRequestBody(operation.requestBody);
        }
        
        // Carregar responses
        if (operation.responses) {
            loadResponses(operation.responses);
        }
        
    } else {
        // Fallback: tentar extrair dados do DOM
        loadEndpointDataFromDOM(button.closest('.endpoint-group'));
    }
}

// Função para carregar dados do DOM como fallback
function loadEndpointDataFromDOM(endpointGroup) {
    const path = endpointGroup.dataset.path;
    const method = endpointGroup.dataset.method;
    
    // Preencher campos básicos
    document.getElementById('endpoint-method').value = method;
    document.getElementById('endpoint-path').value = path;
    
    // Extrair summary do DOM - primeiro div com classe text-muted small
    const summaryElements = endpointGroup.querySelectorAll('.text-muted.small');
    if (summaryElements.length > 0) {
        document.getElementById('endpoint-summary').value = summaryElements[0].textContent.trim();
    }
    
    // Extrair description se existir (segundo elemento text-muted small)
    if (summaryElements.length > 1) {
        document.getElementById('endpoint-description').value = summaryElements[1].textContent.trim();
    }
    
    // Extrair tags do DOM
    const tagBadges = endpointGroup.querySelectorAll('.badge.bg-secondary');
    if (tagBadges.length > 0) {
        const tags = Array.from(tagBadges).map(badge => badge.textContent.trim()).join(', ');
        document.getElementById('endpoint-tags').value = tags;
    }
}

// Função para carregar parâmetros no modal
function loadParameters(parameters) {
    const container = document.getElementById('parameters-container');
    container.innerHTML = '';
    
    parameters.forEach(param => {
        parameterCounter++;
        const parameterId = 'param-' + parameterCounter;
        const parameterHtml = `
            <div class="parameter-item border rounded p-3 mb-3" id="${parameterId}">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Nome</label>
                        <input type="text" class="form-control" name="param_name[]" value="${escapeHtml(param.name || '')}" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Localização</label>
                        <select class="form-select" name="param_in[]">
                            <option value="query" ${param.in === 'query' ? 'selected' : ''}>Query</option>
                            <option value="path" ${param.in === 'path' ? 'selected' : ''}>Path</option>
                            <option value="header" ${param.in === 'header' ? 'selected' : ''}>Header</option>
                            <option value="cookie" ${param.in === 'cookie' ? 'selected' : ''}>Cookie</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tipo</label>
                        <select class="form-select" name="param_type[]">
                            <option value="string" ${param.schema?.type === 'string' ? 'selected' : ''}>String</option>
                            <option value="integer" ${param.schema?.type === 'integer' ? 'selected' : ''}>Integer</option>
                            <option value="number" ${param.schema?.type === 'number' ? 'selected' : ''}>Number</option>
                            <option value="boolean" ${param.schema?.type === 'boolean' ? 'selected' : ''}>Boolean</option>
                            <option value="array" ${param.schema?.type === 'array' ? 'selected' : ''}>Array</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Descrição</label>
                        <input type="text" class="form-control" name="param_description[]" value="${escapeHtml(param.description || '')}" placeholder="Descrição do parâmetro">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="param_required[]" value="${parameterId}" ${param.required ? 'checked' : ''}>
                                <label class="form-check-label small">Obrigatório</label>
                            </div>
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeParameter('${parameterId}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <label class="form-label">Valor Exemplo</label>
                        <input type="text" class="form-control" name="param_example[]" value="${escapeHtml(param.example || param.schema?.example || '')}" placeholder="Exemplo de valor">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Valor Padrão</label>
                        <input type="text" class="form-control" name="param_default[]" value="${escapeHtml(param.schema?.default || '')}" placeholder="Valor padrão (opcional)">
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', parameterHtml);
    });
}

// Função para carregar request body no modal
function loadRequestBody(requestBody) {
    if (requestBody.content) {
        // Pegar o primeiro content-type disponível
        const contentType = Object.keys(requestBody.content)[0];
        const contentData = requestBody.content[contentType];
        
        document.getElementById('request-content-type').value = contentType || '';
        document.getElementById('request-required').value = requestBody.required ? 'true' : 'false';
        
        if (contentData.schema) {
            document.getElementById('request-schema').value = JSON.stringify(contentData.schema, null, 2);
        }
        
        // Carregar exemplo se existir
        if (contentData.example) {
            document.getElementById('request-example').value = JSON.stringify(contentData.example, null, 2);
        } else if (contentData.examples) {
            // Se houver múltiplos exemplos, pegar o primeiro
            const firstExampleKey = Object.keys(contentData.examples)[0];
            if (firstExampleKey && contentData.examples[firstExampleKey].value) {
                document.getElementById('request-example').value = JSON.stringify(contentData.examples[firstExampleKey].value, null, 2);
            }
        }
    }
}

// Função auxiliar para extrair exemplo da response
function getResponseExample(response, contentType) {
    if (response.content && response.content[contentType]) {
        const content = response.content[contentType];
        
        // Verificar se há exemplo direto
        if (content.example) {
            return JSON.stringify(content.example, null, 2);
        }
        
        // Verificar se há examples (múltiplos)
        if (content.examples) {
            const firstExampleKey = Object.keys(content.examples)[0];
            if (firstExampleKey && content.examples[firstExampleKey].value) {
                return JSON.stringify(content.examples[firstExampleKey].value, null, 2);
            }
        }
        
        // Verificar se há exemplo no schema
        if (content.schema && content.schema.example) {
            return JSON.stringify(content.schema.example, null, 2);
        }
    }
    
    return ''; // Retornar string vazia se não houver exemplo
}

// Função para carregar responses no modal
function loadResponses(responses) {
    const container = document.getElementById('responses-container');
    container.innerHTML = '';
    
    Object.keys(responses).forEach(statusCode => {
        const response = responses[statusCode];
        responseCounter++;
        const responseId = 'response-' + responseCounter;
        
        // Pegar o primeiro content-type se existir
        let contentType = 'application/json';
        let schema = null;
        
        if (response.content) {
            contentType = Object.keys(response.content)[0] || 'application/json';
            if (response.content[contentType] && response.content[contentType].schema) {
                schema = response.content[contentType].schema;
            }
        }
        
        const responseHtml = `
            <div class="response-item border rounded p-3 mb-3" id="${responseId}">
                <div class="row">
                    <div class="col-md-2">
                        <label class="form-label">Status Code</label>
                        <select class="form-select" name="response_code[]">
                            <option value="200" ${statusCode === '200' ? 'selected' : ''}>200 - OK</option>
                            <option value="201" ${statusCode === '201' ? 'selected' : ''}>201 - Created</option>
                            <option value="204" ${statusCode === '204' ? 'selected' : ''}>204 - No Content</option>
                            <option value="400" ${statusCode === '400' ? 'selected' : ''}>400 - Bad Request</option>
                            <option value="401" ${statusCode === '401' ? 'selected' : ''}>401 - Unauthorized</option>
                            <option value="403" ${statusCode === '403' ? 'selected' : ''}>403 - Forbidden</option>
                            <option value="404" ${statusCode === '404' ? 'selected' : ''}>404 - Not Found</option>
                            <option value="422" ${statusCode === '422' ? 'selected' : ''}>422 - Unprocessable Entity</option>
                            <option value="500" ${statusCode === '500' ? 'selected' : ''}>500 - Internal Server Error</option>
                            ${!['200','201','204','400','401','403','404','422','500'].includes(statusCode) ? 
                                `<option value="${statusCode}" selected>${statusCode} - Custom</option>` : ''}
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Content-Type</label>
                        <select class="form-select" name="response_content_type[]">
                            <option value="application/json" ${contentType === 'application/json' ? 'selected' : ''}>application/json</option>
                            <option value="application/xml" ${contentType === 'application/xml' ? 'selected' : ''}>application/xml</option>
                            <option value="text/plain" ${contentType === 'text/plain' ? 'selected' : ''}>text/plain</option>
                            <option value="text/html" ${contentType === 'text/html' ? 'selected' : ''}>text/html</option>
                            ${!['application/json','application/xml','text/plain','text/html'].includes(contentType) ? 
                                `<option value="${contentType}" selected>${contentType}</option>` : ''}
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Descrição</label>
                        <input type="text" class="form-control" name="response_description[]" value="${escapeHtml(response.description || '')}" placeholder="Descrição da resposta" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="button" class="btn btn-outline-danger btn-sm d-block" onclick="removeResponse('${responseId}')">
                            <i class="fas fa-trash"></i> Remover
                        </button>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <label class="form-label">Schema da Resposta (JSON)</label>
                        <textarea class="form-control" name="response_schema[]" rows="4" placeholder='{"type": "object", "properties": {...}}'>${schema ? JSON.stringify(schema, null, 2) : ''}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Exemplo da Resposta (JSON)</label>
                        <textarea class="form-control" name="response_example[]" rows="4" placeholder='{"id": 1, "name": "João Silva"}'>${getResponseExample(response, contentType)}</textarea>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', responseHtml);
    });
}

function removeEndpointGroup(button) {
    if (confirm('Tem certeza que deseja remover este endpoint?')) {
        button.closest('.endpoint-group').remove();
        checkEmptyState();
    }
}

function addParameter() {
    const container = document.getElementById('parameters-container');
    if (container.querySelector('.text-muted')) {
        container.innerHTML = '';
    }
    
    const parameterId = 'param-' + (++parameterCounter);
    const parameterHtml = `
        <div class="parameter-item border rounded p-3 mb-3" id="${parameterId}">
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Nome</label>
                    <input type="text" class="form-control" name="param_name[]" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Localização</label>
                    <select class="form-select" name="param_in[]">
                        <option value="query">Query</option>
                        <option value="path">Path</option>
                        <option value="header">Header</option>
                        <option value="cookie">Cookie</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tipo</label>
                    <select class="form-select" name="param_type[]">
                        <option value="string">String</option>
                        <option value="integer">Integer</option>
                        <option value="number">Number</option>
                        <option value="boolean">Boolean</option>
                        <option value="array">Array</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Descrição</label>
                    <input type="text" class="form-control" name="param_description[]" placeholder="Descrição do parâmetro">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="param_required[]" value="${parameterId}">
                            <label class="form-check-label small">Obrigatório</label>
                        </div>
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeParameter('${parameterId}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6">
                    <label class="form-label">Valor Exemplo</label>
                    <input type="text" class="form-control" name="param_example[]" placeholder="Exemplo de valor">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Valor Padrão</label>
                    <input type="text" class="form-control" name="param_default[]" placeholder="Valor padrão (opcional)">
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', parameterHtml);
}

function addResponse() {
    const container = document.getElementById('responses-container');
    if (container.querySelector('.text-muted')) {
        container.innerHTML = '';
    }
    
    const responseId = 'response-' + (++responseCounter);
    const responseHtml = `
        <div class="response-item border rounded p-3 mb-3" id="${responseId}">
            <div class="row">
                <div class="col-md-2">
                    <label class="form-label">Status Code</label>
                    <select class="form-select" name="response_code[]">
                        <option value="200">200 - OK</option>
                        <option value="201">201 - Created</option>
                        <option value="204">204 - No Content</option>
                        <option value="400">400 - Bad Request</option>
                        <option value="401">401 - Unauthorized</option>
                        <option value="403">403 - Forbidden</option>
                        <option value="404">404 - Not Found</option>
                        <option value="422">422 - Unprocessable Entity</option>
                        <option value="500">500 - Internal Server Error</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Content-Type</label>
                    <select class="form-select" name="response_content_type[]">
                        <option value="application/json">application/json</option>
                        <option value="application/xml">application/xml</option>
                        <option value="text/plain">text/plain</option>
                        <option value="text/html">text/html</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label">Descrição</label>
                    <input type="text" class="form-control" name="response_description[]" placeholder="Descrição da resposta" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-outline-danger btn-sm d-block" onclick="removeResponse('${responseId}')">
                        <i class="fas fa-trash"></i> Remover
                    </button>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <label class="form-label">Schema da Resposta (JSON)</label>
                    <textarea class="form-control" name="response_schema[]" rows="4" placeholder='{"type": "object", "properties": {...}}'></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Exemplo da Resposta (JSON)</label>
                    <textarea class="form-control" name="response_example[]" rows="4" placeholder='{"id": 1, "name": "João Silva"}'></textarea>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', responseHtml);
}

function addHeader() {
    const container = document.getElementById('headers-container');
    if (container.querySelector('.text-muted')) {
        container.innerHTML = '';
    }
    
    const headerId = 'header-' + (++headerCounter);
    const headerHtml = `
        <div class="header-item border rounded p-3 mb-3" id="${headerId}">
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Nome do Header</label>
                    <input type="text" class="form-control" name="header_name[]" placeholder="X-Custom-Header" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tipo</label>
                    <select class="form-select" name="header_type[]">
                        <option value="string">String</option>
                        <option value="integer">Integer</option>
                        <option value="number">Number</option>
                        <option value="boolean">Boolean</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label">Descrição</label>
                    <input type="text" class="form-control" name="header_description[]" placeholder="Descrição do header personalizado">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="header_required[]" value="${headerId}">
                            <label class="form-check-label small">Obrigatório</label>
                        </div>
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeHeader('${headerId}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', headerHtml);
}

function removeParameter(id) {
    document.getElementById(id).remove();
    if (document.querySelectorAll('.parameter-item').length === 0) {
        document.getElementById('parameters-container').innerHTML = '<div class="text-muted text-center py-3">Nenhum parâmetro adicionado</div>';
    }
}

function removeResponse(id) {
    document.getElementById(id).remove();
    if (document.querySelectorAll('.response-item').length === 0) {
        document.getElementById('responses-container').innerHTML = '<div class="text-muted text-center py-3">Nenhuma resposta definida</div>';
    }
}

function removeHeader(id) {
    document.getElementById(id).remove();
    if (document.querySelectorAll('.header-item').length === 0) {
        document.getElementById('headers-container').innerHTML = '<div class="text-muted text-center py-3">Nenhum header personalizado</div>';
    }
}

function clearAllContainers() {
    document.getElementById('parameters-container').innerHTML = '<div class="text-muted text-center py-3">Nenhum parâmetro adicionado</div>';
    document.getElementById('responses-container').innerHTML = '<div class="text-muted text-center py-3">Nenhuma resposta definida</div>';
    document.getElementById('headers-container').innerHTML = '<div class="text-muted text-center py-3">Nenhum header personalizado</div>';
    
    // Limpar campos de exemplo
    document.getElementById('request-example').value = '';
}

function serializeDataBeforeSubmit(form) {
    try {
        const endpoints = document.querySelectorAll('.endpoint-item');
        const pathsData = {};
        
        endpoints.forEach(endpoint => {
            const path = endpoint.getAttribute('data-path');
            const method = endpoint.getAttribute('data-method');
            
            if (path && method) {
                if (!pathsData[path]) {
                    pathsData[path] = {};
                }
                
                const summaryEl = endpoint.querySelector('.endpoint-summary');
                const descriptionEl = endpoint.querySelector('.endpoint-description');
                const tagsEl = endpoint.querySelector('.endpoint-tags');
                
                pathsData[path][method] = {
                    summary: summaryEl ? summaryEl.textContent : '',
                    description: descriptionEl ? descriptionEl.textContent : '',
                    tags: tagsEl ? tagsEl.textContent.split(',').map(t => t.trim()).filter(t => t) : []
                };
            }
        });
        
        // Adicionar campo hidden com dados serializados
        let hiddenInput = form.querySelector('input[name="paths"]');
        if (!hiddenInput) {
            hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'paths';
            form.appendChild(hiddenInput);
        }
        hiddenInput.value = JSON.stringify(pathsData);
        return true;
        
    } catch (error) {
        return false;
    }
}

function saveEndpoint() {
    const form = document.getElementById('endpoint-form');
    const method = document.getElementById('endpoint-method').value;
    const path = document.getElementById('endpoint-path').value;
    const summary = document.getElementById('endpoint-summary').value;
    const description = document.getElementById('endpoint-description').value;
    const tags = document.getElementById('endpoint-tags').value;
    
    if (!method || !path || !summary) {
        alert('Por favor, preencha os campos obrigatórios: Método, Caminho e Resumo.');
        return;
    }
    
    // Atualizar estrutura de dados OpenAPI
    updateOpenAPIData(method, path, summary, description, tags);
    
    // Criar elemento do endpoint
    const endpointHtml = createEndpointHtml(method, path, summary, description, tags);
    
    // Adicionar ao container
    const container = document.getElementById('endpoints-container');
    const emptyState = container.querySelector('#empty-state');
    if (emptyState) {
        emptyState.remove();
    }
    
    container.insertAdjacentHTML('beforeend', endpointHtml);
    
    // Atualizar tags disponíveis se novas tags foram adicionadas
    if (tags) {
        const tagsArray = tags.split(',').map(t => t.trim()).filter(t => t);
        tagsArray.forEach(tag => availableTags.add(tag));
        updateTagFilterMenu();
    }
    
    // Fechar modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('addEndpointModal'));
    modal.hide();
    
    // Mostrar mensagem de sucesso
    const action = currentEndpoint ? 'atualizado' : 'adicionado';
    showSuccessMessage(`Endpoint ${action} com sucesso!`);
}

// Função para atualizar a estrutura de dados OpenAPI
function updateOpenAPIData(method, path, summary, description, tags) {
    // Inicializar estrutura global se não existir
    if (!window.openApiSpec) {
        window.openApiSpec = {
            openapi: "3.0.3",
            info: { title: "API", version: "1.0.0" },
            paths: {}
        };
    }
    
    if (!window.openApiSpec.paths) {
        window.openApiSpec.paths = {};
    }
    
    // Adicionar ou atualizar o path
    if (!window.openApiSpec.paths[path]) {
        window.openApiSpec.paths[path] = {};
    }
    
    // Criar objeto da operação
    const operation = {
        summary: summary,
        operationId: path.replace(/[^a-zA-Z0-9]/g, '_') + '_' + method
    };
    
    if (description) {
        operation.description = description;
    }
    
    if (tags) {
        const tagsArray = tags.split(',').map(t => t.trim()).filter(t => t);
        if (tagsArray.length > 0) {
            operation.tags = tagsArray;
        }
    }
    
    // Adicionar responses padrão
    operation.responses = {
        "200": {
            "description": "Successful response"
        }
    };
    
    // Salvar a operação no método especificado
    window.openApiSpec.paths[path][method.toLowerCase()] = operation;
    
    // Atualizar mainEditor se existir
    if (window.mainEditor && window.mainEditor.currentSpec) {
        window.mainEditor.currentSpec.paths = window.openApiSpec.paths;
    }
}

function createEndpointHtml(method, path, summary, description, tags) {
    const methodColor = getMethodColorJS(method);
    const operationId = path + '_' + method;
    const tagsArray = tags ? tags.split(',').map(t => t.trim()).filter(t => t) : [];
    
    let tagsHtml = '';
    if (tagsArray.length > 0) {
        tagsHtml = '<div class="mt-2">' + 
            tagsArray.map(tag => `<span class="badge bg-secondary me-1">${escapeHtml(tag)}</span>`).join('') + 
            '</div>';
    }
    
    return `
        <div class="endpoint-group border rounded p-3 mb-4" data-path="${escapeHtml(path)}" data-method="${method}">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h6 class="mb-1">
                        <span class="badge bg-${methodColor} me-2">${method.toUpperCase()}</span>
                        <code class="bg-light px-2 py-1 rounded">${escapeHtml(path)}</code>
                    </h6>
                    <div class="text-muted small">${escapeHtml(summary)}</div>
                    ${description ? `<div class="text-muted small mt-1">${escapeHtml(description)}</div>` : ''}
                    ${tagsHtml}
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="editEndpoint(this)" title="Editar endpoint">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeEndpointGroup(this)" title="Remover endpoint">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
}

function getMethodColorJS(method) {
    const colors = {
        'get': 'primary',
        'post': 'success',
        'put': 'warning', 
        'patch': 'info',
        'delete': 'danger',
        'head': 'secondary',
        'options': 'dark'
    };
    return colors[method.toLowerCase()] || 'secondary';
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

function checkEmptyState() {
    const container = document.getElementById('endpoints-container');
    if (container.querySelectorAll('.endpoint-group').length === 0) {
        container.innerHTML = `
            <div class="text-center py-4" id="empty-state">
                <i class="fas fa-code display-4 text-muted"></i>
                <p class="text-muted mt-2">Nenhum endpoint definido ainda. Comece adicionando seu primeiro endpoint!</p>
            </div>
        `;
    }
}

function showSuccessMessage(message) {
    showToast('success', message);
}

function showFilterNotification(message) {
    showToast('info', message);
}

function showToast(type, message) {
    const colors = {
        'success': 'bg-success',
        'info': 'bg-info',
        'warning': 'bg-warning',
        'error': 'bg-danger'
    };
    
    const icons = {
        'success': 'fa-check',
        'info': 'fa-info-circle',
        'warning': 'fa-exclamation-triangle', 
        'error': 'fa-times'
    };
    
    const toastHtml = `
        <div class="toast align-items-center text-white ${colors[type]} border-0 position-fixed" 
             style="top: 20px; right: 20px; z-index: 9999;" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas ${icons[type]} me-2"></i>${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', toastHtml);
    const toast = new bootstrap.Toast(document.querySelector('.toast:last-child'));
    toast.show();
    
    // Remover após o tempo especificado
    const duration = type === 'info' ? 3000 : 5000;
    setTimeout(() => {
        const toastEl = document.querySelector('.toast:last-child');
        if (toastEl) toastEl.remove();
    }, duration);
}

// Inicializar quando a página carregar
document.addEventListener('DOMContentLoaded', function() {
    initializeTags();
    
    // Observar mudanças no container de endpoints para atualizar tags
    const observer = new MutationObserver(function(mutations) {
        let shouldUpdate = false;
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList') {
                shouldUpdate = true;
            }
        });
        if (shouldUpdate) {
            setTimeout(initializeTags, 100); // Pequeno delay para garantir que o DOM foi atualizado
        }
    });
    
    const container = document.getElementById('endpoints-container');
    if (container) {
        observer.observe(container, { childList: true, subtree: true });
    }
});
</script>