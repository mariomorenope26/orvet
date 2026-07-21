<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Copia de seguridad y restauración de la base de datos en formato SQL puro
 * (sin depender de mysqldump), portable para cualquier hosting.
 */
class DatabaseBackup
{
    protected string $disk = 'local';

    protected string $folder = 'backups';

    public function directory(): string
    {
        return storage_path('app/'.$this->folder);
    }

    public function path(string $file): string
    {
        return $this->directory().DIRECTORY_SEPARATOR.basename($file);
    }

    /** Genera una copia de seguridad y devuelve el nombre del archivo. */
    public function create(): string
    {
        if (! is_dir($this->directory())) {
            mkdir($this->directory(), 0775, true);
        }

        $conn = DB::connection();
        $pdo = $conn->getPdo();
        $name = 'backup-'.Carbon::now()->format('Y-m-d_His').'.sql';
        $handle = fopen($this->path($name), 'w');

        fwrite($handle, "-- Orvet · copia de seguridad de `{$conn->getDatabaseName()}`\n");
        fwrite($handle, '-- Generada: '.Carbon::now()->toDateTimeString()."\n");
        fwrite($handle, "SET NAMES utf8mb4;\nSET FOREIGN_KEY_CHECKS = 0;\n\n");

        foreach ($conn->select('SHOW TABLES') as $row) {
            $table = array_values((array) $row)[0];

            $create = (array) $conn->select("SHOW CREATE TABLE `{$table}`")[0];
            $createSql = $create['Create Table'] ?? $create['Create View'] ?? null;
            if (! $createSql) {
                continue;
            }

            fwrite($handle, "DROP TABLE IF EXISTS `{$table}`;\n{$createSql};\n\n");

            $columns = [];
            foreach ($conn->table($table)->cursor() as $record) {
                $data = (array) $record;
                if (empty($columns)) {
                    $columns = array_keys($data);
                }
                $values = array_map(
                    fn ($value) => is_null($value) ? 'NULL' : $pdo->quote((string) $value),
                    array_values($data)
                );
                fwrite($handle, "INSERT INTO `{$table}` (`".implode('`, `', $columns).'`) VALUES ('.implode(', ', $values).");\n");
            }
            fwrite($handle, "\n");
        }

        fwrite($handle, "SET FOREIGN_KEY_CHECKS = 1;\n");
        fclose($handle);

        return $name;
    }

    /** Restaura la base de datos desde un archivo .sql. */
    public function restore(string $absolutePath): void
    {
        if (! is_file($absolutePath)) {
            throw new \RuntimeException("No se encontró el archivo de respaldo: {$absolutePath}");
        }

        $sql = file_get_contents($absolutePath);
        DB::connection()->getPdo()->exec($sql);
    }

    /** @return array<int, array{name:string, size:int, date:Carbon}> */
    public function all(): array
    {
        if (! is_dir($this->directory())) {
            return [];
        }

        $files = glob($this->directory().DIRECTORY_SEPARATOR.'*.sql') ?: [];
        $items = [];
        foreach ($files as $file) {
            $items[] = [
                'name' => basename($file),
                'size' => filesize($file),
                'date' => Carbon::createFromTimestamp(filemtime($file)),
            ];
        }

        usort($items, fn ($a, $b) => $b['date'] <=> $a['date']);

        return $items;
    }

    public function delete(string $file): void
    {
        $path = $this->path($file);
        if (is_file($path)) {
            unlink($path);
        }
    }

    public function exists(string $file): bool
    {
        return is_file($this->path($file));
    }
}
