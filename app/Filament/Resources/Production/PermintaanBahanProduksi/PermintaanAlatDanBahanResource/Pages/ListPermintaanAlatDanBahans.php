<?php

namespace App\Filament\Resources\Production\PermintaanBahanProduksi\PermintaanAlatDanBahanResource\Pages;

use App\Filament\Resources\Production\PermintaanBahanProduksi\PermintaanAlatDanBahanResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
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

    public function getTabs(): array
    {
        return
            [
                null => Tab::make('All'),
                'ready' => Tab::make()->query(fn($query) => $query->where('status', 1)),
                'not Ready' => Tab::make()->query(fn($query) => $query->where('status', 0)),
            ];
    }
}
