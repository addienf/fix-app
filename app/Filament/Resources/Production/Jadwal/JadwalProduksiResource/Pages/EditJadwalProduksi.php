<?php

namespace App\Filament\Resources\Production\Jadwal\JadwalProduksiResource\Pages;

use App\Filament\Resources\Production\Jadwal\JadwalProduksiResource;
use App\Jobs\Production\SendJadwalProduksiNotif;
use App\Jobs\SendGenericNotif;
use App\Models\Production\Jadwal\JadwalProduksi;
use App\Notifications\GenericNotification;
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

    // protected function afterSave(): void
    // {
    //     if ($this->record && $this->record->id) {
    //         SendGenericNotif::dispatch(
    //             $this->record,
    //             'jadwal_produksi_pics',
    //             'jadwal_produksi_id',
    //             'approve_signature',
    //             'approve_name',
    //             GenericNotification::class,
    //             '/admin/produksi/jadwal-produksi',
    //             'Data jadwal produksi berhasil dibuat',
    //             'Ada data jadwal produksi yang telah Anda tanda tangani.'
    //         );
    //     } else {
    //         Log::error('afterCreate dipanggil tapi record belum lengkap.');
    //     }
    // }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
