<?php

namespace App\Filament\Resources\Warehouse\Incomming\IncommingMaterialResource\Pages;

use App\Filament\Resources\Warehouse\Incomming\IncommingMaterialResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIncommingMaterials extends ListRecords
{
    protected static string $resource = IncommingMaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
