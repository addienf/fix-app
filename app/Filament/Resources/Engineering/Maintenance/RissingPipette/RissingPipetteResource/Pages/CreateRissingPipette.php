<?php

namespace App\Filament\Resources\Engineering\Maintenance\RissingPipette\RissingPipetteResource\Pages;

use App\Filament\Resources\Engineering\Maintenance\RissingPipette\RissingPipetteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRissingPipette extends CreateRecord
{
    protected static string $resource = RissingPipetteResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string
    {
        return 'Tambah Data Rissing Pipette';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
