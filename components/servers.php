<?php
// Componente para edição dos servidores da API
$servers = $openApiData['servers'] ?? [];
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header component-header-gradient d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-1">
                        <i class="fas fa-server me-2"></i>
                        <?php echo t('servers'); ?> - Configuração dos Servidores
                    </h5>
                    <p class="mb-0"><?php echo t('servers_description'); ?></p>
                </div>
                <button type="button" class="btn btn-light btn-sm" onclick="addServer()">
                    <i class="fas fa-plus me-2"></i>
                    Adicionar Servidor
                </button>
            </div>
            <div class="card-body">
                <form method="POST" id="servers-form">
                    <input type="hidden" name="save_section" value="1">
                    <input type="hidden" name="section" value="servers">
                    
                    <div id="servers-container">
                        <?php if (empty($servers)): ?>
                            <div class="server-item border rounded p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">Servidor #1</h6>
                                    <button type="button" class="btn btn-outline-danger btn-sm removable-btn hidden" onclick="removeServer(this)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">URL do Servidor</label>
                                            <input type="url" class="form-control" name="servers[0][url]" 
                                                   value="https://api.example.com/v1" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Descrição</label>
                                            <input type="text" class="form-control" name="servers[0][description]" 
                                                   value="Production server">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <?php foreach ($servers as $index => $server): ?>
                                <div class="server-item border rounded p-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0">Servidor #<?php echo $index + 1; ?></h6>
                                        <button type="button" class="btn btn-outline-danger btn-sm removable-btn <?php echo $index === 0 ? 'hidden' : ''; ?>" onclick="removeServer(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">URL do Servidor</label>
                                                <input type="url" class="form-control" name="servers[<?php echo $index; ?>][url]" 
                                                       value="<?php echo htmlspecialchars($server['url'] ?? ''); ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Descrição</label>
                                                <input type="text" class="form-control" name="servers[<?php echo $index; ?>][description]" 
                                                       value="<?php echo htmlspecialchars($server['description'] ?? ''); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Variáveis do servidor (se houver) -->
                                    <?php if (isset($server['variables']) && !empty($server['variables'])): ?>
                                        <div class="mt-3">
                                            <label class="form-label">Variáveis do Servidor</label>
                                            <div class="variables-container">
                                                <?php foreach ($server['variables'] as $varName => $varData): ?>
                                                    <div class="row mb-2">
                                                        <div class="col-md-3">
                                                            <input type="text" class="form-control" placeholder="Nome da variável" 
                                                                   name="servers[<?php echo $index; ?>][variables][<?php echo htmlspecialchars($varName); ?>][name]" 
                                                                   value="<?php echo htmlspecialchars($varName); ?>">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="text" class="form-control" placeholder="Valor padrão" 
                                                                   name="servers[<?php echo $index; ?>][variables][<?php echo htmlspecialchars($varName); ?>][default]" 
                                                                   value="<?php echo htmlspecialchars($varData['default'] ?? ''); ?>">
                                                        </div>
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control" placeholder="Descrição da variável" 
                                                                   name="servers[<?php echo $index; ?>][variables][<?php echo htmlspecialchars($varName); ?>][description]" 
                                                                   value="<?php echo htmlspecialchars($varData['description'] ?? ''); ?>">
                                                        </div>
                                                        <div class="col-md-1">
                                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeVariable(this)">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                            <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="addVariable(this)">
                                                <i class="fas fa-plus"></i>
                                                Adicionar Variável
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-component-save">
                            <i class="fas fa-check me-2"></i>
                            Salvar Servidores
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let serverIndex = <?php echo count($servers); ?>;

function addServer() {
    const container = document.getElementById('servers-container');
    const newServer = document.createElement('div');
    newServer.className = 'server-item border rounded p-3 mb-3';
    newServer.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0">Servidor #${serverIndex + 1}</h6>
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeServer(this)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">URL do Servidor</label>
                    <input type="url" class="form-control" name="servers[${serverIndex}][url]" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Descrição</label>
                    <input type="text" class="form-control" name="servers[${serverIndex}][description]">
                </div>
            </div>
        </div>
    `;
    container.appendChild(newServer);
    serverIndex++;
    updateRemoveButtons();
}

function removeServer(button) {
    button.closest('.server-item').remove();
    updateRemoveButtons();
    updateServerNumbers();
}

function updateRemoveButtons() {
    const servers = document.querySelectorAll('.server-item');
    servers.forEach((server, index) => {
        const removeBtn = server.querySelector('.btn-outline-danger');
        if (index === 0 && servers.length === 1) {
            removeBtn.classList.add('hidden');
        } else {
            removeBtn.classList.remove('hidden');
        }
    });
}

function updateServerNumbers() {
    const servers = document.querySelectorAll('.server-item');
    servers.forEach((server, index) => {
        const header = server.querySelector('h6');
        header.textContent = `Servidor #${index + 1}`;
    });
}

function addVariable(button) {
    const serverItem = button.closest('.server-item');
    let variablesContainer = serverItem.querySelector('.variables-container');
    
    if (!variablesContainer) {
        const variablesSection = document.createElement('div');
        variablesSection.className = 'mt-3';
        variablesSection.innerHTML = `
            <label class="form-label">Variáveis do Servidor</label>
            <div class="variables-container"></div>
        `;
        serverItem.querySelector('.row').after(variablesSection);
        variablesContainer = variablesSection.querySelector('.variables-container');
        button.remove();
        variablesSection.appendChild(button);
    }
    
    const varRow = document.createElement('div');
    varRow.className = 'row mb-2';
    varRow.innerHTML = `
        <div class="col-md-3">
            <input type="text" class="form-control" placeholder="Nome da variável">
        </div>
        <div class="col-md-3">
            <input type="text" class="form-control" placeholder="Valor padrão">
        </div>
        <div class="col-md-5">
            <input type="text" class="form-control" placeholder="Descrição da variável">
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeVariable(this)">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    variablesContainer.appendChild(varRow);
}

function removeVariable(button) {
    button.closest('.row').remove();
}

// Serializa os dados do formulário antes do submit
const serversForm = document.getElementById('servers-form');
if (serversForm) {
    serversForm.addEventListener('submit', function(e) {
        // Monta array de servidores
        const serverItems = document.querySelectorAll('.server-item');
        const serversArr = [];
        serverItems.forEach((item, idx) => {
            const url = item.querySelector('input[name^="servers"][name$="[url]"]')?.value || '';
            const description = item.querySelector('input[name^="servers"][name$="[description]"]')?.value || '';
            // Variáveis
            const variables = {};
            const varRows = item.querySelectorAll('.variables-container .row');
            varRows.forEach(row => {
                const name = row.querySelector('input[placeholder="Nome da variável"]')?.value;
                const def = row.querySelector('input[placeholder="Valor padrão"]')?.value;
                const desc = row.querySelector('input[placeholder="Descrição da variável"]')?.value;
                if (name) {
                    variables[name] = { default: def, description: desc };
                }
            });
            const serverObj = { url, description };
            if (Object.keys(variables).length > 0) serverObj.variables = variables;
            serversArr.push(serverObj);
        });
        // Cria campo oculto
        let hidden = serversForm.querySelector('input[name="servers_data"]');
        if (!hidden) {
            hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'servers_data';
            serversForm.appendChild(hidden);
        }
        hidden.value = JSON.stringify(serversArr);
    });
}
</script>