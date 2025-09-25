# 🔧 PROJECT_CONTEXT.md - Contexto Técnico Completo

## 📋 **INFORMAÇÕES ESSENCIAIS DO PROJETO**

### 🎯 **Resumo do Projeto**
**Nome:** OpenAPI Editor  
**Versão:** 1.0.0  
**Tipo:** Sistema web para edição e visualização de documentações OpenAPI/Swagger  
**Demo:** https://openapi.myrotech.com  
**Autor:** Myrotech (suporte@myrotech.com)  

### 🏗️ **ARQUITETURA DO SISTEMA**

#### **📁 Estrutura de Arquivos Principais:**
```
openapi-editor/
├── 📄 index.php          # Página inicial com SEO completo
├── 📝 editor.php         # Interface de edição OpenAPI
├── 👁️ preview.php        # Preview Swagger UI integrado
├── ⚙️ config.php         # Configurações (dados genéricos)
├── 🏭 config.production.php # Configurações produção (Myrotech)
├── 🛠️ gulpfile.js        # Build system (SCSS → CSS)
├── 📦 package.json       # Dependências Node.js
└── 📚 docs/              # Documentação organizada
```

#### **🔄 Fluxo de Funcionamento:**
1. **index.php** → Landing page com SEO enterprise
2. **editor.php** → Edição de arquivos OpenAPI (YAML/JSON)
3. **preview.php** → Visualização com Swagger UI
4. **Assets compilados** → SCSS → CSS via Gulp

### 💻 **STACK TECNOLÓGICO DETALHADO**

#### **Backend (PHP 8.0+):**
- **Linguagem:** PHP 8.0+ (orientado a objetos)
- **Servidor:** Apache/Nginx (compatível com ambos)
- **Configuração:** .htaccess para URLs amigáveis e segurança
- **Estrutura:** MVC simples, sem frameworks externos

#### **Frontend:**
- **HTML5:** Estrutura semântica com microdata
- **CSS3/SCSS:** Sistema de build com Gulp
- **JavaScript ES6+:** Vanilla JS, sem frameworks
- **Libraries:** 
  - **Swagger UI** (preview de APIs)
  - **CodeMirror** (editor com syntax highlighting)
  - **Schema.org** (structured data)

#### **Build System:**
- **Gulp:** Compilação SCSS → CSS
- **npm:** Gerenciamento de dependências
- **SCSS:** Pré-processador CSS com variáveis e mixins

### 🔍 **SEO ENTERPRISE IMPLEMENTADO**

#### **Schema.org Structured Data:**
- **SoftwareApplication** (index.php)
- **WebApplication** (editor.php)  
- **APIReference** (preview.php)
- **TechnicalArticle** (documentação)
- **BreadcrumbList** (navegação)
- **Organization** (dados da empresa)

#### **Meta Tags Dinâmicas:**
- Títulos contextuais baseados na API
- Descrições automáticas da OpenAPI spec
- Keywords dinâmicas por projeto
- Canonical URLs apropriadas

#### **Social Media:**
- **Open Graph** completo (Facebook/LinkedIn)
- **Twitter Cards** otimizados
- Imagens personalizadas por contexto

### 🛡️ **SEGURANÇA IMPLEMENTADA**

#### **Configurações .htaccess:**
```apache
# Headers de segurança
Header always set X-Content-Type-Options nosniff
Header always set X-Frame-Options DENY
Header always set X-XSS-Protection "1; mode=block"
Header always set Strict-Transport-Security "max-age=31536000"

# Proteção de arquivos sensíveis
<FilesMatch "\.(env|log|md)$">
    Require all denied
</FilesMatch>
```

#### **Sanitização PHP:**
- **htmlspecialchars()** para outputs
- **filter_var()** para validações
- **prepared statements** (se usar DB)
- **CSRF tokens** em formulários

### 📂 **CONFIGURAÇÕES AMBIENTE**

#### **Desenvolvimento (config.php):**
```php
define('API_BASE_URL', 'https://api.exemplo.com');
define('COMPANY_NAME', 'Sua Empresa Ltda');
define('COMPANY_URL', 'https://seudominio.com');
define('ENVIRONMENT', 'development');
```

#### **Produção (config.production.php):**
```php
define('API_BASE_URL', 'https://api.myrotech.com');
define('COMPANY_NAME', 'Myrotech');
define('COMPANY_URL', 'https://myrotech.com');
define('ENVIRONMENT', 'production');
```

### 🚀 **DEPLOY E INFRAESTRUTURA**

