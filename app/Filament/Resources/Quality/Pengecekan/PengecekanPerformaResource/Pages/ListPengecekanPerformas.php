<?php

namespace App\Filament\Resources\Quality\Pengecekan\PengecekanPerformaResource\Pages;

use App\Filament\Resources\Quality\Pengecekan\PengecekanPerformaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPengecekanPerformas extends ListRecords
{
    protected static string $resource = PengecekanPerformaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
