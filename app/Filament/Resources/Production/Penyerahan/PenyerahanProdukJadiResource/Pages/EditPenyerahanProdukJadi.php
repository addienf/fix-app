<?php

namespace App\Filament\Resources\Production\Penyerahan\PenyerahanProdukJadiResource\Pages;

use App\Filament\Resources\Production\Penyerahan\PenyerahanProdukJadiResource;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditPenyerahanProdukJadi extends EditRecord
{
    protected static string $resource = PenyerahanProdukJadiResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterSave(): void
    {
        if ($this->record && $this->record->id) {
            SendGenericNotif::dispatch(
                $this->record,
                'penyerahan_produk_jadi_pics',
                'produk_jadi_id',
                'receive_signature',
                'receive_name',
                GenericNotification::class,
                '/admin/produksi/penyerahan-produk-jadi',
                'Data penyerahan produk jadi berhasil dibuat',
                'Ada data peenyerahan produk jadi yang telah Anda tanda tangani.'
            );
        } else {
            Log::error('afterCreate dipanggil tapi record belum lengkap.');
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
