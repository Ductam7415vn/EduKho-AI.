#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT_DIR"

HOST="${HOST:-127.0.0.1}"
PORT="${PORT:-8000}"
SYNC_TIMEOUT="${SYNC_TIMEOUT:-900}"
SYNC_INTERVAL="${SYNC_INTERVAL:-3}"

if find . -type f -flags +dataless -print -quit >/dev/null 2>&1; then
  missing_vendor="$(find vendor -type f -flags +dataless 2>/dev/null | wc -l | tr -d ' ')"
  missing_core="$(find app bootstrap config database public resources routes scripts -type f -flags +dataless 2>/dev/null | wc -l | tr -d ' ')"

  if [ "$missing_vendor" -gt 0 ] || [ "$missing_core" -gt 0 ]; then
    echo "[dev-up] dataless detected (vendor=${missing_vendor}, core+scripts=${missing_core})."
    echo "[dev-up] syncing local files from iCloud..."
    SYNC_TIMEOUT="$SYNC_TIMEOUT" SYNC_INTERVAL="$SYNC_INTERVAL" bash scripts/ensure-local-files.sh
  fi
fi

echo "[dev-up] starting Laravel server at http://${HOST}:${PORT}"
exec php artisan serve --host="${HOST}" --port="${PORT}"
