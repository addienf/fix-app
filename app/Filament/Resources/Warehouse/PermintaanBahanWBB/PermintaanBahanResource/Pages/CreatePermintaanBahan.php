<?php

namespace App\Filament\Resources\Warehouse\PermintaanBahanWBB\PermintaanBahanResource\Pages;

use App\Filament\Resources\Warehouse\PermintaanBahanWBB\PermintaanBahanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;
use Illuminate\Support\Facades\Log;

class CreatePermintaanBahan extends CreateRecord
{
    protected static string $resource = PermintaanBahanResource::class;
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
                '/admin/warehouse/permintaan-bahan-warehouse',
                'Data Permintaan Bahan Warehouse berhasil dibuat',
                'Ada data Permintaan Bahan Warehouse yang harus ditanda tangani.'
            );
        } else {
            Log::error('afterCreate dipanggil tapi record belum lengkap.');
        }
    }

    public function getTitle(): string
    {
        return 'Tambah Data Permintaan Bahan Pembelian';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
