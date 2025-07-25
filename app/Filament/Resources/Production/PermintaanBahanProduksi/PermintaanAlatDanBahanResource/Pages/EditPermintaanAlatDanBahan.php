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

    protected function afterSave(): void
    {
        if ($this->record && $this->record->id) {
            SendGenericNotif::dispatch(
                $this->record,
                'permintaan_alat_dan_bahan_pics',
                'permintaan_bahan_id',
                'diserahkan_signature',
                'diserahkan_name',
                GenericNotification::class,
                '/admin/produksi/permintaan-alat-dan-bahan',
                'Data permintaan alat dan bahan berhasil dibuat',
                'Ada data permintaan alat dan bahan yang telah Anda tanda tangani.'
            );
        } else {
            Log::error('afterCreate dipanggil tapi record belum lengkap.');
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
