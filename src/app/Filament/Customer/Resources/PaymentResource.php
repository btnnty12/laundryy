<?php

namespace App\Filament\Customer\Resources;

use App\Filament\Customer\Resources\PaymentResource\Pages;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationLabel = 'My Payments';

    protected static ?string $modelLabel = 'Payment';

    protected static ?string $pluralModelLabel = 'Payments';

    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        // Only show payments for orders belonging to the authenticated customer
        return parent::getEloquentQuery()
            ->whereHas('order', function (Builder $query) {
                $query->where('customer_id', Auth::id());
            })
            ->with(['order', 'order.customer', 'order.service', 'creator']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Customer can only view, not create/edit payments
                Forms\Components\Placeholder::make('readonly_notice')
                    ->content('You can only view your payment history. To make a new payment, please contact our staff.')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order.order_number')
                    ->label('Order Number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('order.service.name')
                    ->label('Service')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('payment_method')
                    ->label('Payment Method')
                    ->colors([
                        'success' => 'cash',
                        'info' => 'transfer',
                        'warning' => 'ewallet',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'cash' => 'Cash',
                        'transfer' => 'Bank Transfer',
                        'ewallet' => 'E-Wallet',
                        default => $state,
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_date')
                    ->label('Payment Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reference_number')
                    ->label('Reference Number')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Processed By')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('notes')
                    ->label('Notes')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_method')
                    ->options([
                        'cash' => 'Cash',
                        'transfer' => 'Bank Transfer',
                        'ewallet' => 'E-Wallet',
                    ]),
                Tables\Filters\Filter::make('payment_date')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('From Date'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Until Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('payment_date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('payment_date', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // No bulk actions for customers
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('No Payments Found')
            ->emptyStateDescription('You haven\'t made any payments yet.')
            ->emptyStateIcon('heroicon-o-credit-card');
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
            'view' => Pages\ViewPayment::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false; // Customers cannot create payments
    }

    public static function canEdit(Model $record): bool
    {
        return false; // Customers cannot edit payments
    }

    public static function canDelete(Model $record): bool
    {
        return false; // Customers cannot delete payments
    }

    public static function canDeleteAny(): bool
    {
        return false; // Customers cannot bulk delete payments
    }
}