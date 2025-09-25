<?php
require_once 'assets/translate.php';
require_once 'assets/openapi-helper.php';

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

$jsonData = json_encode($openApiData);
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION['language']; ?>" data-bs-theme="light" itemscope itemtype="http://schema.org/WebPage">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes, maximum-scale=5.0">
    
    <?php 
    $projectName = htmlspecialchars(pathinfo($filename, PATHINFO_FILENAME));
    $apiTitle = $openApiData['info']['title'] ?? 'API Documentation';
    $apiVersion = $openApiData['info']['version'] ?? '1.0.0';
    $apiDescription = $openApiData['info']['description'] ?? 'API Documentation Preview';
    ?>
    
    <!-- Primary Meta Tags -->
    <title><?php echo $apiTitle; ?> v<?php echo $apiVersion; ?> - API Documentation Preview | OpenAPI Editor</title>
    <meta name="title" content="<?php echo $apiTitle; ?> v<?php echo $apiVersion; ?> - API Documentation Preview">
    <meta name="description" content="<?php echo strip_tags($apiDescription); ?> - Interactive API documentation powered by Swagger UI. Explore endpoints, test requests, and view detailed API specifications.">
    <meta name="keywords" content="<?php echo $projectName; ?>, <?php echo $apiTitle; ?>, API Documentation, Swagger UI, REST API, API Reference, <?php echo $apiVersion; ?>, Interactive Documentation, API Testing">
    <meta name="author" content="OpenAPI Editor">
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex, nofollow">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:title" content="<?php echo $apiTitle; ?> v<?php echo $apiVersion; ?> - API Documentation">
    <meta property="og:description" content="<?php echo strip_tags($apiDescription); ?> - Interactive API documentation with Swagger UI.">
    <meta property="og:image" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']; ?>/assets/images/swagger-preview-og.jpg">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="<?php echo $apiTitle; ?> API Documentation Preview">
    <meta property="og:site_name" content="OpenAPI Editor">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    <meta name="twitter:title" content="<?php echo $apiTitle; ?> - API Documentation Preview">
    <meta name="twitter:description" content="<?php echo strip_tags($apiDescription); ?> - Interactive Swagger UI documentation.">
    <meta name="twitter:image" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']; ?>/assets/images/swagger-preview-twitter.jpg">
    
    <!-- Application Meta -->
    <meta name="application-name" content="<?php echo $apiTitle; ?> Documentation">
    <meta name="theme-color" content="#4f46e5">
    <meta name="msapplication-TileColor" content="#4f46e5">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="<?php echo $apiTitle; ?> Docs">
    
    <!-- API Documentation Structured Data -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "APIReference",
      "name": "<?php echo $apiTitle; ?>",
      "description": "<?php echo strip_tags($apiDescription); ?>",
      "version": "<?php echo $apiVersion; ?>",
      "url": "<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>",
      "provider": {
        "@type": "Organization",
        "name": "OpenAPI Editor"
      },
      "programmingLanguage": [
        "JavaScript",
        "PHP", 
        "Python",
        "Java",
        "C#",
        "Ruby",
        "Go"
      ],
      "operatingSystem": [
        "Web Browser",
        "Any"
      ],
      "applicationCategory": "API Documentation",
      "softwareHelp": {
        "@type": "CreativeWork",
        "url": "<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>"
      }
    }
    </script>
    
    <!-- TechnicalArticle Structured Data -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "TechnicalArticle",
      "headline": "<?php echo $apiTitle; ?> API Documentation",
      "description": "<?php echo strip_tags($apiDescription); ?>",
      "version": "<?php echo $apiVersion; ?>",
      "author": {
        "@type": "Organization",
        "name": "OpenAPI Editor Team"
      },
      "publisher": {
        "@type": "Organization",
        "name": "OpenAPI Editor"
      },
      "dateModified": "<?php echo date('c', filemtime(__DIR__ . '/files/' . $filename)); ?>",
      "datePublished": "<?php echo date('c', filemtime(__DIR__ . '/files/' . $filename)); ?>",
      "mainEntityOfPage": "<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>",
      "image": "<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']; ?>/assets/images/api-docs-image.jpg",
      "keywords": "<?php echo $projectName; ?>, API, Documentation, REST, <?php echo $apiVersion; ?>",
      "genre": "Technical Documentation",
      "learningResourceType": "API Reference"
    }
    </script>
    
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
      },{
        "@type": "ListItem",
        "position": 2,
        "name": "Editor",
        "item": "<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/editor.php?file=' . urlencode($filename); ?>"
      },{
        "@type": "ListItem",
        "position": 3,
        "name": "Preview",
        "item": "<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>"
      }]
    }
    </script>
    
    <!-- Performance Optimization -->
    <link rel="preconnect" href="https://unpkg.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" href="https://unpkg.com/swagger-ui-dist@4.15.5/swagger-ui-bundle.js" as="script">
    <link rel="preload" href="assets/dist/css/main.min.css" as="style">
    
    <!-- CSS Resources -->
    <link href="assets/dist/css/vendors/bootstrap.min.css" rel="stylesheet">
    <link href="assets/dist/css/vendors/all.min.css" rel="stylesheet">
    <link href="assets/dist/css/main.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/swagger-ui-dist@4.15.5/swagger-ui.css" />
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <style>
        .swagger-ui .topbar { display: none !important; }
        .swagger-ui .wrapper { padding: 2rem !important; }
        .swagger-ui { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif !important; }
        
        /* Fix method badges visibility */
        .swagger-ui .opblock-summary-method {
            display: inline-block !important;
            min-width: 80px !important;
            text-align: center !important;
            font-weight: 700 !important;
            font-size: 0.75rem !important;
            text-transform: uppercase !important;
            border-radius: 6px !important;
            padding: 0.375rem 0.75rem !important;
            margin-right: 1rem !important;
        }
        
        .swagger-ui .opblock.opblock-get .opblock-summary-method {
            background: #10b981 !important;
            color: white !important;
        }
        
        .swagger-ui .opblock.opblock-post .opblock-summary-method {
            background: #3b82f6 !important;
            color: white !important;
        }
        
        .swagger-ui .opblock.opblock-put .opblock-summary-method {
            background: #f59e0b !important;
            color: white !important;
        }
        
        .swagger-ui .opblock.opblock-delete .opblock-summary-method {
            background: #ef4444 !important;
            color: white !important;
        }
        
        .swagger-ui .opblock.opblock-patch .opblock-summary-method {
            background: #8b5cf6 !important;
            color: white !important;
        }
        
        .swagger-ui .opblock.opblock-head .opblock-summary-method {
            background: #6b7280 !important;
            color: white !important;
        }
        
        .swagger-ui .opblock.opblock-options .opblock-summary-method {
            background: #14b8a6 !important;
            color: white !important;
        }
        
        /* Ensure topbar is completely hidden */
        .swagger-ui .topbar,
        .swagger-ui .topbar-wrapper {
            display: none !important;
            height: 0 !important;
            overflow: hidden !important;
        }
        
        /* Fix container padding */
        .swagger-ui .swagger-container {
            padding-top: 0 !important;
        }
        
        .loading-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 60vh;
            flex-direction: column;
        }
        
        .loading-animation {
            width: 80px;
            height: 80px;
            border: 3px solid #e5e7eb;
            border-top: 3px solid #6366f1;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 1rem;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Container styles without CSS variables conflicts */
        .preview-container {
            min-height: 100vh;
            background: #f8fafc;
            padding-top: 80px;
        }
        
        [data-bs-theme="dark"] .preview-container {
            background: #0f172a;
        }
        
        .swagger-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            margin: 2rem;
            overflow: hidden;
        }
        
        [data-bs-theme="dark"] .swagger-container {
            background: #1e293b;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
        }
        
        /* Method badges - explicit styling to override conflicts */
        .swagger-ui .opblock-summary-method {
            display: inline-block !important;
            min-width: 80px !important;
            text-align: center !important;
            font-weight: 700 !important;
            font-size: 12px !important;
            text-transform: uppercase !important;
            border-radius: 6px !important;
            padding: 6px 12px !important;
            margin-right: 12px !important;
            visibility: visible !important;
            opacity: 1 !important;
            color: white !important;
            text-indent: 0 !important;
            line-height: 1.2 !important;
        }
        
        /* Method colors */
        .swagger-ui .opblock.opblock-get .opblock-summary-method {
            background: #10b981 !important;
        }
        
        .swagger-ui .opblock.opblock-post .opblock-summary-method {
            background: #3b82f6 !important;
        }
        
        .swagger-ui .opblock.opblock-put .opblock-summary-method {
            background: #f59e0b !important;
        }
        
        .swagger-ui .opblock.opblock-delete .opblock-summary-method {
            background: #ef4444 !important;
        }
        
        .swagger-ui .opblock.opblock-patch .opblock-summary-method {
            background: #8b5cf6 !important;
        }
        
        .swagger-ui .opblock.opblock-head .opblock-summary-method {
            background: #6b7280 !important;
        }
        
        .swagger-ui .opblock.opblock-options .opblock-summary-method {
            background: #14b8a6 !important;
        }
        

    </style>
