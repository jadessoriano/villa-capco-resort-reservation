<?php

namespace App\Filament\Resources;

use App\Enums;
use App\Facades\Format;
use App\Filament\Resources\PaymentResource\Pages;
use App\Filament\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationGroup = 'Transactions';

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function canCreate(): bool { return false; }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('status')
                    ->required()
                    ->options([
                        Enums\PaymentStatus::paid->value => Enums\PaymentStatus::paid->value,
                        Enums\PaymentStatus::unpaid->value => Enums\PaymentStatus::unpaid->value,
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reservation_transaction_no')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => Enums\PaymentStatus::paid->value,
                        'danger' => Enums\PaymentStatus::unpaid->value,
                    ]),
                Tables\Columns\TextColumn::make('amount_to_pay')
                    ->money('php'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\MultiSelectFilter::make('status')
                    ->options([
                        Enums\PaymentStatus::paid->value => Enums\PaymentStatus::paid->value,
                        Enums\PaymentStatus::unpaid->value => Enums\PaymentStatus::unpaid->value,
                    ]),
                Tables\Filters\MultiSelectFilter::make('type')
                    ->options([
                        Enums\PaymentType::cod->value => Enums\PaymentType::cod->value,
                        Enums\PaymentType::paypal->value => Enums\PaymentType::paypal->value,
                    ]),
                Tables\Filters\MultiSelectFilter::make('name')
                    ->options([
                        Enums\PaymentName::reservation->value => Enums\PaymentName::reservation->value,
                        Enums\PaymentName::extension->value => Enums\PaymentName::extension->value,
                    ]),
                Tables\Filters\Filter::make('amount_to_pay')
                    ->form([
                        Forms\Components\TextInput::make('amount_to_pay')
                            ->required()
                            ->hint('Min: 1 - Max: 99,999')
                            ->placeholder(1_299)
                            ->prefix('₱')
                            ->numeric()
                            ->mask(fn (Forms\Components\TextInput\Mask $mask) => $mask
                                ->numeric()
                                ->decimalPlaces(2)
                                ->minValue(1)
                                ->maxValue(99_999)
                                ->normalizeZeros()
                                ->thousandsSeparator(',')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['amount_to_pay'],
                                fn (Builder $query, $rate): Builder => $query->where('amount_to_pay', '<=', Format::moneyForDatabase($rate))
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
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
            // 'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }    
}
