<?php

namespace App\Filament\Resources\Quality\Defect\Traits;

use App\Models\Quality\PengecekanMaterial\Electrical\PengecekanMaterialElectrical;
use App\Models\Quality\PengecekanMaterial\SS\PengecekanMaterialSS;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
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

                self::textInput('no_surat', 'No Surat'),

                self::textInput('volume', 'Volume'),

                self::textInput('serial_number', 'S/N')
                    ->extraAttributes([
                        'readonly' => true,
                        'style' => 'pointer-events: none;'
                    ]),

                // Hidden::make('spk_marketing_id'),

            ])->columns($isEdit ? 3 : 4);
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
            ->required();
        // ->disabledOn('edit');
    }

    protected static function pilihId(): Select
    {
        return Select::make('sumber_id')
            ->label('Data Pengecekan')
            // ->options(function (callable $get) {
            //     $tipe = $get('tipe_sumber');

            //     return match ($tipe) {
            //         'electrical' => PengecekanMaterialElectrical::whereDoesntHave('defectStatus')
            //             ->get()
            //             ->filter(
            //                 fn($item) => collect($item->detail->details)
            //                     ->contains(function ($d) {
            //                         $hasMain = ($d['mainPart_result'] ?? '') === '0';
            //                         $hasParts = collect($d['parts'] ?? [])
            //                             ->contains(fn($p) => ($p['result'] ?? '') === '0');
            //                         return $hasMain || $hasParts;
            //                     })
            //             )
            //             ->mapWithKeys(fn($item) => [$item->id => $item->serial_number]),

            //         'stainless_steel' => PengecekanMaterialSS::whereDoesntHave('defectStatus')
            //             ->get()
            //             ->filter(
            //                 fn($item) => collect($item->detail->details)
            //                     ->contains(function ($d) {
            //                         $hasMain = ($d['mainPart_result'] ?? '') === '0';
            //                         $hasParts = collect($d['parts'] ?? [])
            //                             ->contains(fn($p) => ($p['result'] ?? '') === '0');
            //                         return $hasMain || $hasParts;
            //                     })
            //             )
            //             ->mapWithKeys(fn($item) => [$item->id => $item->serial_number]),

            //         default => [],
            //     };
            // })
            ->options(function (callable $get) {
                $tipe = $get('tipe_sumber');

                return match ($tipe) {
                    'electrical' => PengecekanMaterialElectrical::with([
                        'spk.jadwalProduksi.identifikasiProduks'
                    ])
                        ->get()
                        ->mapWithKeys(function ($item) {

                            // No SPK
                            $spkNo = $item->spk->no_spk ?? '-';

                            // No Seri (ambil semua seri, pisah koma)
                            $seri = $item->spk
                                ?->jadwalProduksi
                                ?->identifikasiProduks
                                ?->pluck('no_seri')
                                ?->filter()
                                ?->implode(', ') ?? '-';

                            return [
                                $item->id => "{$spkNo} - {$seri}",
                            ];
                        }),

                    'stainless_steel' => PengecekanMaterialSS::with([
                        'kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi'
                    ])
                        ->get()
                        ->mapWithKeys(function ($item) {

                            // $spkNo = $item->spk->no_spk ?? '-';
                            $spkNo =
                                $item
                                ?->kelengkapanMaterial
                                ?->standarisasiDrawing
                                ?->serahTerimaWarehouse
                                ?->peminjamanAlat
                                ?->spkVendor
                                ?->permintaanBahanProduksi
                                ?->jadwalProduksi
                                ?->spk
                                ?->no_spk
                                ?? '-';

                            $seri =
                                $item
                                ?->kelengkapanMaterial
                                ?->standarisasiDrawing
                                ?->serahTerimaWarehouse
                                ?->peminjamanAlat
                                ?->spkVendor
                                ?->permintaanBahanProduksi
                                ?->jadwalProduksi
                                ?->identifikasiProduks
                                ->pluck('no_seri')
                                ->filter()
                                ->implode(', ')
                                ?: '-';

                            return [
                                $item->id => "{$spkNo} - {$seri}",
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
                    default => null
                };

                if (!$model) return;

                $seri =
                    $model
                    ?->kelengkapanMaterial
                    ?->standarisasiDrawing
                    ?->serahTerimaWarehouse
                    ?->peminjamanAlat
                    ?->spkVendor
                    ?->permintaanBahanProduksi
                    ?->jadwalProduksi
                    ?->identifikasiProduks
                    ->pluck('no_seri')
                    ->filter()
                    ->implode(', ')
                    ?: '-';

                $tipe =
                    $model
                    ?->kelengkapanMaterial
                    ?->standarisasiDrawing
                    ?->serahTerimaWarehouse
                    ?->peminjamanAlat
                    ?->spkVendor
                    ?->permintaanBahanProduksi
                    ?->jadwalProduksi
                    ?->identifikasiProduks
                    ?->first()
                    ?->tipe
                    ?? '-';

                // dd(vars: $tipe);

                $set('tipe', $tipe);
                $set('serial_number', $seri);

                // FILTER DETAIL YANG DITOLAK
                $ditolak = collect($model->detail->details)
                    ->map(function ($item) {
                        $partsDitolak = collect($item['parts'] ?? [])
                            ->filter(fn($p) => $p['result'] === "0")
                            ->values()
                            ->toArray();

                        if (count($partsDitolak) === 0) return null;

                        return [
                            'mainPart' => $item['mainPart'] ?? '',
                            'mainPart_result' => $item['mainPart_result'] ?? '',
                            'mainPart_status' => $item['mainPart_status'] ?? '',
                            'parts' => $partsDitolak
                        ];
                    })
                    ->filter()
                    ->values()
                    ->toArray();

                $set('details', [
                    [
                        'spesifikasi_ditolak' => $ditolak,
                        'spesifikasi_revisi' => $ditolak,
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
                // Textarea::make('note')
                //     ->required()
                //     ->label('Note')
                //     ->columnSpanFull()
            ]);
    }

    protected static function getFileUploadSection()
    {
        return
            Fieldset::make('')
            ->schema([
                // FileUpload::make('file_upload')
                //     ->label('File Pendukung')
                //     ->directory('Quality/DefectStatus/Files')
                //     ->acceptedFileTypes(['application/pdf'])
                //     ->maxSize(10240)
                //     ->required()
                //     ->columnSpanFull()
                //     ->helperText('Hanya file PDF yang diperbolehkan. Maksimal ukuran 10 MB.'),

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
