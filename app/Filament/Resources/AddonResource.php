<?php

namespace App\Filament\Resources;

use App\Facades\Format;
use App\Filament\Resources\AddonResource\Pages;
use App\Models\Addon;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class AddonResource extends Resource
{
    protected static ?string $model = Addon::class;

    protected static ?string $navigationGroup = 'Reservation';

    protected static ?string $navigationIcon = 'heroicon-o-inbox';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('rate')
                    ->required()
                    ->hint('Min: 1 - Max: 99,999')
                    ->placeholder(1_299)
                    ->prefix('₱')
                    ->numeric()
                    ->mask(fn (TextInput\Mask $mask) => $mask
                        ->numeric()
                        ->decimalPlaces(2)
                        ->minValue(1)
                        ->maxValue(99_999)
                        ->normalizeZeros()
                        ->thousandsSeparator(',')),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->maxLength(65_535)
                    ->columnSpan([
                        'sm' => 2
                    ]),
                Forms\Components\FileUpload::make('image_path')
                    ->required()
                    ->directory('images/addons/')
                    ->preserveFilenames()
                    ->image()
                    ->imagePreviewHeight(200)
                    ->imageCropAspectRatio('16:9')
                    ->panelAspectRatio('2:1'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->rounded()
                    ->size(150)
                    ->url(fn (Addon $record): string => asset('storage/'.$record->image_path), true),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->extraAttributes(['style' => 'width: 30rem'])
                    ->wrap(),
                Tables\Columns\TextColumn::make('rate')
                    ->money('php'),
                Tables\Columns\TextColumn::make('created_at')
                    ->date(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->date(),
            ])
            ->filters([
                // Rate
                Filter::make('rate')
                    ->form([
                        Forms\Components\TextInput::make('rate')
                            ->required()
                            ->hint('Min: 1 - Max: 99,999')
                            ->placeholder(1_299)
                            ->prefix('₱')
                            ->numeric()
                            ->mask(fn (TextInput\Mask $mask) => $mask
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
                                $data['rate'],
                                fn (Builder $query, $rate): Builder => $query->where('rate', '<=', Format::moneyForDatabase($rate))
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
            'index' => Pages\ListAddons::route('/'),
            'create' => Pages\CreateAddon::route('/create'),
            'edit' => Pages\EditAddon::route('/{record}/edit'),
        ];
    }    
}
