<?php

namespace App\Filament\Resources\Warehouse\Incomming\IncommingMaterialResource\Pages;

use App\Filament\Resources\Warehouse\Incomming\IncommingMaterialResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;
use App\Services\DocumentNumber;

class CreateIncommingMaterial extends CreateRecord
{
    protected static string $resource = IncommingMaterialResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['no_surat'] = DocumentNumber::next('incomming_materials', 'no_surat', 'PM', 'QKS', 'WBB');
        return $data;
    }

    protected function afterCreate(): void
    {
        if ($this->record && $this->record->id) {
            SendGenericNotif::dispatch(
                $this->record,
                ['warehouse'],
                GenericNotification::class,
                // '/admin/warehouse/incoming-material',
                IncommingMaterialResource::getUrl('index'),
                'Data Incoming Material berhasil dibuat',
                'Ada data Incoming Material yang harus di tanda tangani.'
            );
        } else {
            Log::error('Record belum lengkap.');
        }
    }

    public function getTitle(): string
    {
        return 'Tambah Data Incoming Material';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
