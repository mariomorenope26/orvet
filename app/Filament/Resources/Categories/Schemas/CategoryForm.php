<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255),
                Select::make('parent_id')
                    ->label('Categoría padre')
                    ->helperText('Déjala vacía si es una categoría principal (ej. Animales Mayores).')
                    ->options(fn () => Category::whereNull('parent_id')->pluck('name', 'id'))
                    ->searchable()
                    ->nullable(),
                FileUpload::make('image')
                    ->label('Imagen de portada')
                    ->image()
                    ->disk('public')
                    ->directory('categories')
                    ->imageEditor(),
                Textarea::make('description')
                    ->label('Descripción')
                    ->rows(3)
                    ->columnSpanFull(),
                TextInput::make('sort')
                    ->label('Orden')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_featured')
                    ->label('Destacada en la home'),
                Toggle::make('is_active')
                    ->label('Activa')
                    ->default(true),
            ]);
    }
}
