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
                'berita_acara_pics',
                'berita_id',
                'jasa_ttd',
                'jasa_name',
                GenericNotification::class,
                '/admin/engineering/berita-acara',
                'Data berita acara berhasil dibuat',
                'Ada data berita acara yang telah Anda tanda tangani.'
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
