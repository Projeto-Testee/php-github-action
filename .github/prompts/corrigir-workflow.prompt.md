---
mode: agent
description: Analisa um workflow com falha, identifica a causa raiz e aplica uma correção mínima.
---

# Modo CORRIGIR — Diagnóstico de Falha no Workflow

Analise o workflow com falha informado e aplique a correção mínima necessária.

## Processo obrigatório

### 1. Identifique o primeiro step que falhou
Não analise steps que falharam por consequência. Foque no **primeiro** step com erro.

### 2. Extraia a mensagem de erro exata
Cole a mensagem de erro completa. Não parafraseie.

### 3. Diferencie causa raiz de sintoma
Exemplos:
- Sintoma: `npm ci` falhou | Causa raiz: `package-lock.json` ausente no repositório
- Sintoma: `php artisan migrate` falhou | Causa raiz: MySQL service sem health-check adequado
- Sintoma: `composer install` lento | Causa raiz: ausência de cache configurado
- Sintoma: testes falharam em `Unit` | Causa raiz: teste acessa banco — classificação incorreta

### 4. Verifique o contexto
- Qual branch e commit executaram o workflow?
- O arquivo de lock (`composer.lock`, `package-lock.json`) está versionado?
- Houve alteração recente em `composer.json` ou `package.json` sem atualizar o lock?
- O step anterior ao falho gerou artefato necessário?

### 5. Aplique a correção mínima
- Não reescreva o workflow inteiro
- Altere apenas o necessário para corrigir o problema identificado
- Se a correção exigir mais de um arquivo, liste todos

### 6. Valide a correção
- Execute sintaxe YAML localmente se possível
- Verifique que a correção não quebra outros jobs

## Problemas comuns e correções conhecidas

| Problema | Causa provável | Correção |
|----------|---------------|----------|
| `npm ci` falha com "missing package-lock.json" | `package-lock.json` não versionado | Commitar `package-lock.json` |
| MySQL connection refused | Health-check ausente ou timeout insuficiente | Adicionar `options: --health-cmd` com retries |
| `composer install` instala versões erradas | `composer.lock` desatualizado | Executar `composer update` e commitar o lock |
| PHPStan falha com centenas de erros | Nível de análise muito alto para código legado | Reduzir para `level: 1` no `phpstan.neon` |
| Artefato não encontrado no job seguinte | Nome do artefato com `$GITHUB_SHA` vs `${{ github.sha }}` inconsistente | Padronizar a expressão de SHA |
| Step `migrate` falha antes de conectar | MySQL service ainda não pronto | Aumentar `health-retries` e `health-interval` |
| Build demora por não usar cache | Cache do composer/npm não configurado | Adicionar `cache: composer` no setup-php e `cache: npm` no setup-node |

## Formato da resposta

### Diagnóstico
O que existe e qual é o problema (com evidência do log).

### Causa raiz
Por que o comportamento aconteceu (não o sintoma).

### Correção
Arquivo e diff exato da alteração.

### Validação
Como verificar que a correção resolveu o problema.

### Riscos residuais
O que permanece não verificado após a correção.

### Próximo passo
Uma única ação para confirmar a resolução.
