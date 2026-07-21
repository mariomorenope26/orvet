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
                    ->description('Lo que se muestra al inicio. La foto es opcional.')
                    ->icon('heroicon-o-identification')
                    ->columns(2)
                    ->schema([
                        FileUpload::make('photo')
                            ->label('Foto (opcional)')
                            ->image()
                            ->disk('public')
                            ->directory('team')
                            ->imageEditor()
                            ->columnSpanFull(),
                        TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('zone')
                            ->label('Zona')
                            ->placeholder('Ej. Chiclayo, Norte, Sur...')
                            ->maxLength(255),
                        TextInput::make('role')
                            ->label('Cargo / Puesto'),
                    ]),

                Section::make('Reverso de la tarjeta')
                    ->description('Se muestra al pasar el mouse (o tocar). El número de WhatsApp será un enlace para escribirle al empleado.')
                    ->icon('heroicon-o-arrow-path')
                    ->columns(2)
                    ->schema([
                        TextInput::make('whatsapp')
                            ->label('N° de WhatsApp')
                            ->placeholder('Ej. 979 699 808 o 51979699808')
                            ->helperText('Al pulsarlo en la web se abrirá un chat de WhatsApp con esta persona.'),
                        TextInput::make('phone')
                            ->label('Teléfono (opcional)')
                            ->tel(),
                        TextInput::make('email')
                            ->label('Correo (opcional)')
                            ->email(),
                        Textarea::make('description')
                            ->label('Descripción')
                            ->rows(3)
                            ->columnSpanFull(),
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
