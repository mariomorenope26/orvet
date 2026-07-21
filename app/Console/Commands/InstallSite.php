<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class InstallSite extends Command
{
    protected $signature = 'orvet:install
        {--fresh : Recrea todas las tablas (borra los datos existentes)}
        {--seed : Carga los datos de ejemplo}
        {--content : Migra el contenido real desde orvet.pe (requiere internet)}
        {--optimize-images : Convierte las imágenes a WebP tras migrar contenido}
        {--admin-email=admin@orvet.pe : Correo del administrador}
        {--admin-password= : Contraseña del administrador (si se omite, se genera una)}
        {--admin-name=Administrador : Nombre del administrador}';

    protected $description = 'Instala el sitio Orvet: migraciones, contenido, enlace de storage, usuario admin y caché.';

    public function handle(): int
    {
        $this->info('== Instalación de Orvet ==');

        // 0. Directorios de almacenamiento (por si el paquete subido no los incluye)
        $this->task('Preparando directorios de almacenamiento', function () {
            foreach ([
                storage_path('framework/cache/data'),
                storage_path('framework/sessions'),
                storage_path('framework/views'),
                storage_path('logs'),
                storage_path('app/public'),
                storage_path('app/backups'),
                base_path('bootstrap/cache'),
            ] as $dir) {
                if (! is_dir($dir)) {
                    mkdir($dir, 0775, true);
                }
            }
        });

        // 1. Migraciones
        $this->task('Ejecutando migraciones', function () {
            Artisan::call($this->option('fresh') ? 'migrate:fresh' : 'migrate', ['--force' => true]);
        });

        // 2. Contenido
        if ($this->option('content')) {
            $this->task('Migrando contenido real de orvet.pe', fn () => Artisan::call('orvet:migrate'));
            if ($this->option('optimize-images')) {
                $this->task('Optimizando imágenes a WebP', fn () => Artisan::call('images:optimize'));
            }
        } elseif ($this->option('seed')) {
            $this->task('Cargando datos de ejemplo', function () {
                Artisan::call('db:seed', ['--force' => true]);
                Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\BlogSeeder', '--force' => true]);
            });
        }

        // 3. Storage link (con respaldo por copia si el hosting no permite symlinks)
        $this->task('Enlazando almacenamiento público', function () {
            if (file_exists(public_path('storage'))) {
                return;
            }
            try {
                Artisan::call('storage:link');
            } catch (\Throwable $e) {
                // Algunos hostings (cPanel) bloquean symlink(): se copia el contenido.
                @mkdir(public_path('storage'), 0775, true);
                $this->copyDirectory(storage_path('app/public'), public_path('storage'));
            }
        });

        // 4. Administrador
        $password = $this->option('admin-password') ?: str()->password(12);
        $email = $this->option('admin-email');

        $this->task('Creando/actualizando administrador', function () use ($email, $password) {
            User::updateOrCreate(
                ['email' => $email],
                ['name' => $this->option('admin-name'), 'password' => Hash::make($password)]
            );
        });

        // 5. Caché de producción
        $this->task('Optimizando la aplicación', function () {
            Artisan::call('optimize:clear');
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');
        });

        $this->newLine();
        $this->info('✓ Instalación completada.');
        $this->newLine();
        $this->line('  Panel de administración: '.rtrim(config('app.url'), '/').'/admin');
        $this->line('  Usuario: '.$email);
        $this->line('  Contraseña: '.$password);
        $this->newLine();
        $this->warn('  Guarda estas credenciales y cámbialas tras el primer inicio de sesión.');

        return self::SUCCESS;
    }

    protected function task(string $label, callable $callback): void
    {
        $this->output->write("  → {$label}... ");
        try {
            $callback();
            $this->output->writeln('<info>OK</info>');
        } catch (\Throwable $e) {
            $this->output->writeln('<error>ERROR</error>');
            $this->error('    '.$e->getMessage());
        }
    }

    /** Copia recursiva (respaldo cuando no se pueden crear symlinks). */
    protected function copyDirectory(string $source, string $destination): void
    {
        if (! is_dir($source)) {
            return;
        }
        @mkdir($destination, 0775, true);
        foreach (scandir($source) as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $from = $source.DIRECTORY_SEPARATOR.$item;
            $to = $destination.DIRECTORY_SEPARATOR.$item;
            is_dir($from) ? $this->copyDirectory($from, $to) : @copy($from, $to);
        }
    }
}
