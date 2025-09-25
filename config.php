<?php
/**
 * OpenAPI Editor - Configurações
 * 
 * Arquivo de configuração principal do editor OpenAPI.
 * Configure aqui as variáveis de acordo com o ambiente (desenvolvimento/produção).
 */

// =============================================================================
// CONFIGURAÇÕES GERAIS
// =============================================================================

// Nome da aplicação
define('APP_NAME', 'OpenAPI Editor');

// Versão da aplicação
define('APP_VERSION', '1.0.0');

// Ambiente (development, production, staging)
define('APP_ENV', 'development');

// Debug mode (true para desenvolvimento, false para produção)
define('APP_DEBUG', true);

// =============================================================================
// CONFIGURAÇÕES DE INTERFACE
// =============================================================================

// Mostrar card de listagem de arquivos salvos (recomendado: false em produção)
define('SHOW_FILE_LIST', true);

// Mostrar card de upload de arquivos
define('SHOW_FILE_UPLOAD', true);

// Mostrar card de novo arquivo
define('SHOW_NEW_FILE', true);

// Mostrar card de visualização
define('SHOW_PREVIEW', true);

// Tema padrão (light, dark, auto)
define('DEFAULT_THEME', 'auto');

// Idioma padrão (pt, en, es)
define('DEFAULT_LANGUAGE', 'pt');

// =============================================================================
// CONFIGURAÇÕES DE SEGURANÇA
// =============================================================================

// Tipos de arquivo permitidos para upload
define('ALLOWED_FILE_TYPES', ['json', 'yaml', 'yml']);

// Tamanho máximo de upload em MB
define('MAX_UPLOAD_SIZE', 5);

// Permitir criação de novos arquivos
define('ALLOW_FILE_CREATION', true);

// Permitir exclusão de arquivos
define('ALLOW_FILE_DELETION', false);

// Permitir edição de arquivos existentes
define('ALLOW_FILE_EDITING', true);

// =============================================================================
// CONFIGURAÇÕES DE DIRETÓRIOS
// =============================================================================

// Diretório onde os arquivos são salvos
define('FILES_DIRECTORY', __DIR__ . '/files');

// Diretório de assets compilados
define('ASSETS_DIRECTORY', __DIR__ . '/assets/dist');

// Diretório de componentes
define('COMPONENTS_DIRECTORY', __DIR__ . '/components');

// =============================================================================
// CONFIGURAÇÕES DE API
// =============================================================================

// URL base da API (EXEMPLO - ALTERE CONFORME SUA EMPRESA)
define('API_BASE_URL', 'https://api.exemplo.com');

// URL de documentação (EXEMPLO - ALTERE CONFORME SUA EMPRESA)  
define('DOCS_URL', 'https://docs.exemplo.com');

// URL de suporte (EXEMPLO - ALTERE CONFORME SUA EMPRESA)
define('SUPPORT_URL', 'mailto:suporte@exemplo.com');

// =============================================================================
// CONFIGURAÇÕES DE BRANDING
// =============================================================================

// Nome da empresa/organização (EXEMPLO - ALTERE PARA SUA EMPRESA)
define('COMPANY_NAME', 'Sua Empresa Ltda');

// URL da empresa (EXEMPLO - ALTERE PARA SUA EMPRESA)
define('COMPANY_URL', 'https://seudominio.com');

// Logo da empresa (caminho relativo ou URL)
define('COMPANY_LOGO', 'assets/img/logo.png');

// Cor primária (hex)
define('PRIMARY_COLOR', '#007bff');

// =============================================================================
// CONFIGURAÇÕES AVANÇADAS
// =============================================================================

// Habilitar cache de arquivos
define('ENABLE_CACHE', false);

// Tempo de cache em segundos (3600 = 1 hora)
define('CACHE_DURATION', 3600);

// Habilitar logs
define('ENABLE_LOGGING', APP_DEBUG);

// Nível de log (error, warning, info, debug)
define('LOG_LEVEL', APP_DEBUG ? 'debug' : 'error');

// Diretório de logs
define('LOG_DIRECTORY', __DIR__ . '/logs');

// =============================================================================
// CONFIGURAÇÕES ESPECÍFICAS POR AMBIENTE
// =============================================================================

