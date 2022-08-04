<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservationResource\Pages;
use App\Models\Reservation;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\MultiSelectFilter;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $navigationGroup = 'Reservation';

    protected static ?string $navigationIcon = 'heroicon-o-qrcode';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('status_id')
                    ->relationship('status', 'name')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('qr_code_path')
                    ->rounded()
                    ->size(150),
                Tables\Columns\TextColumn::make('transaction_no'),
                Tables\Columns\TextColumn::make('accommodation.name'),
                Tables\Columns\TextColumn::make('package.name'),
                Tables\Columns\TextColumn::make('user.email'),
                Tables\Columns\BadgeColumn::make('status.name')
                    ->colors([
                        'primary' => 'Booked',
                        'warning' => 'Rebooked',
                        'danger' => 'Cancelled',
                        'success' => 'Done',
                    ]),
                Tables\Columns\TextColumn::make('no_of_people'),
                Tables\Columns\TextColumn::make('amount_to_pay')
                    ->money('php'),
                Tables\Columns\TextColumn::make('mode_of_payment'),
                Tables\Columns\TextColumn::make('reserved_date')
                    ->date(),
                Tables\Columns\TextColumn::make('created_at')
                    ->date(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->date(),
            ])
            ->filters([
                MultiSelectFilter::make('status')
                    ->relationship('status', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReservations::route('/'),
        ];
    }    
}
