<?php

namespace App\Filament\Resources\Slides\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SlideForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('image')
                    ->label('Imagen de fondo')
                    ->image()
                    ->disk('public')
                    ->directory('slides')
                    ->imageEditor()
                    ->columnSpanFull(),
                TextInput::make('title')
                    ->label('Título')
                    ->maxLength(255),
                TextInput::make('subtitle')
                    ->label('Subtítulo')
                    ->maxLength(255),
                TextInput::make('button_text')
                    ->label('Texto del botón'),
                TextInput::make('button_url')
                    ->label('Enlace del botón')
                    ->url(),
                TextInput::make('sort')
                    ->label('Orden')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->label('Activo')
                    ->default(true),
            ]);
    }
}
