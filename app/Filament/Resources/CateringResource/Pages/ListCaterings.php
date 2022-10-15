<?php

namespace App\Filament\Resources\CateringResource\Pages;

use App\Filament\Resources\CateringResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCaterings extends ListRecords
{
    protected static string $resource = CateringResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
