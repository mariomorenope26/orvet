<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class Manual extends Page
{
    protected string $view = 'filament.pages.manual';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static string|UnitEnum|null $navigationGroup = 'Ayuda';

    protected static ?string $navigationLabel = 'Manual de uso';

    protected static ?string $title = 'Manual de uso del panel de administración';

    protected static ?int $navigationSort = 99;
}
