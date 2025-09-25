<?php
session_start();

// Definir idioma padrão se não estiver definido
if (!isset($_SESSION['language'])) {
    $_SESSION['language'] = 'pt';
}

// Alterar idioma se solicitado
if (isset($_GET['lang']) && in_array($_GET['lang'], ['pt', 'en', 'es'])) {
    $_SESSION['language'] = $_GET['lang'];
}

$translations = [
    'pt' => [
        'project_name' => 'OpenAPI Editor',
        'light_dark_mode' => 'Alternar modo escuro/claro',
        'create_blank_project' => 'Criar Projeto em Branco',
        'upload_existing_file' => 'Fazer Upload de Arquivo',
        'existing_files' => 'Arquivos Existentes',
        'project_name_label' => 'Nome do Projeto',
        'create_project' => 'Criar Projeto',
        'choose_file' => 'Escolher Arquivo',
        'upload_file' => 'Fazer Upload',
        'open_edit' => 'Abrir/Editar',
        'delete' => 'Excluir',
        'file_exists' => 'Arquivo já existe! Deseja substituir?',
        'yes' => 'Sim',
        'no' => 'Não',
        'success_created' => 'Projeto criado com sucesso!',
        'success_uploaded' => 'Arquivo enviado com sucesso!',
        'error_invalid_file' => 'Arquivo inválido! Por favor, envie um arquivo JSON.',
        'error_upload' => 'Erro ao fazer upload do arquivo.',
        'no_files' => 'Nenhum arquivo encontrado.',
        'header' => 'Cabeçalho',
        'servers' => 'Servidores',
        'security' => 'Segurança',
        'tags' => 'Tags',
        'main' => 'Principal',
        'schemas' => 'Esquemas',
        'export_json' => 'Exportar JSON',
        'export_yaml' => 'Exportar YAML',
        'preview_swagger' => 'Visualizar Swagger',
        'back_to_home' => 'Voltar ao Início',
        
        // Títulos e subtítulos
        'hero_title' => 'OpenAPI Editor',
        'hero_subtitle' => 'Crie, edite e gerencie suas especificações OpenAPI 3.0.3 com uma interface moderna e intuitiva.',
        'get_started' => 'Comece Agora',
        'get_started_description' => 'Escolha como deseja começar seu projeto OpenAPI',
        'existing_projects' => 'Projetos Existentes',
        'existing_projects_description' => 'Arquivos OpenAPI salvos na pasta do projeto',
        'no_projects_found' => 'Nenhum projeto encontrado',
        'no_projects_description' => 'Você ainda não criou nenhum projeto. Comece criando um novo projeto acima ou importando um arquivo existente.',
        
        // Formulários
        'create_description' => 'Comece com um template OpenAPI 3.0.3 limpo e profissional, pronto para personalização.',
        'upload_description' => 'Importe um arquivo OpenAPI existente ou especificação de outra ferramenta.',
        'project_name_placeholder' => 'minha-api-incrivel',
        'project_name_help' => 'Apenas letras, números, _ e -',
        'file_help' => 'Arquivos .json, .yaml ou .yml aceitos',
        'modified_at' => 'Modificado em',
        'edit' => 'Editar',
        
        // Navegação do Editor
        'editing' => 'Editando',
        'add_new' => 'Adicionar Novo',
        'remove' => 'Remover',
        'save_changes' => 'Salvar Alterações',
        'cancel' => 'Cancelar',
        'close' => 'Fechar',
        
        // Tags
        'tags_organization' => 'Organização das Tags',
        'tags_description' => 'Organize e categorize os endpoints da sua API',
        'no_tags_yet' => 'Nenhuma tag adicionada ainda. Clique em "Adicionar Tag" para começar.',
        'tag_name' => 'Nome da Tag',
        'tag_description' => 'Descrição',
        'external_docs_url' => 'URL da Documentação Externa',
        'external_docs_description' => 'Descrição da Documentação',
        'tag_number' => 'Tag #',
        
        // Servidores
        'servers_description' => 'Defina os servidores onde sua API estará disponível',
        
        // Segurança
        'security_audit' => 'Relatório de Auditoria de Segurança',
        'security_audit_description' => 'Análise de vulnerabilidades e recomendações de segurança',
        'analyzing_security' => 'Analisando segurança...',
        'loading' => 'Carregando...',
        'export_report' => 'Exportar Relatório',
        
        // Estados de produção
        'file_listing_disabled' => 'Listagem de Arquivos Desabilitada',
        'production_notice_description' => 'Por questões de segurança, a listagem de arquivos está desabilitada em ambiente de produção. Esta é uma medida de proteção para evitar exposição de informações sensíveis.',
        'learn_more' => 'Saiba Mais',
        
        // Preview
        'back_to_editor' => 'Voltar ao Editor',
        'continue_editing' => 'Continuar Editando',
        'preview' => 'Visualizar',
        'swagger_documentation' => 'Documentação Swagger',
        'swagger_preview' => 'Visualizar Swagger',
        'download' => 'Baixar',
        'toggle_theme' => 'Alternar Tema',
        'toggle_fullscreen' => 'Alternar Tela Cheia',
        'download_openapi_json' => 'Baixar JSON OpenAPI',
        
        // Recursos da API
        'create_openapi_specs' => 'Criar Especificações OpenAPI 3.0.3',
        'realtime_swagger_preview' => 'Visualização Swagger em Tempo Real',
        'visual_schema_designer' => 'Designer Visual de Esquemas',
        'multiformat_export' => 'Exportação Multi-formato (JSON, YAML)',
        
        // Componente Header
        'api_info' => 'Informações da API',
        'api_info_description' => 'Configure as informações básicas da sua API OpenAPI',
        'api_title' => 'Título da API',
        'api_version' => 'Versão',
        'openapi_version' => 'Versão OpenAPI',
        'api_description' => 'Descrição',
        'terms_of_service' => 'Termos de Serviço (URL)',
        'contact_info' => 'Informações de Contato',
        'contact_name' => 'Nome do Contato',
        'contact_email' => 'Email do Contato',
        'contact_url' => 'URL do Contato',
        'license_info' => 'Informações de Licença',
        'license_name' => 'Nome da Licença',
        'license_url' => 'URL da Licença',
        
        // Componente Servers
        'servers_config' => 'Configuração dos Servidores',
        'add_server' => 'Adicionar Servidor',
        'server_number' => 'Servidor #',
        'server_url' => 'URL do Servidor',
        'server_description' => 'Descrição',
        'server_variables' => 'Variáveis do Servidor',
        'variable_name' => 'Nome da variável',
        'default_value' => 'Valor padrão',
        'variable_description' => 'Descrição da variável',
        'add_variable' => 'Adicionar Variável',
        'save_servers' => 'Salvar Servidores',
        'production_server' => 'Production server',
        
        // Componente Security
        'security_config' => 'Configurações de Segurança',
        'security_description' => 'Configure os esquemas de autenticação e autorização da API',
        'add_scheme' => 'Adicionar Esquema',
        'auth_schemes' => 'Esquemas de Autenticação',
        'no_schemes_defined' => 'Nenhum esquema de segurança definido. Comece adicionando um esquema de autenticação!',
        'security_info' => 'Configure os esquemas de autenticação que sua API utiliza. Defina como os usuários devem se autenticar para acessar os endpoints protegidos.',
        'required_schemes' => 'Esquemas Obrigatórios',
        'required_schemes_help' => 'Configure quais esquemas são obrigatórios para toda a API. Endpoints individuais podem sobrescrever essas configurações.',
        
        // Componente Schemas
        'schema_definition' => 'Definição de Esquemas',
        'schemas_description' => 'Defina a estrutura dos dados utilizados nos requests e responses',
        'add_schema' => 'Adicionar Schema',
        'schemas_info' => 'Os esquemas definem a estrutura dos dados utilizados pela sua API. Eles são referenciados nos requests e responses e garantem consistência na documentação.',
        'no_schemas_defined' => 'Nenhum esquema definido',
        'schemas_help' => 'Esquemas definem a estrutura dos seus dados. Comece criando seu primeiro esquema!',
        'global_api_security' => 'Segurança Global da API',
        'required_schemes' => 'Esquemas Obrigatórios',
        'current_config' => 'Configuração Atual',
        'save_security_config' => 'Salvar Configurações de Segurança',
        'config_security_scheme' => 'Configurar Esquema de Segurança',
        'define_auth_methods' => 'Defina os métodos de autenticação da sua API',
        'scheme_name' => 'Nome do Esquema',
        'scheme_name_placeholder' => 'ex: bearerAuth, apiKey',
        'unique_name_help' => 'Nome único para identificar este esquema',
        'auth_type' => 'Tipo de Autenticação',
        'select_type' => 'Selecione o tipo',
        'scheme_description_placeholder' => 'Descrição de como usar este esquema de autenticação...',
        'save_scheme' => 'Salvar Esquema',
        
        // Tipos de autenticação
        'api_key' => 'API Key',
        'http_auth' => 'HTTP Authentication',
        'oauth2' => 'OAuth 2.0',
        'openid_connect' => 'OpenID Connect',
        
        // API Key fields
        'api_key_name' => 'Nome da API Key',
        'api_key_name_placeholder' => 'X-API-Key, Authorization',
        'api_key_name_help' => 'Nome do header, query ou cookie',
        'location' => 'Localização',
        'header' => 'Header',
        'query_parameter' => 'Query Parameter',
        'cookie' => 'Cookie',
        
        // HTTP Auth fields
        'http_scheme' => 'Esquema HTTP',
        'basic_auth' => 'Basic Auth',
        'bearer_token' => 'Bearer Token',
        'digest_auth' => 'Digest Auth',
        'bearer_format' => 'Formato do Bearer',
        'bearer_format_placeholder' => 'JWT, opaque',
        'bearer_format_help' => 'Formato do token (opcional)',
        
        // OAuth2 fields
        'oauth2_flows' => 'Fluxos OAuth2',
        'implicit_flow' => 'Implicit Flow',
        'password_flow' => 'Password Flow',
        'client_credentials_flow' => 'Client Credentials Flow',
        'authorization_code_flow' => 'Authorization Code Flow',
        'authorization_url' => 'Authorization URL',
        'token_url' => 'Token URL',
        'scopes' => 'Scopes (um por linha)',
        'scopes_help' => 'Defina os escopos disponíveis, um por linha',
        
        // OpenID Connect fields
        'openid_connect_url' => 'OpenID Connect URL',
        'discovery_document_help' => 'URL do discovery document',
    ],
    'en' => [
        'project_name' => 'OpenAPI Editor',
        'light_dark_mode' => 'Toggle dark/light mode',
        'create_blank_project' => 'Create Blank Project',
        'upload_existing_file' => 'Upload Existing File',
        'existing_files' => 'Existing Files',
        'project_name_label' => 'Project Name',
        'create_project' => 'Create Project',
        'choose_file' => 'Choose File',
        'upload_file' => 'Upload File',
        'open_edit' => 'Open/Edit',
        'delete' => 'Delete',
        'file_exists' => 'File already exists! Do you want to replace it?',
        'yes' => 'Yes',
        'no' => 'No',
        'success_created' => 'Project created successfully!',
        'success_uploaded' => 'File uploaded successfully!',
        'error_invalid_file' => 'Invalid file! Please upload a JSON file.',
        'error_upload' => 'Error uploading file.',
        'no_files' => 'No files found.',
        'header' => 'Header',
        'servers' => 'Servers',
        'security' => 'Security',
        'tags' => 'Tags',
        'main' => 'Main',
        'schemas' => 'Schemas',
        'export_json' => 'Export JSON',
        'export_yaml' => 'Export YAML',
        'preview_swagger' => 'Preview Swagger',
        'back_to_home' => 'Back to Home',
        
        // Titles and subtitles
        'hero_title' => 'OpenAPI Editor',
        'hero_subtitle' => 'Create, edit, and manage your OpenAPI 3.0.3 specifications with a modern and intuitive interface.',
        'get_started' => 'Get Started',
        'get_started_description' => 'Choose how you want to start your OpenAPI project',
        'existing_projects' => 'Existing Projects',
        'existing_projects_description' => 'OpenAPI files saved in the project folder',
        'no_projects_found' => 'No projects found',
        'no_projects_description' => 'You haven\'t created any projects yet. Start by creating a new project above or importing an existing file.',
        
        // Forms
        'create_description' => 'Start with a clean and professional OpenAPI 3.0.3 template, ready for customization.',
        'upload_description' => 'Import an existing OpenAPI file or specification from another tool.',
        'project_name_placeholder' => 'my-awesome-api',
        'project_name_help' => 'Only letters, numbers, _ and -',
        'file_help' => '.json, .yaml or .yml files accepted',
        'modified_at' => 'Modified on',
        'edit' => 'Edit',
        
        // Editor Navigation
        'editing' => 'Editing',
        'add_new' => 'Add New',
        'remove' => 'Remove',
        'save_changes' => 'Save Changes',
        'cancel' => 'Cancel',
        'close' => 'Close',
        
        // Tags
        'tags_organization' => 'Tags Organization',
        'tags_description' => 'Organize and categorize your API endpoints',
        'no_tags_yet' => 'No tags added yet. Click "Add Tag" to get started.',
        'tag_name' => 'Tag Name',
        'tag_description' => 'Description',
        'external_docs_url' => 'External Documentation URL',
        'external_docs_description' => 'Documentation Description',
        'tag_number' => 'Tag #',
        
        // Servers
        'servers_description' => 'Define the servers where your API will be available',
        
        // Security
        'security_audit' => 'Security Audit Report',
        'security_audit_description' => 'Vulnerability analysis and security recommendations',
        'analyzing_security' => 'Analyzing security...',
        'loading' => 'Loading...',
        'export_report' => 'Export Report',
        
        // Production states
        'file_listing_disabled' => 'File Listing Disabled',
        'production_notice_description' => 'For security reasons, file listing is disabled in production environment. This is a protection measure to prevent exposure of sensitive information.',
        'learn_more' => 'Learn More',
        
        // Preview
        'back_to_editor' => 'Back to Editor',
        'continue_editing' => 'Continue Editing',
        'preview' => 'Preview',
        'swagger_documentation' => 'Swagger Documentation',
        'swagger_preview' => 'Swagger Preview',
        'download' => 'Download',
        'toggle_theme' => 'Toggle Theme',
        'toggle_fullscreen' => 'Toggle Fullscreen',
        'download_openapi_json' => 'Download OpenAPI JSON',
        
        // API Features
        'create_openapi_specs' => 'Create OpenAPI 3.0.3 Specifications',
        'realtime_swagger_preview' => 'Real-time Swagger UI Preview',
        'visual_schema_designer' => 'Visual Schema Designer',
        'multiformat_export' => 'Multi-format Export (JSON, YAML)',
        
        // Header Component
        'api_info' => 'API Information',
        'api_info_description' => 'Configure the basic information of your OpenAPI',
        'api_title' => 'API Title',
        'api_version' => 'Version',
        'openapi_version' => 'OpenAPI Version',
        'api_description' => 'Description',
        'terms_of_service' => 'Terms of Service (URL)',
        'contact_info' => 'Contact Information',
        'contact_name' => 'Contact Name',
        'contact_email' => 'Contact Email',
        'contact_url' => 'Contact URL',
        'license_info' => 'License Information',
        'license_name' => 'License Name',
        'license_url' => 'License URL',
        
        // Servers Component
        'servers_config' => 'Servers Configuration',
        'add_server' => 'Add Server',
        'server_number' => 'Server #',
        'server_url' => 'Server URL',
        'server_description' => 'Description',
        'server_variables' => 'Server Variables',
        'variable_name' => 'Variable name',
        'default_value' => 'Default value',
        'variable_description' => 'Variable description',
        'add_variable' => 'Add Variable',
        'save_servers' => 'Save Servers',
        'production_server' => 'Production server',
        
        // Security Component
        'security_config' => 'Security Configuration',
        'security_description' => 'Configure the authentication and authorization schemes of the API',
        'add_scheme' => 'Add Scheme',
        'auth_schemes' => 'Authentication Schemes',
        'no_schemes_defined' => 'No security schemes defined. Start by adding an authentication scheme!',
        'security_info' => 'Configure the authentication schemes your API uses. Define how users should authenticate to access protected endpoints.',
        'required_schemes' => 'Required Schemes',
        'required_schemes_help' => 'Configure which schemes are required for the entire API. Individual endpoints can override these settings.',
        
        // Schemas Component
        'schema_definition' => 'Schema Definition',
        'schemas_description' => 'Define the structure of data used in requests and responses',
        'add_schema' => 'Add Schema',
        'schemas_info' => 'Schemas define the structure of data used by your API. They are referenced in requests and responses and ensure consistency in documentation.',
        'no_schemas_defined' => 'No schemas defined',
        'schemas_help' => 'Schemas define the structure of your data. Start by creating your first schema!',
        'global_api_security' => 'Global API Security',
        'current_config' => 'Current Configuration',
        'save_security_config' => 'Save Security Configuration',
        'config_security_scheme' => 'Configure Security Scheme',
        'define_auth_methods' => 'Define the authentication methods of your API',
        'scheme_name' => 'Scheme Name',
        'scheme_name_placeholder' => 'e.g: bearerAuth, apiKey',
        'unique_name_help' => 'Unique name to identify this scheme',
        'auth_type' => 'Authentication Type',
        'select_type' => 'Select type',
        'scheme_description_placeholder' => 'Description of how to use this authentication scheme...',
        'save_scheme' => 'Save Scheme',
        
        // Authentication types
        'api_key' => 'API Key',
        'http_auth' => 'HTTP Authentication',
        'oauth2' => 'OAuth 2.0',
        'openid_connect' => 'OpenID Connect',
        
        // API Key fields
        'api_key_name' => 'API Key Name',
        'api_key_name_placeholder' => 'X-API-Key, Authorization',
        'api_key_name_help' => 'Header, query or cookie name',
        'location' => 'Location',
        'header' => 'Header',
        'query_parameter' => 'Query Parameter',
        'cookie' => 'Cookie',
        
        // HTTP Auth fields
        'http_scheme' => 'HTTP Scheme',
        'basic_auth' => 'Basic Auth',
        'bearer_token' => 'Bearer Token',
        'digest_auth' => 'Digest Auth',
        'bearer_format' => 'Bearer Format',
        'bearer_format_placeholder' => 'JWT, opaque',
        'bearer_format_help' => 'Token format (optional)',
        
        // OAuth2 fields
        'oauth2_flows' => 'OAuth2 Flows',
        'implicit_flow' => 'Implicit Flow',
        'password_flow' => 'Password Flow',
        'client_credentials_flow' => 'Client Credentials Flow',
        'authorization_code_flow' => 'Authorization Code Flow',
        'authorization_url' => 'Authorization URL',
        'token_url' => 'Token URL',
        'scopes' => 'Scopes (one per line)',
        'scopes_help' => 'Define available scopes, one per line',
        
        // OpenID Connect fields
        'openid_connect_url' => 'OpenID Connect URL',
        'discovery_document_help' => 'Discovery document URL',
    ],
    'es' => [
        'project_name' => 'Editor OpenAPI',
        'light_dark_mode' => 'Alternar modo oscuro/claro',
        'create_blank_project' => 'Crear Proyecto en Blanco',
        'upload_existing_file' => 'Subir Archivo Existente',
        'existing_files' => 'Archivos Existentes',
        'project_name_label' => 'Nombre del Proyecto',
        'create_project' => 'Crear Proyecto',
        'choose_file' => 'Elegir Archivo',
        'upload_file' => 'Subir Archivo',
        'open_edit' => 'Abrir/Editar',
        'delete' => 'Eliminar',
        'file_exists' => '¡El archivo ya existe! ¿Desea reemplazarlo?',
        'yes' => 'Sí',
        'no' => 'No',
        'success_created' => '¡Proyecto creado exitosamente!',
        'success_uploaded' => '¡Archivo subido exitosamente!',
        'error_invalid_file' => '¡Archivo inválido! Por favor, suba un archivo JSON.',
        'error_upload' => 'Error al subir el archivo.',
        'no_files' => 'No se encontraron archivos.',
        'header' => 'Encabezado',
        'servers' => 'Servidores',
        'security' => 'Seguridad',
        'tags' => 'Etiquetas',
        'main' => 'Principal',
        'schemas' => 'Esquemas',
        'export_json' => 'Exportar JSON',
        'export_yaml' => 'Exportar YAML',
        'preview_swagger' => 'Vista Previa Swagger',
        'back_to_home' => 'Volver al Inicio',
        
        // Títulos y subtítulos
        'hero_title' => 'Editor OpenAPI',
        'hero_subtitle' => 'Cree, edite y gestione sus especificaciones OpenAPI 3.0.3 con una interfaz moderna e intuitiva.',
        'get_started' => 'Comenzar',
        'get_started_description' => 'Elija cómo desea comenzar su proyecto OpenAPI',
        'existing_projects' => 'Proyectos Existentes',
        'existing_projects_description' => 'Archivos OpenAPI guardados en la carpeta del proyecto',
        'no_projects_found' => 'No se encontraron proyectos',
        'no_projects_description' => 'Aún no ha creado ningún proyecto. Comience creando un nuevo proyecto arriba o importando un archivo existente.',
        
        // Formularios
        'create_description' => 'Comience con una plantilla OpenAPI 3.0.3 limpia y profesional, lista para personalización.',
        'upload_description' => 'Importe un archivo OpenAPI existente o especificación de otra herramienta.',
        'project_name_placeholder' => 'mi-api-increible',
        'project_name_help' => 'Solo letras, números, _ y -',
        'file_help' => 'Archivos .json, .yaml o .yml aceptados',
        'modified_at' => 'Modificado el',
        'edit' => 'Editar',
        
        // Navegación del Editor
        'editing' => 'Editando',
        'add_new' => 'Agregar Nuevo',
        'remove' => 'Eliminar',
        'save_changes' => 'Guardar Cambios',
        'cancel' => 'Cancelar',
        'close' => 'Cerrar',
        
        // Etiquetas
        'tags_organization' => 'Organización de Etiquetas',
        'tags_description' => 'Organice y categorice los endpoints de su API',
        'no_tags_yet' => 'Aún no se han agregado etiquetas. Haga clic en "Agregar Etiqueta" para comenzar.',
        'tag_name' => 'Nombre de Etiqueta',
        'tag_description' => 'Descripción',
        'external_docs_url' => 'URL de Documentación Externa',
        'external_docs_description' => 'Descripción de Documentación',
        'tag_number' => 'Etiqueta #',
        
        // Servidores
        'servers_description' => 'Defina los servidores donde estará disponible su API',
        
        // Seguridad
        'security_audit' => 'Reporte de Auditoría de Seguridad',
        'security_audit_description' => 'Análisis de vulnerabilidades y recomendaciones de seguridad',
        'analyzing_security' => 'Analizando seguridad...',
        'loading' => 'Cargando...',
        'export_report' => 'Exportar Reporte',
        
        // Estados de producción
        'file_listing_disabled' => 'Listado de Archivos Deshabilitado',
        'production_notice_description' => 'Por razones de seguridad, el listado de archivos está deshabilitado en ambiente de producción. Esta es una medida de protección para evitar la exposición de información sensible.',
        'learn_more' => 'Saber Más',
        
        // Vista previa
        'back_to_editor' => 'Volver al Editor',
        'continue_editing' => 'Continuar Editando',
        'preview' => 'Vista Previa',
        'swagger_documentation' => 'Documentación Swagger',
        'swagger_preview' => 'Vista Previa Swagger',
        'download' => 'Descargar',
        'toggle_theme' => 'Alternar Tema',
        'toggle_fullscreen' => 'Alternar Pantalla Completa',
        'download_openapi_json' => 'Descargar JSON OpenAPI',
        
        // Características de la API
        'create_openapi_specs' => 'Crear Especificaciones OpenAPI 3.0.3',
        'realtime_swagger_preview' => 'Vista Previa Swagger en Tiempo Real',
        'visual_schema_designer' => 'Diseñador Visual de Esquemas',
        'multiformat_export' => 'Exportación Multi-formato (JSON, YAML)',
        
        // Componente Header
        'api_info' => 'Información de la API',
        'api_info_description' => 'Configure la información básica de su OpenAPI',
        'api_title' => 'Título de la API',
        'api_version' => 'Versión',
        'openapi_version' => 'Versión OpenAPI',
        'api_description' => 'Descripción',
        'terms_of_service' => 'Términos de Servicio (URL)',
        'contact_info' => 'Información de Contacto',
        'contact_name' => 'Nombre de Contacto',
        'contact_email' => 'Email de Contacto',
        'contact_url' => 'URL de Contacto',
        'license_info' => 'Información de Licencia',
        'license_name' => 'Nombre de Licencia',
        'license_url' => 'URL de Licencia',
        
        // Componente Servidores
        'servers_config' => 'Configuración de Servidores',
        'add_server' => 'Agregar Servidor',
        'server_number' => 'Servidor #',
        'server_url' => 'URL del Servidor',
        'server_description' => 'Descripción',
        'server_variables' => 'Variables del Servidor',
        'variable_name' => 'Nombre de variable',
        'default_value' => 'Valor por defecto',
        'variable_description' => 'Descripción de variable',
        'add_variable' => 'Agregar Variable',
        'save_servers' => 'Guardar Servidores',
        'production_server' => 'Servidor de producción',
        
        // Componente Seguridad
        'security_config' => 'Configuración de Seguridad',
        'security_description' => 'Configure los esquemas de autenticación y autorización de la API',
        'add_scheme' => 'Agregar Esquema',
        'auth_schemes' => 'Esquemas de Autenticación',
        'no_schemes_defined' => '¡No hay esquemas de seguridad definidos. Comience agregando un esquema de autenticación!',
        'security_info' => 'Configure los esquemas de autenticación que utiliza su API. Defina cómo los usuarios deben autenticarse para acceder a los endpoints protegidos.',
        'required_schemes' => 'Esquemas Requeridos',
        'required_schemes_help' => 'Configure qué esquemas son obligatorios para toda la API. Los endpoints individuales pueden sobrescribir estas configuraciones.',
        
        // Componente Esquemas
        'schema_definition' => 'Definición de Esquemas',
        'schemas_description' => 'Define la estructura de los datos utilizados en requests y responses',
        'add_schema' => 'Agregar Esquema',
        'schemas_info' => 'Los esquemas definen la estructura de los datos utilizados por su API. Se referencian en requests y responses y garantizan consistencia en la documentación.',
        'no_schemas_defined' => 'No hay esquemas definidos',
        'schemas_help' => '¡Los esquemas definen la estructura de sus datos. Comience creando su primer esquema!',
        'global_api_security' => 'Seguridad Global de la API',
        'current_config' => 'Configuración Actual',
        'save_security_config' => 'Guardar Configuración de Seguridad',
        'config_security_scheme' => 'Configurar Esquema de Seguridad',
        'define_auth_methods' => 'Defina los métodos de autenticación de su API',
        'scheme_name' => 'Nombre del Esquema',
        'scheme_name_placeholder' => 'ej: bearerAuth, apiKey',
        'unique_name_help' => 'Nombre único para identificar este esquema',
        'auth_type' => 'Tipo de Autenticación',
        'select_type' => 'Seleccionar tipo',
        'scheme_description_placeholder' => 'Descripción de cómo usar este esquema de autenticación...',
        'save_scheme' => 'Guardar Esquema',
        
        // Tipos de autenticación
        'api_key' => 'API Key',
        'http_auth' => 'Autenticación HTTP',
        'oauth2' => 'OAuth 2.0',
        'openid_connect' => 'OpenID Connect',
        
        // Campos API Key
        'api_key_name' => 'Nombre de API Key',
        'api_key_name_placeholder' => 'X-API-Key, Authorization',
        'api_key_name_help' => 'Nombre del header, query o cookie',
        'location' => 'Ubicación',
        'header' => 'Header',
        'query_parameter' => 'Parámetro de Query',
        'cookie' => 'Cookie',
        
        // Campos HTTP Auth
        'http_scheme' => 'Esquema HTTP',
        'basic_auth' => 'Autenticación Básica',
        'bearer_token' => 'Token Bearer',
        'digest_auth' => 'Autenticación Digest',
        'bearer_format' => 'Formato Bearer',
        'bearer_format_placeholder' => 'JWT, opaque',
        'bearer_format_help' => 'Formato del token (opcional)',
        
        // Campos OAuth2
        'oauth2_flows' => 'Flujos OAuth2',
        'implicit_flow' => 'Flujo Implícito',
        'password_flow' => 'Flujo de Contraseña',
        'client_credentials_flow' => 'Flujo de Credenciales de Cliente',
        'authorization_code_flow' => 'Flujo de Código de Autorización',
        'authorization_url' => 'URL de Autorización',
        'token_url' => 'URL de Token',
        'scopes' => 'Ámbitos (uno por línea)',
        'scopes_help' => 'Defina los ámbitos disponibles, uno por línea',
        
        // Campos OpenID Connect
        'openid_connect_url' => 'URL de OpenID Connect',
        'discovery_document_help' => 'URL del documento de descubrimiento',
    ]
];

function t($key) {
    global $translations;
    $lang = $_SESSION['language'] ?? 'pt';
    return $translations[$lang][$key] ?? $key;
}
?>