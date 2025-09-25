# 📋 CODING_STANDARDS.md

## 🎯 **PADRÕES DE CÓDIGO DO PROJETO**

### 📝 **PHP Standards**

#### **Estrutura de Arquivos PHP:**
```php
<?php
/**
 * Descrição do arquivo
 * 
 * @author Myrotech
 * @version 1.0.0
 */

// Configurações e includes
require_once 'config.php';

// Funções principais
function generateSeoMeta($title, $description) {
    return [
        'title' => htmlspecialchars($title),
        'description' => htmlspecialchars($description)
    ];
}

// HTML com structured data
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <!-- SEO Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Schema.org Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "SoftwareApplication"
    }
    </script>
</head>
```

#### **Convenções PHP:**
- **Constantes:** `UPPER_CASE` (ex: `API_BASE_URL`)
- **Funções:** `camelCase` (ex: `getSeoTitle()`)
- **Classes:** `PascalCase` (ex: `ApiValidator`)
- **Variáveis:** `$camelCase` (ex: `$apiEndpoint`)

#### **Sanitização Obrigatória:**
```php
// SEMPRE sanitize outputs
echo htmlspecialchars($userInput, ENT_QUOTES, 'UTF-8');

// Para URLs
$safeUrl = filter_var($url, FILTER_SANITIZE_URL);

// Para emails
$email = filter_var($email, FILTER_VALIDATE_EMAIL);
```

### 🎨 **SCSS/CSS Standards**

#### **Estrutura SCSS:**
```scss
// 1. Variáveis (_vars.scss)
$primary-color: #007bff;
$secondary-color: #6c757d;
$border-radius: 0.375rem;

// 2. Mixins (_mixins.scss)
@mixin responsive($breakpoint) {
    @media (max-width: $breakpoint) {
        @content;
    }
}

// 3. Components (BEM methodology)
.editor {
    display: flex;
    
    &__container {
        flex: 1;
        padding: 1rem;
    }
    
    &__button {
        background: $primary-color;
        
        &--active {
            background: darken($primary-color, 10%);
        }
    }
}
```

#### **Responsive Design:**
```scss
// Mobile-first approach
.component {
    // Mobile styles (base)
    font-size: 14px;
    
    @include responsive(768px) {
        // Tablet
        font-size: 16px;
    }
    
    @include responsive(1024px) {
        // Desktop
        font-size: 18px;
    }
}
```

### 🔧 **JavaScript Standards**

#### **ES6+ Syntax:**
```javascript
// Use const/let, não var
const API_URL = 'https://api.example.com';
let currentEditor = null;

// Arrow functions quando apropriado
const initEditor = () => {
    return new CodeMirror(document.getElementById('editor'), {
        mode: 'yaml',
        theme: 'default',
        lineNumbers: true
    });
};

// Template literals para strings
const generateApiUrl = (endpoint) => {
    return `${API_URL}/${endpoint}`;
};

// Destructuring quando útil
const {title, description} = getApiInfo();
```

### 📊 **SEO Structured Data Patterns**

#### **Schema.org Implementation:**
```php
// SoftwareApplication (index.php)
$schemaData = [
    "@context" => "https://schema.org",
    "@type" => "SoftwareApplication",
    "name" => "OpenAPI Editor",
    "applicationCategory" => "DeveloperApplication",
    "operatingSystem" => "Web Browser",
    "offers" => [
        "@type" => "Offer",
        "price" => "0",
        "priceCurrency" => "USD"
    ]
];

// WebApplication (editor.php)  
$editorSchema = [
    "@context" => "https://schema.org",
    "@type" => "WebApplication",
    "name" => "OpenAPI Editor - Editor",
    "url" => getCurrentUrl(),
    "applicationCategory" => "WebApplication"
];
```

#### **Meta Tags Pattern:**
```php
function generateMetaTags($page, $project = null) {
    $baseTitle = "OpenAPI Editor";
    $baseDomain = "https://openapi.myrotech.com";
    
    switch($page) {
        case 'index':
            $title = $baseTitle . " - Editor Profissional de APIs";
            $description = "Sistema completo para edição e visualização de documentações OpenAPI/Swagger";
            break;
            
        case 'editor':
            $title = ($project ? $project . " - " : "") . "Editor OpenAPI";
            $description = "Edite suas especificações OpenAPI com syntax highlighting e validação em tempo real";
            break;
    }
    
    return [
        'title' => $title,
        'description' => $description,
        'canonical' => $baseDomain . $_SERVER['REQUEST_URI']
    ];
}
```

