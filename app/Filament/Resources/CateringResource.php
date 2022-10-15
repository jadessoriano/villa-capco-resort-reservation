<?php

namespace App\Filament\Resources;

use App\Facades\Format;
use App\Filament\Resources\CateringResource\Pages;
use App\Filament\Resources\CateringResource\RelationManagers;
use App\Models\Catering;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CateringResource extends Resource
{
    protected static ?string $model = Catering::class;

    protected static ?string $navigationGroup = 'Reservation';

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

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
                    ->mask(fn (Forms\Components\TextInput\Mask $mask) => $mask
                        ->numeric()
                        ->decimalPlaces(2)
                        ->minValue(1)
                        ->maxValue(99_999)
                        ->normalizeZeros()
                        ->thousandsSeparator(',')),
                Forms\Components\RichEditor::make('description')
                    ->required()
                    ->toolbarButtons([
                        'blockquote',
                        'bold',
                        'bulletList',
                        'h2',
                        'h3',
                        'italic',
                        'link',
                        'orderedList',
                        'redo',
                        'strike',
                        'undo',
                    ])
                    ->columnSpan([
                        'sm' => 2
                    ]),
                Forms\Components\FileUpload::make('image_path')
                    ->required()
                    ->imagePreviewHeight(200)
                    ->directory('images/addons') //TODO: change path to caterings.
                    ->preserveFilenames()
                    ->image()
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
                    ->url(fn (Catering $record): string => $record->image_path, true),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->extraAttributes(['style' => 'width: 20rem'])
                    ->wrap()
                    ->html(),
                Tables\Columns\TextColumn::make('rate')
                    ->money('php'),
                // Tables\Columns\TextColumn::make('image_path'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\Filter::make('rate')
                    ->form([
                        Forms\Components\TextInput::make('rate')
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
            'index' => Pages\ListCaterings::route('/'),
            'create' => Pages\CreateCatering::route('/create'),
            'edit' => Pages\EditCatering::route('/{record}/edit'),
        ];
    }    
}
