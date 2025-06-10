<?php

namespace App\Filament\Resources\Quality\Defect\DefectStatusResource\Pages;

use App\Filament\Resources\Quality\Defect\DefectStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDefectStatus extends EditRecord
{
    protected static string $resource = DefectStatusResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
