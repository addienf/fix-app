<?php

namespace App\Filament\Resources\Warehouse\PermintaanBahanWBB\PermintaanBahanResource\Pages;

use App\Filament\Resources\Warehouse\PermintaanBahanWBB\PermintaanBahanResource;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditPermintaanBahan extends EditRecord
{
    protected static string $resource = PermintaanBahanResource::class;
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
