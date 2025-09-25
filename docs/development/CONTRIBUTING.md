# 🤝 Contribuindo para o OpenAPI Editor

Obrigado por considerar contribuir para o OpenAPI Editor! Este projeto é open source e acolhe contribuições da comunidade.

## 🌟 Como Contribuir

### 🐛 Reportar Bugs

1. **Verifique** se o bug já foi reportado nas [Issues](https://github.com/rodrigobahia/openapi-editor/issues)
2. **Crie uma nova issue** com:
   - Descrição clara do problema
   - Passos para reproduzir
   - Comportamento esperado vs atual
   - Screenshots (se aplicável)
   - Versão do PHP, browser, OS

### ✨ Sugerir Features

1. **Abra uma issue** com o label "enhancement"
2. **Descreva** a funcionalidade desejada
3. **Explique** por que seria útil
4. **Considere** a implementação

### 🔧 Contribuir com Código

#### 1️⃣ Fork e Clone
```bash
# Fork o repositório no GitHub
git clone https://github.com/rodrigobahia/openapi-editor.git
cd openapi-editor
```

#### 2️⃣ Configure o Ambiente
```bash
# Instale dependências
npm install

# Configure para desenvolvimento
cp .env.example config.local.php

# Execute em modo desenvolvimento
npm run dev
```

#### 3️⃣ Crie uma Branch
```bash
# Crie uma branch para sua feature/fix
git checkout -b feature/nova-funcionalidade
# ou
git checkout -b fix/corrigir-bug
```

#### 4️⃣ Desenvolva
- **Siga** os padrões de código existentes
- **Teste** suas modificações
- **Documente** novas funcionalidades
- **Mantenha** commits atômicos e bem descritos

#### 5️⃣ Teste
```bash
# Execute o build
npm run build

# Teste sintaxe PHP
php -l index.php
php -l config.php

# Teste funcionalidades manualmente
```

#### 6️⃣ Submit Pull Request
1. **Push** sua branch para seu fork
2. **Abra** um Pull Request no repositório original
3. **Descreva** as mudanças claramente
4. **Aguarde** review e feedback

## 📋 Padrões de Código

### PHP
- **PSR-12** para formatação
- **Comentários** em português para código interno
- **Nomes** descritivos para funções e variáveis
- **Validação** de entrada sempre

```php
<?php
// ✅ Bom
function isFeatureEnabled($featureName) {
    // Lógica clara e validada
}

// ❌ Evitar  
function check($f) {
    // Lógica confusa
}
```

### JavaScript  
- **ES6+** quando possível
- **Comentários** claros
- **Consistent** indentation (2 spaces)
- **Semicolons** obrigatórios

```javascript
// ✅ Bom
const config = {
  theme: 'light',
  language: 'pt'
};

// ❌ Evitar
var cfg = {theme:'light',language:'pt'}
```

### SCSS
- **Modulares** por componente
- **BEM** methodology quando aplicável  
- **Variables** para valores reutilizáveis
- **Mixins** para padrões comuns

```scss
// ✅ Bom
.editor-card {
  &__header {
    background: $primary-color;
  }
}

// ❌ Evitar
.editorcard-hdr {
  background: #007bff;
}
```

## 🏗️ Estrutura do Projeto

```
openapi-editor/
├── assets/
│   ├── scss/           # Estilos organizados por tipo
│   ├── js/            # JavaScript modular
│   └── dist/          # Compilados (não commitar)
├── components/         # Componentes PHP reutilizáveis
├── files/             # Arquivos OpenAPI de exemplo
├── config.php         # Configurações base
└── *.php              # Páginas principais
```

## 🎯 Tipos de Contribuições Bem-vindas

### 🐛 **Bug Fixes**
- Correções de funcionalidades quebradas
- Melhorias de compatibilidade
- Fixes de segurança

### ✨ **Features**
- Novos tipos de validação OpenAPI
- Melhorias na UI/UX
- Suporte a mais formatos
- Integração com outras ferramentas

### 📖 **Documentação**
- Melhorias no README
- Exemplos práticos
- Guias de uso
- Tradução para outros idiomas

### 🎨 **Design/UI**
- Melhorias visuais
- Responsividade
- Acessibilidade
- Temas adicionais

### ⚡ **Performance**
- Otimizações de código
- Redução do bundle size
- Melhorias no build process

## 🚫 O que NÃO Aceitar

- **Mudanças breaking** sem discussão prévia
- **Código malicioso** ou inseguro  
- **Dependências** desnecessárias ou pesadas
- **Features** muito específicas que não beneficiam a maioria
- **Código** sem documentação adequada

## 💡 Dicas para Contribuições Aceitas

1. **Discuta antes** de implementar features grandes
2. **Mantenha** o foco na simplicidade
3. **Considere** diferentes casos de uso
4. **Teste** em diferentes ambientes
5. **Documente** adequadamente

## 📞 Dúvidas?

- 💬 **Issues**: [GitHub Issues](https://github.com/rodrigobahia/openapi-editor/issues)
- 📧 **Email**: contato@rodrigobahia.com
- 💼 **LinkedIn**: [linkedin.com/in/rohbahia](https://www.linkedin.com/in/rohbahia/)
- 💡 **Discussions**: [GitHub Discussions](https://github.com/rodrigobahia/openapi-editor/discussions)

## 🙏 Reconhecimento

Todos os contribuidores serão reconhecidos no README e terão seus commits preservados no histórico do Git.

### Hall da Fama 🌟
<!-- Lista atualizada automaticamente -->

---

**Obrigado por tornar o OpenAPI Editor ainda melhor!** 🚀