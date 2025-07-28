<?php

namespace App\Filament\Resources\Quality\KelengkapanMaterial\SS\KelengkapanMaterialSSResource\Pages;

use App\Filament\Resources\Quality\KelengkapanMaterial\SS\KelengkapanMaterialSSResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;


class CreateKelengkapanMaterialSS extends CreateRecord
{
    protected static string $resource = KelengkapanMaterialSSResource::class;

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
                '/admin/quality/kelengkapan-material',
                'Data Kelengkapan Material berhasil dibuat',
                'Ada data Kelengkapan Material yang harus di tanda tangani.'
            );
        } else {
            Log::error('Record belum lengkap.');
        }
    }

    public function getTitle(): string
    {
        return 'Tambah Data Kelengkapan Material';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
