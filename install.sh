#!/usr/bin/env bash
# Instalador de terminal para PRODUCCIÓN — Orvet (Laravel 13 + Filament v5)
# Uso:
#   bash install.sh            -> datos de ejemplo
#   CONTENT=1 bash install.sh  -> migra el contenido real de orvet.pe
set -e

echo "== Instalación de Orvet (producción) =="

# 1. Dependencias
composer install --no-dev --optimize-autoloader
npm ci
npm run build

# 2. Entorno
[ -f .env ] || cp .env.example .env
grep -q "^APP_KEY=base64:" .env || php artisan key:generate --force

# 3. Enlace de almacenamiento
php artisan storage:link || true

# 4. Instalación de la aplicación (migraciones, datos, admin, caché de producción)
if [ "${CONTENT:-0}" = "1" ]; then
  php artisan orvet:install --content --optimize-images --admin-email="${ADMIN_EMAIL:-admin@orvet.pe}"
else
  php artisan orvet:install --seed --admin-email="${ADMIN_EMAIL:-admin@orvet.pe}"
fi

echo ""
echo "Instalación finalizada. Revisa las credenciales mostradas arriba."
echo "Apunta el Document Root del dominio a la carpeta /public (o usa el .htaccess de la raíz)."
