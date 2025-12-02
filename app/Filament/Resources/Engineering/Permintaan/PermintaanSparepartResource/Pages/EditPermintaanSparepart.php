<?php

namespace App\Filament\Resources\Engineering\Permintaan\PermintaanSparepartResource\Pages;

use App\Filament\Resources\Engineering\Permintaan\PermintaanSparepartResource;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditPermintaanSparepart extends EditRecord
{
    protected static string $resource = PermintaanSparepartResource::class;

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
