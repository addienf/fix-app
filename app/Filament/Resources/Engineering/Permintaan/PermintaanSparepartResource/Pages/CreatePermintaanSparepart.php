<?php

namespace App\Filament\Resources\Engineering\Permintaan\PermintaanSparepartResource\Pages;

use App\Filament\Resources\Engineering\Permintaan\PermintaanSparepartResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;
use App\Services\DocumentNumber;

class CreatePermintaanSparepart extends CreateRecord
{
    protected static string $resource = PermintaanSparepartResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['no_surat'] = DocumentNumber::next('permintaan_spareparts', 'no_surat', 'PSAK', 'QKS', 'ENG');
        return $data;
    }

    protected function afterCreate(): void
    {
        if ($this->record && $this->record->id) {
            SendGenericNotif::dispatch(
                $this->record,
                ['MR', 'engineering'],
                GenericNotification::class,
                // '/admin/engineering/permintaan-spareparts',
                PermintaanSparepartResource::getUrl('index'),
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
