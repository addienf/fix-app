<?php

namespace App\Filament\Resources\Production\Jadwal\JadwalProduksiResource\Pages;

use App\Filament\Resources\Production\Jadwal\JadwalProduksiResource;
use App\Jobs\Production\SendJadwalProduksiNotif;
use App\Jobs\SendGenericNotif;
use App\Models\Production\Jadwal\JadwalProduksi;
use App\Notifications\Production\JadwalProduksiNotif;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditJadwalProduksi extends EditRecord
{
    protected static string $resource = JadwalProduksiResource::class;

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
