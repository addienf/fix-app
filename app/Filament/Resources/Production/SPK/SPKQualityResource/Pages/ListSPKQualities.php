<?php

namespace App\Filament\Resources\Production\SPK\SPKQualityResource\Pages;

use App\Filament\Resources\Production\SPK\SPKQualityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSPKQualities extends ListRecords
{
    protected static string $resource = SPKQualityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
