#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT_DIR"

HOST="${HOST:-127.0.0.1}"
PORT="${PORT:-8000}"

echo "[doctor] project: $ROOT_DIR"

if find . -type f -flags +dataless -print -quit >/dev/null 2>&1; then
  vendor_missing="$(find vendor -type f -flags +dataless 2>/dev/null | wc -l | tr -d ' ')"
  core_missing="$(find app bootstrap config database public resources routes -type f -flags +dataless 2>/dev/null | wc -l | tr -d ' ')"
  echo "[doctor] dataless vendor files: $vendor_missing"
  echo "[doctor] dataless core files:   $core_missing"
  if [ "$vendor_missing" -gt 0 ] || [ "$core_missing" -gt 0 ]; then
    echo "[doctor] run: bash scripts/ensure-local-files.sh"
  fi
else
  echo "[doctor] dataless scan not supported on this system."
fi

if command -v lsof >/dev/null 2>&1; then
  echo "[doctor] port check (${PORT}):"
  lsof -nP -iTCP:${PORT} -sTCP:LISTEN 2>/dev/null || echo "[doctor] no process listening on ${PORT}"
fi

if command -v curl >/dev/null 2>&1; then
  echo "[doctor] endpoint check: http://${HOST}:${PORT}/login"
  curl -I --max-time 8 "http://${HOST}:${PORT}/login" || true
fi
