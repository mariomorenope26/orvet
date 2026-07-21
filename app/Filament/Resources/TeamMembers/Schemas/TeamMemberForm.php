<?php

namespace App\Filament\Resources\TeamMembers\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TeamMemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Frente de la tarjeta')
                    ->description('Lo que se muestra al inicio: foto, nombre y cargo.')
                    ->icon('heroicon-o-identification')
                    ->columns(2)
                    ->schema([
                        FileUpload::make('photo')
                            ->label('Foto')
                            ->image()
                            ->disk('public')
                            ->directory('team')
                            ->imageEditor()
                            ->columnSpanFull(),
                        TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('role')
                            ->label('Cargo / Puesto'),
                    ]),

                Section::make('Reverso de la tarjeta')
                    ->description('Lo que aparece al voltear la tarjeta: descripción y datos de contacto. Se conservan los mismos colores.')
                    ->icon('heroicon-o-arrow-path')
                    ->columns(2)
                    ->schema([
                        Textarea::make('description')
                            ->label('Descripción')
                            ->rows(3)
                            ->columnSpanFull(),
                        TextInput::make('phone')
                            ->label('Teléfono')
                            ->tel(),
                        TextInput::make('email')
                            ->label('Correo')
                            ->email(),
                    ]),

                Section::make('Visibilidad')
                    ->columns(2)
                    ->schema([
                        TextInput::make('sort')
                            ->label('Orden')
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_active')
                            ->label('Activo (visible en el sitio)')
                            ->default(true),
                    ]),
            ]);
    }
}
