<?php

namespace App\Filament\Resources\Quality\Defect\DefectStatusResource\Pages;

use App\Filament\Resources\Quality\Defect\DefectStatusResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListDefectStatuses extends ListRecords
{
    protected static string $resource = DefectStatusResource::class;

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
                'Stainless Steel' => Tab::make()->query(fn($query) => $query->where('defectable_type', 'App\Models\Quality\PengecekanMaterial\SS\PengecekanMaterialSS')),
                'Electrical' => Tab::make()->query(fn($query) => $query->where('defectable_type', 'App\Models\Quality\PengecekanMaterial\Electrical\PengecekanMaterialElectrical')),
            ];
    }
}