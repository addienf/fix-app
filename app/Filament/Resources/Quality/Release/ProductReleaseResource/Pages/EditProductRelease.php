<?php

namespace App\Filament\Resources\Quality\Release\ProductReleaseResource\Pages;

use App\Filament\Resources\Quality\Release\ProductReleaseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductRelease extends EditRecord
{
    protected static string $resource = ProductReleaseResource::class;

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
