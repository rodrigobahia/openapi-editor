<?php
// Componente para edição do cabeçalho da API
$info = $openApiData['info'] ?? [];
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    <?php echo t('header'); ?> - Informações da API
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" onsubmit="return serializeDataBeforeSubmit(this);">
                    <input type="hidden" name="save_section" value="1">
                    <input type="hidden" name="section" value="header">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="api_title" class="form-label">Título da API</label>
                                <input type="text" class="form-control" id="api_title" name="api_title" 
                                       value="<?php echo htmlspecialchars($info['title'] ?? ''); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="api_version" class="form-label">Versão</label>
                                <input type="text" class="form-control" id="api_version" name="api_version" 
                                       value="<?php echo htmlspecialchars($info['version'] ?? '1.0.0'); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="openapi_version" class="form-label">Versão OpenAPI</label>
                                <select class="form-select" id="openapi_version" name="openapi_version">
                                    <option value="3.0.0" <?php echo ($openApiData['openapi'] ?? '') === '3.0.0' ? 'selected' : ''; ?>>3.0.0</option>
                                    <option value="3.0.1" <?php echo ($openApiData['openapi'] ?? '') === '3.0.1' ? 'selected' : ''; ?>>3.0.1</option>
                                    <option value="3.0.2" <?php echo ($openApiData['openapi'] ?? '') === '3.0.2' ? 'selected' : ''; ?>>3.0.2</option>
                                    <option value="3.0.3" <?php echo ($openApiData['openapi'] ?? '') === '3.0.3' ? 'selected' : ''; ?>>3.0.3</option>
                                    <option value="3.1.0" <?php echo ($openApiData['openapi'] ?? '') === '3.1.0' ? 'selected' : ''; ?>>3.1.0</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="api_description" class="form-label">Descrição</label>
                                <textarea class="form-control" id="api_description" name="api_description" rows="5"><?php echo htmlspecialchars($info['description'] ?? ''); ?></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="terms_of_service" class="form-label">Termos de Serviço (URL)</label>
                                <input type="url" class="form-control" id="terms_of_service" name="terms_of_service" 
                                       value="<?php echo htmlspecialchars($info['termsOfService'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <!-- Seção de Contato -->
                    <h6>Informações de Contato</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="contact_name" class="form-label">Nome do Contato</label>
                                <input type="text" class="form-control" id="contact_name" name="contact_name" 
                                       value="<?php echo htmlspecialchars($info['contact']['name'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="contact_email" class="form-label">Email do Contato</label>
                                <input type="email" class="form-control" id="contact_email" name="contact_email" 
                                       value="<?php echo htmlspecialchars($info['contact']['email'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="contact_url" class="form-label">URL do Contato</label>
                                <input type="url" class="form-control" id="contact_url" name="contact_url" 
                                       value="<?php echo htmlspecialchars($info['contact']['url'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <!-- Seção de Licença -->
                    <h6>Informações de Licença</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="license_name" class="form-label">Nome da Licença</label>
                                <input type="text" class="form-control" id="license_name" name="license_name" 
                                       value="<?php echo htmlspecialchars($info['license']['name'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="license_url" class="form-label">URL da Licença</label>
                                <input type="url" class="form-control" id="license_url" name="license_url" 
                                       value="<?php echo htmlspecialchars($info['license']['url'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>
                            Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>