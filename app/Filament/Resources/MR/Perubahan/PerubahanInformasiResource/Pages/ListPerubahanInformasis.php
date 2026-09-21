<?php

namespace App\Filament\Resources\MR\Perubahan\PerubahanInformasiResource\Pages;

use App\Filament\Resources\MR\Perubahan\PerubahanInformasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPerubahanInformasis extends ListRecords
{
    protected static string $resource = PerubahanInformasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data Jadwal Produksi'),
        ];
    }

    public function getBreadcrumb(): string
    {
        return 'Daftar';
    }
}
