#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT_DIR"

SYNC_TIMEOUT="${SYNC_TIMEOUT:-900}"
SYNC_INTERVAL="${SYNC_INTERVAL:-3}"
MAX_BATCH="${MAX_BATCH:-500}"

TARGETS=(
  artisan
  composer.json
  composer.lock
  .env
  app
  bootstrap
  config
  database
  public
  resources
  routes
  scripts
  vendor
)

if ! command -v brctl >/dev/null 2>&1; then
  echo "[sync] brctl is not available."
  exit 1
fi

if ! find . -type f -flags +dataless -print -quit >/dev/null 2>&1; then
  echo "[sync] dataless scan is not supported on this system."
  exit 0
fi

download_path() {
  local rel_path="$1"
  local abs_path="$ROOT_DIR/$rel_path"
  [ -e "$abs_path" ] || return 0
  brctl download "$abs_path" >/dev/null 2>&1 || true
}

echo "[sync] requesting iCloud download for critical paths..."
for path in "${TARGETS[@]}"; do
  download_path "$path"
done

started_at="$(date +%s)"

while true; do
  vendor_missing="$(find vendor -type f -flags +dataless 2>/dev/null | wc -l | tr -d ' ')"
  core_missing="$(find app bootstrap config database public resources routes -type f -flags +dataless 2>/dev/null | wc -l | tr -d ' ')"
  script_missing="$(find scripts -type f -flags +dataless 2>/dev/null | wc -l | tr -d ' ')"
  total_missing="$((vendor_missing + core_missing + script_missing))"

  echo "[sync] dataless files => vendor: ${vendor_missing}, core: ${core_missing}, scripts: ${script_missing}"

  if [ "$total_missing" -eq 0 ]; then
    echo "[sync] local files are ready."
    exit 0
  fi

  now="$(date +%s)"
  elapsed="$((now - started_at))"
  if [ "$elapsed" -ge "$SYNC_TIMEOUT" ]; then
    echo "[sync] timed out after ${SYNC_TIMEOUT}s while waiting for iCloud files."
    exit 1
  fi

  find vendor app bootstrap config database public resources routes scripts \
    -type f -flags +dataless 2>/dev/null | head -n "$MAX_BATCH" | while IFS= read -r rel; do
    rel="${rel#./}"
    download_path "$rel"
  done || true

  sleep "$SYNC_INTERVAL"
done
