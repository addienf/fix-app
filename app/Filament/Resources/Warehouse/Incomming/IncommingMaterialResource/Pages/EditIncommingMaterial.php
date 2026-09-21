<?php

namespace App\Filament\Resources\Warehouse\Incomming\IncommingMaterialResource\Pages;

use App\Filament\Resources\Warehouse\Incomming\IncommingMaterialResource;
use App\Jobs\SendGenericNotif;
use App\Notifications\GenericNotification;
use App\Services\GoogleSheetSyncService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditIncommingMaterial extends EditRecord
{
    protected static string $resource = IncommingMaterialResource::class;

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

    protected function afterSave(): void
    {
        $record = $this->record;

        if ($record->status_penerimaan_pic !== 'Diterima') {
            return;
        }

        if ($record->is_synced_sheet) {
            return;
        }

        $rows = [];

        foreach ($record->details as $detail) {
            for ($i = 1; $i <= $detail->jumlah; $i++) {
                $statusQcText = $detail->status_qc == '1' ? 'DITERIMA' : 'REJECT';
                $rows[] = [
                    $record->tanggal?->format('Y-m-d'),
                    'CH004 - Sealant Merah Xtraseal', //Ini harus nya Kode Barang - Nama Barang
                    (string) $detail->nama_material,
                    (string) $detail->batch_no,
                    '-', //Ini harus nya nomor seri
                    (int) $detail->jumlah,
                    (string) $statusQcText,
                ];
            }
        }

        // dd($rows);

        app(GoogleSheetSyncService::class)->appendBarangMasuk($rows);

        $record->update([
            'is_synced_sheet' => true,
        ]);
    }
}
