<?php

namespace App\Filament\Pages;

use App\Services\DatabaseBackup;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class Backups extends Page
{
    protected string $view = 'filament.pages.backups';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCircleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Configuración';

    protected static ?string $navigationLabel = 'Copias de seguridad';

    protected static ?string $title = 'Copias de seguridad de la base de datos';

    /** @return array<int, array{name:string, size:int, date:\Illuminate\Support\Carbon}> */
    public function getBackups(): array
    {
        return app(DatabaseBackup::class)->all();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('create')
                ->label('Crear copia de seguridad')
                ->icon('heroicon-o-plus-circle')
                ->action(function () {
                    $file = app(DatabaseBackup::class)->create();

                    Notification::make()
                        ->success()
                        ->title('Copia creada')
                        ->body($file)
                        ->send();
                }),
        ];
    }

    public function humanSize(int $bytes): string
    {
        return $bytes >= 1048576
            ? round($bytes / 1048576, 2).' MB'
            : round($bytes / 1024).' KB';
    }
}
