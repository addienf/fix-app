<?php

namespace App\Filament\Resources\Production\PermintaanBahanProduksi\PermintaanAlatDanBahanResource\Pages;

use App\Filament\Resources\Production\PermintaanBahanProduksi\PermintaanAlatDanBahanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;


class CreatePermintaanAlatDanBahan extends CreateRecord
{
    protected static string $resource = PermintaanAlatDanBahanResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        if ($this->record && $this->record->id) {
            SendGenericNotif::dispatch(
                $this->record,
                ['sales', 'super_admin'],
                GenericNotification::class,
                '/admin/produksi/permintaan-alat-dan-bahan',
                'Data Permintaan Bahan dan Alat Produksi berhasil dibuat',
                'Ada data Permintaan Bahan dan Alat Produksi yang harus di tanda tangani.'
            );
        } else {
            Log::error('Record belum lengkap.');
        }
    }

    public function getTitle(): string
    {
        return 'Permintaan Bahan dan Alat Produksi';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
