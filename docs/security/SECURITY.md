# Política de Segurança

## Versões Suportadas

Nós fornecemos atualizações de segurança para as seguintes versões do OpenAPI Editor:

| Versão | Suportada          |
| ------ | ------------------ |
| 1.0.x  | :white_check_mark: |
| < 1.0  | :x:                |

## Reportando uma Vulnerabilidade

A segurança do OpenAPI Editor é importante para nós. Se você descobriu uma vulnerabilidade de segurança, pedimos que nos ajude de forma responsável, divulgando-a de forma privada.

### Como Reportar

**Por favor, NÃO reporte vulnerabilidades de segurança através de issues públicas do GitHub.**

Em vez disso, envie um email para **contato@rodrigobahia.com** com os seguintes detalhes:

- **Tipo de problema** (ex: buffer overflow, SQL injection, cross-site scripting, etc.)
- **Localização** do código fonte afetado (tag/branch/commit ou URL direto)
- **Configurações especiais** necessárias para reproduzir o problema
- **Instruções passo-a-passo** para reproduzir o problema
- **Proof-of-concept ou exploit code** (se possível)
- **Impacto potencial** da vulnerabilidade

### O que Esperar

Após enviar uma vulnerabilidade, você pode esperar:

1. **Confirmação** do recebimento em até **48 horas**
2. **Avaliação inicial** da vulnerabilidade em até **7 dias**
3. **Atualização regular** sobre o progresso da correção
4. **Notificação** quando a vulnerabilidade for corrigida

### Processo de Divulgação Responsável

1. **Investigação**: Investigaremos e confirmaremos a vulnerabilidade
2. **Desenvolvimento**: Desenvolveremos uma correção
3. **Teste**: Testaremos a correção em diferentes ambientes
4. **Release**: Lançaremos uma versão corrigida
5. **Divulgação**: Divulgaremos a vulnerabilidade após a correção estar disponível

### Timeline Esperado

- **Vulnerabilidades Críticas**: Correção em até 7 dias
- **Vulnerabilidades Altas**: Correção em até 30 dias
- **Vulnerabilidades Médias**: Correção em até 90 dias
- **Vulnerabilidades Baixas**: Correção na próxima release planejada

## Configurações de Segurança Recomendadas

### Para Ambiente de Produção

```php
// config.local.php
define('APP_ENV', 'production');
define('APP_DEBUG', false);
define('SHOW_FILE_LIST', false);        // CRÍTICO: Não expor arquivos
define('ALLOW_FILE_DELETION', false);   // CRÍTICO: Não permitir exclusão
define('LOG_LEVEL', 'error');
```

### Headers de Segurança

Configure seu servidor web com estes headers:

```apache
# Apache (.htaccess)
Header always set X-Content-Type-Options nosniff
Header always set X-Frame-Options DENY
Header always set X-XSS-Protection "1; mode=block"
Header always set Referrer-Policy strict-origin-when-cross-origin
```

```nginx
# Nginx
add_header X-Content-Type-Options nosniff always;
add_header X-Frame-Options DENY always;
add_header X-XSS-Protection "1; mode=block" always;
add_header Referrer-Policy strict-origin-when-cross-origin always;
```

### Permissões de Arquivo

```bash
# Diretórios
chmod 755 /caminho/para/openapi-editor/
chmod 755 files/
chmod 755 logs/

# Arquivos PHP
chmod 644 *.php
chmod 600 config.local.php  # Arquivo de configuração restrito

# Assets
chmod 644 assets/dist/css/*
chmod 644 assets/dist/js/*
```

## Atualizações de Segurança

Para receber notificações sobre atualizações de segurança:

1. **Watch** este repositório no GitHub
2. **Follow** [@rodrigobahia](https://github.com/rodrigobahia) no GitHub
3. **Connect** no [LinkedIn](https://www.linkedin.com/in/rohbahia/)

## Escopo

Esta política de segurança aplica-se a:

- ✅ **Código principal** do OpenAPI Editor
- ✅ **Arquivos de configuração** e templates
- ✅ **Scripts de build** e deployment
- ❌ **Dependências de terceiros** (reporte aos respectivos projetos)
- ❌ **Configurações do servidor web** (responsabilidade do usuário)

## Histórico de Segurança

Nenhuma vulnerabilidade de segurança foi reportada até o momento.

## Recursos Adicionais

- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [Guia de Segurança PHP](https://phpsecurity.readthedocs.io/)
- [Boas práticas de segurança web](https://web.dev/secure/)

---

**Obrigado por ajudar a manter o OpenAPI Editor seguro!**