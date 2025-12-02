<?php

namespace App\Filament\Resources\Purchasing\Permintaan\PermintaanPembelianResource\Pages;

use App\Filament\Resources\Purchasing\Permintaan\PermintaanPembelianResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class CreatePermintaanPembelian extends CreateRecord
{
    protected static string $resource = PermintaanPembelianResource::class;

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
                '/admin/purchasing/permintaan-pembelian',
                'Data Permintaan Pembelian berhasil dibuat',
                'Ada data Permintaan Pembelian yang harus di tanda tangani.'
            );
        } else {
            Log::error('Record belum lengkap.');
        }
    }

    public function getTitle(): string
    {
        return 'Tambah Data Jadwal Produksi';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }

    // protected function handleRecordCreation(array $data): Model
    // {
    // try {
    //     return static::getModel()::create($data);
    // } catch (\Throwable $e) {
    //     Notification::make()
    //         ->title('Gagal Menyimpan')
    //         ->body('Terjadi error. Cek ulang inputan kamu ya bro.')
    //         ->danger()
    //         ->send();

    //     \Log::error($e->getMessage());

    //     throw ValidationException::withMessages([
    //         'error' => 'Gagal menyimpan data.',
    //     ]);
    // }
    // }

    public function handleRecordCreation(array $data): Model
    {
        try {
            return static::getModel()::create($data);
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Gagal Menyimpan')
                ->body('Terjadi error. Cek ulang inputan kamu ya bro.')
                ->danger()
                ->send();

            Log::error($e->getMessage());

            throw ValidationException::withMessages([
                'error' => 'Gagal menyimpan data.',
            ]);
        }
    }
}
