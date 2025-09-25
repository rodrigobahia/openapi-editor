<?php
// Carregar configurações
$configFile = file_exists(__DIR__ . '/config.local.php')
  ? __DIR__ . '/config.local.php'
  : __DIR__ . '/config.php';

require_once $configFile;
require_once 'assets/translate.php';
require_once 'assets/openapi-helper.php';

// Processar ações
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['action'])) {
    switch ($_POST['action']) {
      case 'create_project':
        $projectName = trim($_POST['project_name']);
        if (!empty($projectName) && preg_match('/^[a-zA-Z0-9_-]+$/', $projectName)) {
          $filename = $projectName . '.json';
          $filePath = __DIR__ . '/files/' . $filename;

          if (file_exists($filePath) && !isset($_POST['confirm_replace'])) {
            $message = t('file_exists');
            $messageType = 'warning';
            $showConfirm = $projectName;
          } else {
            $template = getBlankOpenAPITemplate($projectName);
            if (saveOpenAPIFile($projectName, $template)) {
              // Regra padrão: sempre redirecionar para o editor após criar novo projeto
              header('Location: editor.php?file=' . urlencode($filename));
              exit;
            }
          }
        }
        break;

      case 'upload_file':
        if (isset($_FILES['json_file']) && $_FILES['json_file']['error'] === UPLOAD_ERR_OK) {
          $uploadedFile = $_FILES['json_file'];
          $fileExtension = pathinfo($uploadedFile['name'], PATHINFO_EXTENSION);

          if ($fileExtension === 'json') {
            $targetPath = __DIR__ . '/files/' . $uploadedFile['name'];

            if (file_exists($targetPath) && !isset($_POST['confirm_upload'])) {
              $message = t('file_exists');
              $messageType = 'warning';
              $showConfirmUpload = $uploadedFile['name'];
            } else {
              if (move_uploaded_file($uploadedFile['tmp_name'], $targetPath)) {
                // Regra padrão: sempre redirecionar para o editor após upload
                header('Location: editor.php?file=' . urlencode($uploadedFile['name']));
                exit;
              } else {
                $message = t('error_upload');
                $messageType = 'danger';
              }
            }
          } else {
            $message = t('error_invalid_file');
            $messageType = 'danger';
          }
        }
        break;
    }
  }
}

// Processar ações GET
if (isset($_GET['action'])) {
  switch ($_GET['action']) {
    case 'delete':
      if (isset($_GET['file'])) {
        if (deleteOpenAPIFile($_GET['file'])) {
          $message = 'Arquivo excluído com sucesso!';
          $messageType = 'success';
        } else {
          $message = 'Erro ao excluir arquivo.';
          $messageType = 'danger';
        }
      }
      break;
  }
}

