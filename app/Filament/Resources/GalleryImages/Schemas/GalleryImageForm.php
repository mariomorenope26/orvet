<?php

namespace App\Filament\Resources\GalleryImages\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class GalleryImageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('image')
                    ->label('Imagen')
                    ->image()
                    ->disk('public')
                    ->directory('gallery')
                    ->imageEditor()
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('title')
                    ->label('Título / Descripción'),
                Select::make('type')
                    ->label('Tipo de galería')
                    ->options([
                        'team' => 'Nuestro Equipo',
                        'institutional' => 'Institucional (Nosotros)',
                    ])
                    ->default('team')
                    ->required(),
                TextInput::make('sort')
                    ->label('Orden')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->label('Activa')
                    ->default(true),
            ]);
    }
}
