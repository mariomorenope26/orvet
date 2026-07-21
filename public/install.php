<?php
/**
 * Instalador web de Orvet (Laravel 13 + Filament v5) para PRODUCCIÓN.
 *
 * Uso:
 *   1) Sube el proyecto ya con `vendor/` (composer install --no-dev) y los
 *      assets compilados (`public/build`, npm run build).
 *   2) Apunta el dominio a la carpeta `/public` (o usa el `.htaccess` de la
 *      raíz incluido, que reenvía a /public si el docroot es la raíz).
 *   3) Visita  https://tu-dominio/install.php  y completa el asistente.
 *   4) ELIMINA este archivo al terminar.
 */

error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
@set_time_limit(900);
@ini_set('memory_limit', '512M');

$root = dirname(__DIR__);
$lockFile = $root.'/storage/installed.lock';
$step = $_GET['step'] ?? 'form';

/* ----------------------------------------------------------------------- *
 *  Utilidades
 * ----------------------------------------------------------------------- */
function envPath(): string { return dirname(__DIR__).'/.env'; }

function readEnv(): string
{
    $p = envPath();
    if (is_file($p)) return file_get_contents($p);
    $example = dirname(__DIR__).'/.env.example';
    return is_file($example) ? file_get_contents($example) : '';
}

function setEnvValue(string $content, string $key, string $value): string
{
    $value = preg_match('/\s/', $value) ? '"'.$value.'"' : $value;
    if (preg_match("/^{$key}=.*$/m", $content)) {
        return preg_replace("/^{$key}=.*$/m", "{$key}={$value}", $content);
    }
    return rtrim($content)."\n{$key}={$value}\n";
}

function requirements(): array
{
    $root = dirname(__DIR__);
    return [
        'PHP >= 8.3 (actual '.PHP_VERSION.')' => version_compare(PHP_VERSION, '8.3.0', '>='),
        'Extensión pdo_mysql' => extension_loaded('pdo_mysql'),
        'Extensión mbstring' => extension_loaded('mbstring'),
        'Extensión openssl' => extension_loaded('openssl'),
        'Extensión curl' => extension_loaded('curl'),
        'Extensión gd con WebP' => extension_loaded('gd') && function_exists('imagewebp'),
        'Extensión fileinfo' => extension_loaded('fileinfo'),
        'Extensión dom/xml' => extension_loaded('dom'),
        'Extensión tokenizer' => extension_loaded('tokenizer'),
        'Extensión ctype' => extension_loaded('ctype'),
        'Dependencias (vendor/)' => is_dir($root.'/vendor'),
        'Assets compilados (public/build)' => is_file($root.'/public/build/manifest.json'),
        'storage/ escribible' => is_writable($root.'/storage'),
        'bootstrap/cache escribible' => is_writable($root.'/bootstrap/cache'),
    ];
}

function h($v): string { return htmlspecialchars((string) $v, ENT_QUOTES); }

function servedFromPublic(): bool
{
    $docroot = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? ''), '/');
    return str_ends_with($docroot, '/public') || str_ends_with($docroot, 'public');
}

/* ----------------------------------------------------------------------- *
 *  Cabecera / pie HTML
 * ----------------------------------------------------------------------- */