switch (APP_ENV) {
    case 'production':
        // Configurações de produção (mais restritivas)
        if (!defined('SHOW_FILE_LIST')) define('SHOW_FILE_LIST', false);
        if (!defined('ALLOW_FILE_DELETION')) define('ALLOW_FILE_DELETION', false);
        if (!defined('APP_DEBUG')) define('APP_DEBUG', false);
        if (!defined('ENABLE_LOGGING')) define('ENABLE_LOGGING', true);
        if (!defined('LOG_LEVEL')) define('LOG_LEVEL', 'error');
        break;
        
    case 'staging':
        // Configurações de homologação
        if (!defined('SHOW_FILE_LIST')) define('SHOW_FILE_LIST', false);
        if (!defined('ALLOW_FILE_DELETION')) define('ALLOW_FILE_DELETION', false);
        if (!defined('APP_DEBUG')) define('APP_DEBUG', false);
        if (!defined('ENABLE_LOGGING')) define('ENABLE_LOGGING', true);
        if (!defined('LOG_LEVEL')) define('LOG_LEVEL', 'warning');
        break;
        
    case 'development':
    default:
        // Configurações de desenvolvimento (mais permissivas)
        if (!defined('SHOW_FILE_LIST')) define('SHOW_FILE_LIST', true);
        if (!defined('ALLOW_FILE_DELETION')) define('ALLOW_FILE_DELETION', true);
        if (!defined('APP_DEBUG')) define('APP_DEBUG', true);
        if (!defined('ENABLE_LOGGING')) define('ENABLE_LOGGING', true);
        if (!defined('LOG_LEVEL')) define('LOG_LEVEL', 'debug');
        break;
}

// =============================================================================
// VALIDAÇÕES
// =============================================================================

// Verificar se o diretório de arquivos existe, senão criar
if (!is_dir(FILES_DIRECTORY)) {
    if (!mkdir(FILES_DIRECTORY, 0755, true)) {
        throw new Exception('Não foi possível criar o diretório de arquivos: ' . FILES_DIRECTORY);
    }
}

// Verificar se o diretório de logs existe (se logging estiver habilitado)
if (ENABLE_LOGGING && !is_dir(LOG_DIRECTORY)) {
    if (!mkdir(LOG_DIRECTORY, 0755, true)) {
        throw new Exception('Não foi possível criar o diretório de logs: ' . LOG_DIRECTORY);
    }
}

// =============================================================================
// FUNÇÕES AUXILIARES
// =============================================================================

/**
 * Retorna se uma funcionalidade está habilitada
 */
function isFeatureEnabled($feature) {
    switch ($feature) {
        case 'file_list':
            return SHOW_FILE_LIST;
        case 'file_upload':
            return SHOW_FILE_UPLOAD;
        case 'new_file':
            return SHOW_NEW_FILE;
        case 'preview':
            return SHOW_PREVIEW;
        case 'file_deletion':
            return ALLOW_FILE_DELETION;
        case 'file_editing':
            return ALLOW_FILE_EDITING;
        case 'file_creation':
            return ALLOW_FILE_CREATION;
        default:
            return false;
    }
}

/**
 * Retorna configuração formatada para JavaScript
 */
function getJSConfig() {
    return json_encode([
        'appName' => APP_NAME,
        'appVersion' => APP_VERSION,
        'environment' => APP_ENV,
        'debug' => APP_DEBUG,
        'features' => [
            'fileList' => SHOW_FILE_LIST,
            'fileUpload' => SHOW_FILE_UPLOAD,
            'newFile' => SHOW_NEW_FILE,
            'preview' => SHOW_PREVIEW,
            'fileDeletion' => ALLOW_FILE_DELETION,
            'fileEditing' => ALLOW_FILE_EDITING,
            'fileCreation' => ALLOW_FILE_CREATION,
        ],
        'theme' => DEFAULT_THEME,
        'language' => DEFAULT_LANGUAGE,
        'branding' => [
            'companyName' => COMPANY_NAME,
            'companyUrl' => COMPANY_URL,
            'primaryColor' => PRIMARY_COLOR,
        ],
        'api' => [
            'baseUrl' => API_BASE_URL,
            'docsUrl' => DOCS_URL,
            'supportUrl' => SUPPORT_URL,
        ]
    ], JSON_PRETTY_PRINT);
}

/**
 * Log simples de mensagens
 */
function logMessage($level, $message) {
    if (!ENABLE_LOGGING) return;
    
    $levels = ['debug' => 0, 'info' => 1, 'warning' => 2, 'error' => 3];
    $currentLevel = $levels[LOG_LEVEL] ?? 3;
    $messageLevel = $levels[$level] ?? 0;
    
    if ($messageLevel >= $currentLevel) {
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[{$timestamp}] {$level}: {$message}" . PHP_EOL;
        file_put_contents(LOG_DIRECTORY . '/app.log', $logEntry, FILE_APPEND | LOCK_EX);
    }
}