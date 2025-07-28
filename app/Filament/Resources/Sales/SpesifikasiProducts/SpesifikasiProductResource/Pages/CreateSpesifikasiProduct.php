<?php

namespace App\Filament\Resources\Sales\SpesifikasiProducts\SpesifikasiProductResource\Pages;

use App\Filament\Resources\Sales\SpesifikasiProducts\SpesifikasiProductResource;
use App\Jobs\Sales\SendSpesifikasiProductNotif;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;
use App\Notifications\Sales\SpesifikasiProductNotif;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
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
                ['sales', 'super_admin'],
                GenericNotification::class,
                '/admin/sales/spesifikasi-produk',
                'Data Spesifikasi Produk berhasil dibuat',
                'Ada data Spesifikasi Produk baru yang masuk.'
            );
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
