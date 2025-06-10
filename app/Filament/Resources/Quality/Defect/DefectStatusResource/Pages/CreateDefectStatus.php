<?php

namespace App\Filament\Resources\Quality\Defect\DefectStatusResource\Pages;

use App\Filament\Resources\Quality\Defect\DefectStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDefectStatus extends CreateRecord
{
    protected static string $resource = DefectStatusResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
