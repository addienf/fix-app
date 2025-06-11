<?php

namespace App\Filament\Resources\Quality\Penyerahan\PenyerahanElectricalResource\Pages;

use App\Filament\Resources\Quality\Penyerahan\PenyerahanElectricalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPenyerahanElectrical extends EditRecord
{
    protected static string $resource = PenyerahanElectricalResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
