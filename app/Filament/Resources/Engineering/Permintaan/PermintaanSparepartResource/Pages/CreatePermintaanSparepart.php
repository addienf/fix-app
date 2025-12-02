<?php

namespace App\Filament\Resources\Engineering\Permintaan\PermintaanSparepartResource\Pages;

use App\Filament\Resources\Engineering\Permintaan\PermintaanSparepartResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;


class CreatePermintaanSparepart extends CreateRecord
{
    protected static string $resource = PermintaanSparepartResource::class;

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
                '/admin/engineering/permintaan-spareparts',
                'Data Permintaan Sparepart berhasil dibuat',
                'Ada data Permintaan Sparepart yang harus di tanda tangani.'
            );
        } else {
            Log::error('Record belum lengkap.');
        }
    }

    public function getTitle(): string
    {
        return 'Tambah Data Permintaan Spareparts';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
