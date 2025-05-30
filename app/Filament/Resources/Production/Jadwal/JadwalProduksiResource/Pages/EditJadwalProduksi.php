<?php

namespace App\Filament\Resources\Production\Jadwal\JadwalProduksiResource\Pages;

use App\Filament\Resources\Production\Jadwal\JadwalProduksiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJadwalProduksi extends EditRecord
{
    protected static string $resource = JadwalProduksiResource::class;

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
