<?php

namespace App\Filament\Resources\Engineering\Maintenance\RissingPipette\RissingPipetteResource\Pages;

use App\Filament\Resources\Engineering\Maintenance\RissingPipette\RissingPipetteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRissingPipette extends EditRecord
{
    protected static string $resource = RissingPipetteResource::class;

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
