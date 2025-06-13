<?php

namespace App\Filament\Resources\Sales\SpesifikasiProducts\SpesifikasiProductResource\Pages;

use App\Filament\Resources\Sales\SpesifikasiProducts\SpesifikasiProductResource;
use App\Jobs\Sales\SendSpesifikasiProductNotif;
use Filament\Resources\Pages\CreateRecord;

class CreateSpesifikasiProduct extends CreateRecord
{
    protected static string $resource = SpesifikasiProductResource::class;
    protected static bool $canCreateAnother = false;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        dispatch(new SendSpesifikasiProductNotif($this->record));
    }

    public function getTitle(): string
    {
        return 'Tambah Spesifikasi Produk';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
