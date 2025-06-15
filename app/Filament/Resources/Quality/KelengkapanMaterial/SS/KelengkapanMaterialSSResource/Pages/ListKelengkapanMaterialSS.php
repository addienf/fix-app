<?php

namespace App\Filament\Resources\Quality\KelengkapanMaterial\SS\KelengkapanMaterialSSResource\Pages;

use App\Filament\Resources\Quality\KelengkapanMaterial\SS\KelengkapanMaterialSSResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKelengkapanMaterialSS extends ListRecords
{
    protected static string $resource = KelengkapanMaterialSSResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data Kelengkapan Material'),
        ];
    }

    public function getBreadcrumb(): string
    {
        return 'Daftar';
    }
}
