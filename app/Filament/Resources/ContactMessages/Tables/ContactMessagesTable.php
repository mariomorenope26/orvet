<?php

namespace App\Filament\Resources\ContactMessages\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContactMessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                IconColumn::make('read_at')
                    ->label('Leído')
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->read_at !== null),
                TextColumn::make('name')
                    ->label('Nombre')
                    ->weight(fn ($record) => $record->read_at ? null : 'bold')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Correo')
                    ->searchable(),
                TextColumn::make('subject')
                    ->label('Asunto')
                    ->limit(40)
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Recibido')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Ver')
                    ->after(fn ($record) => $record->read_at ?? $record->update(['read_at' => now()])),
                Action::make('toggleRead')
                    ->label(fn ($record) => $record->read_at ? 'Marcar no leído' : 'Marcar leído')
                    ->icon('heroicon-o-check')
                    ->action(fn ($record) => $record->update([
                        'read_at' => $record->read_at ? null : now(),
                    ])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
