<?php

namespace App\Filament\Resources\Engineering\Maintenance\RissingPipette\RissingPipetteResource\Pages;

use App\Filament\Resources\Engineering\Maintenance\RissingPipette\RissingPipetteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListRissingPipettes extends ListRecords
{
    protected static string $resource = RissingPipetteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data Rising Pipette'),
        ];
    }

    public function getTabs(): array
    {
        return
            [
                null => Tab::make('All'),
                'Disetujui' => Tab::make()->query(fn($query) => $query->where('status_penyetujuan', 'Disetujui')),
                'Belum Disetujui' => Tab::make()->query(fn($query) => $query->where('status_penyetujuan', 'Belum Disetujui')),
            ];
    }

    public function getBreadcrumb(): string
    {
        return 'Daftar';
    }
}
