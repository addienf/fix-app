<?php

namespace App\Filament\Resources\Quality\Ketidaksesuaian\KetidaksesuaianResource\Pages;

use App\Filament\Resources\Quality\Ketidaksesuaian\KetidaksesuaianResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateKetidaksesuaian extends CreateRecord
{
    protected static string $resource = KetidaksesuaianResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string
    {
        return 'Tambah Data Ketidaksesuaian Produk & Material';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
