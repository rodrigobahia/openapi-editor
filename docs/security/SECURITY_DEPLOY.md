# 🔒 Guia de Segurança - Upload cPanel

## ✅ **Checklist Pré-Upload**

### **1. Arquivos que NÃO devem ser enviados:**
```
❌ node_modules/              # Dependências Node.js
❌ .git/                      # Controle de versão Git
❌ .github/                   # Templates GitHub
❌ *.log                      # Arquivos de log
❌ package*.json              # Configurações Node
❌ gulpfile.js                # Build configuration
❌ .env*                      # Variáveis ambiente
❌ *.md (exceto README.md)    # Documentação sensível
❌ TECHNICAL_GUIDE.md         # Guia técnico interno
❌ BACKLOG.md                 # Roadmap interno
❌ IMPROVEMENTS.md            # Melhorias internas
```

### **2. Arquivos essenciais para upload:**
```
✅ index.php                 # Página principal
✅ editor.php                # Editor OpenAPI
✅ preview.php               # Preview Swagger
✅ export.php                # Exportação
✅ config.php                # Config base (sem dados sensíveis)
✅ assets/dist/              # Assets compilados apenas
✅ components/               # Componentes PHP
✅ files/                    # Pasta arquivos OpenAPI
✅ logs/                     # Pasta logs (vazia)
✅ docs/images/              # Imagens do projeto
✅ .htaccess                 # Segurança principal
✅ files/.htaccess           # Segurança pasta files
✅ logs/.htaccess            # Segurança pasta logs
✅ README.md                 # Documentação pública
✅ CHANGELOG.md              # Histórico de versões
✅ LICENSE                   # Licença
```

## 🛡️ **Configurações de Segurança Implementadas**

### **Arquivo .htaccess Principal:**
- ✅ **Bloqueia arquivos sensíveis** (config, env, logs)
- ✅ **Headers de segurança** (XSS, CSRF, Clickjacking)
- ✅ **Compressão Gzip** para performance
- ✅ **Cache otimizado** para assets
- ✅ **Bloqueia user agents maliciosos**
- ✅ **Proteção contra hot linking**
- ✅ **Força HTTPS** (opcional)

### **Proteções Específicas:**

#### **Pasta /files/**
- ✅ Permite apenas `.json`, `.yaml`, `.yml`
- ✅ Bloqueia execução de scripts PHP/JS
- ✅ Sem listagem de diretórios

#### **Pasta /logs/**
- ✅ Acesso completamente negado
- ✅ Logs acessíveis apenas via PHP

#### **Assets /dist/**
- ✅ Cache otimizado (1 ano CSS/JS)
- ✅ Compressão automática
- ✅ Headers de segurança

## 🚀 **Passos para Upload Seguro**

### **1. Preparação Local:**
```bash
# Build final dos assets
npm run build

# Verificar se dist/ foi gerado
ls assets/dist/

# Remover arquivos desnecessários (opcional)
rm -rf node_modules/ .git/ .github/
```

### **2. Estrutura Final para Upload:**
```
openapi-editor/
├── .htaccess                 ✅ Upload
├── index.php                 ✅ Upload  
├── editor.php                ✅ Upload
├── preview.php               ✅ Upload
├── export.php                ✅ Upload
├── config.php                ✅ Upload (sem dados sensíveis)
├── README.md                 ✅ Upload
├── CHANGELOG.md              ✅ Upload
├── LICENSE                   ✅ Upload
├── assets/
│   └── dist/                 ✅ Upload (apenas esta pasta)
├── components/               ✅ Upload
├── files/
│   └── .htaccess             ✅ Upload
├── logs/
│   └── .htaccess             ✅ Upload  
└── docs/
    └── images/               ✅ Upload
```

### **3. No cPanel:**

#### **A. Acesso via File Manager:**
1. 🌐 Login no cPanel
2. 📁 File Manager → public_html
3. 📤 Upload/Extract dos arquivos
4. ✅ Verificar permissões (755 para pastas, 644 para arquivos)

#### **B. Permissões Importantes:**
```bash
# Pasta files deve ser gravável pelo PHP
chmod 755 files/

# Pasta logs deve ser gravável pelo PHP  
chmod 755 logs/

# Assets devem ser legíveis
chmod -R 644 assets/dist/

# Arquivos PHP executáveis
chmod 644 *.php
```

### **4. Configuração Pós-Upload:**

#### **Criar config.local.php:**
```php
<?php
// config.local.php - Configurações de produção
define('APP_ENV', 'production');
define('APP_DEBUG', false);
define('SHOW_FILE_LIST', false);        // Segurança
define('ALLOW_FILE_DELETION', false);   // Segurança
define('SHOW_FILE_UPLOAD', true);
define('SHOW_NEW_FILE', true);

// URLs de produção
define('BASE_URL', 'https://openapi.myrotech.com');

// Logs apenas de erro em produção
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/error.log');
ini_set('display_errors', 0);
?>
```

#### **Testar Funcionalidades:**
1. ✅ Acesso à página principal
2. ✅ Upload de arquivo OpenAPI
3. ✅ Editor funcionando
4. ✅ Preview Swagger carregando
5. ✅ Export funcionando
6. ✅ Temas claro/escuro
7. ✅ Segurança dos arquivos protegidos

## 🔍 **Verificações de Segurança Pós-Deploy**

### **Testes de Acesso Negado:**
```bash
# Estes URLs devem retornar 403 Forbidden:
https://openapi.myrotech.com/config.php
https://openapi.myrotech.com/logs/
https://openapi.myrotech.com/package.json
https://openapi.myrotech.com/gulpfile.js
https://openapi.myrotech.com/TECHNICAL_GUIDE.md
https://openapi.myrotech.com/files/exemplo.php  # (se tentar upload)
```

### **Testes de Funcionalidade:**
```bash
# Estes devem funcionar normalmente:
https://openapi.myrotech.com/
https://openapi.myrotech.com/editor.php
https://openapi.myrotech.com/preview.php
https://openapi.myrotech.com/assets/dist/css/main.min.css
https://openapi.myrotech.com/assets/dist/js/app.min.js
```

## 📊 **Monitoramento Contínuo**

### **Logs para Monitorar:**
1. 📊 **Error Log:** `logs/error.log`
2. 🔒 **Access Log:** cPanel → Raw Access Logs
3. 🛡️ **Security Log:** cPanel → Security

### **Alertas Importantes:**
- 🚨 Tentativas de acesso a arquivos bloqueados
- 🚨 Uploads de arquivos não permitidos
- 🚨 User agents suspeitos
- 🚨 Múltiplas tentativas 403/404

## ✅ **Checklist Final**

Antes de considerar o deploy completo:

- [ ] ✅ Upload realizado apenas com arquivos permitidos
- [ ] ✅ `.htaccess` funcionando (teste 403 em config.php)
- [ ] ✅ `config.local.php` criado com configurações de produção
- [ ] ✅ Permissões de arquivo corretas
- [ ] ✅ HTTPS ativo e funcionando
- [ ] ✅ Funcionalidades principais testadas
- [ ] ✅ SEO meta tags funcionando
- [ ] ✅ Performance otimizada
- [ ] ✅ Logs configurados
- [ ] ✅ Backup da versão atual

**🌐 URL Final:** https://openapi.myrotech.com
**🔒 Status:** Enterprise Security Ready
**📅 Deploy Date:** 25/09/2025