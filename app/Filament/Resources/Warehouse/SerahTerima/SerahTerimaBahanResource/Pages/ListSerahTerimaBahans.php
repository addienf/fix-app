<?php

namespace App\Filament\Resources\Warehouse\SerahTerima\SerahTerimaBahanResource\Pages;

use App\Filament\Resources\Warehouse\SerahTerima\SerahTerimaBahanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSerahTerimaBahans extends ListRecords
{
    protected static string $resource = SerahTerimaBahanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data Serah Terima Bahan'),
        ];
    }

    public function getBreadcrumb(): string
    {
        return 'Daftar';
    }
}
