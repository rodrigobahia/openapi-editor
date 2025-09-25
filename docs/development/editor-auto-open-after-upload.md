# Correção: Abrir Editor Após Upload de Arquivo

**Data:** 25/09/2025
**Autor:** rodrigobahia
**Branch:** bugfix/editor-open-after-upload

## Problema
Após o upload de um novo arquivo, o editor não era aberto automaticamente. O usuário recebia apenas uma mensagem de sucesso, mas não conseguia editar o arquivo, especialmente em ambientes onde a listagem de arquivos está desabilitada.

## Solução
Foi implementada uma regra padrão para redirecionar o usuário automaticamente para o editor (`editor.php`) após o upload bem-sucedido, independentemente da configuração de listagem de arquivos.

### Trecho aplicado
```php
// Regra padrão: sempre redirecionar para o editor após upload
header('Location: editor.php?file=' . urlencode($uploadedFile['name']));
exit;
```

## Impacto
- Melhora a experiência do usuário.
- Garante acesso imediato ao editor após importação.
- Evita dependência da listagem de arquivos.

---

> Essa melhoria foi aplicada em `/index.php` e está disponível a partir da versão beta 1.0.0.
