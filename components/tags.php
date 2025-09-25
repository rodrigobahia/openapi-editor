<?php
// Componente para edição de tags da API
$tags = $openApiData['tags'] ?? [];
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-tags me-2"></i>
                    <?php echo t('tags'); ?> - Organização das Tags
                </h5>
                <button type="button" class="btn btn-success btn-sm" onclick="addTag()">
                    <i class="fas fa-plus"></i>
                    Adicionar Tag
                </button>
            </div>
            <div class="card-body">
                <form method="POST" id="tags-form" onsubmit="return serializeDataBeforeSubmit(this);">
                    <input type="hidden" name="save_section" value="1">
                    <input type="hidden" name="section" value="tags">
                    
                    <div id="tags-container">
                        <?php if (empty($tags)): ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                As tags ajudam a organizar e agrupar os endpoints da sua API. Adicione tags para categorizar suas operações.
                            </div>
                        <?php else: ?>
                            <?php foreach ($tags as $index => $tag): ?>
                                <div class="tag-item border rounded p-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0">Tag #<?php echo $index + 1; ?></h6>
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeTag(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Nome da Tag</label>
                                                <input type="text" class="form-control" name="tags[<?php echo $index; ?>][name]" 
                                                       value="<?php echo htmlspecialchars($tag['name'] ?? ''); ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="mb-3">
                                                <label class="form-label">Descrição</label>
                                                <input type="text" class="form-control" name="tags[<?php echo $index; ?>][description]" 
                                                       value="<?php echo htmlspecialchars($tag['description'] ?? ''); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <?php if (isset($tag['externalDocs'])): ?>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">URL da Documentação Externa</label>
                                                    <input type="url" class="form-control" name="tags[<?php echo $index; ?>][externalDocs][url]" 
                                                           value="<?php echo htmlspecialchars($tag['externalDocs']['url'] ?? ''); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Descrição da Documentação</label>
                                                    <input type="text" class="form-control" name="tags[<?php echo $index; ?>][externalDocs][description]" 
                                                           value="<?php echo htmlspecialchars($tag['externalDocs']['description'] ?? ''); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>
                            Salvar Tags
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let tagIndex = <?php echo count($tags); ?>;

function addTag() {
    const container = document.getElementById('tags-container');
    
    // Remover alert de info se existir
    const infoAlert = container.querySelector('.alert-info');
    if (infoAlert) {
        infoAlert.remove();
    }
    
    const newTag = document.createElement('div');
    newTag.className = 'tag-item border rounded p-3 mb-3';
    newTag.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0">Tag #${tagIndex + 1}</h6>
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeTag(this)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Nome da Tag</label>
                    <input type="text" class="form-control" name="tags[${tagIndex}][name]" required>
                </div>
            </div>
            <div class="col-md-8">
                <div class="mb-3">
                    <label class="form-label">Descrição</label>
                    <input type="text" class="form-control" name="tags[${tagIndex}][description]">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">URL da Documentação Externa (opcional)</label>
                    <input type="url" class="form-control" name="tags[${tagIndex}][externalDocs][url]">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Descrição da Documentação (opcional)</label>
                    <input type="text" class="form-control" name="tags[${tagIndex}][externalDocs][description]">
                </div>
            </div>
        </div>
    `;
    container.appendChild(newTag);
    tagIndex++;
}

function removeTag(button) {
    button.closest('.tag-item').remove();
    updateTagNumbers();
    
    // Se não houver mais tags, mostrar o alert informativo
    const container = document.getElementById('tags-container');
    if (container.querySelectorAll('.tag-item').length === 0) {
        const infoAlert = document.createElement('div');
        infoAlert.className = 'alert alert-info';
        infoAlert.innerHTML = '<i class="fas fa-info-circle me-2"></i>As tags ajudam a organizar e agrupar os endpoints da sua API. Adicione tags para categorizar suas operações.';
        container.appendChild(infoAlert);
    }
}

function updateTagNumbers() {
    const tags = document.querySelectorAll('.tag-item');
    tags.forEach((tag, index) => {
        const header = tag.querySelector('h6');
        header.textContent = `Tag #${index + 1}`;
    });
}
</script>