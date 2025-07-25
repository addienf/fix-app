<?php

namespace App\Filament\Resources\Warehouse\Peminjaman\PeminjamanAlatResource\Pages;

use App\Filament\Resources\Warehouse\Peminjaman\PeminjamanAlatResource;
use App\Jobs\SendGenericNotif;
use App\Jobs\Warehouse\SendPeminjamanAlatNotif;
use App\Notifications\GenericNotification;
use App\Notifications\Warehouse\PeminjamanAlatNotif;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;

class CreatePeminjamanAlat extends CreateRecord
{
    protected static string $resource = PeminjamanAlatResource::class;
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
                'peminjaman_alat_pics',
                'peminjaman_alat_id',
                'signature',
                'nama_peminjam',
                GenericNotification::class,
                '/admin/warehouse/peminjaman-alat',
                'Data peminjaman alat berhasil dibuat',
                'Ada data peminjaman alat yang telah Anda tanda tangani.'
            );
        } else {
            Log::error('afterCreate dipanggil tapi record belum lengkap.');
        }
    }

    public function getTitle(): string
    {
        return 'Tambah Data Peminjaman Alat';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
