<?php

namespace App\Filament\Resources\Purchasing\Penerimaan\PenerimaanBarangResource\Pages;

use App\Filament\Resources\Purchasing\Penerimaan\PenerimaanBarangResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePenerimaanBarang extends CreateRecord
{
    protected static string $resource = PenerimaanBarangResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // protected function afterCreate(): void
    // {
    //     if ($this->record && $this->record->id) {
    //         SendGenericNotif::dispatch(
    //             $this->record,
    //             ['purchase', 'MR'],
    //             GenericNotification::class,
    //             '/admin/purchasing/permintaan-pembelian',
    //             'Data Permintaan Pembelian berhasil dibuat',
    //             'Ada data Permintaan Pembelian yang harus di tanda tangani.'
    //         );
    //     } else {
    //         Log::error('Record belum lengkap.');
    //     }
    // }

    public function getTitle(): string
    {
        return 'Tambah Data Penerimaan Barang';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
