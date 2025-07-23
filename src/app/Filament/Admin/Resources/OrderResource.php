<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\User;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Get;
use Filament\Forms\Set;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Laundry Management';

    protected static ?string $recordTitleAttribute = 'order_number';

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereIn('status', ['pending', 'confirmed', 'in_progress'])->count();
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['order_number', 'customer.name', 'customer.email'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Customer' => $record->customer->name,
            'Status' => $record->status,
            'Total' => 'Rp ' . number_format($record->total_price, 0, ',', '.'),
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\TextInput::make('order_number')
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpan(1),
                        Forms\Components\Select::make('customer_id')
                            ->label('Customer')
                            ->options(User::where('role', 'customer')->pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\Select::make('service_id')
                            ->label('Service')
                            ->options(Service::where('is_active', true)->pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                $service = Service::find($state);
                                $weight = $get('weight');
                                if ($service && $weight) {
                                    $set('total_price', $service->price_per_kg * $weight);
                                }
                            })
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('weight')
                            ->required()
                            ->numeric()
                            ->step(0.1)
                            ->suffix('kg')
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                $serviceId = $get('service_id');
                                if ($serviceId && $state) {
                                    $service = Service::find($serviceId);
                                    if ($service) {
                                        $set('total_price', $service->price_per_kg * $state);
                                    }
                                }
                            })
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('total_price')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated()
                            ->columnSpan(1),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'confirmed' => 'Confirmed',
                                'in_progress' => 'In Progress',
                                'ready' => 'Ready',
                                'delivered' => 'Delivered',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\DatePicker::make('pickup_date')
                            ->columnSpan(1),
                        Forms\Components\DatePicker::make('delivery_date')
                            ->columnSpan(1),
                        Forms\Components\Select::make('payment_status')
                            ->options([
                                'unpaid' => 'Unpaid',
                                'paid' => 'Paid',
                                'refunded' => 'Refunded',
                            ])
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\Textarea::make('notes')
                            ->columnSpan('full')
                            ->rows(3),
                    ]),
                
                Forms\Components\Section::make('Order Items')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('item_type')
                                    ->required()
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('quantity')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->columnSpan(1),
                                Forms\Components\Textarea::make('description')
                                    ->columnSpan(2)
                                    ->rows(2),
                            ])
                            ->columns(4)
                            ->defaultItems(1)
                            ->addActionLabel('Add Item')
                            ->collapsible(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('service.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('weight')
                    ->suffix(' kg')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'pending',
                        'warning' => 'confirmed',
                        'primary' => 'in_progress',
                        'success' => 'ready',
                        'success' => 'delivered',
                        'danger' => 'cancelled',
                    ])
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('payment_status')
                    ->colors([
                        'danger' => 'unpaid',
                        'success' => 'paid',
                        'warning' => 'refunded',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'unpaid' => 'Unpaid',
                        'paid' => 'Paid',
                        'refunded' => 'Refunded',
                    ]),
                Tables\Filters\SelectFilter::make('service')
                    ->relationship('service', 'name'),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}