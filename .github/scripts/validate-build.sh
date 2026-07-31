#!/usr/bin/env bash
# Verifica existência dos arquivos obrigatórios antes de iniciar o build
set -euo pipefail

REQUIRED_FILES=(
    "composer.json"
    "composer.lock"
    "package.json"
    "package-lock.json"
    "phpunit.xml"
    ".env.example"
    "webpack.mix.js"
    "artisan"
)

FAILED=0

for file in "${REQUIRED_FILES[@]}"; do
    if [ ! -f "$file" ]; then
        echo "::error file=${file}::Required file '${file}' not found in repository"
        FAILED=1
    fi
done

if [ "$FAILED" -eq 1 ]; then
    echo "::error::Build validation failed — one or more required files are missing"
    exit 1
fi

echo "All required files present."
