<?php

namespace App\Filament\Resources\Sales\SpesifikasiProducts\SpesifikasiProductResource\Pages;

use App\Filament\Resources\Sales\SpesifikasiProducts\SpesifikasiProductResource;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListSpesifikasiProducts extends ListRecords
{
    protected static string $resource = SpesifikasiProductResource::class;

    // use ExposesTableToWidgets;

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
                'stock' => Tab::make()->query(fn($query) => $query->where('is_stock', 1)),
                'non Stock' => Tab::make()->query(fn($query) => $query->where('is_stock', 0)),
            ];
    }
}
