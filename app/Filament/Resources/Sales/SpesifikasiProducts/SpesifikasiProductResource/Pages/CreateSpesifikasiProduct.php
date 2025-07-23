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
                'spesifikasi_product_pics',
                'spesifikasi_product_id',
                'signature',
                'name',
                GenericNotification::class,
                '/admin/sales/spesifikasi-produk',
                'Data spesifikasi produk berhasil dibuat',
                'Ada data spesifikasi produk yang telah Anda tanda tangani.'
            );
        } else {
            Log::error('afterCreate dipanggil tapi record belum lengkap.');
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
