<?php

namespace App\Filament\Resources\Engineering\Service\ServiceReportResource\Pages;

use App\Filament\Resources\Engineering\Service\ServiceReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditServiceReport extends EditRecord
{
    protected static string $resource = ServiceReportResource::class;

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
