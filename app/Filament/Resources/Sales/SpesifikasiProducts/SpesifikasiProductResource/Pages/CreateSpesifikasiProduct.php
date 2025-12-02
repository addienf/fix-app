<?php

namespace App\Filament\Resources\Sales\SpesifikasiProducts\SpesifikasiProductResource\Pages;

use App\Filament\Resources\Sales\SpesifikasiProducts\SpesifikasiProductResource;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;

class CreateSpesifikasiProduct extends CreateRecord
{
    protected static string $resource = SpesifikasiProductResource::class;
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
                ['sales', 'production', 'MR'],
                GenericNotification::class,
                '/admin/sales/spesifikasi-produk',
                'Data Spesifikasi Produk berhasil dibuat',
                'Ada data Spesifikasi Produk baru yang masuk.'
            )->delay(now()->addSeconds(2));
        } else {
            Log::error('Record belum lengkap.');
        }
    }

    public function getTitle(): string
    {
        return 'Tambah Data Spesifikasi Produk';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
