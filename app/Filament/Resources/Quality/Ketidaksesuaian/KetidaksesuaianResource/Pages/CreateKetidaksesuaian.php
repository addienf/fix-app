<?php

namespace App\Filament\Resources\Quality\Ketidaksesuaian\KetidaksesuaianResource\Pages;

use App\Filament\Resources\Quality\Ketidaksesuaian\KetidaksesuaianResource;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;

class CreateKetidaksesuaian extends CreateRecord
{
    protected static string $resource = KetidaksesuaianResource::class;

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
                '/admin/quality/ketidaksesuaian-produk-dan-material',
                'Data Ketidaksesuaian Produk dan Material berhasil dibuat',
                'Ada data Ketidaksesuaian Produk dan Material yang harus di tanda tangani.'
            );
        } else {
            Log::error('Record belum lengkap.');
        }
    }

    public function getTitle(): string
    {
        return 'Tambah Data Ketidaksesuaian Produk & Material';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
