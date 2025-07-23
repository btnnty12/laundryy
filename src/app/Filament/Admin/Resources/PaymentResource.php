<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PaymentResource\Pages;
use App\Models\Payment;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Laundry Management';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereDate('created_at', today())->count();
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['order.order_number', 'reference_number'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Order' => $record->order->order_number,
            'Amount' => 'Rp ' . number_format($record->amount, 0, ',', '.'),
            'Method' => ucfirst($record->payment_method),
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\Select::make('order_id')
                            ->label('Order')
                            ->options(Order::with('customer')->get()->pluck('order_number_with_customer', 'id'))
                            ->searchable()
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('amount')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->step(0.01)
                            ->columnSpan(1),
                        Forms\Components\Select::make('payment_method')
                            ->options([
                                'cash' => 'Cash',
                                'transfer' => 'Transfer',
                                'ewallet' => 'E-Wallet',
                            ])
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\DateTimePicker::make('payment_date')
                            ->required()
                            ->default(now())
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('reference_number')
                            ->maxLength(255)
                            ->columnSpan(1),
                        Forms\Components\Textarea::make('notes')
                            ->columnSpan('full')
                            ->rows(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order.order_number')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('order.customer.name')
                    ->label('Customer')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('payment_method')
                    ->colors([
                        'success' => 'cash',
                        'primary' => 'transfer',
                        'warning' => 'ewallet',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reference_number')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Created By')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_method')
                    ->options([
                        'cash' => 'Cash',
                        'transfer' => 'Transfer',
                        'ewallet' => 'E-Wallet',
                    ]),
                Tables\Filters\Filter::make('payment_date')
                    ->form([
                        Forms\Components\DatePicker::make('payment_from'),
                        Forms\Components\DatePicker::make('payment_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['payment_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('payment_date', '>=', $date),
                            )
                            ->when(
                                $data['payment_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('payment_date', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}