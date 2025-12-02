<?php

namespace App\Filament\Resources\Engineering\Permintaan\Traits;

use App\Models\Engineering\SPK\SPKService;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;

trait InformasiUmum
{
    use SimpleFormResource, HasAutoNumber;
    public static function getInformasiUmumSection($form)
    {
        $isEdit = $form->getOperation() === 'edit';

        return Section::make('Informasi Umum')
            ->collapsible()
            ->schema([

                Select::make('spk_service_id')
                    ->label('Nomor SPK Service')
                    ->options(function () {
                        return SPKService::query()
                            ->where('status', 'Selesai')
                            ->whereDoesntHave('permintaanSparepart')
                            ->limit(10)
                            ->pluck('no_spk_service', 'id');
                    })
                    ->getSearchResultsUsing(function (string $search) {
                        return SPKService::query()
                            ->where('status', 'Selesai')
                            ->whereDoesntHave('permintaanSparepart')
                            ->where('no_spk_service', 'like', "%{$search}%")
                            ->limit(10)
                            ->pluck('no_spk_service', 'id');
                    })
                    ->native(false)
                    ->searchable()
                    ->preload()
                    ->required()
                    ->columnSpanFull()
                    ->hiddenOn(operations: 'edit'),

                self::autoNumberField2('no_surat', 'Nomor Surat', [
                    'prefix' => 'QKS',
                    'section' => 'ENG',
                    'type' => 'PERMINTAAN',
                    'table' => 'permintaan_spareparts',
                ])
                    ->hiddenOn('edit'),

                self::dateInput('tanggal', 'Tanggal'),

                self::textInput('dari', 'Dari'),

                self::textInput('kepada', 'Kepada')
                    ->placeholder('Warehouse')
            ])
            ->columns($isEdit ? 3 : 2);
    }
}
