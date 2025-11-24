<?php

namespace App\Filament\Resources\Quality\Defect\Traits;

use App\Models\Quality\PengecekanMaterial\Electrical\PengecekanMaterialElectrical;
use App\Models\Quality\PengecekanMaterial\SS\PengecekanMaterialSS;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;

trait ChamberIdentification
{
    use SimpleFormResource;
    protected static function getChamberIdentificationSection($form): Section
    {
        $isEdit = $form->getOperation() === 'edit';

        return
            Section::make('Product Identification')
            ->collapsible()
            ->schema([
                Grid::make(2)
                    ->schema([
                        self::pilihModel()
                            ->hiddenOn('edit'),

                        self::pilihId()
                            ->hiddenOn('edit'),
                    ]),

                self::textInput('tipe', 'Type/Model')
                    ->extraAttributes([
                        'readonly' => true,
                        'style' => 'pointer-events: none;'
                    ]),

                self::textInput('no_surat', 'No Form'),

                self::textInput('volume', 'Volume'),

                self::textInput('serial_number', 'S/N')
                    ->extraAttributes([
                        'readonly' => true,
                        'style' => 'pointer-events: none;'
                    ]),

                // Hidden::make('spk_marketing_id'),

            ])->columns($isEdit ? 4 : 2);
    }

    protected static function pilihModel(): Select
    {
        return
            Select::make('tipe_sumber')
            ->label('Jenis Pengecekan')
            ->options([
                'electrical' => 'Pengecekan Electrical',
                'stainless_steel' => 'Pengecekan Stainless Steel',
            ])
            ->reactive()
            ->required()
            ->afterStateUpdated(function ($state, callable $set) {
                // RESET ketika tipe berubah
                $set('sumber_id', null);
                $set('serial_number', null);
                $set('tipe', null);
                $set('details', []);
            });
        // ->disabledOn('edit');
    }

    protected static function pilihId(): Select
    {
        return
            Select::make('sumber_id')
            ->label('Data Pengecekan')
            ->options(function (callable $get) {

                $tipe = $get('tipe_sumber');

                return match ($tipe) {
                    'electrical' =>
                    PengecekanMaterialElectrical::with([
                        'penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi'
                    ])
                        ->whereDoesntHave('defectStatus')
                        ->get()
                        ->mapWithKeys(function ($item) {
                            $spkNo =
                                $item?->penyerahanElectrical?->pengecekanSS?->kelengkapanMaterial
                                ?->standarisasiDrawing?->serahTerimaWarehouse?->peminjamanAlat
                                ?->spkVendor?->permintaanBahanProduksi?->jadwalProduksi?->spk?->no_spk
                                ?? '-';

                            $seri =
                                $item?->penyerahanElectrical?->pengecekanSS?->kelengkapanMaterial
                                ?->standarisasiDrawing?->serahTerimaWarehouse?->peminjamanAlat
                                ?->spkVendor?->permintaanBahanProduksi?->jadwalProduksi
                                ?->identifikasiProduks?->pluck('no_seri')->implode(', ')
                                ?: '-';

                            return [
                                $item->id => "{$spkNo} - {$seri}"
                            ];
                        }),

                    'stainless_steel' =>
                    PengecekanMaterialSS::with([
                        'kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi'
                    ])
                        ->whereDoesntHave('defectStatus')
                        ->get()
                        ->mapWithKeys(function ($item) {

                            $spkNo =
                                $item?->kelengkapanMaterial?->standarisasiDrawing?->serahTerimaWarehouse
                                ?->peminjamanAlat?->spkVendor?->permintaanBahanProduksi
                                ?->jadwalProduksi?->spk?->no_spk
                                ?? '-';

                            $seri =
                                $item?->kelengkapanMaterial?->standarisasiDrawing?->serahTerimaWarehouse
                                ?->peminjamanAlat?->spkVendor?->permintaanBahanProduksi
                                ?->jadwalProduksi?->identifikasiProduks?->pluck('no_seri')->implode(', ')
                                ?: '-';

                            return [
                                $item->id => "{$spkNo} - {$seri}"
                            ];
                        }),

                    default => [],
                };
            })
            ->afterStateUpdated(function ($state, callable $get, callable $set) {

                $tipe = $get('tipe_sumber');
                $model = match ($tipe) {
                    'electrical' => PengecekanMaterialElectrical::find($state),
                    'stainless_steel' => PengecekanMaterialSS::find($state),
                    default => null,
                };

                if (!$model) return;

                $root = $tipe === 'electrical'
                    ? $model?->penyerahanElectrical?->pengecekanSS
                    : $model;

                $seri =
                    $root?->kelengkapanMaterial?->standarisasiDrawing?->serahTerimaWarehouse
                    ?->peminjamanAlat?->spkVendor?->permintaanBahanProduksi
                    ?->jadwalProduksi?->identifikasiProduks?->pluck('no_seri')
                    ->implode(', ')
                    ?: '-';

                $tipeProduk =
                    $root?->kelengkapanMaterial?->standarisasiDrawing?->serahTerimaWarehouse
                    ?->peminjamanAlat?->spkVendor?->permintaanBahanProduksi
                    ?->jadwalProduksi?->identifikasiProduks?->first()?->tipe
                    ?? '-';

                $set('serial_number', $seri);
                $set('tipe', $tipeProduk);

                $ditolak = collect($model->detail->details)
                    ->map(function ($item) {
                        $partsDitolak = collect($item['parts'] ?? [])
                            ->filter(fn($p) => $p['result'] === "0")
                            ->values()
                            ->toArray();

                        if (empty($partsDitolak)) return null;

                        return [
                            'mainPart'         => $item['mainPart'] ?? '',
                            'mainPart_result'  => $item['mainPart_result'] ?? '',
                            'mainPart_status'  => $item['mainPart_status'] ?? '',
                            'parts'            => $partsDitolak,
                        ];
                    })
                    ->filter()
                    ->values()
                    ->toArray();

                $set('details', [
                    [
                        'spesifikasi_ditolak' => $ditolak,
                        'spesifikasi_revisi'  => $ditolak,
                    ]
                ]);
            })
            ->reactive()
            ->required()
            ->visible(fn(callable $get) => filled($get('tipe_sumber')))
            ->disabledOn('edit');
    }

    protected static function getNoteSection()
    {
        return
            Fieldset::make('')
            ->schema([
                self::textareaInput('note', 'Note')
                    ->columnSpanFull()
            ]);
    }

    protected static function getFileUploadSection()
    {
        return
            Fieldset::make('')
            ->schema([
                self::uploadField(
                    'file_upload',
                    'File Pendukung',
                    'Quality/DefectStatus/Files',
                    'Hanya file PDF yang diperbolehkan. Maksimal ukuran 10 MB.',
                    types: ['application/pdf'],
                    maxSize: 10240,
                ),
            ]);
    }
}