### 🛡️ **Security Patterns**

#### **Input Validation:**
```php
// Validar e sanitizar inputs
function validateApiEndpoint($input) {
    // Remove espaços
    $input = trim($input);
    
    // Valida URL
    if (!filter_var($input, FILTER_VALIDATE_URL)) {
        throw new InvalidArgumentException('URL inválida');
    }
    
    // Sanitiza para output
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}
```

#### **CSRF Protection Pattern:**
```php
// Gerar token CSRF
function generateCsrfToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Validar token
function validateCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && 
           hash_equals($_SESSION['csrf_token'], $token);
}
```

### 📁 **File Organization**

#### **Assets Structure:**
```
assets/
├── scss/
│   ├── main.scss           # Import de todos os outros
│   ├── _vars.scss          # Variáveis globais
│   ├── _mixins.scss        # Mixins reutilizáveis
│   ├── _base.scss          # Reset e base styles
│   ├── _layout.scss        # Layout geral
│   ├── _components.scss    # Componentes
│   └── _utilities.scss     # Classes utilitárias
├── css/
│   └── main.css           # Arquivo compilado
├── js/
│   ├── main.js            # JavaScript principal
│   ├── editor.js          # Específico do editor
│   └── utils.js           # Funções utilitárias
└── img/
    ├── icons/             # Ícones SVG
    └── screenshots/       # Prints do sistema
```

### 🔄 **Build Process**

#### **Gulpfile.js Pattern:**
```javascript
const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const cleanCSS = require('gulp-clean-css');
const sourcemaps = require('gulp-sourcemaps');

// Compilar SCSS
gulp.task('styles', function() {
    return gulp.src('assets/scss/main.scss')
        .pipe(sourcemaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(cleanCSS())
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('assets/css'));
});

// Watch para desenvolvimento
gulp.task('watch', function() {
    gulp.watch('assets/scss/**/*.scss', gulp.series('styles'));
});
```

### 📋 **Documentation Standards**

#### **README Pattern:**
```markdown
# Título do Projeto

Descrição concisa

## Features
- Feature 1
- Feature 2

## Quick Start
Código de exemplo

## Documentation
Links organizados

## Author
Informações do autor
```

#### **Code Comments:**
```php
/**
 * Gera meta tags SEO baseadas no contexto da página
 * 
 * @param string $page Tipo de página (index, editor, preview)
 * @param string|null $project Nome do projeto OpenAPI (opcional)
 * @return array Array com title, description e canonical URL
 * 
 * @example
 * $meta = generateMetaTags('editor', 'My API Project');
 * echo $meta['title']; // "My API Project - Editor OpenAPI"
 */
function generateMetaTags($page, $project = null) {
    // Implementation...
}
```

---

## ✅ **CHECKLIST PARA NOVOS CÓDIGOS**

### **📝 PHP:**
- [ ] Todas as saídas sanitizadas com `htmlspecialchars()`
- [ ] Constantes em UPPER_CASE
- [ ] Funções documentadas com PHPDoc
- [ ] Validação de inputs implementada

### **🎨 CSS/SCSS:**
- [ ] Usando variáveis SCSS para cores/tamanhos
- [ ] Seguindo metodologia BEM para classes
- [ ] Mobile-first responsive design
- [ ] Compilação via Gulp funcionando

### **🔧 JavaScript:**
- [ ] ES6+ syntax utilizada
- [ ] Strict mode habilitado
- [ ] Tratamento de erros implementado
- [ ] Código modular e reutilizável

### **🔍 SEO:**
- [ ] Schema.org structured data adicionado
- [ ] Meta tags contextuais implementadas
- [ ] Open Graph/Twitter Cards configurados
- [ ] URLs canônicas definidas

---

**🎯 Mantenha sempre a consistência com os padrões existentes!**