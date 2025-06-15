<?php

namespace App\Filament\Resources\Production\Penyerahan\PenyerahanElectrical\PenyerahanElectricalResource\Pages;

use App\Filament\Resources\Production\Penyerahan\PenyerahanElectrical\PenyerahanElectricalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPenyerahanElectricals extends ListRecords
{
    protected static string $resource = PenyerahanElectricalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data Serah Terima Electrical'),
        ];
    }

    public function getBreadcrumb(): string
    {
        return 'Daftar';
    }
}
