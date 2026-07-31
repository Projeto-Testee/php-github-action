---
mode: agent
description: Implementa ou reescreve a pipeline de CI com jobs separados, artefato compartilhado e análise estática.
---

# Modo IMPLEMENTAR — Pipeline de CI

Implemente a pipeline de CI para este repositório Laravel seguindo a arquitetura obrigatória.

## Arquitetura exigida

```
push / pull_request
        ↓
     build
        ↓
unit-tests   integration-tests   static-analysis
        ↓            ↓                  ↓
                  package
```

Cada estágio é um **job separado**. Use `needs` para representar dependências.

## Pré-requisitos antes de implementar

1. Leia `composer.json` para confirmar versão do PHP e dependências
2. Leia `package.json` para confirmar versão do Node e script de build
3. Leia `phpunit.xml` para confirmar os nomes exatos das suítes (`Unit`, `Feature`)
4. Leia `.env.example` para confirmar variáveis necessárias no CI
5. Verifique se `composer.lock` e `package-lock.json` estão presentes
6. Verifique se `phpstan.neon` existe; se não, crie antes do workflow

## Job: build

O job `build` deve:
- usar `ubuntu-latest`
- configurar PHP 7.4 com extensões: `bcmath, ctype, fileinfo, json, mbstring, openssl, pdo_mysql, tokenizer, xml`
- configurar Node.js 18 com cache npm
- executar `.github/scripts/validate-build.sh` para verificar arquivos obrigatórios
- executar `composer validate --strict`
- executar `composer install --no-interaction --prefer-dist --no-progress --optimize-autoloader`
- executar `npm ci`
- executar `npm run production`
- validar existência de `vendor/`, `public/js/app.js`, `public/mix-manifest.json`
- publicar artefato `laravel-ci-build-${{ github.sha }}` sem `.git`, `.env`, `node_modules`
- ter `timeout-minutes: 15`

## Job: unit-tests

- `needs: build`
- sem serviço MySQL
- baixar artefato do build
- criar diretório `reports/`
- executar: `vendor/bin/phpunit --testsuite=Unit --log-junit=reports/unit-junit.xml`
- publicar relatório JUnit como artefato
- ter `timeout-minutes: 10`

## Job: integration-tests

- `needs: build`
- serviço MySQL 8.0 com health-check
- baixar artefato do build
- configurar `.env` a partir do `.env.example`
- executar `php artisan key:generate`, `config:clear`, `cache:clear`, `migrate --force`
- executar: `vendor/bin/phpunit --testsuite=Feature --log-junit=reports/integration-junit.xml`
- publicar relatório JUnit como artefato
- ter `timeout-minutes: 15`

## Job: static-analysis

- `needs: build`
- baixar artefato do build
- executar PHPStan com `phpstan.neon`, `--error-format=github`, `--memory-limit=1G`
- ter `timeout-minutes: 10`

## Job: package

- `needs: [unit-tests, integration-tests, static-analysis]`
- apenas em push para `main` (não em pull_request)
- baixar artefato do build
- remover: `tests/`, `reports/`, `.github/`, `node_modules/`, `*.log`
- criar `laravel-release-${{ github.sha }}.tar.gz`
- publicar artefato com `retention-days: 30`
- ter `timeout-minutes: 5`

## Regras de qualidade obrigatórias

- `permissions: contents: read` no nível do workflow
- `concurrency` com `cancel-in-progress: true` para pull requests
- Nenhum step de build nos jobs de teste
- Scripts bash com `set -euo pipefail`
- Artefatos com `retention-days` definido
- `workflow_dispatch` adicionado ao trigger

## Formato da resposta

### Alterações
Liste cada arquivo criado ou modificado.

### Validação
Comandos executados para verificar sintaxe e lógica.

### Riscos
Itens não verificáveis localmente.

### Próximo passo
Uma única ação para verificar o resultado no GitHub Actions.
