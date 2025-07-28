<?php

namespace App\Filament\Resources\Production\PermintaanBahanProduksi\PermintaanAlatDanBahanResource\Pages;

use App\Filament\Resources\Production\PermintaanBahanProduksi\PermintaanAlatDanBahanResource;
use App\Jobs\Production\SendPermintaanAlatdanBahanNotif;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;
use App\Notifications\Production\PermintaanAlatdanBahanNotif;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditPermintaanAlatDanBahan extends EditRecord
{
    protected static string $resource = PermintaanAlatDanBahanResource::class;

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