$files = getOpenAPIFiles();
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION['language']; ?>" data-bs-theme="light" itemscope itemtype="http://schema.org/SoftwareApplication">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes, maximum-scale=5.0">
  
  <!-- Primary Meta Tags -->
  <title>OpenAPI Editor - Professional API Documentation Tool | Create, Edit & Manage OpenAPI 3.0 Specifications</title>
  <meta name="title" content="OpenAPI Editor - Professional API Documentation Tool | Create, Edit & Manage OpenAPI 3.0 Specifications">
  <meta name="description" content="Create, edit and manage your OpenAPI 3.0.3 specifications with a modern, intuitive interface. Professional API documentation tool with real-time preview, validation, and export features.">
  <meta name="keywords" content="OpenAPI, Swagger, API Documentation, REST API, API Design, Swagger Editor, OpenAPI 3.0, API Specification, Developer Tools, Documentation Generator">
  <meta name="author" content="OpenAPI Editor Team">
  <meta name="generator" content="OpenAPI Editor v1.0">
  <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
  <meta name="googlebot" content="index, follow, max-video-preview:-1, max-image-preview:large, max-snippet:-1">
  <meta name="bingbot" content="index, follow, max-video-preview:-1, max-image-preview:large, max-snippet:-1">
  
  <!-- Canonical URL -->
  <link rel="canonical" href="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
  
  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:url" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
  <meta property="og:title" content="OpenAPI Editor - Professional API Documentation Tool">
  <meta property="og:description" content="Create, edit and manage your OpenAPI 3.0.3 specifications with a modern, intuitive interface. Professional API documentation tool with real-time preview and validation.">
  <meta property="og:image" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']; ?>/assets/images/og-image.jpg">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta property="og:image:alt" content="OpenAPI Editor - Create and manage API documentation">
  <meta property="og:site_name" content="OpenAPI Editor">
  <meta property="og:locale" content="<?php echo $_SESSION['language'] == 'pt' ? 'pt_BR' : ($_SESSION['language'] == 'es' ? 'es_ES' : 'en_US'); ?>">
  
  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:url" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
  <meta name="twitter:title" content="OpenAPI Editor - Professional API Documentation Tool">
  <meta name="twitter:description" content="Create, edit and manage your OpenAPI 3.0.3 specifications with a modern, intuitive interface. Professional API documentation tool.">
  <meta name="twitter:image" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']; ?>/assets/images/twitter-card.jpg">
  <meta name="twitter:image:alt" content="OpenAPI Editor - API Documentation Tool">
  <meta name="twitter:creator" content="@openapi_editor">
  <meta name="twitter:site" content="@openapi_editor">
  
  <!-- Additional SEO Meta -->
  <meta name="application-name" content="OpenAPI Editor">
  <meta name="theme-color" content="#4f46e5">
  <meta name="msapplication-TileColor" content="#4f46e5">
  <meta name="msapplication-config" content="/browserconfig.xml">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <meta name="apple-mobile-web-app-title" content="OpenAPI Editor">
  <meta name="format-detection" content="telephone=no">
  
  <!-- Favicon and Icons -->
  <link rel="icon" type="image/x-icon" href="/favicon.ico">
  <link rel="icon" type="image/png" sizes="16x16" href="/assets/icons/favicon-16x16.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/assets/icons/favicon-32x32.png">
  <link rel="apple-touch-icon" sizes="180x180" href="/assets/icons/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="192x192" href="/assets/icons/android-chrome-192x192.png">
  <link rel="icon" type="image/png" sizes="512x512" href="/assets/icons/android-chrome-512x512.png">
  <link rel="manifest" href="/site.webmanifest">
  
  <!-- Preconnect to external domains -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
  
  <!-- Structured Data -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "SoftwareApplication",
    "name": "OpenAPI Editor",
    "description": "Professional API documentation tool for creating, editing and managing OpenAPI 3.0.3 specifications with modern interface and real-time validation.",
    "url": "<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']; ?>",
    "applicationCategory": "DeveloperApplication",
    "operatingSystem": "Web Browser",
    "offers": {
      "@type": "Offer",
      "price": "0",
      "priceCurrency": "USD"
    },
    "creator": {
      "@type": "Organization",
      "name": "OpenAPI Editor Team"
    },
    "featureList": [
      "OpenAPI 3.0.3 Support",
      "Real-time Validation",
      "Swagger UI Preview",
      "Multi-format Export",
      "Visual Editor",
      "Schema Management",
      "Security Configuration",
      "Multi-language Support"
    ],
    "screenshot": "<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']; ?>/assets/images/screenshot.jpg"
  }
  </script>
  
  <!-- Language Alternatives -->
  <link rel="alternate" hreflang="en" href="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>?lang=en">
  <link rel="alternate" hreflang="pt" href="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>?lang=pt">
  <link rel="alternate" hreflang="es" href="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>?lang=es">
  <link rel="alternate" hreflang="x-default" href="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
  
  <!-- Performance Optimization -->
  <link rel="preload" href="assets/dist/css/main.min.css" as="style">
  <link rel="preload" href="assets/dist/js/home-page.min.js" as="script">
  <link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" as="style">
  
  <!-- CSS Resources -->
  <link href="assets/dist/css/vendors/bootstrap.min.css" rel="stylesheet">
  <link href="assets/dist/css/vendors/all.min.css" rel="stylesheet">
  <link href="assets/dist/css/main.min.css" rel="stylesheet">
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
  <!-- Modern Navigation -->
  <nav class="navbar navbar-modern navbar-expand-lg">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        <i class="fas fa-code"></i>
        <span><?php echo t('project_name'); ?></span>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link active" href="#home">
              <i class="fas fa-home me-1"></i>
              <?php echo t('home'); ?>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#editor">
              <i class="fas fa-edit me-1"></i>
              <?php echo t('editor'); ?>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="https://github.com/rodrigobahia/openapi-editor" target="_blank">
              <i class="fas fa-github me-1"></i>
              Github
            </a>
          </li>
        </ul>

        <div class="d-flex align-items-center gap-3">
          <!-- Security Status -->
          <div id="security-alerts"></div>

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
              <li><a class="dropdown-item" href="?lang=pt">🇧🇷 Português</a></li>
              <li><a class="dropdown-item" href="?lang=en">🇺🇸 English</a></li>
              <li><a class="dropdown-item" href="?lang=es">🇪🇸 Español</a></li>
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

  <!-- Hero Section -->
  <section class="home-hero" itemscope itemtype="http://schema.org/WebPageElement">
    <div class="container">
      <div class="hero-content text-center">
        <h1 class="hero-title" itemprop="headline"><?php echo t('hero_title'); ?></h1>
        <p class="hero-subtitle" itemprop="description">
          <?php echo t('hero_subtitle'); ?>
        </p>
        <!-- Rich Snippets for Features -->
        <div class="sr-only" itemscope itemtype="http://schema.org/ItemList">
          <div itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
            <meta itemprop="position" content="1">
            <span itemprop="name"><?php echo t('create_openapi_specs'); ?></span>
          </div>
          <div itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
            <meta itemprop="position" content="2">
            <span itemprop="name"><?php echo t('realtime_swagger_preview'); ?></span>
          </div>
          <div itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
            <meta itemprop="position" content="3">
            <span itemprop="name"><?php echo t('visual_schema_designer'); ?></span>
          </div>
          <div itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
            <meta itemprop="position" content="4">
            <span itemprop="name"><?php echo t('multiformat_export'); ?></span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Messages -->
  <?php if ($message): ?>
    <div class="container mt-4">
      <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
        <?php echo $message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    </div>
  <?php endif; ?>

  <!-- Confirmations -->
  <?php if (isset($showConfirm)): ?>
    <div class="container mt-4">
      <div class="alert alert-warning">
        <p><?php echo t('file_exists'); ?></p>
        <form method="POST" class="d-inline">
          <input type="hidden" name="action" value="create_project">
          <input type="hidden" name="project_name" value="<?php echo htmlspecialchars($showConfirm); ?>">
          <input type="hidden" name="confirm_replace" value="1">
          <button type="submit" class="btn btn-warning me-2"><?php echo t('yes'); ?></button>
          <a href="?" class="btn btn-secondary"><?php echo t('no'); ?></a>
        </form>
      </div>
    </div>
  <?php endif; ?>

  <?php if (isset($showConfirmUpload)): ?>
    <div class="container mt-4">
      <div class="alert alert-warning">
        <p><?php echo t('file_exists'); ?></p>
        <form method="POST" enctype="multipart/form-data" class="d-inline">
          <input type="hidden" name="action" value="upload_file">
          <input type="hidden" name="confirm_upload" value="1">
          <button type="submit" class="btn btn-warning me-2"><?php echo t('yes'); ?></button>
          <a href="?" class="btn btn-secondary"><?php echo t('no'); ?></a>
        </form>
      </div>
    </div>
  <?php endif; ?>

  <!-- Action Cards Section -->
  <section class="action-cards-section">
    <div class="container">
      <div class="section-title">
        <h2><?php echo t('get_started'); ?></h2>
        <p><?php echo t('get_started_description'); ?></p>
      </div>
      
      <div class="row justify-content-center">
        <?php if (isFeatureEnabled('new_file')): ?>
        <!-- Create New Project Card -->
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="action-card">
            <div class="action-icon">
              <i class="fas fa-plus"></i>
            </div>
            <h3 class="action-title"><?php echo t('create_blank_project'); ?></h3>
            <p class="action-description">
              <?php echo t('create_description'); ?>
            </p>
            <form method="POST">
              <input type="hidden" name="action" value="create_project">
              <div class="mb-3">
                <label for="project_name" class="form-label"><?php echo t('project_name_label'); ?></label>
                <input type="text" class="form-control" id="project_name" name="project_name"
                  pattern="[a-zA-Z0-9_-]+" required
                  placeholder="<?php echo t('project_name_placeholder'); ?>">
                <div class="form-text"><?php echo t('project_name_help'); ?></div>
              </div>
              <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-plus me-2"></i>
                <?php echo t('create_project'); ?>
              </button>
            </form>
          </div>
        </div>
        <?php endif; ?>

        <?php if (isFeatureEnabled('file_upload')): ?>
        <!-- Upload File Card -->
        <div class="col-lg-4 col-md-6 mb-4">
          <div class="action-card">
            <div class="action-icon">
              <i class="fas fa-upload"></i>
            </div>
            <h3 class="action-title"><?php echo t('upload_existing_file'); ?></h3>
            <p class="action-description">
              <?php echo t('upload_description'); ?>
            </p>
            <form method="POST" enctype="multipart/form-data">
              <input type="hidden" name="action" value="upload_file">
              <div class="mb-3">
                <label for="json_file" class="form-label"><?php echo t('choose_file'); ?></label>
                <input type="file" class="form-control" id="json_file" name="json_file"
                  accept=".json" required>
                <div class="form-text"><?php echo t('file_help'); ?></div>
              </div>
              <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-upload me-2"></i>
                <?php echo t('upload_file'); ?>
              </button>
            </form>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- Files Section -->
  <section class="files-section">
    <div class="container">
      <?php if (isFeatureEnabled('file_list')): ?>
        <!-- Section Header -->
        <div class="section-header">
          <div class="section-title">
            <h3>
              <i class="fas fa-folder-open"></i>
              <?php echo t('existing_projects'); ?>
            </h3>
            <p><?php echo t('existing_projects_description'); ?></p>
          </div>
        </div>

        <!-- Files Grid or Empty State -->
        <?php if (empty($files)): ?>
          <div class="empty-state">
            <div class="empty-icon">
              <i class="fas fa-folder-open"></i>
            </div>
            <h3 class="empty-title"><?php echo t('no_projects_found'); ?></h3>
            <p class="empty-description">
              <?php echo t('no_projects_description'); ?>
            </p>
          </div>
        <?php else: ?>
          <div class="file-grid">
            <?php foreach ($files as $file): ?>
              <div class="file-card">
                <div class="file-header">
                  <div class="file-icon">
                    <i class="fas fa-file-code"></i>
                  </div>
                  <div class="file-info">
                    <div class="file-name"><?php echo htmlspecialchars($file); ?></div>
                    <div class="file-date">
                      <?php 
                        $filePath = __DIR__ . '/files/' . $file;
                        if (file_exists($filePath)) {
                          echo t('modified_at') . ' ' . date('d/m/Y H:i', filemtime($filePath));
                        }
                      ?>
                    </div>
                  </div>
                </div>
                <div class="file-actions">
                  <a href="editor.php?file=<?php echo urlencode($file); ?>" class="btn btn-primary">
                    <i class="fas fa-edit me-1"></i>
                    <?php echo t('edit'); ?>
                  </a>
                  <?php if (isFeatureEnabled('file_deletion')): ?>
                    <button type="button" class="btn btn-outline-danger"
                      onclick="deleteFile('<?php echo htmlspecialchars($file); ?>')">
                      <i class="fas fa-trash"></i>
                    </button>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      <?php else: ?>
        <!-- Production Notice -->
        <div class="production-notice">
          <div class="notice-header">
            <div class="notice-icon">
              <i class="fas fa-shield-alt"></i>
            </div>
            <h4 class="notice-title"><?php echo t('file_listing_disabled'); ?></h4>
          </div>
          <p class="notice-description">
            <?php echo t('production_notice_description'); ?>
          </p>
          <div class="notice-actions">
            <button class="btn btn-warning" onclick="showProductionInfo()">
              <i class="fas fa-info-circle me-2"></i>
              <?php echo t('learn_more'); ?>
            </button>
          </div>
        </div>
      <?php endif; ?>

    </div>
  </section>

  <!-- Security Modal -->
  <div class="modal fade" id="securityModal" tabindex="-1" aria-labelledby="securityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-xl-down modal-xl">
      <div class="modal-content border-0 shadow-lg">
        <div class="modal-header modal-header-gradient text-white position-relative overflow-hidden">
          <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10">
            <div class="bg-primary w-100 h-100 gradient-bg-purple"></div>
          </div>
          <div class="d-flex align-items-center position-relative z-index-2 w-100">
            <div class="flex-grow-1">
              <h5 class="modal-title mb-1 fw-bold" id="securityModalLabel">
                <i class="fas fa-shield-alt me-2"></i>
                <?php echo t('security_audit'); ?>
              </h5>
              <small class="opacity-75"><?php echo t('security_audit_description'); ?></small>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
        </div>
        <div class="modal-body">
          <div id="security-report-content">
            <div class="text-center py-4">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden"><?php echo t('loading'); ?></span>
              </div>
              <p class="mt-2"><?php echo t('analyzing_security'); ?></p>
            </div>
          </div>
        </div>
        <div class="modal-footer border-0 bg-light px-4 py-3 d-flex justify-content-end gap-3">
          <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">
            <i class="fas fa-times me-2"></i>
            <?php echo t('close'); ?>
          </button>
          <button type="button" class="btn btn-save" onclick="securityManager.exportAuditResults()">
            <i class="fas fa-download me-2"></i>
            <?php echo t('export_report'); ?>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Core Scripts -->
  <script src="assets/dist/js/vendors/bootstrap.bundle.min.js"></script>
  <script src="assets/dist/js/home-page.min.js"></script>
  
  <!-- Home Page Configuration -->
  <script>
    // Basic configuration for home page
    window.AppConfig = <?php echo getJSConfig(); ?>;

    // All functionality moved to home-page.min.js for better performance

    // Delete file function (specific to this page)
    function deleteFile(filename) {
      if (confirm(`Tem certeza que deseja excluir "${filename}"?`)) {
        window.location.href = `?action=delete&file=${encodeURIComponent(filename)}`;
      }
    }


  </script>

  <!-- Footer -->
  <footer class="text-center py-4 bg-light mt-5 border-top">
    <span class="fw-semibold" style="color:#888;">
      Desenvolvido com <span style="color:#e25555">&#10084;&#65039;</span> por Rodrigo Bahia
      <a href="https://myrotech.com" target="_blank"><img src="assets/images/myrotech.png" alt="Myrotech"></a>
    </span>
  </footer>
</body>
</html>
