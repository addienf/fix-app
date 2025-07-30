<?php

namespace App\Filament\Resources\Engineering\Complain\ComplainResource\Pages;

use App\Filament\Resources\Engineering\Complain\ComplainResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditComplain extends EditRecord
{
    protected static string $resource = ComplainResource::class;

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
