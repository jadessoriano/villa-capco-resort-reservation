<?php

namespace App\Filament\Resources\CateringResource\Pages;

use App\Facades\Format;
use App\Filament\Resources\CateringResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCatering extends CreateRecord
{
    protected static string $resource = CateringResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['rate'] = Format::moneyForDatabase($data['rate']);

        return $data;
    }
}
