@echo off
REM Instalador de terminal para produccion (Windows) - Orvet
echo == Instalacion de Orvet ==

call composer install --no-dev --optimize-autoloader
call npm ci
call npm run build

if not exist ".env" copy ".env.example" ".env"
findstr /b /c:"APP_KEY=base64:" .env >nul || php artisan key:generate --force

php artisan orvet:install --seed --admin-email="admin@orvet.pe"

echo.
echo Instalacion finalizada. Revisa las credenciales mostradas arriba.
echo Apunta el docroot del dominio a la carpeta \public.
pause
