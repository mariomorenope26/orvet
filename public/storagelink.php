<?php
/**
 * Ayudante para publicar las imágenes en producción.
 *
 * Crea el enlace  public/storage -> ../storage/app/public
 * y, si el hosting no permite symlinks, COPIA los archivos.
 *
 * Súbelo a la carpeta /public, visita  https://tu-dominio/storagelink.php
 * y ELIMÍNALO al terminar.
 */

error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
header('Content-Type: text/html; charset=utf-8');

$target = dirname(__DIR__).'/storage/app/public';
$link = __DIR__.'/storage';

function copyDir(string $src, string $dst): void
{
    if (! is_dir($src)) return;
    @mkdir($dst, 0775, true);
    foreach (scandir($src) as $item) {
        if ($item === '.' || $item === '..') continue;
        $from = $src.DIRECTORY_SEPARATOR.$item;
        $to = $dst.DIRECTORY_SEPARATOR.$item;
        is_dir($from) ? copyDir($from, $to) : @copy($from, $to);
    }
}

echo '<!doctype html><meta charset="utf-8"><title>Publicar imágenes — Orvet</title>';
echo '<body style="font-family:system-ui;max-width:640px;margin:40px auto;padding:0 16px;color:#1f2937">';
echo '<h1 style="color:#157040">Publicar imágenes</h1>';

if (! is_dir($target)) {
    echo '<p style="color:#b91c1c">✗ No se encontró <code>storage/app/public</code>. Sube primero los archivos del proyecto.</p>';
    exit;
}

$method = '';
if (is_link($link) || (is_dir($link) && count(@scandir($link)) > 2)) {
    $method = 'Ya existía';
} elseif (@symlink($target, $link)) {
    $method = 'Enlace simbólico creado';
} else {
    // No se permiten symlinks: copiar.
    if (is_file($link)) @unlink($link);
    copyDir($target, $link);
    $method = 'Symlink no permitido → archivos copiados';
}

$ok = is_dir($link) || is_link($link);
$count = 0;
foreach (['products', 'brands', 'team', 'slides', 'categories', 'brand', 'blog'] as $d) {
    if (is_dir($link.'/'.$d)) $count += count(scandir($link.'/'.$d)) - 2;
}

echo $ok
    ? '<p style="color:#15803d">✓ '.$method.'. Imágenes disponibles: <strong>'.$count.'</strong></p>'
    : '<p style="color:#b91c1c">✗ No se pudo crear <code>public/storage</code>.</p>';

echo '<p>Prueba una imagen: <a href="storage/products/" target="_blank">/storage/products/</a></p>';
echo '<div style="background:#fef3c7;border:1px solid #fcd34d;border-radius:12px;padding:14px;margin-top:16px">';
echo '<strong>IMPORTANTE:</strong> elimina ahora <code>public/storagelink.php</code>.</div>';
echo '</body>';
