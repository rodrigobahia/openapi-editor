# 🚀 Guia de Dep# Clone o repositório
git clone https://github.com/rodrigobahia/openapi-editor.git
cd openapi-editor - OpenAPI Editor

Este guia ajuda você a fazer deploy do OpenAPI Editor em diferentes ambientes de forma segura.

## 📋 Pré-requisitos

- **Servidor Web**: Apache ou Nginx
- **PHP**: 7.4 ou superior
- **Node.js**: 14+ (apenas para build)
- **Domínio/Subdomínio**: Para ambiente de produção

## 🔧 Configuração por Ambiente

### 🏠 Desenvolvimento Local

```bash
# 1. Clone o repositório
git clone https://github.com/seuusuario/openapi-editor.git
cd openapi-editor

# 2. Instale dependências
npm install

# 3. Configure o ambiente
cp .env.example config.local.php

# 4. Execute em modo desenvolvimento
npm run dev
```

**Configuração de desenvolvimento** (`config.local.php`):
```php
<?php
require_once __DIR__ . '/config.php';

// Sobrescrever para desenvolvimento
define('APP_ENV', 'development');
define('APP_DEBUG', true);
define('SHOW_FILE_LIST', true);
define('ALLOW_FILE_DELETION', true);
```

### 🌐 Produção (Subdomínio Público)

#### 1️⃣ Preparação do Servidor

```bash
# Upload dos arquivos (exceto node_modules e dist)
rsync -av --exclude node_modules --exclude assets/dist ./openapi-editor/ user@servidor:/var/www/openapi.seudominio.com/

# Conectar ao servidor
ssh user@servidor

# Ir para o diretório
cd /var/www/openapi.seudominio.com/
```

#### 2️⃣ Configuração de Produção

Crie `config.local.php` no servidor:
```php
<?php
require_once __DIR__ . '/config.php';

// =============================================================================
// CONFIGURAÇÃO DE PRODUÇÃO - SEGURA
// =============================================================================

// Ambiente de produção
define('APP_ENV', 'production');
define('APP_DEBUG', false);

// SEGURANÇA: Ocultar listagem de arquivos
define('SHOW_FILE_LIST', false);

// SEGURANÇA: Desabilitar exclusão de arquivos
define('ALLOW_FILE_DELETION', false);

// Habilitar funcionalidades seguras
define('SHOW_FILE_UPLOAD', true);
define('SHOW_NEW_FILE', true);
define('SHOW_PREVIEW', true);

// Personalização da sua empresa
define('COMPANY_NAME', 'Sua Empresa Ltda');
define('COMPANY_URL', 'https://seudominio.com');
define('API_BASE_URL', 'https://api.seudominio.com');

// Logs apenas para erros
define('ENABLE_LOGGING', true);
define('LOG_LEVEL', 'error');
```

#### 3️⃣ Build de Produção

```bash
# No servidor ou localmente
npm install --production
npm run build

# Se localmente, faça upload dos assets
rsync -av assets/dist/ user@servidor:/var/www/openapi.seudominio.com/assets/dist/
```

#### 4️⃣ Configuração do Apache/Nginx

**Apache** (`.htaccess`):
```apache
# Segurança
Header always set X-Content-Type-Options nosniff
Header always set X-Frame-Options DENY
Header always set X-XSS-Protection "1; mode=block"

# Proteger arquivos de configuração
<Files "config*.php">
    Order allow,deny
    Deny from all
</Files>

<Files ".env*">
    Order allow,deny  
    Deny from all
</Files>

# Cache para assets
<LocationMatch "\.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2)$">
    ExpiresActive On
    ExpiresDefault "access plus 1 month"
</LocationMatch>
```

**Nginx**:
```nginx
server {
    listen 80;
    server_name openapi.seudominio.com;
    root /var/www/openapi.seudominio.com;
    index index.php;

    # Segurança
    add_header X-Content-Type-Options nosniff always;
    add_header X-Frame-Options DENY always;
    add_header X-XSS-Protection "1; mode=block" always;

    # Proteger arquivos sensíveis
    location ~ /config.*\.php$ {
        deny all;
    }
    
    location ~ /\.env {
        deny all;
    }

    # PHP
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }

    # Assets com cache
    location ~* \.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2)$ {
        expires 1M;
        add_header Cache-Control "public, immutable";
    }
}
```

