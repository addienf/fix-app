<?php

namespace App\Filament\Resources\Warehouse\Peminjaman\PeminjamanAlatResource\Pages;

use App\Filament\Resources\Warehouse\Peminjaman\PeminjamanAlatResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPeminjamanAlats extends ListRecords
{
    protected static string $resource = PeminjamanAlatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data Peminjaman Alat'),
        ];
    }

    public function getBreadcrumb(): string
    {
        return 'Daftar';
    }
}
