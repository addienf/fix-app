<?php

namespace App\Filament\Resources\Quality\IncommingMaterial\MaterialNonSS\IncommingMaterialNonSSResource\Pages;

use App\Filament\Resources\Quality\IncommingMaterial\MaterialNonSS\IncommingMaterialNonSSResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIncommingMaterialNonSS extends ListRecords
{
    protected static string $resource = IncommingMaterialNonSSResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
