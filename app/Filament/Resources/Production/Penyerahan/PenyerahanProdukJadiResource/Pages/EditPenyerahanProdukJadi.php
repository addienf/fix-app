<?php

namespace App\Filament\Resources\Production\Penyerahan\PenyerahanProdukJadiResource\Pages;

use App\Filament\Resources\Production\Penyerahan\PenyerahanProdukJadiResource;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditPenyerahanProdukJadi extends EditRecord
{
    protected static string $resource = PenyerahanProdukJadiResource::class;

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
