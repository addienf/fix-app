<?php

namespace App\Filament\Resources\Quality\PengecekanMaterial\Electrical\PengecekanElectricalResource\Pages;

use App\Filament\Resources\Quality\PengecekanMaterial\Electrical\PengecekanElectricalResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePengecekanElectrical extends CreateRecord
{
    protected static string $resource = PengecekanElectricalResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