## 🔒 Configurações de Segurança Recomendadas

### ✅ Para Produção (Público)

```php
define('SHOW_FILE_LIST', false);      // 🚫 Nunca mostrar arquivos em produção
define('ALLOW_FILE_DELETION', false); // 🚫 Nunca permitir exclusão
define('ALLOW_FILE_EDITING', true);   // ✅ Permitir edição
define('SHOW_FILE_UPLOAD', true);     // ✅ Permitir upload
define('APP_DEBUG', false);           // 🚫 Desabilitar debug
define('LOG_LEVEL', 'error');         // ⚠️  Apenas logs de erro
```

### ⚙️ Para Staging/Homologação

```php
define('SHOW_FILE_LIST', false);      // 🚫 Ocultar listagem  
define('ALLOW_FILE_DELETION', false); // 🚫 Sem exclusão
define('APP_DEBUG', false);           // 🚫 Sem debug
define('LOG_LEVEL', 'warning');       // ⚠️  Logs de warning+
```

### 🛠️ Para Desenvolvimento

```php
define('SHOW_FILE_LIST', true);       // ✅ Mostrar tudo
define('ALLOW_FILE_DELETION', true);  // ✅ Permitir exclusão  
define('APP_DEBUG', true);            // ✅ Debug completo
define('LOG_LEVEL', 'debug');         // 📝 Todos os logs
```

## 🗂️ Estrutura de Arquivos no Servidor

```
/var/www/openapi.seudominio.com/
├── index.php                 # Página principal
├── editor.php               # Editor OpenAPI
├── config.php               # Configurações base (commitado)
├── config.local.php         # Configurações locais (NÃO commitado)
├── assets/
│   ├── dist/                # Assets compilados
│   ├── scss/               # Fontes SCSS
│   └── js/                 # Fontes JS
├── components/             # Componentes PHP
├── files/                  # Arquivos OpenAPI salvos
│   ├── swagger.json        # Exemplo (commitado)
│   └── outros-arquivos.*   # Arquivos dos usuários (NÃO commitados)
└── logs/                   # Logs da aplicação
```

## 🎯 Checklist de Deploy

### Antes do Deploy
- [ ] Configurar `config.local.php` adequadamente
- [ ] Executar `npm run build` 
- [ ] Testar localmente com configurações de produção
- [ ] Verificar se arquivos sensíveis estão no `.gitignore`

### Durante o Deploy  
- [ ] Upload dos arquivos (exceto `node_modules`)
- [ ] Configurar servidor web (Apache/Nginx)
- [ ] Criar diretórios necessários com permissões corretas
- [ ] Testar todas as funcionalidades

### Após o Deploy
- [ ] Verificar se configurações de segurança estão ativas
- [ ] Testar upload e criação de arquivos
- [ ] Verificar se logs estão sendo gerados
- [ ] Confirmar que arquivos sensíveis não são acessíveis

## 🆘 Troubleshooting

### Problema: "Arquivo de configuração não encontrado"
**Solução**: Criar `config.local.php` baseado em `config.php`

### Problema: "Permissões negadas para criar arquivos"  
**Solução**: 
```bash
chmod 755 /var/www/openapi.seudominio.com
chmod 777 files/
chmod 777 logs/
```

### Problema: "Assets não carregam"
**Solução**: 
```bash
# Executar build no servidor
npm run build

# Ou fazer upload dos assets compilados
```

### Problema: "Erro 500 - Internal Server Error"
**Solução**: Verificar logs do PHP e da aplicação
```bash
tail -f /var/log/apache2/error.log
tail -f logs/app.log
```

---

## 📞 Suporte

Para dúvidas sobre deployment:
- 📖 **Documentação**: README.md
- 🐛 **Issues**: [GitHub Issues](https://github.com/rodrigobahia/openapi-editor/issues)
- 📧 **Email**: contato@rodrigobahia.com
- 💼 **LinkedIn**: [linkedin.com/in/rohbahia](https://www.linkedin.com/in/rohbahia/)