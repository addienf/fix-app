<?php

namespace App\Filament\Resources\Quality\PengecekanMaterial\Electrical\PengecekanElectricalResource\Pages;

use App\Filament\Resources\Quality\PengecekanMaterial\Electrical\PengecekanElectricalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPengecekanElectricals extends ListRecords
{
    protected static string $resource = PengecekanElectricalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data Pengecekan Material Electrical'),
        ];
    }

    public function getBreadcrumb(): string
    {
        return 'Daftar';
    }
}
