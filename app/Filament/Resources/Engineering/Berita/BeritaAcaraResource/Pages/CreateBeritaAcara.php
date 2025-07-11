<?php

namespace App\Filament\Resources\Engineering\Berita\BeritaAcaraResource\Pages;

use App\Filament\Resources\Engineering\Berita\BeritaAcaraResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBeritaAcara extends CreateRecord
{
    protected static string $resource = BeritaAcaraResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
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
