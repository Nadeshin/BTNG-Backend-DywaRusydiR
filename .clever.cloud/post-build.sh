#!/bin/bash
# Build hook Clever Cloud (CC_POST_BUILD_HOOK=./.clever.cloud/post-build.sh)
set -e

echo "==> Install dependency produksi"
composer install --no-dev --optimize-autoloader --no-interaction

echo "==> Bersihkan cache lama"
php artisan config:clear
php artisan cache:clear || true

echo "==> Cache konfigurasi & view"
php artisan config:cache
php artisan view:cache

echo "==> Build selesai"
