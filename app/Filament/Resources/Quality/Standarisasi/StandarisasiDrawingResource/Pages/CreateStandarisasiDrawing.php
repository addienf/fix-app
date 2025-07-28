<?php

namespace App\Filament\Resources\Quality\Standarisasi\StandarisasiDrawingResource\Pages;

use App\Filament\Resources\Quality\Standarisasi\StandarisasiDrawingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;


class CreateStandarisasiDrawing extends CreateRecord
{
    protected static string $resource = StandarisasiDrawingResource::class;

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
                '/admin/quality/standarisasi-gambar-kerja',
                'Data Standarisasi Drawing berhasil dibuat',
                'Ada data Standarisasi Drawing yang harus di tanda tangani.'
            );
        } else {
            Log::error('Record belum lengkap.');
        }
    }

    public function getTitle(): string
    {
        return 'Tambah Data Standarisasi Gambar Kerja';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
