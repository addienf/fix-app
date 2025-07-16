<?php

namespace App\Filament\Resources\Engineering\Service\ServiceReportResource\Pages;

use App\Filament\Resources\Engineering\Service\ServiceReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListServiceReports extends ListRecords
{
    protected static string $resource = ServiceReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data Service Report'),
        ];
    }

    public function getTabs(): array
    {
        return
            [
                null => Tab::make('All'),
                'Selesai' => Tab::make()->query(fn($query) => $query->where('status_penyelesaian', 'Selesai')),
                'Belum Diselesaikan' => Tab::make()->query(fn($query) => $query->where('status_penyelesaian', 'Belum Diselesaikan')),
            ];
    }

    public function getBreadcrumb(): string
    {
        return 'Daftar';
    }
}
