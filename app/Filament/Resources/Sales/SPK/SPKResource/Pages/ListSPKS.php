<?php

namespace App\Filament\Resources\Sales\SPK\SPKResource\Pages;

use App\Filament\Resources\Sales\SPK\SPKResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSPKS extends ListRecords
{
    protected static string $resource = SPKResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