#### **Requisitos do Servidor:**
- **PHP:** 8.0+ com extensões (json, mbstring, curl)
- **Servidor:** Apache 2.4+ ou Nginx 1.18+
- **SSL:** Certificado válido (Let's Encrypt recomendado)
- **Storage:** 50MB+ para arquivos e cache

#### **Processo de Deploy:**
1. Upload via FTP/cPanel File Manager
2. Configurar config.production.php
3. Configurar .htaccess (simplificado se necessário)
4. Testar URLs: /, /editor.php, /preview.php
5. Verificar headers de segurança

#### **URLs de Produção:**
- **Principal:** https://openapi.myrotech.com
- **Editor:** https://openapi.myrotech.com/editor.php
- **Preview:** https://openapi.myrotech.com/preview.php

### 🔧 **COMANDOS DE BUILD**

#### **Desenvolvimento:**
```bash
# Instalar dependências
npm install

# Build SCSS → CSS
gulp build

# Watch para desenvolvimento
gulp watch

# Build para produção
gulp build --production
```

#### **Estrutura de Assets:**
```
assets/
├── scss/           # Arquivos fonte SCSS
│   ├── main.scss   # Arquivo principal
│   ├── _vars.scss  # Variáveis
│   └── _mixins.scss # Mixins
├── css/            # CSS compilado
├── js/             # JavaScript
└── img/            # Imagens
```

### 📊 **FUNCIONALIDADES TÉCNICAS**

#### **Editor OpenAPI:**
- **CodeMirror** com mode YAML/JSON
- **Validação** em tempo real da spec
- **Auto-complete** para schemas OpenAPI
- **Syntax highlighting** contextual
- **Error detection** visual

#### **Preview Swagger UI:**
- **Swagger UI 4.15+** integrado
- **Responsive design** completo
- **Custom themes** suportados  
- **Try-it-out** habilitado
- **Download spec** em múltiplos formatos

#### **Performance:**
- **Lazy loading** de componentes pesados
- **CSS minificado** em produção
- **JavaScript otimizado** 
- **Caching** via .htaccess headers
- **Compressão gzip** habilitada

### 🐛 **DEBUGGING E LOGS**

#### **Ambientes:**
- **Desenvolvimento:** Errors visíveis, debug habilitado
- **Produção:** Errors em logs, debug desabilitado

#### **Logs Importantes:**
- **PHP errors:** /var/log/php_errors.log
- **Apache/Nginx:** access.log e error.log
- **Application:** logs/ (se implementado)

### 📝 **PADRÕES DE CÓDIGO**

#### **PHP:**
- **PSR-4** para autoloading (se usar)
- **camelCase** para métodos
- **PascalCase** para classes
- **UPPER_CASE** para constantes

#### **JavaScript:**
- **ES6+** syntax
- **camelCase** para variáveis
- **PascalCase** para construtores
- **Strict mode** sempre habilitado

#### **CSS/SCSS:**
- **BEM methodology** para classes
- **Mobile-first** approach
- **Variáveis SCSS** para cores/tamanhos
- **Mixins** para responsividade

### 🤖 **INTEGRAÇÃO COM AI**

#### **Para Copilot/ChatGPT:**
- **Contexto técnico:** Esta pasta .github contém informações completas
- **Estrutura clara:** Arquivos organizados em docs/
- **Padrões definidos:** Seguir convenções estabelecidas
- **SEO enterprise:** Sistema já implementado e testado

#### **Comandos Úteis AI:**
```bash
# Ver estrutura completa
find . -name "*.php" -o -name "*.js" -o -name "*.scss" | head -20

# Ver configurações
cat config.php config.production.php

# Ver assets compilados  
ls -la assets/css/ assets/js/

# Verificar documentação
ls -la docs/
```

---

## 🎯 **RESUMO PARA AI ASSISTANTS**

Este é um **sistema OpenAPI Editor completo e profissional** com:
- ✅ **3 páginas principais** (index, editor, preview)  
- ✅ **SEO enterprise** com Schema.org completo
- ✅ **Build system** Gulp para SCSS
- ✅ **Segurança robusta** implementada
- ✅ **Documentação organizada** em docs/
- ✅ **Deploy em produção** funcionando
- ✅ **Código limpo** e bem estruturado

**Para desenvolvimento:** Use gulpfile.js, edite SCSS, teste localmente  
**Para deploy:** Use config.production.php, configure .htaccess  
**Para documentação:** Tudo está organizado em docs/

**Demo live:** https://openapi.myrotech.com