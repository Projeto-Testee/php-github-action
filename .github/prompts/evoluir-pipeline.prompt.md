---
mode: agent
description: Adiciona novos recursos a uma pipeline estável sem reescrevê-la desnecessariamente.
---

# Modo EVOLUIR — Evolução Incremental da Pipeline

Adicione o novo recurso solicitado à pipeline existente sem reescrevê-la desnecessariamente.

## Princípios de evolução

1. **Leia antes de alterar** — entenda o que já existe antes de propor mudanças
2. **Alterações mínimas** — modifique apenas o necessário para o novo recurso
3. **Não quebre o que funciona** — valide que jobs existentes continuam passando
4. **Documente a intenção** — registre por que o recurso foi adicionado, não o que ele faz

## Pré-requisitos

Leia o workflow atual em `.github/workflows/ci.yml` e responda:
- Quais jobs existem e quais são suas dependências (`needs`)?
- Qual é o artefato produzido e qual é seu nome exato?
- Existe CD pipeline em `.github/workflows/cd.yml`?
- Onde o novo recurso se encaixa na arquitetura?

## Recursos comuns e orientações

### Adicionar PHPStan
- Verificar se `phpstan.neon` existe
- Adicionar `nunomaduro/larastan` ao `require-dev` do `composer.json`
- Criar job `static-analysis` com `needs: build`
- Começar em `level: 1` para código legado
- Não bloquear a pipeline com erros antigos sem baseline

### Adicionar cobertura de testes
- Adicionar `coverage: xdebug` no `setup-php` do job de testes
- Gerar relatório Clover: `--coverage-clover reports/coverage.xml`
- Publicar como artefato
- Não usar cobertura em todos os jobs (custo de tempo)

### Adicionar cache de dependências
- `cache: composer` no `shivammathur/setup-php`
- `cache: npm` + `cache-dependency-path: package-lock.json` no `actions/setup-node`
- Verificar que `composer.lock` e `package-lock.json` estão versionados

### Adicionar workflow_dispatch
- Adicionar `workflow_dispatch:` ao bloco `on:`
- Opcionalmente adicionar inputs para SHA ou ambiente

### Adicionar CD pipeline
- Criar `.github/workflows/cd.yml`
- Trigger: `workflow_run` com `workflows: ['Laravel CI']` e `types: [completed]`
- Condicional: `if: github.event.workflow_run.conclusion == 'success' && github.event.workflow_run.head_branch == 'main'`
- Usar o mesmo artefato produzido pela CI

### Adicionar reusable workflow
- Extrair a lógica estável para `.github/workflows/laravel-ci-reusable.yml`
- Expor inputs: `php-version`, `node-version`, `test-suite`, `artifact-name`
- Chamar com `uses: ./.github/workflows/laravel-ci-reusable.yml`

### Adicionar security scan
- Criar job `security-scan` com `needs: build`
- Usar `composer audit` para dependências PHP
- Usar `npm audit --audit-level=high` para dependências npm (não `npm audit fix --force`)
- Publicar relatório como artefato

## Evolução proibida sem análise prévia

Não aplique as seguintes mudanças sem apresentar impacto, riscos e plano de rollback:
- Upgrade de versão principal de PHP, Laravel, Node.js, Vue ou Webpack
- Migração de `npm` para `pnpm` ou `yarn`
- Substituição do runner
- Alteração no modelo de deploy

## Formato da resposta

### Diagnóstico
Estado atual da pipeline (extraído dos arquivos).

### Recurso adicionado
O que foi implementado e onde se encaixa.

### Alterações
Arquivos criados ou modificados com diff.

### Validação
Como confirmar que o novo recurso funciona sem quebrar os existentes.

### Riscos
Itens não verificáveis localmente.

### Próximo passo
Uma única ação para verificar o resultado.
