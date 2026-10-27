#!/bin/bash
# Pre-run hook Clever Cloud (CC_PRE_RUN_HOOK=./.clever.cloud/pre-run.sh)
# Dijalankan setiap aplikasi start, sebelum web server menerima request.
set -e

echo "==> Menjalankan migration"
php artisan migrate --force

echo "==> Mengisi data dummy (idempotent)"
php artisan db:seed --force

echo "==> Aplikasi siap"
