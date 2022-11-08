<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccommodationResource\Pages;
use App\Filament\Resources\AccommodationResource\RelationManagers;
use App\Models\Accommodation;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class AccommodationResource extends Resource
{
    protected static ?string $model = Accommodation::class;

    protected static ?string $navigationGroup = 'Reservation';

    protected static ?string $navigationIcon = 'heroicon-o-office-building';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    // ->required()
                    // ->maxLength(255)
                    ->disabled(),
                Forms\Components\TagsInput::make('details')
                    ->required()
                    ->separator(',')
                    ->suggestions([
                        'x pools',
                        'x beds',
                        'x rooms',
                        'x bathrooms',
                    ]),
                Forms\Components\Repeater::make('file_path')
                    ->relationship('images')
                    ->columnSpan(2)
                    ->grid(2)
                    ->collapsible()
                    ->schema([
                        Forms\Components\FileUpload::make('file_path')
                            ->required()
                            ->imagePreviewHeight(200)
                            ->directory('images/accommodations')
                            ->preserveFilenames()
                            ->image()
                            ->imageCropAspectRatio('16:9')
                            ->panelAspectRatio('2:1'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TagsColumn::make('details')
                    ->separator(','),
                Tables\Columns\TextColumn::make('created_at')
                    ->date(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->date(),
            ])
            ->filters([
                //
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
            RelationManagers\PackagesRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccommodations::route('/'),
            'create' => Pages\CreateAccommodation::route('/create'),
            'edit' => Pages\EditAccommodation::route('/{record}/edit'),
        ];
    }    
}
