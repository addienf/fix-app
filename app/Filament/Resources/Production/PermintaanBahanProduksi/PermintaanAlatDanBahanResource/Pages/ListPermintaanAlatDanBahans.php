<?php

namespace App\Filament\Resources\Production\PermintaanBahanProduksi\PermintaanAlatDanBahanResource\Pages;

use App\Filament\Resources\Production\PermintaanBahanProduksi\PermintaanAlatDanBahanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPermintaanAlatDanBahans extends ListRecords
{
    protected static string $resource = PermintaanAlatDanBahanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
