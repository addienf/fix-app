<?php

namespace App\Filament\Resources\Engineering\Pelayanan\PermintaanPelayananPelangganResource\Pages;

use App\Filament\Resources\Engineering\Pelayanan\PermintaanPelayananPelangganResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPermintaanPelayananPelanggan extends EditRecord
{
    protected static string $resource = PermintaanPelayananPelangganResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
