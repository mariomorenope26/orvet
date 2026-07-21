#!/usr/bin/env bash
# Actualización de una instalación existente en producción (tras git pull).
set -e

echo "== Actualizando Orvet =="

php artisan down || true

git pull --ff-only || true
composer install --no-dev --optimize-autoloader
npm ci
npm run build

php artisan migrate --force
php artisan storage:link || true

# Recompila la caché de producción
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

php artisan up
echo "Despliegue completado."
