<?php

namespace App\Filament\Resources\BlogPosts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BlogPostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Contenido')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Título')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        TextInput::make('category')
                            ->label('Categoría'),
                        FileUpload::make('image')
                            ->label('Imagen destacada')
                            ->image()
                            ->disk('public')
                            ->directory('blog')
                            ->imageEditor(),
                        Textarea::make('excerpt')
                            ->label('Resumen')
                            ->rows(2)
                            ->columnSpanFull(),
                        RichEditor::make('body')
                            ->label('Contenido')
                            ->columnSpanFull(),
                    ]),
                Section::make('Publicación y SEO')
                    ->columns(2)
                    ->schema([
                        DateTimePicker::make('published_at')
                            ->label('Fecha de publicación')
                            ->default(now()),
                        Toggle::make('is_published')
                            ->label('Publicado'),
                        TextInput::make('meta_title')
                            ->label('Meta título (SEO)'),
                        TextInput::make('meta_description')
                            ->label('Meta descripción (SEO)'),
                    ]),
            ]);
    }
}
