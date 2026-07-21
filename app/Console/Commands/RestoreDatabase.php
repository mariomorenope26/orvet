<?php

namespace App\Console\Commands;

use App\Services\DatabaseBackup;
use Illuminate\Console\Command;

class RestoreDatabase extends Command
{
    protected $signature = 'orvet:restore {file? : Nombre del archivo .sql en storage/app/backups} {--force : No pedir confirmación}';

    protected $description = 'Restaura la base de datos desde una copia de seguridad.';

    public function handle(DatabaseBackup $backup): int
    {
        $file = $this->argument('file');

        if (! $file) {
            $backups = $backup->all();
            if (empty($backups)) {
                $this->error('No hay copias de seguridad en storage/app/backups.');
                return self::FAILURE;
            }
            $file = $this->choice('Elige la copia a restaurar', array_column($backups, 'name'), 0);
        }

        if (! $backup->exists($file)) {
            $this->error("No se encontró la copia: {$file}");
            return self::FAILURE;
        }

        if (! $this->option('force') && ! $this->confirm("Esto REEMPLAZARÁ todos los datos actuales por los de «{$file}». ¿Continuar?")) {
            $this->comment('Operación cancelada.');
            return self::SUCCESS;
        }

        $this->info('Restaurando...');
        $backup->restore($backup->path($file));
        $this->info('✓ Base de datos restaurada desde '.$file);

        return self::SUCCESS;
    }
}
