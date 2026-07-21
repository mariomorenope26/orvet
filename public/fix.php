<?php
/**
 * ORVET · Herramienta de diagnóstico y reparación para producción (sin SSH).
 *
 * Sube este archivo a la carpeta /public, visita  https://tu-dominio/fix.php
 * y ELIMÍNALO al terminar (por seguridad).
 *
 * Limpia cachés compiladas, corre migraciones, enlaza imágenes, resetea el
 * admin y muestra el último error real del log.
 */

error_reporting(E_ALL);
@ini_set('display_errors', '1');
@set_time_limit(300);
@ini_set('memory_limit', '512M');

$root = dirname(__DIR__);
header('Content-Type: text/html; charset=utf-8');
echo "<pre style='font:13px/1.5 ui-monospace,monospace;background:#0f172a;color:#e2e8f0;padding:24px;margin:0;min-height:100vh'>";
echo "== ORVET · Diagnóstico y reparación ==\n\n";

function ok($m) { echo "  \e[32m✓\e[0m ".$m."\n"; }

/* 1) Borrar cachés compiladas (causa #1 de 500 tras cambios) */
$cleared = [];
foreach (glob($root.'/bootstrap/cache/*.php') ?: [] as $f) {
    @unlink($f);
    $cleared[] = basename($f);
}
echo "[1] Cachés compiladas eliminadas: ".(implode(', ', $cleared) ?: 'ninguna')."\n";

/* 2) Limpiar storage/framework (vistas y caché de datos compiladas) */
foreach (['cache/data', 'views'] as $d) {
    foreach (glob($root.'/storage/framework/'.$d.'/*') ?: [] as $f) {
        if (is_file($f)) @unlink($f);
    }
}
echo "[2] storage/framework limpiado\n";

/* 3) Último error real del log (aquí está la causa exacta del 500) */
$log = $root.'/storage/logs/laravel.log';
if (is_file($log) && filesize($log) > 0) {
    $content = file_get_contents($log);
    $tail = substr($content, -3500);
    echo "\n[3] ÚLTIMO ERROR REGISTRADO (laravel.log):\n";
    echo "----------------------------------------------------------\n";
    echo htmlspecialchars($tail);
    echo "\n----------------------------------------------------------\n";
} else {
    echo "\n[3] (log vacío o inexistente)\n";
}

/* 4) Bootstrap + comprobaciones + reparación */
try {
    require $root.'/vendor/autoload.php';
    $app = require $root.'/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    echo "\n[4] Laravel arrancó correctamente\n";

    // Base de datos
    try {
        Illuminate\Support\Facades\DB::connection()->getPdo();
        ok("Base de datos conectada");
        foreach (['settings', 'products', 'categories', 'team_members', 'brands', 'blog_posts', 'users'] as $t) {
            $has = Illuminate\Support\Facades\Schema::hasTable($t);
            $count = $has ? Illuminate\Support\Facades\DB::table($t)->count() : '-';
            echo "      tabla ".str_pad($t, 14)." ".($has ? "OK ($count filas)" : "NO EXISTE")."\n";
        }
        if (Illuminate\Support\Facades\Schema::hasTable('team_members')) {
            $cols = Illuminate\Support\Facades\Schema::getColumnListing('team_members');
            echo "      team_members: ".implode(', ', $cols)."\n";
        }
    } catch (\Throwable $e) {
        echo "  \e[31m✗ DB ERROR:\e[0m ".$e->getMessage()."\n";
    }

    // Migraciones pendientes (agrega columnas nuevas como zone/whatsapp)
    try {
        $kernel->call('migrate', ['--force' => true]);
        echo "[5] Migraciones:\n";
        foreach (explode("\n", trim($kernel->output())) as $l) echo "      ".$l."\n";
    } catch (\Throwable $e) {
        echo "[5] migrate ERROR: ".$e->getMessage()."\n";
    }

    // Enlace de imágenes
    $link = $root.'/public/storage';
    if (! file_exists($link)) {
        if (! @symlink($root.'/storage/app/public', $link)) {
            @mkdir($link, 0775, true);
            $copy = function ($s, $d) use (&$copy) {
                if (! is_dir($s)) return;
                @mkdir($d, 0775, true);
                foreach (scandir($s) as $i) {
                    if ($i === '.' || $i === '..') continue;
                    is_dir("$s/$i") ? $copy("$s/$i", "$d/$i") : @copy("$s/$i", "$d/$i");
                }
            };
            $copy($root.'/storage/app/public', $link);
        }
    }
    ok("Enlace de imágenes (public/storage): ".(file_exists($link) ? 'OK' : 'FALTA'));

    // Admin con contraseña conocida
    try {
        App\Models\User::updateOrCreate(
            ['email' => 'admin@orvet.pe'],
            ['name' => 'Administrador', 'password' => Illuminate\Support\Facades\Hash::make('orvet2026')]
        );
        ok("Admin listo: admin@orvet.pe / orvet2026");
    } catch (\Throwable $e) {
        echo "  \e[31m✗ admin:\e[0m ".$e->getMessage()."\n";
    }

    echo "\n[6] El sitio corre SIN caché compilada (más lento pero estable).\n";
    echo "    Prueba: https://orvet.pe   y   https://orvet.pe/admin\n";

} catch (\Throwable $e) {
    echo "\n[!] ERROR CRÍTICO AL ARRANCAR:\n    ".$e->getMessage()."\n\n";
    echo htmlspecialchars($e->getTraceAsString())."\n";
}

echo "\n==========================================================\n";
echo "IMPORTANTE: elimina ahora  public/fix.php\n";
echo "</pre>";
