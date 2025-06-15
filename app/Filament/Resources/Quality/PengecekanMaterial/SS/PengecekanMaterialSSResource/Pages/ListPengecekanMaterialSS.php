<?php

namespace App\Filament\Resources\Quality\PengecekanMaterial\SS\PengecekanMaterialSSResource\Pages;

use App\Filament\Resources\Quality\PengecekanMaterial\SS\PengecekanMaterialSSResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPengecekanMaterialSS extends ListRecords
{
    protected static string $resource = PengecekanMaterialSSResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data Pengecekan Material'),
        ];
    }

    public function getBreadcrumb(): string
    {
        return 'Daftar';
    }
}
