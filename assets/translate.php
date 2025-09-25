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
    ]
];

function t($key) {
    global $translations;
    $lang = $_SESSION['language'] ?? 'pt';
    return $translations[$lang][$key] ?? $key;
}
?>