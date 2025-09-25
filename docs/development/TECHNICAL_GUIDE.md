# 🔧 Guia Técnico - OpenAPI Editor

## 📋 **Visão Geral Técnica**

### **Stack Tecnológico**
- **Frontend:** Bootstrap 5.3, SCSS, JavaScript ES6+
- **Backend:** PHP 7.4+, Sistema de sessões
- **Build:** Gulp.js, Sass, Autoprefixer, Minificação
- **SEO:** Schema.org, Open Graph, Twitter Cards, Structured Data
- **Deployment:** Apache/Nginx, HTTPS Ready

### **Arquitetura do Projeto**
```
openapi-editor/
├── index.php              # Página principal (landing)
├── editor.php             # Interface de edição
├── preview.php            # Preview Swagger UI
├── config.php             # Configurações base
├── assets/
│   ├── scss/              # Estilos fonte (Sass)
│   ├── js/                # JavaScript fonte
│   └── dist/              # Assets compilados
├── components/            # Componentes PHP
├── files/                 # Arquivos OpenAPI salvos
└── docs/                  # Documentação e imagens
```

---

## 🔍 **Sistema SEO Implementado**

### **Schema.org Structured Data**

#### **index.php - SoftwareApplication**
```json
{
  "@type": "SoftwareApplication",
  "name": "OpenAPI Editor",
  "applicationCategory": "DeveloperApplication",
  "operatingSystem": ["Web Browser"],
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "USD"
  }
}
```

#### **editor.php - WebApplication + Breadcrumbs**
```json
{
  "@type": "WebApplication",
  "name": "OpenAPI Editor - {ProjectName}",
  "applicationCategory": "API Development Tool",
  "browserRequirements": "Requires JavaScript"
}
```

#### **preview.php - APIReference + TechnicalArticle**
```json
{
  "@type": "APIReference",
  "name": "{API Title}",
  "version": "{API Version}",
  "programmingLanguage": ["JavaScript", "PHP", "Python"]
}
```

### **Meta Tags Dinâmicas**

#### **Estrutura Base**
```php
// Título dinâmico baseado no contexto
<title><?php echo $dynamicTitle; ?> - OpenAPI Editor</title>

// Descrição baseada no conteúdo da API
<meta name="description" content="<?php echo strip_tags($apiDescription); ?>">

// Keywords contextuais
<meta name="keywords" content="<?php echo $projectName; ?>, API, Documentation, REST">
```

#### **Open Graph Personalizado**
```php
<meta property="og:title" content="<?php echo $apiTitle; ?> - API Documentation">
<meta property="og:description" content="<?php echo $apiDescription; ?>">
<meta property="og:url" content="<?php echo $currentUrl; ?>">
<meta property="og:image" content="<?php echo $contextualImage; ?>">
```

### **Performance SEO**
```html
<!-- Preload crítico -->
<link rel="preload" href="assets/dist/css/main.min.css" as="style">
<link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter" as="style">

<!-- Preconnect para CDNs -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://unpkg.com" crossorigin>
```

---

## 🎨 **Sistema de Build**

### **Gulp Tasks Principais**

#### **Development**
```bash
gulp dev          # Build desenvolvimento + watch
gulp watch        # Observar mudanças
gulp serve        # BrowserSync server
```

#### **Production**
```bash
gulp build        # Build produção minificado
gulp clean        # Limpar dist/
gulp deploy       # Build + otimizações
```

### **SCSS Architecture**
```
assets/scss/
├── abstracts/
│   ├── _variables.scss    # Variáveis globais
│   ├── _mixins.scss       # Mixins úteis
│   └── _functions.scss    # Funções Sass
├── base/
│   ├── _reset.scss        # CSS Reset
│   ├── _typography.scss   # Tipografia base
│   └── _helpers.scss      # Classes auxiliares
├── components/
│   ├── _buttons.scss      # Botões
│   ├── _cards.scss        # Cards modernos
│   ├── _navbar.scss       # Navegação
│   └── _forms.scss        # Formulários
├── layout/
│   ├── _header.scss       # Cabeçalho
│   ├── _main.scss         # Conteúdo principal
│   └── _footer.scss       # Rodapé
├── pages/
│   ├── _home.scss         # Página inicial
│   ├── _editor.scss       # Editor
│   └── _preview.scss      # Preview
├── themes/
│   ├── _light.scss        # Tema claro
│   └── _dark.scss         # Tema escuro
└── main.scss              # Import principal
```

