#!/usr/bin/env bash
# Verifica se a aplicação responde com HTTP 200 após o deploy
set -euo pipefail

APP_URL="${APP_URL:?APP_URL environment variable is required}"

echo "Running smoke test against: ${APP_URL}"

curl \
    --fail \
    --silent \
    --show-error \
    --retry 10 \
    --retry-delay 5 \
    --retry-all-errors \
    --max-time 10 \
    "${APP_URL}/"

echo "Smoke test passed."
