# 🤖 AI_ASSISTANT_GUIDE.md

## 🎯 **GUIA RÁPIDO PARA AI ASSISTANTS**

### 📋 **CONTEXTO IMEDIATO**
- **Projeto:** OpenAPI Editor (sistema web completo)
- **Status:** Produção ativa em https://openapi.myrotech.com
- **Versão:** 1.0.0 (primeiro commit limpo)
- **Autor:** Myrotech

### 🏗️ **ARQUIVOS PRINCIPAIS QUE VOCÊ DEVE CONHECER**

#### **🔧 Core Files:**
```
index.php       → Landing page com SEO completo
editor.php      → Interface de edição OpenAPI  
preview.php     → Preview Swagger UI
config.php      → Configurações desenvolvimento
config.production.php → Configurações produção
```

#### **📁 Estrutura Crítica:**
```
assets/scss/    → Arquivos SCSS (compile com gulp)
assets/css/     → CSS compilado
docs/           → Documentação completa organizada
.github/        → Context files para AI (este arquivo)
```

### ⚡ **AÇÕES MAIS COMUNS**

#### **🎨 Styling/CSS:**
- Edite arquivos `.scss` em `assets/scss/`
- Execute `gulp build` para compilar
- NUNCA edite CSS compilado diretamente

#### **📝 Funcionalidades PHP:**
- Código principal nos 3 arquivos .php da raiz
- Use funções já existentes quando possível
- Mantenha padrão de SEO enterprise

#### **📚 Documentação:**
- Toda doc organizada em `docs/`
- Use estrutura existente em `docs/INDEX.md`
- Mantenha consistência de formato

#### **🔒 Segurança:**
- SEMPRE sanitize inputs com `htmlspecialchars()`
- Use constantes do config.php para URLs/dados
- Mantenha .htaccess simplificado para compatibilidade

### 🚨 **IMPORTANTES - NÃO QUEBRAR**

#### **✅ SEO Sistema:**
- Schema.org structured data já implementado
- Meta tags dinâmicas funcionando
- NÃO altere sem entender o sistema completo

#### **✅ Build System:**
- Gulp configurado e funcionando
- SCSS → CSS pipeline estabelecido
- Package.json com dependências corretas

#### **✅ Configurações:**
- config.php = desenvolvimento (dados genéricos)
- config.production.php = produção (dados Myrotech)
- NUNCA commit dados reais de clientes

### 📋 **CHECKLIST ANTES DE EDIÇÕES**

#### **🔍 Antes de Modificar Código:**
- [ ] Entendi qual arquivo devo editar?
- [ ] É SCSS ou CSS compilado?
- [ ] Preciso executar build após edição?
- [ ] Vai quebrar SEO existente?

#### **🛡️ Antes de Deploy/Commit:**
- [ ] Testei localmente?
- [ ] Build executado com sucesso?
- [ ] Nenhum dado sensível no código?
- [ ] Documentação atualizada se necessário?

### 🎯 **PADRÕES ESTABELECIDOS**

#### **PHP Code Style:**
```php
// Constantes em UPPER_CASE
define('SITE_NAME', 'OpenAPI Editor');

// Funções em camelCase  
function getSeoTitle() {
    return 'Título SEO';
}

// Sempre sanitize outputs
echo htmlspecialchars($userInput);
```

#### **SCSS Structure:**
```scss
// Variáveis em _vars.scss
$primary-color: #007bff;

// Mixins em _mixins.scss
@mixin responsive($breakpoint) {
    @media (max-width: $breakpoint) { @content; }
}

// BEM methodology
.editor {
    &__container { }
    &__button {
        &--active { }
    }
}
```

### 🔧 **COMANDOS ÚTEIS**

#### **Development:**
```bash
# Build CSS
gulp build

# Watch para dev
gulp watch

# Ver estrutura
find . -name "*.php" -maxdepth 1
```

#### **Debug Info:**
```bash
# Ver configurações
cat config.php

# Ver assets compilados
ls -la assets/css/

# Ver docs organizadas
ls -la docs/
```

### 📞 **QUANDO PEDIR AJUDA**

#### **🚨 Situações para Confirmar:**
- Modificar sistema SEO existente
- Alterar estrutura de build (gulpfile.js)
- Mudanças na configuração de segurança
- Alterações que afetam produção

#### **✅ Pode Fazer Livremente:**
- Editar SCSS e compilar
- Adicionar funcionalidades nas páginas PHP
- Atualizar documentação
- Melhorar UX/UI sem quebrar estrutura

### 🎪 **FUNCIONALIDADES ESPECIAIS**

#### **🔍 SEO Enterprise:**
- Sistema completo já implementado
- Schema.org structured data
- Meta tags dinâmicas
- Open Graph + Twitter Cards

#### **🛡️ Security Headers:**
- .htaccess com proteções
- Sanitização de dados
- Headers de segurança
- Proteção de arquivos sensíveis

#### **📱 Responsive Design:**
- Mobile-first approach
- Breakpoints definidos
- Grid system customizado

---

## 🚀 **TL;DR PARA AI**

**Este é um sistema OpenAPI Editor profissional e completo:**
- 3 páginas PHP principais com SEO enterprise
- Build system Gulp (SCSS → CSS)  
- Documentação organizada em docs/
- Produção ativa e estável
- Código limpo e bem estruturado

**Para editar:** SCSS → gulp build → testar → commit
**Para deploy:** usar config.production.php
**Para docs:** seguir estrutura em docs/INDEX.md

**Demo:** https://openapi.myrotech.com