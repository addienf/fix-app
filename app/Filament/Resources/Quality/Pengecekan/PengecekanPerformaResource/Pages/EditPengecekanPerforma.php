<?php

namespace App\Filament\Resources\Quality\Pengecekan\PengecekanPerformaResource\Pages;

use App\Filament\Resources\Quality\Pengecekan\PengecekanPerformaResource;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditPengecekanPerforma extends EditRecord
{
    protected static string $resource = PengecekanPerformaResource::class;

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
