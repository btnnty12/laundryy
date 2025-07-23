<?php

namespace App\Filament\Customer\Resources;

use App\Filament\Customer\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationLabel = 'Services';

    protected static ?string $pluralModelLabel = 'Services';

    protected static ?string $modelLabel = 'Service';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Read-only form for viewing service details
                Forms\Components\TextInput::make('name')
                    ->label('Service Name')
                    ->disabled(),
                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->disabled()
                    ->rows(3),
                Forms\Components\TextInput::make('price_per_kg')
                    ->label('Price per Kg')
                    ->prefix('Rp')
                    ->disabled(),
                Forms\Components\TextInput::make('estimated_hours')
                    ->label('Estimated Hours')
                    ->suffix('hours')
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Service Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),
                Tables\Columns\TextColumn::make('price_per_kg')
                    ->label('Price per Kg')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('estimated_hours')
                    ->label('Estimated Time')
                    ->suffix(' hours')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Available')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('active_only')
                    ->label('Available Services Only')
                    ->query(fn (Builder $query): Builder => $query->where('is_active', true))
                    ->default(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // No bulk actions for customers
            ])
            ->defaultSort('name')
            ->query(fn (): Builder => Service::query()->where('is_active', true));
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'view' => Pages\ViewService::route('/{record}'),
        ];
    }

    // Disable create, edit, delete for customers
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }
}