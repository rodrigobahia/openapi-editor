<?php
// Componente para edição de segurança da API
$security = $openApiData['security'] ?? [];
$securitySchemes = $openApiData['components']['securitySchemes'] ?? [];
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header component-header-gradient d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-1">
                        <i class="fas fa-shield-alt me-2"></i>
                        <?php echo t('security'); ?> - <?php echo t('security_config'); ?>
                    </h5>
                    <p class="mb-0"><?php echo t('security_description'); ?></p>
                </div>
                <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addSecuritySchemeModal">
                    <i class="fas fa-plus me-2"></i>
                    <?php echo t('add_scheme'); ?>
                </button>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <?php echo t('security_info'); ?>
                </div>
                
                <form method="POST" id="security-form" onsubmit="return serializeSecurityBeforeSubmit(this);">
                    <input type="hidden" name="save_section" value="1">
                    <input type="hidden" name="section" value="security">
                    
                    <!-- Esquemas de Segurança Definidos -->
                    <div class="mb-4">
                        <h6 class="mb-3">
                            <i class="fas fa-key me-2"></i>
                            <?php echo t('auth_schemes'); ?>
                        </h6>
                        
                        <div id="security-schemes-container">
                            <?php if (empty($securitySchemes)): ?>
                                <div class="text-center py-4" id="empty-schemes-state">
                                    <i class="fas fa-shield-alt display-4 text-muted"></i>
                                    <p class="text-muted mt-2"><?php echo t('no_schemes_defined'); ?></p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($securitySchemes as $schemeName => $schemeData): ?>
                                    <?php echo renderSecurityScheme($schemeName, $schemeData); ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Configuração Global de Segurança -->
                    <?php if (!empty($securitySchemes)): ?>
                        <div class="border-top pt-4">
                            <h6 class="mb-3">
                                <i class="fas fa-globe me-2"></i>
                                Segurança Global da API
                            </h6>
                            <div class="alert alert-secondary">
                                <small><?php echo t('required_schemes_help'); ?></small>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label"><?php echo t('required_schemes'); ?></label>
                                    <div class="security-requirements">
                                        <?php foreach ($securitySchemes as $schemeName => $schemeData): ?>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="global_security[]" value="<?php echo htmlspecialchars($schemeName); ?>"
                                                       id="global_<?php echo htmlspecialchars($schemeName); ?>"
                                                       <?php echo isGlobalSecurityEnabled($security, $schemeName) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="global_<?php echo htmlspecialchars($schemeName); ?>">
                                                    <span class="badge bg-<?php echo getSchemeTypeColor($schemeData['type']); ?> me-2">
                                                        <?php echo strtoupper($schemeData['type']); ?>
                                                    </span>
                                                    <?php echo htmlspecialchars($schemeName); ?>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Configuração Atual</label>
                                    <div class="bg-light p-3 rounded">
                                        <pre class="mb-0"><code id="security-preview"><?php echo json_encode($security, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); ?></code></pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-component-save">
                            <i class="fas fa-check me-2"></i>
                            Salvar Configurações de Segurança
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
function renderSecurityScheme($schemeName, $schemeData) {
    $typeColor = getSchemeTypeColor($schemeData['type']);
    $typeLabel = strtoupper($schemeData['type']);
    
    ob_start();
    ?>
    <div class="security-scheme-item border rounded p-3 mb-3" data-scheme-name="<?php echo htmlspecialchars($schemeName); ?>">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <h6 class="mb-1">
                    <span class="badge bg-<?php echo $typeColor; ?> me-2"><?php echo $typeLabel; ?></span>
                    <?php echo htmlspecialchars($schemeName); ?>
                </h6>
                <?php if (!empty($schemeData['description'])): ?>
                    <p class="text-muted small mb-2"><?php echo htmlspecialchars($schemeData['description']); ?></p>
                <?php endif; ?>
                <div class="scheme-details">
                    <?php echo renderSchemeDetails($schemeData); ?>
                </div>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="editSecurityScheme('<?php echo htmlspecialchars($schemeName); ?>')" title="Editar esquema">
                    <i class="fas fa-edit"></i>
                </button>
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeSecurityScheme(this)" title="Remover esquema">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

function renderSchemeDetails($schemeData) {
    $details = [];
    
    switch ($schemeData['type']) {
        case 'apiKey':
            $details[] = '<strong>Localização:</strong> ' . htmlspecialchars($schemeData['in'] ?? 'header');
            $details[] = '<strong>Nome:</strong> ' . htmlspecialchars($schemeData['name'] ?? '');
            break;
            
        case 'http':
            $details[] = '<strong>Esquema:</strong> ' . htmlspecialchars($schemeData['scheme'] ?? '');
            if (!empty($schemeData['bearerFormat'])) {
                $details[] = '<strong>Formato:</strong> ' . htmlspecialchars($schemeData['bearerFormat']);
            }
            break;
            
        case 'oauth2':
            if (!empty($schemeData['flows'])) {
                $flows = array_keys($schemeData['flows']);
                $details[] = '<strong>Fluxos:</strong> ' . implode(', ', $flows);
            }
            break;
            
        case 'openIdConnect':
            if (!empty($schemeData['openIdConnectUrl'])) {
                $details[] = '<strong>URL:</strong> ' . htmlspecialchars($schemeData['openIdConnectUrl']);
            }
            break;
    }
    
    return '<div class="small text-muted">' . implode(' • ', $details) . '</div>';
}

function getSchemeTypeColor($type) {
    $colors = [
        'apiKey' => 'primary',
        'http' => 'success',
        'oauth2' => 'warning',
        'openIdConnect' => 'info'
    ];
    return $colors[$type] ?? 'secondary';
}

function isGlobalSecurityEnabled($security, $schemeName) {
    if (empty($security)) return false;
    
    foreach ($security as $requirement) {
        if (isset($requirement[$schemeName])) {
            return true;
        }
    }
    return false;
}
?>

<!-- Modal para Adicionar/Editar Esquema de Segurança -->
<div class="modal fade" id="addSecuritySchemeModal" tabindex="-1" aria-labelledby="addSecuritySchemeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-xl-down modal-xl">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header modal-header-gradient text-white position-relative overflow-hidden">
                <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10">
                    <div class="bg-primary w-100 h-100 gradient-bg-purple"></div>
                </div>
                <div class="d-flex align-items-center position-relative z-index-2 w-100">
                    <div class="flex-grow-1">
                        <h5 class="modal-title mb-1 fw-bold" id="addSecuritySchemeModalLabel">
                            <i class="fas fa-plus me-2"></i>
                            Configurar Esquema de Segurança
                        </h5>
                        <small class="opacity-75">Defina os métodos de autenticação da sua API</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body">
                <form id="security-scheme-form">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="scheme-name" class="form-label">Nome do Esquema</label>
                            <input type="text" class="form-control" id="scheme-name" required 
                                   placeholder="ex: bearerAuth, apiKey">
                            <div class="form-text">Nome único para identificar este esquema</div>
                        </div>
                        <div class="col-md-6">
                            <label for="scheme-type" class="form-label">Tipo de Autenticação</label>
                            <select class="form-select" id="scheme-type" required onchange="updateSchemeFields()">
                                <option value="">Selecione o tipo</option>
                                <option value="apiKey">API Key</option>
                                <option value="http">HTTP Authentication</option>
                                <option value="oauth2">OAuth 2.0</option>
                                <option value="openIdConnect">OpenID Connect</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <label for="scheme-description" class="form-label">Descrição</label>
                        <textarea class="form-control" id="scheme-description" rows="2" 
                                  placeholder="Descrição de como usar este esquema de autenticação..."></textarea>
                    </div>
                    
                    <!-- Campos específicos por tipo -->
                    <div id="scheme-specific-fields" class="mt-3">
                        <!-- Campos dinâmicos baseados no tipo selecionado -->
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 bg-light px-4 py-3 d-flex justify-content-end gap-3">
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>
                    Cancelar
                </button>
                <button type="button" class="btn btn-save" onclick="saveSecurityScheme()">
                    <i class="fas fa-check me-2"></i>
                    Salvar Esquema
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function serializeSecurityBeforeSubmit(form) {
    // Serializa todos os esquemas de segurança presentes no DOM
    const schemeItems = document.querySelectorAll('.security-scheme-item');
    const schemes = {};
    schemeItems.forEach(item => {
        const name = item.getAttribute('data-scheme-name');
        if (!name) return;
        // Buscar detalhes do esquema
        const details = {};
        // Tipo
        const badge = item.querySelector('.badge');
        if (badge) {
            details.type = badge.textContent.trim().toLowerCase();
        }
        // Descrição
        const descEl = item.querySelector('p.text-muted');
        if (descEl) {
            details.description = descEl.textContent.trim();
        }
        // Detalhes específicos
        const detailsEl = item.querySelector('.scheme-details');
        if (detailsEl) {
            // Parse simples: busca por labels e valores
            const text = detailsEl.textContent;
            if (details.type === 'apikey') {
                const nomeMatch = text.match(/Nome:\s*(\S+)/);
                const inMatch = text.match(/Localização:\s*(\S+)/);
                if (nomeMatch) details.name = nomeMatch[1];
                if (inMatch) details.in = inMatch[1].toLowerCase();
            } else if (details.type === 'http') {
                const esquemaMatch = text.match(/Esquema:\s*(\S+)/);
                if (esquemaMatch) details.scheme = esquemaMatch[1].toLowerCase();
                const formatoMatch = text.match(/Formato:\s*(\S+)/);
                if (formatoMatch) details.bearerFormat = formatoMatch[1];
            } else if (details.type === 'oauth2') {
                // Fluxos não são extraídos do preview, mas podem ser implementados se necessário
            } else if (details.type === 'openidconnect') {
                const urlMatch = text.match(/URL:\s*(\S+)/);
                if (urlMatch) details.openIdConnectUrl = urlMatch[1];
            }
        }
        schemes[name] = details;
    });
    // Adiciona campo hidden
    let hiddenInput = form.querySelector('input[name="security_schemes"]');
    if (!hiddenInput) {
        hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'security_schemes';
        form.appendChild(hiddenInput);
    }
    hiddenInput.value = JSON.stringify(schemes);
    return true;
}
let currentScheme = null;

function updateSchemeFields() {
    const type = document.getElementById('scheme-type').value;
    const container = document.getElementById('scheme-specific-fields');
    
    let fieldsHtml = '';
    
    switch (type) {
        case 'apiKey':
            fieldsHtml = `
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Nome da API Key</label>
                        <input type="text" class="form-control" id="api-key-name" required
                               placeholder="X-API-Key, Authorization">
                        <div class="form-text">Nome do header, query ou cookie</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Localização</label>
                        <select class="form-select" id="api-key-in" required>
                            <option value="header">Header</option>
                            <option value="query">Query Parameter</option>
                            <option value="cookie">Cookie</option>
                        </select>
                    </div>
                </div>
            `;
            break;
            
        case 'http':
            fieldsHtml = `
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Esquema HTTP</label>
                        <select class="form-select" id="http-scheme" required onchange="updateHttpFields()">
                            <option value="basic">Basic Auth</option>
                            <option value="bearer">Bearer Token</option>
                            <option value="digest">Digest Auth</option>
                        </select>
                    </div>
                    <div class="col-md-6 bearer-format-container" id="bearer-format-container">
                        <label class="form-label">Formato do Bearer</label>
                        <input type="text" class="form-control" id="bearer-format"
                               placeholder="JWT, opaque">
                        <div class="form-text">Formato do token (opcional)</div>
                    </div>
                </div>
            `;
            break;
            
        case 'oauth2':
            fieldsHtml = `
                <div class="mb-3">
                    <label class="form-label">Fluxos OAuth2</label>
                    <div class="oauth2-flows">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="flow-implicit">
                            <label class="form-check-label" for="flow-implicit">
                                Implicit Flow
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="flow-password">
                            <label class="form-check-label" for="flow-password">
                                Password Flow
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="flow-clientCredentials">
                            <label class="form-check-label" for="flow-clientCredentials">
                                Client Credentials Flow
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="flow-authorizationCode">
                            <label class="form-check-label" for="flow-authorizationCode">
                                Authorization Code Flow
                            </label>
                        </div>
                    </div>
                </div>
                <div id="oauth2-urls" class="row">
                    <div class="col-md-6">
                        <label class="form-label">Authorization URL</label>
                        <input type="url" class="form-control" id="authorization-url"
                               placeholder="https://example.com/oauth/authorize">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Token URL</label>
                        <input type="url" class="form-control" id="token-url"
                               placeholder="https://example.com/oauth/token">
                    </div>
                </div>
                <div class="mt-3">
                    <label class="form-label">Scopes (um por linha)</label>
                    <textarea class="form-control" id="oauth2-scopes" rows="4"
                              placeholder="read:user&#10;write:user&#10;admin:system"></textarea>
                    <div class="form-text">Defina os escopos disponíveis, um por linha</div>
                </div>
            `;
            break;
            
        case 'openIdConnect':
            fieldsHtml = `
                <div class="mb-3">
                    <label class="form-label">OpenID Connect URL</label>
                    <input type="url" class="form-control" id="openid-connect-url" required
                           placeholder="https://example.com/.well-known/openid_configuration">
                    <div class="form-text">URL do discovery document</div>
                </div>
            `;
            break;
    }
    
    container.innerHTML = fieldsHtml;
}

function updateHttpFields() {
    const scheme = document.getElementById('http-scheme').value;
    const bearerContainer = document.getElementById('bearer-format-container');
    
    if (scheme === 'bearer') {
        bearerContainer.classList.add('show');
    } else {
        bearerContainer.classList.remove('show');
    }
}

function saveSecurityScheme() {
    const name = document.getElementById('scheme-name').value.trim();
    const type = document.getElementById('scheme-type').value;
    const description = document.getElementById('scheme-description').value.trim();
    
    if (!name || !type) {
        alert('Por favor, preencha o nome e tipo do esquema.');
        return;
    }
    
    // Validar nome único
    if (!currentScheme && document.querySelector(`[data-scheme-name="${name}"]`)) {
        alert('Já existe um esquema com este nome. Escolha outro nome.');
        return;
    }
    
    const schemeData = {
        type: type,
        description: description
    };
    
    // Adicionar campos específicos do tipo
    switch (type) {
        case 'apiKey':
            const apiKeyName = document.getElementById('api-key-name').value.trim();
            const apiKeyIn = document.getElementById('api-key-in').value;
            
            if (!apiKeyName) {
                alert('Por favor, informe o nome da API Key.');
                return;
            }
            
            schemeData.name = apiKeyName;
            schemeData.in = apiKeyIn;
            break;
            
        case 'http':
            const httpScheme = document.getElementById('http-scheme').value;
            schemeData.scheme = httpScheme;
            
            if (httpScheme === 'bearer') {
                const bearerFormat = document.getElementById('bearer-format').value.trim();
                if (bearerFormat) {
                    schemeData.bearerFormat = bearerFormat;
                }
            }
            break;
            
        case 'oauth2':
            const flows = {};
            
            if (document.getElementById('flow-implicit').checked) {
                flows.implicit = {
                    authorizationUrl: document.getElementById('authorization-url').value,
                    scopes: parseScopesFromTextarea()
                };
            }
            
            if (document.getElementById('flow-password').checked) {
                flows.password = {
                    tokenUrl: document.getElementById('token-url').value,
                    scopes: parseScopesFromTextarea()
                };
            }
            
            if (document.getElementById('flow-clientCredentials').checked) {
                flows.clientCredentials = {
                    tokenUrl: document.getElementById('token-url').value,
                    scopes: parseScopesFromTextarea()
                };
            }
            
            if (document.getElementById('flow-authorizationCode').checked) {
                flows.authorizationCode = {
                    authorizationUrl: document.getElementById('authorization-url').value,
                    tokenUrl: document.getElementById('token-url').value,
                    scopes: parseScopesFromTextarea()
                };
            }
            
            if (Object.keys(flows).length === 0) {
                alert('Selecione pelo menos um fluxo OAuth2.');
                return;
            }
            
            schemeData.flows = flows;
            break;
            
        case 'openIdConnect':
            const openIdUrl = document.getElementById('openid-connect-url').value.trim();
            if (!openIdUrl) {
                alert('Por favor, informe a URL do OpenID Connect.');
                return;
            }
            
            schemeData.openIdConnectUrl = openIdUrl;
            break;
    }
    
    // Criar HTML do esquema
    const schemeHtml = createSecuritySchemeHtml(name, schemeData);
    
    // Adicionar ao container
    const container = document.getElementById('security-schemes-container');
    const emptyState = container.querySelector('#empty-schemes-state');
    if (emptyState) {
        emptyState.remove();
    }
    
    if (currentScheme) {
        // Editar existente
        const existingItem = document.querySelector(`[data-scheme-name="${currentScheme}"]`);
        existingItem.outerHTML = schemeHtml;
    } else {
        // Adicionar novo
        container.insertAdjacentHTML('beforeend', schemeHtml);
    }
    
    // Fechar modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('addSecuritySchemeModal'));
    modal.hide();
    
    // Reset form
    document.getElementById('security-scheme-form').reset();
    document.getElementById('scheme-specific-fields').innerHTML = '';
    currentScheme = null;
    
    // Mostrar mensagem de sucesso
    showSuccessMessage('Esquema de segurança salvo com sucesso!');
    
    // Atualizar preview da configuração
    updateSecurityPreview();
}

function createSecuritySchemeHtml(name, schemeData) {
    const typeColor = getSchemeTypeColorJS(schemeData.type);
    const typeLabel = schemeData.type.toUpperCase();
    const details = createSchemeDetailsHtml(schemeData);
    
    return `
        <div class="security-scheme-item border rounded p-3 mb-3" data-scheme-name="${escapeHtml(name)}">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h6 class="mb-1">
                        <span class="badge bg-${typeColor} me-2">${typeLabel}</span>
                        ${escapeHtml(name)}
                    </h6>
                    ${schemeData.description ? `<p class="text-muted small mb-2">${escapeHtml(schemeData.description)}</p>` : ''}
                    <div class="scheme-details">
                        ${details}
                    </div>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="editSecurityScheme('${escapeHtml(name)}')" title="Editar esquema">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeSecurityScheme(this)" title="Remover esquema">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
}

function createSchemeDetailsHtml(schemeData) {
    const details = [];
    
    switch (schemeData.type) {
        case 'apiKey':
            details.push(`<strong>Localização:</strong> ${schemeData.in || 'header'}`);
            details.push(`<strong>Nome:</strong> ${schemeData.name || ''}`);
            break;
            
        case 'http':
            details.push(`<strong>Esquema:</strong> ${schemeData.scheme || ''}`);
            if (schemeData.bearerFormat) {
                details.push(`<strong>Formato:</strong> ${schemeData.bearerFormat}`);
            }
            break;
            
        case 'oauth2':
            if (schemeData.flows) {
                const flows = Object.keys(schemeData.flows);
                details.push(`<strong>Fluxos:</strong> ${flows.join(', ')}`);
            }
            break;
            
        case 'openIdConnect':
            if (schemeData.openIdConnectUrl) {
                details.push(`<strong>URL:</strong> ${schemeData.openIdConnectUrl}`);
            }
            break;
    }
    
    return `<div class="small text-muted">${details.join(' • ')}</div>`;
}

function getSchemeTypeColorJS(type) {
    const colors = {
        'apiKey': 'primary',
        'http': 'success',
        'oauth2': 'warning',
        'openIdConnect': 'info'
    };
    return colors[type] || 'secondary';
}

function parseScopesFromTextarea() {
    const textarea = document.getElementById('oauth2-scopes');
    if (!textarea) return {};
    
    const scopes = {};
    const lines = textarea.value.split('\n');
    
    lines.forEach(line => {
        const trimmed = line.trim();
        if (trimmed) {
            // Formato: "scope:description" ou apenas "scope"
            const parts = trimmed.split(':');
            const scopeName = parts[0].trim();
            const scopeDesc = parts.length > 1 ? parts.slice(1).join(':').trim() : scopeName;
            scopes[scopeName] = scopeDesc;
        }
    });
    
    return scopes;
}

function editSecurityScheme(schemeName) {
    currentScheme = schemeName;
    
    // Preencher modal com dados existentes (implementação simplificada)
    document.getElementById('addSecuritySchemeModalLabel').innerHTML = '<i class="fas fa-edit me-2"></i>Editar Esquema de Segurança';
    document.getElementById('scheme-name').value = schemeName;
    
    // Abrir modal
    const modal = new bootstrap.Modal(document.getElementById('addSecuritySchemeModal'));
    modal.show();
}

function removeSecurityScheme(button) {
    if (confirm('Tem certeza que deseja remover este esquema de segurança?')) {
        button.closest('.security-scheme-item').remove();
        checkEmptySecurityState();
        updateSecurityPreview();
    }
}

function checkEmptySecurityState() {
    const container = document.getElementById('security-schemes-container');
    if (container.querySelectorAll('.security-scheme-item').length === 0) {
        container.innerHTML = `
            <div class="text-center py-4" id="empty-schemes-state">
                <i class="fas fa-shield-alt display-4 text-muted"></i>
                <p class="text-muted mt-2">Nenhum esquema de segurança definido. Comece adicionando um esquema de autenticação!</p>
            </div>
        `;
    }
}

function updateSecurityPreview() {
    const preview = document.getElementById('security-preview');
    if (!preview) return;
    
    // Construir preview da configuração de segurança
    const security = [];
    const checkboxes = document.querySelectorAll('input[name="global_security[]"]:checked');
    
    if (checkboxes.length > 0) {
        const requirement = {};
        checkboxes.forEach(cb => {
            requirement[cb.value] = [];
        });
        security.push(requirement);
    }
    
    preview.textContent = JSON.stringify(security, null, 2);
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

function showSuccessMessage(message) {
    const toastHtml = `
        <div class="toast align-items-center text-white bg-success border-0 toast-notification" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-check me-2"></i>${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', toastHtml);
    const toast = new bootstrap.Toast(document.querySelector('.toast:last-child'));
    toast.show();
    
    setTimeout(() => {
        const toastEl = document.querySelector('.toast:last-child');
        if (toastEl) toastEl.remove();
    }, 5000);
}

// Event listeners
document.addEventListener('change', function(e) {
    if (e.target.name === 'global_security[]') {
        updateSecurityPreview();
    }
});

// Reset modal when closed
document.getElementById('addSecuritySchemeModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('security-scheme-form').reset();
    document.getElementById('scheme-specific-fields').innerHTML = '';
    document.getElementById('addSecuritySchemeModalLabel').innerHTML = '<i class="fas fa-plus me-2"></i>Adicionar Esquema de Segurança';
    currentScheme = null;
});
</script>