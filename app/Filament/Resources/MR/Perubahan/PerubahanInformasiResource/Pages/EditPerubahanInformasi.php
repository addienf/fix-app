<?php

namespace App\Filament\Resources\MR\Perubahan\PerubahanInformasiResource\Pages;

use App\Filament\Resources\MR\Perubahan\PerubahanInformasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPerubahanInformasi extends EditRecord
{
    protected static string $resource = PerubahanInformasiResource::class;

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