</head>
<body class="modern-layout preview-body" itemscope itemtype="http://schema.org/WebPage">
    <meta itemprop="name" content="<?php echo $apiTitle; ?> API Documentation Preview">
    <meta itemprop="description" content="<?php echo strip_tags($apiDescription); ?>">
    <meta itemprop="url" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    <!-- Ultra-Modern Navigation - Identical to other screens -->
    <nav class="navbar ultra-modern-nav fixed-top">
        <div class="container-fluid px-4">
            <div class="nav-brand-section">
                <a class="navbar-brand modern-brand" href="editor.php?file=<?php echo urlencode($filename); ?>">
                    <div class="brand-icon">
                        <i class="fas fa-arrow-left"></i>
                    </div>
                    <div class="brand-text">
                        <span class="brand-title">Back to Editor</span>
                        <span class="brand-subtitle">Continue Editing</span>
                    </div>
                </a>
                
                <div class="file-indicator">
                    <div class="file-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div class="file-info">
                        <span class="file-name">Preview: <?php echo htmlspecialchars(pathinfo($filename, PATHINFO_FILENAME)); ?></span>
                        <span class="file-type">Swagger Documentation</span>
                    </div>
                </div>
            </div>
            
            <div class="nav-actions">
                <!-- Theme Toggle -->
                <button class="modern-btn btn-theme" id="theme-toggle" data-tooltip="Toggle Theme">
                    <i class="fas fa-moon theme-icon"></i>
                </button>
                
                <!-- Fullscreen Toggle -->
                <button class="modern-btn btn-fullscreen" id="fullscreen-toggle" data-tooltip="Toggle Fullscreen">
                    <i class="fas fa-expand"></i>
                </button>
                
                <!-- Download API -->
                <a href="export.php?file=<?php echo urlencode($filename); ?>&format=json" 
                   class="modern-btn btn-download" data-tooltip="Download OpenAPI JSON">
                    <i class="fas fa-download"></i>
                </a>
            </div>
        </div>
    </nav>

    <!-- Preview Container -->
    <div class="preview-container">
        <!-- Loading State -->
        <div id="loading" class="loading-container">
            <div class="loading-animation"></div>
            <div class="loading-content">
                <h3>Loading Swagger Documentation</h3>
                <p>Preparing your API documentation preview...</p>
                <div class="loading-progress">
                    <div class="progress-bar" id="progress-bar"></div>
                </div>
            </div>
        </div>

        <!-- Swagger UI Container -->
        <div class="swagger-container" style="display: none;" itemscope itemtype="http://schema.org/SoftwareApplication">
            <meta itemprop="name" content="<?php echo $apiTitle; ?> API Documentation">
            <meta itemprop="applicationCategory" content="API Documentation">
            <meta itemprop="version" content="<?php echo $apiVersion; ?>">
            <meta itemprop="description" content="<?php echo strip_tags($apiDescription); ?>">
            
            <div id="swagger-ui" itemscope itemtype="http://schema.org/WebApplication">
                <meta itemprop="name" content="Swagger UI">
                <meta itemprop="applicationCategory" content="API Documentation Viewer">
                <meta itemprop="operatingSystem" content="Web Browser">
            </div>
        </div>
    </div>

    <!-- Performance Monitoring -->
    <script>
        // Monitor page performance for SEO
        window.addEventListener('load', function() {
            setTimeout(function() {
                const perfData = performance.getEntriesByType('navigation')[0];
                if (perfData) {
                    console.log('Page Load Performance:', {
                        domContentLoaded: perfData.domContentLoadedEventEnd - perfData.domContentLoadedEventStart,
                        loadComplete: perfData.loadEventEnd - perfData.loadEventStart
                    });
                }
            }, 0);
        });
    </script>

    <script src="assets/dist/js/vendors/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/swagger-ui-dist@4.15.5/swagger-ui-bundle.js"></script>
    <script src="https://unpkg.com/swagger-ui-dist@4.15.5/swagger-ui-standalone-preset.js"></script>
    
    <script>
        // Loading progress animation
        let progress = 0;
        const progressBar = document.getElementById('progress-bar');
        const progressInterval = setInterval(() => {
            progress += Math.random() * 15;
            if (progress > 90) progress = 90;
            progressBar.style.width = progress + '%';
        }, 100);

        window.onload = function() {
            const spec = <?php echo $jsonData; ?>;
            
            // Complete loading animation
            setTimeout(() => {
                progress = 100;
                progressBar.style.width = '100%';
                clearInterval(progressInterval);
                
                setTimeout(() => {
                    document.getElementById('loading').style.display = 'none';
                    document.querySelector('.swagger-container').style.display = 'block';
                }, 500);
            }, 1000);
            
            SwaggerUIBundle({
                url: '',
                spec: spec,
                dom_id: '#swagger-ui',
                deepLinking: true,
                displayOperationId: false,
                displayRequestDuration: false,
                docExpansion: 'list',
                filter: true,
                showExtensions: true,
                showCommonExtensions: true,
                tryItOutEnabled: true,
                requestInterceptor: (request) => {
                    return request;
                },
                responseInterceptor: (response) => {
                    return response;
                },
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],
                layout: "StandaloneLayout",
                configUrl: null,
                oauth2RedirectUrl: null,
                onComplete: function() {
                    console.log('Swagger UI loaded successfully');
                    
                    // Remove topbar completely
                    setTimeout(() => {
                        const topbar = document.querySelector('.swagger-ui .topbar');
                        if (topbar) {
                            topbar.remove();
                        }
                        
                        const topbarWrapper = document.querySelector('.swagger-ui .topbar-wrapper');
                        if (topbarWrapper) {
                            topbarWrapper.remove();
                        }
                        
                        // Apply custom styling
                        const swaggerContainer = document.querySelector('.swagger-ui');
                        if (swaggerContainer) {
                            swaggerContainer.style.fontFamily = 'Inter, -apple-system, BlinkMacSystemFont, sans-serif';
                        }
                        
                        // Fix method badges visibility
                        const methodBadges = document.querySelectorAll('.swagger-ui .opblock-summary-method');
                        methodBadges.forEach(badge => {
                            badge.style.display = 'inline-block';
                            badge.style.fontWeight = '700';
                            badge.style.textTransform = 'uppercase';
                        });
                        
                        // Additional method badge fix
                        setTimeout(() => {
                            const methodBadges = document.querySelectorAll('.swagger-ui .opblock-summary-method');
                            console.log(`Found ${methodBadges.length} method badges`);
                            
                            methodBadges.forEach((badge, index) => {
                                console.log(`Badge ${index}:`, badge.textContent, badge.style);
                                badge.style.setProperty('display', 'inline-block', 'important');
                                badge.style.setProperty('visibility', 'visible', 'important');
                                badge.style.setProperty('color', 'white', 'important');
                                badge.style.setProperty('font-weight', '700', 'important');
                                badge.style.setProperty('text-transform', 'uppercase', 'important');
                            });
                        }, 1000);
                        
                        console.log('Custom styling applied successfully');
                    }, 500);
                },
                onFailure: function(err) {
                    clearInterval(progressInterval);
                    console.error('Swagger UI failed to load:', err);
                    document.getElementById('loading').innerHTML = 
                        `<div class="status-message message-error">
                            <div class="message-content">
                                <div class="message-icon">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="message-text">
                                    <h3>Error Loading Documentation</h3>
                                    <p>${err.message || 'Failed to load API documentation'}</p>
                                </div>
                            </div>
                        </div>`;
                }
            });
        };

        // Fullscreen toggle
        document.addEventListener('DOMContentLoaded', function() {
            const fullscreenBtn = document.getElementById('fullscreen-toggle');
            if (fullscreenBtn) {
                fullscreenBtn.addEventListener('click', function() {
                    if (!document.fullscreenElement) {
                        document.documentElement.requestFullscreen();
                        this.innerHTML = '<i class="fas fa-compress"></i>';
                    } else {
                        document.exitFullscreen();
                        this.innerHTML = '<i class="fas fa-expand"></i>';
                    }
                });
            }

            // Initialize tooltips
            document.querySelectorAll('[data-tooltip]').forEach(el => {
                el.addEventListener('mouseenter', showTooltip);
                el.addEventListener('mouseleave', hideTooltip);
            });
        });

        function showTooltip(e) {
            const tooltip = document.createElement('div');
            tooltip.className = 'ultra-modern-tooltip';
            tooltip.textContent = e.target.getAttribute('data-tooltip');
            document.body.appendChild(tooltip);
            
            const rect = e.target.getBoundingClientRect();
            tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
            tooltip.style.top = rect.bottom + 8 + 'px';
            
            setTimeout(() => tooltip.classList.add('show'), 10);
            e.target._tooltip = tooltip;
        }
        
        function hideTooltip(e) {
            if (e.target._tooltip) {
                e.target._tooltip.classList.remove('show');
                setTimeout(() => {
                    if (e.target._tooltip && e.target._tooltip.parentNode) {
                        e.target._tooltip.parentNode.removeChild(e.target._tooltip);
                    }
                }, 200);
            }
        }
        
        // SEO Enhancement: Send page view analytics (placeholder for future implementation)
        if (typeof gtag !== 'undefined') {
            gtag('event', 'page_view', {
                page_title: '<?php echo $apiTitle; ?> API Documentation Preview',
                page_location: window.location.href
            });
        }
    </script>
    
    <!-- Schema.org Organization Data -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "OpenAPI Editor",
      "description": "Professional API documentation editor and viewer",
      "url": "<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']; ?>/",
      "sameAs": []
    }
    </script>
</body>
</html>