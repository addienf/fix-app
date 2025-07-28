<?php

namespace App\Filament\Resources\Engineering\Maintenance\WalkinChamber\WalkinChamberResource\Pages;

use App\Filament\Resources\Engineering\Maintenance\WalkinChamber\WalkinChamberResource;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditWalkinChamber extends EditRecord
{
    protected static string $resource = WalkinChamberResource::class;

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
