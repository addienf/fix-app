<?php

namespace App\Filament\Resources\Quality\IncommingMaterial\MaterialSS\IncommingMaterialSSResource\Pages;

use App\Filament\Resources\Quality\IncommingMaterial\MaterialSS\IncommingMaterialSSResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIncommingMaterialSS extends ListRecords
{
    protected static string $resource = IncommingMaterialSSResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data Incoming Material SS'),
        ];
    }

    public function getBreadcrumb(): string
    {
        return 'Daftar';
    }
}