---

## ⚙️ **Configuração por Ambiente**

### **Desenvolvimento (config.php)**
```php
define('APP_ENV', 'development');
define('APP_DEBUG', true);
define('SHOW_FILE_LIST', true);
define('ALLOW_FILE_DELETION', true);
```

### **Produção (config.local.php)**
```php
define('APP_ENV', 'production');
define('APP_DEBUG', false);
define('SHOW_FILE_LIST', false);
define('ALLOW_FILE_DELETION', false);
```

### **Segurança**
```php
// Headers de segurança
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
```

---

## 🚀 **Deploy Production**

### **Checklist Pré-Deploy**
- [ ] `npm run build` executado
- [ ] Assets minificados em `dist/`
- [ ] `config.local.php` configurado
- [ ] Permissões de pasta `files/` e `logs/`
- [ ] HTTPS configurado
- [ ] SEO meta tags testadas

### **Configuração Apache (.htaccess)**
```apache
# Habilitar compressão
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/javascript
</IfModule>

# Cache de assets
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType text/css "access plus 1 year"
    ExpiresByType application/javascript "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
</IfModule>

# Proteção de arquivos
<Files "config.local.php">
    Deny from all
</Files>
```

### **SSL/HTTPS**
```nginx
# Nginx SSL Configuration
server {
    listen 443 ssl http2;
    server_name openapi.myrotech.com;
    
    ssl_certificate /path/to/cert.pem;
    ssl_certificate_key /path/to/private.key;
    
    # Security headers
    add_header X-Frame-Options DENY always;
    add_header X-Content-Type-Options nosniff always;
    add_header X-XSS-Protection "1; mode=block" always;
}
```

---

## 🔍 **Validação SEO**

### **Ferramentas de Teste**
1. **Google Search Console** - Structured data validation
2. **Schema Markup Validator** - Schema.org testing
3. **Facebook Debugger** - Open Graph validation
4. **Twitter Card Validator** - Twitter Cards testing
5. **PageSpeed Insights** - Performance SEO

### **Métricas Importantes**
- **Core Web Vitals:** LCP < 2.5s, FID < 100ms, CLS < 0.1
- **SEO Score:** 90+ (Lighthouse)
- **Structured Data:** 100% válido
- **Mobile Usability:** Responsivo completo

---

## 🛠️ **Manutenção & Monitoramento**

### **Logs Estruturados**
```php
// Sistema de logs por ambiente
if (APP_DEBUG) {
    error_log("Debug: " . json_encode($debugData));
} else {
    error_log("Error: " . $errorMessage, 3, "logs/app.log");
}
```

### **Performance Monitoring**
```javascript
// Performance API monitoring
window.addEventListener('load', function() {
    const perfData = performance.getEntriesByType('navigation')[0];
    console.log('Page Load Time:', perfData.loadEventEnd - perfData.loadEventStart);
});
```

### **Backup & Versionamento**
- **Files:** Backup automático da pasta `files/`
- **Database:** Não aplicável (file-based)
- **Config:** Versionamento de configurações
- **Assets:** Build reproducível via package.json

---

## 📞 **Suporte Técnico**

### **Problemas Comuns**
1. **Build falha:** Verificar Node.js versão 14+
2. **SEO não funciona:** Verificar PHP sessions ativas
3. **Assets não carregam:** Verificar paths em produção
4. **Upload falha:** Verificar permissões pasta `files/`

### **Debug Mode**
```php
// Habilitar debug completo
define('APP_DEBUG', true);
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

**Plataforma:** https://openapi.myrotech.com
**Versão Atual:** 2.0.0 - SEO Enterprise Edition
**Última Atualização:** 25/09/2025