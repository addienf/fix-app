<?php

namespace App\Filament\Resources\Warehouse\PermintaanBahanWBB\PermintaanBahanResource\Pages;

use App\Filament\Resources\Warehouse\PermintaanBahanWBB\PermintaanBahanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\DB;
use Throwable;

class EditPermintaanBahan extends EditRecord
{
    protected static string $resource = PermintaanBahanResource::class;
    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        try {

            return DB::transaction(function () use ($record, $data) {

                $details = $data['details'] ?? [];
                unset($data['details']);

                $record->update($data);

                $record->details()->delete();

                foreach ($details as $detail) {
                    $record->details()->create($detail);
                }

                return $record;
            });
        } catch (Throwable $e) {

            DB::rollBack();

            Notification::make()
                ->title('Gagal Menyimpan Data')
                ->body('Input tidak valid!')
                ->danger()
                ->send();

            throw new Halt();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