function head(): void { ?>
<!doctype html><html lang="es"><head><meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Instalador de Orvet</title>
<style>
  *{box-sizing:border-box} body{font-family:system-ui,Segoe UI,Roboto,sans-serif;background:#f1f5f9;margin:0;color:#1f2937}
  .wrap{max-width:660px;margin:40px auto;padding:0 16px}
  .card{background:#fff;border-radius:16px;box-shadow:0 6px 24px rgba(0,0,0,.06);padding:28px;margin-bottom:20px}
  h1{color:#157040;margin:0 0 4px} h3{margin:22px 0 8px} .sub{color:#6b7280;margin:0 0 20px;font-size:14px}
  label{display:block;font-size:13px;font-weight:600;margin:12px 0 4px}
  input,select{width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:10px;font-size:14px}
  .row{display:flex;gap:12px} .row>div{flex:1}
  .check{display:flex;align-items:center;gap:8px;margin-top:12px} .check input{width:auto}
  button{background:#1f8f4e;color:#fff;border:0;border-radius:999px;padding:12px 26px;font-weight:700;font-size:15px;cursor:pointer;margin-top:20px}
  button:hover{background:#157040}
  ul{list-style:none;padding:0;margin:0} li{display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f1f5f9;font-size:14px}
  .ok{color:#15803d;font-weight:700} .bad{color:#b91c1c;font-weight:700}
  .cred{background:#ecfdf5;border:1px solid #a7f3d0;border-radius:12px;padding:16px;font-family:monospace;font-size:14px}
  .warn{background:#fef3c7;border:1px solid #fcd34d;border-radius:12px;padding:14px;font-size:14px;margin-top:16px}
  .info{background:#eff6ff;border:1px solid #bfdbfe;border-radius:12px;padding:14px;font-size:14px;margin-top:16px}
  pre{background:#0f172a;color:#e2e8f0;padding:14px;border-radius:10px;overflow:auto;font-size:12px}
  code{background:#f1f5f9;padding:1px 6px;border-radius:6px} .logo{font-size:26px;font-weight:800;color:#1f8f4e}
</style></head><body><div class="wrap">
<?php }

function foot(): void { echo '</div></body></html>'; }

/* ----------------------------------------------------------------------- *
 *  ¿Ya instalado?
 * ----------------------------------------------------------------------- */
if (is_file($lockFile) && $step !== 'run') {
    head();
    echo '<div class="card"><h1>Orvet ya está instalado</h1>';
    echo '<p class="sub">Se encontró <code>storage/installed.lock</code>.</p>';
    echo '<div class="warn">Por seguridad, <strong>elimina este archivo <code>install.php</code></strong>. '
        .'Para reinstalar, borra primero <code>storage/installed.lock</code>.</div></div>';
    foot();
    exit;
}

/* ----------------------------------------------------------------------- *
 *  Paso 1: formulario
 * ----------------------------------------------------------------------- */
if ($step === 'form') {
    $reqs = requirements();
    $allOk = ! in_array(false, $reqs, true);
    head();
    echo '<div class="card"><div class="logo">ORVET</div><h1>Instalador de producción</h1>';
    echo '<p class="sub">Configura la base de datos y el administrador para poner el sitio en línea.</p>';

    echo '<h3>Requisitos del servidor</h3><ul>';
    foreach ($reqs as $name => $ok) {
        echo '<li><span>'.h($name).'</span><span class="'.($ok ? 'ok">✓ OK' : 'bad">✗ Falta').'</span></li>';
    }
    echo '</ul>';

    if (! servedFromPublic()) {
        echo '<div class="info">ℹ️ El dominio no apunta a la carpeta <code>/public</code>. '
            .'El archivo <code>.htaccess</code> de la raíz reenviará las peticiones a <code>/public</code>. '
            .'Para un rendimiento óptimo, configura el <em>Document Root</em> del dominio directamente en <code>.../public</code>.</div>';
    }

    if (! $allOk) {
        echo '<div class="warn">Resuelve los requisitos faltantes antes de continuar. '
            .'Si faltan <code>vendor/</code> o <code>public/build</code>, ejecútalos en local antes de subir: '
            .'<code>composer install --no-dev</code> y <code>npm run build</code>.</div></div>';
        foot(); exit;
    }

    $isHttps = (($_SERVER['HTTPS'] ?? '') === 'on') || (($_SERVER['SERVER_PORT'] ?? '') == 443);
    $guessUrl = ($isHttps ? 'https://' : 'http://').($_SERVER['HTTP_HOST'] ?? 'orvet.pe');

    echo '<form method="post" action="?step=run">';
    echo '<h3>Base de datos</h3>';
    echo '<div class="row"><div><label>Host</label><input name="db_host" value="127.0.0.1"></div>'
        .'<div><label>Puerto</label><input name="db_port" value="3306"></div></div>';
    echo '<label>Nombre de la base de datos</label><input name="db_name" value="orvet" required>';
    echo '<div class="row"><div><label>Usuario</label><input name="db_user" value="root"></div>'
        .'<div><label>Contraseña</label><input name="db_pass" type="text"></div></div>';
    echo '<label>URL del sitio</label><input name="app_url" value="'.h($guessUrl).'" required>';
    echo '<div class="check"><input type="checkbox" id="https" name="force_https" value="1"'.($isHttps ? ' checked' : '').'><label for="https" style="margin:0">Forzar HTTPS (recomendado en producción)</label></div>';

    echo '<h3>Administrador</h3>';
    echo '<label>Nombre</label><input name="admin_name" value="Administrador">';
    echo '<label>Correo</label><input name="admin_email" type="email" value="admin@orvet.pe" required>';
    echo '<label>Contraseña</label><input name="admin_pass" type="text" value="" placeholder="Déjala vacía para generar una automáticamente">';

    echo '<h3>Datos iniciales</h3>';
    echo '<label>¿Qué contenido cargar?</label><select name="content">'
        .'<option value="content">Migrar contenido real de orvet.pe + optimizar imágenes (recomendado)</option>'
        .'<option value="seed">Datos de ejemplo (rápido)</option>'
        .'<option value="none">Ninguno (base vacía)</option></select>';

    echo '<button type="submit">Instalar ahora</button></form></div>';
    foot();
    exit;
}

/* ----------------------------------------------------------------------- *
 *  Paso 2: ejecutar instalación
 * ----------------------------------------------------------------------- */
if ($step === 'run' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    head();
    echo '<div class="card"><h1>Instalando…</h1>';

    $appUrl = rtrim($_POST['app_url'] ?? 'http://localhost', '/');
    $forceHttps = ! empty($_POST['force_https']) || str_starts_with($appUrl, 'https://');

    // 1) Escribir .env (endurecido para producción)
    $env = readEnv();
    $env = setEnvValue($env, 'APP_ENV', 'production');
    $env = setEnvValue($env, 'APP_DEBUG', 'false');
    $env = setEnvValue($env, 'APP_URL', $appUrl);
    $env = setEnvValue($env, 'LOG_LEVEL', 'error');
    $env = setEnvValue($env, 'DB_CONNECTION', 'mysql');
    $env = setEnvValue($env, 'DB_HOST', $_POST['db_host'] ?? '127.0.0.1');
    $env = setEnvValue($env, 'DB_PORT', $_POST['db_port'] ?? '3306');
    $env = setEnvValue($env, 'DB_DATABASE', $_POST['db_name'] ?? 'orvet');
    $env = setEnvValue($env, 'DB_USERNAME', $_POST['db_user'] ?? 'root');
    $env = setEnvValue($env, 'DB_PASSWORD', $_POST['db_pass'] ?? '');
    if ($forceHttps) {
        $env = setEnvValue($env, 'SESSION_SECURE_COOKIE', 'true');
    }
    file_put_contents(envPath(), $env);
    echo '<p>✓ Archivo <code>.env</code> configurado (modo producción).</p>';

    // 2) Bootstrap Laravel
    require $root.'/vendor/autoload.php';
    $app = require $root.'/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

    // 3) App key
    if (! preg_match('/^APP_KEY=base64:/m', $env)) {
        $kernel->call('key:generate', ['--force' => true]);
        echo '<p>✓ Clave de aplicación generada.</p>';
    }

    // 4) Probar conexión a la base de datos
    try {
        Illuminate\Support\Facades\DB::connection()->getPdo();
        echo '<p>✓ Conexión a la base de datos correcta.</p>';
    } catch (\Throwable $e) {
        echo '<div class="warn">✗ No se pudo conectar a la base de datos: '.h($e->getMessage()).'</div>';
        echo '<p><a href="?step=form">← Volver y corregir</a></p></div>';
        foot(); exit;
    }

    // 5) Ejecutar la instalación de la aplicación
    $content = $_POST['content'] ?? 'content';
    $options = [
        '--admin-email' => $_POST['admin_email'] ?? 'admin@orvet.pe',
        '--admin-name' => $_POST['admin_name'] ?? 'Administrador',
    ];
    if (! empty($_POST['admin_pass'])) $options['--admin-password'] = $_POST['admin_pass'];
    if ($content === 'seed')    $options['--seed'] = true;
    if ($content === 'content') { $options['--content'] = true; $options['--optimize-images'] = true; }

    $kernel->call('orvet:install', $options);
    $output = $kernel->output();

    // 6) Verificar enlace de storage
    $storageLinked = file_exists($root.'/public/storage');

    // 7) Bloquear reinstalación
    @file_put_contents($lockFile, date('c'));

    preg_match('/Usuario:\s*(.+)/', $output, $mu);
    preg_match('/Contraseña:\s*(.+)/', $output, $mp);

    echo '<p>✓ Instalación ejecutada.</p></div>';

    echo '<div class="card"><h1>¡Listo! 🎉</h1>';
    echo '<p class="sub">El sitio quedó instalado en modo producción.</p>';
    echo '<div class="cred"><strong>Panel:</strong> '.h($appUrl).'/admin<br>';
    echo '<strong>Usuario:</strong> '.h(trim($mu[1] ?? ($_POST['admin_email'] ?? ''))).'<br>';
    echo '<strong>Contraseña:</strong> '.h(trim($mp[1] ?? ($_POST['admin_pass'] ?? '(la que ingresaste)'))).'</div>';

    if (! $storageLinked) {
        echo '<div class="warn">⚠️ No se pudo crear el enlace <code>public/storage</code>. '
            .'Ejecuta por terminal <code>php artisan storage:link</code> o crea el symlink desde el panel de tu hosting.</div>';
    }

    echo '<div class="warn"><strong>IMPORTANTE — hazlo ahora:</strong><br>'
        .'1. Elimina el archivo <code>public/install.php</code>.<br>'
        .'2. Verifica que el dominio use HTTPS.<br>'
        .'3. Cambia la contraseña del administrador tras iniciar sesión.</div>';

    echo '<details style="margin-top:16px"><summary>Ver detalle técnico</summary><pre>'.h($output).'</pre></details>';
    echo '<p style="margin-top:16px"><a href="'.h($appUrl).'/admin">→ Ir al panel de administración</a></p>';
    echo '</div>';
    foot();
    exit;
}

header('Location: install.php?step=form');
