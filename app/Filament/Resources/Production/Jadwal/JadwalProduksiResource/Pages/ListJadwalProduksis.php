<?php

namespace App\Filament\Resources\Production\Jadwal\JadwalProduksiResource\Pages;

use App\Filament\Resources\Production\Jadwal\JadwalProduksiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListJadwalProduksis extends ListRecords
{
    protected static string $resource = JadwalProduksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data Jadwal Produksi'),
        ];
    }
    public function getTabs(): array
    {
        return
            [
                null => Tab::make('All'),
                'Disetujui' => Tab::make()->query(fn($query) => $query->where('status_persetujuan', 'Disetujui')),
                'Belum Disetujui' => Tab::make()->query(fn($query) => $query->where('status_persetujuan', 'Belum Disetujui')),
            ];
    }

    public function getBreadcrumb(): string
    {
        return 'Daftar';
    }
}
