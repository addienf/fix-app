<?php

namespace App\Filament\Resources\Engineering\Berita\BeritaAcaraResource\Pages;

use App\Filament\Resources\Engineering\Berita\BeritaAcaraResource;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;

class CreateBeritaAcara extends CreateRecord
{
    protected static string $resource = BeritaAcaraResource::class;

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
                '/admin/engineering/berita-acara',
                'Data Berita Acara berhasil dibuat',
                'Ada data Berita Acara yang harus ditanda tangani.'
            );
        } else {
            Log::error('afterCreate dipanggil tapi record belum lengkap.');
        }
    }

    public function getTitle(): string
    {
        return 'Tambah Data Berita Acara';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
