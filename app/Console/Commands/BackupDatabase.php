<?php

namespace App\Console\Commands;

use App\Services\DatabaseBackup;
use Illuminate\Console\Command;

class BackupDatabase extends Command
{
    protected $signature = 'orvet:backup';

    protected $description = 'Crea una copia de seguridad de la base de datos en storage/app/backups.';

    public function handle(DatabaseBackup $backup): int
    {
        $this->info('Generando copia de seguridad...');
        $file = $backup->create();

        $this->info('✓ Copia creada: '.$file);
        $this->line('  Ubicación: '.$backup->path($file));

        return self::SUCCESS;
    }
}
