<?php

namespace App\Filament\Resources\Warehouse\PermintaanBahanWBB\PermintaanBahanResource\Pages;

use App\Filament\Resources\Warehouse\PermintaanBahanWBB\PermintaanBahanResource;
use Filament\Resources\Pages\CreateRecord;
use App\Jobs\SendGenericNotif;
use App\Models\Warehouse\PermintaanBahanWBB\PermintaanBahan;
use App\Notifications\GenericNotification;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class CreatePermintaanBahan extends CreateRecord
{
    protected static string $resource = PermintaanBahanResource::class;
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
                '/admin/warehouse/permintaan-bahan-warehouse',
                'Data Permintaan Bahan Warehouse berhasil dibuat',
                'Ada data Permintaan Bahan Warehouse yang harus ditanda tangani.'
            );
        } else {
            Log::error('afterCreate dipanggil tapi record belum lengkap.');
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        try {

            return DB::transaction(function () use ($data) {

                $details = $data['details'] ?? [];
                unset($data['details']);

                $parent = PermintaanBahan::create($data);
                foreach ($details as $i => $detail) {
                    $created = $parent->details()->create($detail);
                }

                return $parent;
            });
        } catch (Throwable $e) {

            DB::rollBack();

            Notification::make()
                ->title('Gagal Menyimpan Data')
                ->body('Input tidak valid !')
                ->danger()
                ->send();

            throw new Halt();
        }
    }

    public function getTitle(): string
    {
        return 'Tambah Data Permintaan Bahan Pembelian';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
