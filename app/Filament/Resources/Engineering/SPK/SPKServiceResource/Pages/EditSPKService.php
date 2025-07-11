<?php

namespace App\Filament\Resources\Engineering\SPK\SPKServiceResource\Pages;

use App\Filament\Resources\Engineering\SPK\SPKServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSPKService extends EditRecord
{
    protected static string $resource = SPKServiceResource::class;

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
