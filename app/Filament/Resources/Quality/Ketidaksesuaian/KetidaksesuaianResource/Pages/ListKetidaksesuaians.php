<?php

namespace App\Filament\Resources\Quality\Ketidaksesuaian\KetidaksesuaianResource\Pages;

use App\Filament\Resources\Quality\Ketidaksesuaian\KetidaksesuaianResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKetidaksesuaians extends ListRecords
{
    protected static string $resource = KetidaksesuaianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
