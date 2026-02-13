#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
SRC_DIR="$ROOT_DIR/component/com_glavpro_crm"
OUT_DIR="$ROOT_DIR/dist"
OUT_ZIP="$OUT_DIR/com_glavpro_crm.zip"

mkdir -p "$OUT_DIR"

if ! command -v zip >/dev/null 2>&1; then
  echo "zip не найден. Установи zip и повтори." >&2
  exit 1
fi

rm -f "$OUT_ZIP"
(
  cd "$SRC_DIR"
  zip -r "$OUT_ZIP" . -x "*/.DS_Store" "*.DS_Store"
)

echo "OK: $OUT_ZIP"
