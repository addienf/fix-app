<?php

namespace App\Filament\Resources\Warehouse\Pelabelan\QCPassedResource\Pages;

use App\Filament\Resources\Warehouse\Pelabelan\QCPassedResource;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditQCPassed extends EditRecord
{
    protected static string $resource = QCPassedResource::class;

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
