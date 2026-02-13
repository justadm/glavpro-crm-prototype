#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
SRC_DIR="$ROOT_DIR/component/com_glavpro_crm"
OUT_DIR="$ROOT_DIR/dist"
OUT_ZIP="$OUT_DIR/com_glavpro_crm.zip"
BUILD_DIR="$ROOT_DIR/dist/.build_com_glavpro_crm"

mkdir -p "$OUT_DIR"

if ! command -v zip >/dev/null 2>&1; then
  echo "zip не найден. Установи zip и повтори." >&2
  exit 1
fi

rm -f "$OUT_ZIP"
rm -rf "$BUILD_DIR"
mkdir -p "$BUILD_DIR/site" "$BUILD_DIR/admin" "$BUILD_DIR/sql"

# Joomla expects an install package layout:
# - com_glavpro_crm.xml at root
# - site/ (copied into /components/com_glavpro_crm)
# - admin/ (copied into /administrator/components/com_glavpro_crm)
cp -f "$SRC_DIR/com_glavpro_crm.xml" "$BUILD_DIR/com_glavpro_crm.xml"
cp -R "$SRC_DIR/components/com_glavpro_crm/." "$BUILD_DIR/site/"
cp -R "$SRC_DIR/administrator/components/com_glavpro_crm/." "$BUILD_DIR/admin/"

# Keep install SQL at the package root so Joomla can find it during install.
cp -f "$SRC_DIR/administrator/components/com_glavpro_crm/sql/install.mysql.utf8mb4.sql" "$BUILD_DIR/sql/install.mysql.utf8mb4.sql"

(
  cd "$BUILD_DIR"
  zip -r "$OUT_ZIP" . -x "*/.DS_Store" "*.DS_Store"
)

rm -rf "$BUILD_DIR"

echo "OK: $OUT_ZIP"
