<?php

namespace App\Http\Controllers;

use App\Services\DatabaseBackup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BackupController extends Controller
{
    public function __construct(protected DatabaseBackup $backup)
    {
    }

    public function download(string $file): BinaryFileResponse
    {
        abort_unless($this->backup->exists($file), 404);

        return response()->download($this->backup->path($file));
    }

    public function restore(string $file): RedirectResponse
    {
        abort_unless($this->backup->exists($file), 404);

        $this->backup->restore($this->backup->path($file));

        return back()->with('backup_status', "Base de datos restaurada desde «{$file}».");
    }

    public function delete(string $file): RedirectResponse
    {
        $this->backup->delete($file);

        return back()->with('backup_status', "Copia «{$file}» eliminada.");
    }

    public function upload(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimetypes:text/plain,application/sql,application/octet-stream', 'max:51200'],
        ]);

        $this->backup->restore($request->file('file')->getRealPath());

        return back()->with('backup_status', 'Base de datos restaurada desde el archivo subido.');
    }
}
