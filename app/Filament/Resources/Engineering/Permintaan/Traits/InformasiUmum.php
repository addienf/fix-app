<?php

namespace App\Filament\Resources\Engineering\Permintaan\Traits;

use App\Models\Engineering\SPK\SPKService;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Cache;

trait InformasiUmum
{
    use SimpleFormResource, HasAutoNumber;
    public static function getInformasiUmumSection($form)
    {
        // $lastValue = PermintaanSparepart::latest('no_surat')->value('no_surat');
        $isEdit = $form->getOperation() === 'edit';

        return Section::make('Informasi Umum')
            ->collapsible()
            ->schema([

                Select::make('spk_service_id')
                    ->label('Nomor SPK Service')
                    ->options(function () {
                        return Cache::rememberForever(SPKService::$CACHE_KEYS['permintaanSparepart'], function () {
                            return SPKService::where('status_penyelesaian', 'Selesai')
                                ->whereDoesntHave('permintaanSparepart')
                                ->get()
                                ->pluck('no_spk_service', 'id');
                        });
                    })
                    ->native(false)
                    ->searchable()
                    ->preload()
                    ->required()
                    ->columnSpanFull()
                    ->hiddenOn(operations: 'edit'),

                // TextInput::make('no_surat')
                //     ->label('Nomor Surat')
                //     ->hint('Format: No Surat')
                //     ->placeholder($lastValue ? "Data Terakhir : {$lastValue}" : 'Data Belum Tersedia')
                //     ->hiddenOn('edit')
                //     ->unique(ignoreRecord: true)
                //     ->required(),

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
