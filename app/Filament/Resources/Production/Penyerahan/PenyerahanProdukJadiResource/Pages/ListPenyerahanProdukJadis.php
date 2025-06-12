<?php

namespace App\Filament\Resources\Production\Penyerahan\PenyerahanProdukJadiResource\Pages;

use App\Filament\Resources\Production\Penyerahan\PenyerahanProdukJadiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPenyerahanProdukJadis extends ListRecords
{
    protected static string $resource = PenyerahanProdukJadiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
