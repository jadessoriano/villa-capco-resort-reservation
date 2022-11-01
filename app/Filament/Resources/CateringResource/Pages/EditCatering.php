<?php

namespace App\Filament\Resources\CateringResource\Pages;

use App\Facades\Format;
use App\Filament\Resources\CateringResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCatering extends EditRecord
{
    protected static string $resource = CateringResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['rate'] = Format::moneyForDisplay($data['rate']);

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['rate'] = Format::moneyForDatabase($data['rate']);

        return $data;
    }
}
