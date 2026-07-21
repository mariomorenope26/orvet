# Orvet — Sitio web (Laravel + MySQL + Filament)

Reconstrucción del sitio **orvet.pe** según `plan_trabajo_orvet_laravel.md`.
Distribuidora de productos veterinarios — Av. Fraternidad Nº 140, La Victoria, Chiclayo.

## Stack

- **Laravel 13** (PHP 8.3)
- **MySQL 8** (base de datos: `orvet`)
- **Filament v5** — panel de administración "todo editable"
- **Blade + Tailwind CSS v4** (Vite) — frontend público responsive

## Acceso

| | URL | Credenciales |
|---|---|---|
| Sitio público | `http://orvet.oo` (Laragon) | — |
| Panel admin | `http://orvet.oo/admin` | `admin@orvet.pe` / `orvet2026` |

> Laragon crea el virtual host automáticamente con el TLD `.oo` (dominio `orvet.oo`).
> Si no usas Laragon, ejecuta `php artisan serve` y entra a `http://127.0.0.1:8000`.

## Instalación

### Opción A — Instalador web (producción)
1. Sube el proyecto y ejecuta `composer install --no-dev` y `npm ci && npm run build`.
2. Apunta el docroot del dominio a la carpeta **`/public`**.
3. Visita `https://tu-dominio/install.php` y sigue el asistente (requisitos, base de datos, admin, datos iniciales).
4. **Elimina `public/install.php`** al terminar.

### Opción B — Terminal
```bash
bash install.sh        # Linux/Mac    (o  install.bat  en Windows)
# equivale a:
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan orvet:install --seed --admin-email="admin@orvet.pe"
```

`php artisan orvet:install` acepta:
`--fresh` (recrea tablas) · `--seed` (datos de ejemplo) · `--content` (migra contenido real de orvet.pe) ·
`--optimize-images` · `--admin-email=` · `--admin-password=`.

## Comandos útiles

| Comando | Acción |
|---|---|
| `php artisan orvet:install` | Instalación completa (migraciones, datos, admin, caché) |
| `php artisan orvet:migrate` | Migra el contenido real desde orvet.pe (API pública) |
| `php artisan images:optimize` | Convierte imágenes a WebP (−82% de peso) |
| `php artisan orvet:backup` | Crea una copia de seguridad de la BD en `storage/app/backups` |
| `php artisan orvet:restore [archivo] --force` | Restaura la BD desde una copia |

## Copias de seguridad (panel)

**Panel → Configuración → Copias de seguridad**:
- Botón **Crear copia de seguridad** (genera un `.sql`).
- **Descargar**, **Restaurar** o **Eliminar** cada copia.
- **Restaurar desde un archivo** subiendo un `.sql`.

Las copias se guardan en `storage/app/backups` (formato SQL puro, sin depender de `mysqldump`).

## Qué es editable desde el panel (`/admin`)

- **Ajustes generales**: logo, colores, contacto, horario, redes, mapa, textos de Nosotros, footer y legales.
- **Slider (Home)**, **Marcas**, **Categorías**, **Productos** (composición, secciones, imágenes, SEO).
- **Nuestro Equipo**: tarjetas volteables (flashcard) con **frente y reverso editables** (foto, nombre, cargo, descripción, teléfono, correo).
- **Blog**, **Galería** y bandeja de **Mensajes de contacto**.
- **Copias de seguridad** de la base de datos.

## SEO

- `sitemap.xml` dinámico (`/sitemap.xml`) con páginas, categorías, productos y blog.
- `robots.txt` (`/robots.txt`) apuntando al sitemap.
- Meta tags editables por producto y página; redirecciones 301 desde las URLs antiguas de WordPress.

## Modo de venta

**Modo cotización** (catálogo + WhatsApp por producto). Cambiable a e-commerce en *Ajustes → Identidad*.

## Notas

- Contenido migrado desde orvet.pe (50 productos, categorías, marcas, imágenes) como set editable.
- Imágenes optimizadas a WebP. Tras re-migrar contenido, ejecuta de nuevo `php artisan images:optimize`.
- Sitio desarrollado por **JMMS Solutions EIRL** · +51 959 199 368.
