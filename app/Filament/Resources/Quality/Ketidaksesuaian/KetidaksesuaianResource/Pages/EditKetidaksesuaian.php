<?php

namespace App\Filament\Resources\Quality\Ketidaksesuaian\KetidaksesuaianResource\Pages;

use App\Filament\Resources\Quality\Ketidaksesuaian\KetidaksesuaianResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKetidaksesuaian extends EditRecord
{
    protected static string $resource = KetidaksesuaianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
