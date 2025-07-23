<?php

namespace App\Filament\Resources\Warehouse\SerahTerima\SerahTerimaBahanResource\Pages;

use App\Filament\Resources\Warehouse\SerahTerima\SerahTerimaBahanResource;
use App\Jobs\Warehouse\SendSerahTerimaBahanNotif;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;

class CreateSerahTerimaBahan extends CreateRecord
{
    protected static string $resource = SerahTerimaBahanResource::class;
    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string
    {
        return 'Tambah Data Serah Terima Bahan';
    }

    public function getBreadcrumb(): string
    {
        return 'Tambah';
    }
}
