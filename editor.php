<?php
require_once 'assets/translate.php';
require_once 'assets/openapi-helper.php';

// Verificar se um arquivo foi especificado
if (!isset($_GET['file'])) {
    header('Location: index.php');
    exit;
}

$filename = $_GET['file'];
$openApiData = loadOpenAPIFile($filename);

if (!$openApiData) {
    header('Location: index.php?error=file_not_found');
    exit;
}

// Processar salvamento
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_section'])) {
    $section = $_POST['section'];
    $saved = false;

    switch ($section) {
        case 'header':
            // Processar dados do header (info, contact, license)
            if (isset($_POST['api_title'])) $openApiData['info']['title'] = $_POST['api_title'];
            if (isset($_POST['api_version'])) $openApiData['info']['version'] = $_POST['api_version'];
            if (isset($_POST['api_description'])) $openApiData['info']['description'] = $_POST['api_description'];
            if (isset($_POST['terms_of_service'])) $openApiData['info']['termsOfService'] = $_POST['terms_of_service'];
            if (isset($_POST['openapi_version'])) $openApiData['openapi'] = $_POST['openapi_version'];

            // Contact info
            if (isset($_POST['contact_name']) || isset($_POST['contact_email']) || isset($_POST['contact_url'])) {
                if (!isset($openApiData['info']['contact'])) $openApiData['info']['contact'] = [];
                if (isset($_POST['contact_name']) && !empty($_POST['contact_name'])) $openApiData['info']['contact']['name'] = $_POST['contact_name'];
                if (isset($_POST['contact_email']) && !empty($_POST['contact_email'])) $openApiData['info']['contact']['email'] = $_POST['contact_email'];
                if (isset($_POST['contact_url']) && !empty($_POST['contact_url'])) $openApiData['info']['contact']['url'] = $_POST['contact_url'];
            }

            // License info
            if (isset($_POST['license_name']) || isset($_POST['license_url'])) {
                if (!isset($openApiData['info']['license'])) $openApiData['info']['license'] = [];
                if (isset($_POST['license_name']) && !empty($_POST['license_name'])) $openApiData['info']['license']['name'] = $_POST['license_name'];
                if (isset($_POST['license_url']) && !empty($_POST['license_url'])) $openApiData['info']['license']['url'] = $_POST['license_url'];
            }
            $saved = true;
            break;

        case 'tags':
            // Processar dados das tags
            if (isset($_POST['tags_data'])) {
                $tagsData = json_decode($_POST['tags_data'], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $openApiData['tags'] = $tagsData;
                    $saved = true;
                }
            }
            break;

        case 'main':
            // Processar dados dos endpoints
            if (isset($_POST['paths'])) {
                $decodedPaths = json_decode($_POST['paths'], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $openApiData['paths'] = $decodedPaths;
                    $saved = true;
                }
            }
            break;

        case 'servers':
            // Processar dados dos servidores
            if (isset($_POST['servers_data'])) {
                $serversData = json_decode($_POST['servers_data'], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $openApiData['servers'] = $serversData;
                    $saved = true;
                }
            }
            break;

        case 'security':
            // Processar dados de segurança
            if (isset($_POST['security_schemes'])) {
                $securityData = json_decode($_POST['security_schemes'], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    if (!isset($openApiData['components'])) $openApiData['components'] = [];
                    $openApiData['components']['securitySchemes'] = $securityData;
                    $saved = true;
                }
            }
            break;

        default:
            break;
    }

    if ($saved) {
        // Salva no arquivo JSON
        $result = file_put_contents(__DIR__ . '/files/' . $filename, json_encode($openApiData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        if ($result) {
            $message = ucfirst($section) . ' salvo com sucesso!';
            $messageType = 'success';
            // Recarregar dados atualizados
            $openApiData = loadOpenAPIFile($filename);
        } else {
            $message = 'Erro ao salvar arquivo.';
            $messageType = 'danger';
        }
    } else {
        $message = 'Nenhum dado para salvar ou dados inválidos.';
        $messageType = 'warning';
    }
}

$currentSection = $_GET['section'] ?? 'header';
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION['language']; ?>" data-bs-theme="light" itemscope itemtype="http://schema.org/WebApplication">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes, maximum-scale=5.0">

    <?php
    $projectName = htmlspecialchars(pathinfo($filename, PATHINFO_FILENAME));
    $sectionName = ucfirst($currentSection);
    ?>

    <!-- Primary Meta Tags -->
    <title><?php echo t('editing'); ?> <?php echo $projectName; ?> - <?php echo $sectionName; ?> | OpenAPI Editor</title>
    <meta name="title" content="<?php echo t('editing'); ?> <?php echo $projectName; ?> - <?php echo $sectionName; ?> | OpenAPI Editor">
    <meta name="description" content="Edit <?php echo $projectName; ?> OpenAPI specification - <?php echo $sectionName; ?> section. Professional API documentation editor with real-time validation, visual design tools, and comprehensive OpenAPI 3.0 support.">
    <meta name="keywords" content="<?php echo $projectName; ?>, OpenAPI Editor, <?php echo $sectionName; ?>, API Documentation, Swagger Editor, REST API Design, API Specification, Developer Tools, OpenAPI 3.0">
    <meta name="author" content="OpenAPI Editor Team">
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex, nofollow">

    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:title" content="Editing <?php echo $projectName; ?> - <?php echo $sectionName; ?> | OpenAPI Editor">
    <meta property="og:description" content="Professional API documentation editing interface for <?php echo $projectName; ?>. Edit <?php echo $sectionName; ?> section with real-time validation and visual tools.">
    <meta property="og:image" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']; ?>/assets/images/editor-og-image.jpg">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="OpenAPI Editor - Professional API Documentation Interface">
    <meta property="og:site_name" content="OpenAPI Editor">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    <meta name="twitter:title" content="Editing <?php echo $projectName; ?> - OpenAPI Editor">
    <meta name="twitter:description" content="Professional API documentation editing interface with real-time validation and visual design tools.">
    <meta name="twitter:image" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']; ?>/assets/images/editor-twitter-card.jpg">

    <!-- Application Meta -->
    <meta name="application-name" content="OpenAPI Editor">
    <meta name="theme-color" content="#4f46e5">
    <meta name="msapplication-TileColor" content="#4f46e5">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="OpenAPI Editor">

    <!-- Breadcrumb Structured Data -->
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "BreadcrumbList",
            "itemListElement": [{
                "@type": "ListItem",
                "position": 1,
                "name": "Home",
                "item": "<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']; ?>/"
            }, {
                "@type": "ListItem",
                "position": 2,
                "name": "Editor",
                "item": "<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/editor.php'; ?>"
            }, {
                "@type": "ListItem",
                "position": 3,
                "name": "<?php echo $projectName; ?>",
                "item": "<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>"
            }]
        }
    </script>

    <!-- WebApplication Structured Data -->
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "WebApplication",
            "name": "OpenAPI Editor - <?php echo $projectName; ?>",
            "description": "Professional API documentation editing interface for <?php echo $projectName; ?> with real-time validation and comprehensive OpenAPI 3.0 support.",
            "url": "<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>",
            "applicationCategory": "DeveloperApplication",
            "operatingSystem": "Web Browser",
            "browserRequirements": "Requires JavaScript. Requires HTML5.",
            "softwareVersion": "1.0",
            "creator": {
                "@type": "Organization",
                "name": "OpenAPI Editor Team"
            },
            "potentialAction": {
                "@type": "UseAction",
                "target": "<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>",
                "actionStatus": "PotentialActionStatus",
                "object": {
                    "@type": "DigitalDocument",
                    "name": "<?php echo $projectName; ?> OpenAPI Specification"
                }
            }
        }
    </script>

    <!-- Language Alternatives -->
    <link rel="alternate" hreflang="en" href="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>&lang=en">
    <link rel="alternate" hreflang="pt" href="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>&lang=pt">
    <link rel="alternate" hreflang="es" href="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>&lang=es">

    <!-- Performance Optimization -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" href="assets/dist/css/main.min.css" as="style">
    <link rel="preload" href="assets/dist/js/app.min.js" as="script">
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" as="style">

    <!-- CSS Resources -->
    <link href="assets/dist/css/vendors/bootstrap.min.css" rel="stylesheet">
    <link href="assets/dist/css/vendors/all.min.css" rel="stylesheet">
    <link href="assets/dist/css/main.min.css" rel="stylesheet">

    <!-- Google Fonts for enhanced typography -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <!-- Editor-specific styles -->
    <style>
        /* Editor Background */
        body {
            background-color: var(--editor-bg, #f8f9fa);
            transition: background-color 0.3s ease;
        }

        [data-bs-theme="dark"] body {
            background-color: var(--editor-bg-dark, #1a1d23);
        }

        /* Content Cards */
        .editor-content-card {
            background: var(--card-bg, #ffffff);
            border: 1px solid var(--border-color, #dee2e6);
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        [data-bs-theme="dark"] .editor-content-card {
            background: var(--card-bg-dark, #2d3748);
            border-color: var(--border-color-dark, #4a5568);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        /* Editor Navigation Section */
        .editor-nav-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 3px solid var(--bs-primary);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        [data-bs-theme="dark"] .editor-nav-section {
            background: linear-gradient(135deg, #2d3748 0%, #1a202c 100%);
            border-bottom: 3px solid var(--bs-primary);
        }

        /* Enhanced Navigation Pills */
        .nav-pills .nav-link {
            border-radius: 10px;
            margin: 0 3px;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid transparent;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
        }

        [data-bs-theme="dark"] .nav-pills .nav-link {
            background: rgba(45, 55, 72, 0.8);
        }

        .nav-pills .nav-link:hover {
            background-color: var(--bs-primary-bg-subtle);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(var(--bs-primary-rgb), 0.2);
        }

        .nav-pills .nav-link.active {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(var(--bs-primary-rgb), 0.3);
        }

        /* Export Dropdown */
        .export-dropdown .btn {
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.9);
        }

        [data-bs-theme="dark"] .export-dropdown .btn {
            background: rgba(45, 55, 72, 0.9);
        }

        .export-dropdown .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(var(--bs-primary-rgb), 0.2);
        }

        .export-dropdown .dropdown-menu {
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            padding: 8px;
            margin-top: 8px;
        }

        .export-dropdown .dropdown-item {
            border-radius: 8px;
            padding: 10px 16px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .export-dropdown .dropdown-item:hover {
            transform: translateX(4px);
        }

        /* Enhanced Form Inputs */
        .form-control,
        .form-select {
            background-color: var(--input-bg, #f8f9fa);
            border: 2px solid var(--input-border, #e9ecef);
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: var(--input-focus-bg, #ffffff);
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.2rem rgba(var(--bs-primary-rgb), 0.25);
        }

        [data-bs-theme="dark"] .form-control,
        [data-bs-theme="dark"] .form-select {
            background-color: var(--input-bg-dark, #374151);
            border-color: var(--input-border-dark, #4b5563);
            color: #e5e7eb;
        }

        [data-bs-theme="dark"] .form-control:focus,
        [data-bs-theme="dark"] .form-select:focus {
            background-color: var(--input-focus-bg-dark, #4b5563);
            border-color: var(--bs-primary);
        }

        /* Button Enhancements */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }
    </style>
</head>

<body>
    <!-- Modern Navigation (Same as Index) -->
    <nav class="navbar navbar-modern navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-code"></i>
                <span><?php echo t('project_name'); ?></span>
                <small class="text-muted ms-2">Editor</small>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <i class="fas fa-home me-1"></i>
                            <?php echo t('home'); ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#editor">
                            <i class="fas fa-edit me-1"></i>
                            <?php echo t('editor'); ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="preview.php?file=<?php echo urlencode($filename); ?>" target="_blank">
                            <i class="fas fa-eye me-1"></i>
                            <?php echo t('preview_swagger'); ?>
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-3">
                    <!-- File Info -->
                    <div class="file-indicator d-none d-md-flex">
                        <i class="fas fa-file-code me-2 text-primary"></i>
                        <span class="text-muted"><?php echo htmlspecialchars(pathinfo($filename, PATHINFO_FILENAME)); ?></span>
                    </div>

                    <!-- Language Selector -->
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-language me-1"></i>
                            <?php
                            $flags = ['pt' => '🇧🇷', 'en' => '🇺🇸', 'es' => '🇪🇸'];
                            echo $flags[$_SESSION['language']];
                            ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="?file=<?php echo urlencode($filename); ?>&lang=pt&section=<?php echo $currentSection; ?>">🇧🇷 Português</a></li>
                            <li><a class="dropdown-item" href="?file=<?php echo urlencode($filename); ?>&lang=en&section=<?php echo $currentSection; ?>">🇺🇸 English</a></li>
                            <li><a class="dropdown-item" href="?file=<?php echo urlencode($filename); ?>&lang=es&section=<?php echo $currentSection; ?>">🇪🇸 Español</a></li>
                        </ul>
                    </div>

                    <!-- Theme Toggle -->
                    <button class="theme-toggle" id="theme-toggle" title="<?php echo t('light_dark_mode'); ?>">
                        <i class="fas fa-moon"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Editor Section Navigation -->
    <section class="editor-nav-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center py-3 flex-wrap gap-3">
                        <!-- Section Navigation -->
                        <nav class="nav nav-pills flex-wrap">
                            <a class="nav-link <?php echo $currentSection === 'header' ? 'active' : ''; ?>"
                                href="?file=<?php echo urlencode($filename); ?>&section=header">
                                <i class="fas fa-info-circle me-1 d-md-inline d-none"></i>
                                <span class="d-md-inline d-none"><?php echo t('header'); ?></span>
                                <i class="fas fa-info-circle d-md-none"></i>
                            </a>
                            <a class="nav-link <?php echo $currentSection === 'servers' ? 'active' : ''; ?>"
                                href="?file=<?php echo urlencode($filename); ?>&section=servers">
                                <i class="fas fa-server me-1 d-md-inline d-none"></i>
                                <span class="d-md-inline d-none"><?php echo t('servers'); ?></span>
                                <i class="fas fa-server d-md-none"></i>
                            </a>
                            <a class="nav-link <?php echo $currentSection === 'security' ? 'active' : ''; ?>"
                                href="?file=<?php echo urlencode($filename); ?>&section=security">
                                <i class="fas fa-shield-alt me-1 d-md-inline d-none"></i>
                                <span class="d-md-inline d-none"><?php echo t('security'); ?></span>
                                <i class="fas fa-shield-alt d-md-none"></i>
                            </a>
                            <a class="nav-link <?php echo $currentSection === 'tags' ? 'active' : ''; ?>"
                                href="?file=<?php echo urlencode($filename); ?>&section=tags">
                                <i class="fas fa-tags me-1 d-md-inline d-none"></i>
                                <span class="d-md-inline d-none"><?php echo t('tags'); ?></span>
                                <i class="fas fa-tags d-md-none"></i>
                            </a>
                            <a class="nav-link <?php echo $currentSection === 'main' ? 'active' : ''; ?>"
                                href="?file=<?php echo urlencode($filename); ?>&section=main">
                                <i class="fas fa-code me-1 d-md-inline d-none"></i>
                                <span class="d-md-inline d-none"><?php echo t('main'); ?></span>
                                <i class="fas fa-code d-md-none"></i>
                            </a>
                            <a class="nav-link <?php echo $currentSection === 'schemas' ? 'active' : ''; ?>"
                                href="?file=<?php echo urlencode($filename); ?>&section=schemas">
                                <i class="fas fa-project-diagram me-1 d-md-inline d-none"></i>
                                <span class="d-md-inline d-none"><?php echo t('schemas'); ?></span>
                                <i class="fas fa-project-diagram d-md-none"></i>
                            </a>
                        </nav>

                        <!-- Export Dropdown -->
                        <div class="export-dropdown">
                            <div class="dropdown">
                                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-download me-2"></i>
                                    <span class="d-none d-sm-inline">Exportar</span>
                                    <span class="d-sm-none">Export</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="exportDropdown">
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="exportFile('json'); return false;">
                                            <i class="fas fa-file-code me-2 text-success"></i>
                                            Exportar como JSON
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="exportFile('yaml'); return false;">
                                            <i class="fas fa-file-alt me-2 text-info"></i>
                                            Exportar como YAML
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-muted" href="preview.php?file=<?php echo urlencode($filename); ?>" target="_blank">
                                            <i class="fas fa-eye me-2"></i>
                                            Visualizar no Swagger UI
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content Area -->
    <main class="py-4" role="main" itemscope itemtype="http://schema.org/WebPageElement">
        <div class="container">
            <!-- Breadcrumb Navigation -->
            <!-- <nav aria-label="breadcrumb" class="d-none d-md-block mb-3">
                <ol class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
                    <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                        <a href="index.php" itemprop="item">
                            <span itemprop="name">Home</span>
                        </a>
                        <meta itemprop="position" content="1" />
                    </li>
                    <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                        <a href="#" itemprop="item">
                            <span itemprop="name">Editor</span>
                        </a>
                        <meta itemprop="position" content="2" />
                    </li>
                    <li class="breadcrumb-item active" aria-current="page" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                        <span itemprop="name"><?php echo htmlspecialchars($projectName); ?></span>
                        <meta itemprop="position" content="3" />
                    </li>
                </ol>
            </nav> -->

            <!-- Status Messages -->
            <?php if (isset($message)): ?>
                <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                    <?php echo $message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Editor Content -->
            <div class="row">
                <div class="col-12">
                    <article class="editor-content-card" itemscope itemtype="http://schema.org/CreativeWork">
                        <!-- Content Body -->
                        <div class="card-body p-0">
                    <?php
                    $componentFile = "components/{$currentSection}.php";
                    if (file_exists($componentFile)) {
                        include $componentFile;
                    } else {
                    ?>
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-tools fa-4x text-primary opacity-50"></i>
                            </div>
                            <h4 class="text-primary">Section Under Development</h4>
                            <p class="text-muted mb-4">The <strong><?php echo htmlspecialchars($currentSection); ?></strong> section is currently being enhanced with advanced features.</p>

                            <!-- Sample Form for Demo -->
                            <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title">Example Configuration</h6>
                                            <div class="mb-3">
                                                <label class="form-label">Example Field</label>
                                                <input type="text" class="form-control" placeholder="Enter some text...">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Example Select</label>
                                                <select class="form-select">
                                                    <option>Choose an option...</option>
                                                    <option>Option 1</option>
                                                    <option>Option 2</option>
                                                </select>
                                            </div>
                                            <button class="btn btn-primary btn-sm">
                                                <i class="fas fa-bell me-2"></i>Notify When Ready
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                </article>
            </div>
        </div>
        </div>
    </main>

    <!-- Core Scripts -->
    <script src="assets/dist/js/vendors/bootstrap.bundle.min.js"></script>
    <script src="assets/dist/js/home-page.min.js"></script>

    <!-- Editor Configuration -->
    <script>
        // Basic configuration for editor page
        window.AppConfig = <?php echo getJSConfig(); ?>;
        window.currentFile = '<?php echo htmlspecialchars($filename); ?>';
        window.currentSection = '<?php echo htmlspecialchars($currentSection); ?>';



        // Export functionality
        function exportFile(format) {
            const dropdown = document.getElementById('exportDropdown');
            const originalText = dropdown.innerHTML;

            // Show loading state
            dropdown.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Exporting...';
            dropdown.disabled = true;

            // Simulate export process
            setTimeout(() => {
                // Create download link
                const link = document.createElement('a');
                link.href = `export.php?file=${encodeURIComponent(window.currentFile)}&format=${format}`;
                link.download = `${window.currentFile.replace('.json', '')}.${format}`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                // Show success and reset
                dropdown.innerHTML = '<i class="fas fa-check me-2"></i>Downloaded!';
                setTimeout(() => {
                    dropdown.innerHTML = originalText;
                    dropdown.disabled = false;
                }, 1500);
            }, 800);
        }
    </script>
</body>

</html>