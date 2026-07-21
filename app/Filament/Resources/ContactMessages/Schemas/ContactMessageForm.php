<?php

namespace App\Filament\Resources\ContactMessages\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactMessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Mensaje recibido')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')->label('Nombre')->disabled(),
                        TextInput::make('email')->label('Correo')->disabled(),
                        TextInput::make('phone')->label('Teléfono')->disabled(),
                        TextInput::make('subject')->label('Asunto')->disabled(),
                        Textarea::make('message')
                            ->label('Mensaje')
                            ->rows(6)
                            ->disabled()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
