<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Category;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Producto')
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('Datos generales')
                            ->schema([
                                Section::make()
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Nombre del producto')
                                            ->required()
                                            ->maxLength(255)
                                            ->columnSpanFull(),
                                        TextInput::make('sku')
                                            ->label('SKU / Código'),
                                        Select::make('category_id')
                                            ->label('Categoría')
                                            ->options(fn () => Category::with('parent')->get()
                                                ->mapWithKeys(fn ($c) => [$c->id => $c->label_path]))
                                            ->searchable()
                                            ->preload(),
                                        Select::make('brand_id')
                                            ->label('Marca')
                                            ->relationship('brand', 'name')
                                            ->searchable()
                                            ->preload(),
                                        TextInput::make('laboratory')
                                            ->label('Laboratorio fabricante'),
                                        TextInput::make('presentation')
                                            ->label('Presentación (ej. Frasco 100 ml)'),
                                        TextInput::make('price')
                                            ->label('Precio (S/)')
                                            ->numeric()
                                            ->prefix('S/'),
                                        TextInput::make('stock')
                                            ->label('Stock')
                                            ->numeric()
                                            ->default(0),
                                        Select::make('availability')
                                            ->label('Disponibilidad')
                                            ->options([
                                                'in_stock' => 'En stock',
                                                'out_of_stock' => 'Agotado',
                                                'on_request' => 'A pedido',
                                            ])
                                            ->default('in_stock'),
                                        Textarea::make('short_description')
                                            ->label('Descripción corta')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                        TagsInput::make('tags')
                                            ->label('Etiquetas')
                                            ->columnSpanFull(),
                                    ]),
                                Grid::make(3)->schema([
                                    Toggle::make('is_featured')->label('Destacado en la home'),
                                    Toggle::make('is_active')->label('Activo')->default(true),
                                    TextInput::make('sort')->label('Orden')->numeric()->default(0),
                                ]),
                            ]),
                        Tab::make('Composición')
                            ->schema([
                                Repeater::make('specs')
                                    ->label('Principios activos / concentración')
                                    ->relationship('specs')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('active_ingredient')
                                            ->label('Principio activo')
                                            ->required(),
                                        TextInput::make('concentration')
                                            ->label('Concentración'),
                                    ])
                                    ->orderColumn('sort')
                                    ->reorderable()
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => $state['active_ingredient'] ?? null)
                                    ->addActionLabel('Agregar principio activo'),
                            ]),
                        Tab::make('Secciones descriptivas')
                            ->schema([
                                Repeater::make('sections')
                                    ->label('Secciones (Propiedades, Indicaciones, Dosis...)')
                                    ->relationship('sections')
                                    ->schema([
                                        TextInput::make('title')
                                            ->label('Título de la sección')
                                            ->required(),
                                        RichEditor::make('body')
                                            ->label('Contenido'),
                                    ])
                                    ->orderColumn('sort')
                                    ->reorderable()
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                                    ->addActionLabel('Agregar sección'),
                            ]),
                        Tab::make('Imágenes y SEO')
                            ->schema([
                                FileUpload::make('image')
                                    ->label('Imagen principal')
                                    ->image()
                                    ->disk('public')
                                    ->directory('products')
                                    ->imageEditor(),
                                Repeater::make('images')
                                    ->label('Imágenes adicionales')
                                    ->relationship('images')
                                    ->schema([
                                        FileUpload::make('image')
                                            ->label('Imagen')
                                            ->image()
                                            ->disk('public')
                                            ->directory('products')
                                            ->required(),
                                    ])
                                    ->orderColumn('sort')
                                    ->reorderable()
                                    ->addActionLabel('Agregar imagen'),
                                TextInput::make('meta_title')
                                    ->label('Meta título (SEO)')
                                    ->maxLength(255),
                                Textarea::make('meta_description')
                                    ->label('Meta descripción (SEO)')
                                    ->rows(2),
                            ]),
                    ]),
            ]);
    }
}
