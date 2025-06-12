<?php

namespace App\Filament\Resources\Warehouse\Pelabelan\QCPassedResource\Pages;

use App\Filament\Resources\Warehouse\Pelabelan\QCPassedResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQCPasseds extends ListRecords
{
    protected static string $resource = QCPassedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
