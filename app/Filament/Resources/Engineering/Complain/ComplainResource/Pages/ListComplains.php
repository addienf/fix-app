<?php

namespace App\Filament\Resources\Engineering\Complain\ComplainResource\Pages;

use App\Filament\Resources\Engineering\Complain\ComplainResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListComplains extends ListRecords
{
    protected static string $resource = ComplainResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Data Complaint'),
        ];
    }

    public function getBreadcrumb(): string
    {
        return 'Daftar';
    }
}
