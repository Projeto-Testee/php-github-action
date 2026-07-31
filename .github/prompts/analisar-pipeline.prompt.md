---
mode: agent
description: Inspeciona o repositório e produz um diagnóstico completo da pipeline sem alterar arquivos.
---

# Modo ANALISAR — Diagnóstico da Pipeline

Inspecione este repositório e produza um diagnóstico completo da pipeline de CI/CD. Não altere nenhum arquivo.

## O que analisar

Leia e interprete os seguintes arquivos, quando existirem:
- `composer.json` e `composer.lock`
- `package.json` e `package-lock.json`
- `phpunit.xml`
- `.env.example`
- `webpack.mix.js`
- `.github/workflows/*.yml`
- `.github/scripts/*`
- `phpstan.neon`
- `Dockerfile` e `docker-compose.yml`

## O que verificar

### Estrutura da pipeline
- Existe separação em jobs (build, testes, análise, pacote, deploy)?
- Existe artefato compartilhado entre jobs?
- Existe CD pipeline?
- Existe configuração de environments (`homologation`, `production`)?

### Classificação dos testes
- Testes em `tests/Unit/` realmente são unitários?
  - Um teste unitário **não deve** usar banco de dados, HTTP, factories com persistência, autenticação ou migrations.
  - Se usar qualquer um desses, está classificado incorretamente em `tests/Unit/`.
- Testes em `tests/Feature/` têm banco de dados isolado no CI?

### Qualidade do YAML
- Todos os jobs têm `timeout-minutes`?
- Existe `concurrency` configurado?
- Permissões estão no mínimo necessário?
- Scripts complexos estão em `.github/scripts/` ou inline no YAML?
- Há `continue-on-error` mascarando falhas?

### Segurança
- Secrets aparecem no YAML?
- Há `pull_request_target` com acesso a secrets?
- Actions de terceiros estão fixadas em SHA imutável?
- `.env` está excluído dos artefatos?

### Dependências
- `composer.lock` está versionado?
- `package-lock.json` está versionado?
- PHPStan/Larastan está configurado?

## Formato da resposta

### Diagnóstico
Descreva o estado atual da pipeline com evidências extraídas dos arquivos.

### Problemas encontrados
Liste cada problema com: localização, descrição, impacto e severidade (crítico / alto / médio / baixo).

### Testes classificados incorretamente
Liste cada teste com: arquivo, razão da classificação incorreta, destino correto.

### Riscos identificados
Liste riscos operacionais, de segurança e de manutenção.

### Proposta de arquitetura
Descreva a arquitetura recomendada com jobs, dependências e artefatos.

### Próximo passo
Uma única ação objetiva para iniciar a melhoria.
