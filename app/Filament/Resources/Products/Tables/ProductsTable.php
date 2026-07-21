<?php

namespace App\Filament\Resources\Products\Tables;

use App\Models\Brand;
use App\Models\Category;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                ImageColumn::make('image')
                    ->label('Imagen')
                    ->disk('public')
                    ->height(45),
                TextColumn::make('name')
                    ->label('Producto')
                    ->description(fn ($record) => $record->sku)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Categoría')
                    ->badge()
                    ->searchable(),
                TextColumn::make('brand.name')
                    ->label('Marca')
                    ->placeholder('—'),
                TextColumn::make('price')
                    ->label('Precio')
                    ->money('PEN')
                    ->placeholder('—')
                    ->sortable(),
                TextColumn::make('stock')
                    ->label('Stock')
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'danger'),
                IconColumn::make('is_featured')
                    ->label('Destacado')
                    ->boolean(),
                IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Categoría')
                    ->options(fn () => Category::orderBy('name')->pluck('name', 'id')),
                SelectFilter::make('brand_id')
                    ->label('Marca')
                    ->options(fn () => Brand::orderBy('name')->pluck('name', 'id')),
                TernaryFilter::make('is_active')->label('Activo'),
                TernaryFilter::make('is_featured')->label('Destacado'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
