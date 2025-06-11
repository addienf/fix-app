<?php

namespace App\Filament\Resources\Quality\Penyerahan\PenyerahanElectricalResource\Pages;

use App\Filament\Resources\Quality\Penyerahan\PenyerahanElectricalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPenyerahanElectricals extends ListRecords
{
    protected static string $resource = PenyerahanElectricalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
