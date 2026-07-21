<?php

namespace App\Console\Commands;

use App\Models\Brand;
use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Setting;
use App\Models\Slide;
use App\Models\TeamMember;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class OptimizeImages extends Command
{
    protected $signature = 'images:optimize {--quality=80 : Calidad WebP (0-100)}';

    protected $description = 'Convierte las imágenes del sitio a WebP (redimensionando) y actualiza las referencias para ganar velocidad.';

    protected int $quality = 80;

    protected array $stats = ['converted' => 0, 'skipped' => 0, 'before' => 0, 'after' => 0];

    public function handle(): int
    {
        if (! function_exists('imagewebp')) {
            $this->error('La extensión GD con soporte WebP no está disponible.');
            return self::FAILURE;
        }

        $this->quality = (int) $this->option('quality');

        $groups = [
            'Productos (principal)' => [Product::all(), 'image', 900],
            'Productos (galería)' => [ProductImage::all(), 'image', 900],
            'Marcas' => [Brand::all(), 'logo', 400],
            'Categorías' => [Category::all(), 'image', 1200],
            'Slides' => [Slide::all(), 'image', 1920],
            'Equipo' => [TeamMember::all(), 'photo', 800],
            'Blog' => [BlogPost::all(), 'image', 1200],
            'Ajustes (logo)' => [collect([Setting::current()]), 'logo', 500],
        ];

        foreach ($groups as $label => [$records, $field, $maxWidth]) {
            $this->line("Optimizando: {$label}...");
            foreach ($records as $record) {
                $this->convert($record, $field, $maxWidth);
            }
        }

        $saved = $this->stats['before'] - $this->stats['after'];
        $this->newLine();
        $this->info('Optimización completada.');
        $this->table(['Métrica', 'Valor'], [
            ['Imágenes convertidas', $this->stats['converted']],
            ['Omitidas (ya WebP / sin archivo)', $this->stats['skipped']],
            ['Peso antes', $this->human($this->stats['before'])],
            ['Peso después', $this->human($this->stats['after'])],
            ['Ahorro', $this->human($saved).($this->stats['before'] ? ' ('.round($saved / $this->stats['before'] * 100).'%)' : '')],
        ]);

        return self::SUCCESS;
    }

    protected function convert(Model $record, string $field, int $maxWidth): void
    {
        $path = $record->{$field};

        if (blank($path) || str_ends_with(strtolower($path), '.webp')) {
            $this->stats['skipped']++;
            return;
        }

        // Si el original ya no existe, puede haber sido convertido por otro
        // registro que compartía el mismo archivo: reapunta al .webp existente.
        if (! Storage::disk('public')->exists($path)) {
            $candidate = preg_replace('/\.(jpe?g|png|gif|bmp)$/i', '.webp', $path);
            if ($candidate !== $path && Storage::disk('public')->exists($candidate)) {
                $record->update([$field => $candidate]);
                $this->stats['converted']++;
            } else {
                $this->stats['skipped']++;
            }
            return;
        }

        $data = Storage::disk('public')->get($path);
        $webp = $this->toWebp($data, $maxWidth);

        if ($webp === null) {
            $this->stats['skipped']++;
            return;
        }

        $newPath = preg_replace('/\.(jpe?g|png|gif|bmp|webp)$/i', '.webp', $path);
        if ($newPath === $path) {
            $newPath .= '.webp';
        }

        Storage::disk('public')->put($newPath, $webp);
        if ($newPath !== $path) {
            Storage::disk('public')->delete($path);
        }
        $record->update([$field => $newPath]);

        $this->stats['converted']++;
        $this->stats['before'] += strlen($data);
        $this->stats['after'] += strlen($webp);
    }

    protected function toWebp(string $data, int $maxWidth): ?string
    {
        $src = @imagecreatefromstring($data);
        if (! $src) {
            return null;
        }

        $w = imagesx($src);
        $h = imagesy($src);
        $nw = $w;
        $nh = $h;
        if ($maxWidth && $w > $maxWidth) {
            $nw = $maxWidth;
            $nh = (int) round($h * $maxWidth / $w);
        }

        $dst = imagecreatetruecolor($nw, $nh);
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
        imagefilledrectangle($dst, 0, 0, $nw, $nh, $transparent);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);

        ob_start();
        imagewebp($dst, null, $this->quality);
        $out = ob_get_clean();

        imagedestroy($src);
        imagedestroy($dst);

        return $out ?: null;
    }

    protected function human(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2).' MB';
        }

        return round($bytes / 1024).' KB';
    }
}
