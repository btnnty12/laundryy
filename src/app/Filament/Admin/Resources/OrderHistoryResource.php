<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\OrderHistoryResource\Pages;
use App\Models\OrderHistory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrderHistoryResource extends Resource
{
    protected static ?string $model = OrderHistory::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationGroup = 'Order Management';

    protected static ?string $navigationLabel = 'Order History';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('order_id')
                    ->relationship('order', 'order_number')
                    ->required()
                    ->searchable()
                    ->preload(),
                    
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'in_progress' => 'In Progress',
                        'ready' => 'Ready',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required(),
                    
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
                    
                Forms\Components\Select::make('changed_by')
                    ->relationship('changedBy', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
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
                    
                Tables\Columns\TextColumn::make('order.customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'confirmed',
                        'primary' => 'in_progress',
                        'success' => 'ready',
                        'success' => 'delivered',
                        'danger' => 'cancelled',
                    ]),
                    
                Tables\Columns\TextColumn::make('notes')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        
                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }
                        
                        return $state;
                    }),
                    
                Tables\Columns\TextColumn::make('changedBy.name')
                    ->label('Changed By')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'in_progress' => 'In Progress',
                        'ready' => 'Ready',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ]),
                    
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListOrderHistories::route('/'),
            'create' => Pages\CreateOrderHistory::route('/create'),
            'edit' => Pages\EditOrderHistory::route('/{record}/edit'),
        ];
    }
}