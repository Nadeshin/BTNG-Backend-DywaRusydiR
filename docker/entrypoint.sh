#!/bin/sh
set -e

PORT="${PORT:-8080}"

echo "==> Menyesuaikan Apache ke port ${PORT}"
sed -i "s/^Listen 80$/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:8080>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

echo "==> Migration"
php artisan migrate --force

echo "==> Seeder (idempotent)"
php artisan db:seed --force

echo "==> Cache produksi"
php artisan config:cache
php artisan view:cache

echo "==> Menjalankan web server di port ${PORT}"
exec "$@"
