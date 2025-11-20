<?php

namespace App\Filament\Resources\Quality\PengecekanMaterial\Electrical\Traits;

use App\Models\Production\Penyerahan\PenyerahanElectrical\PenyerahanElectrical;
use App\Models\Quality\KelengkapanMaterial\SS\KelengkapanMaterialSS;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;

trait ChamberIdentification
{
    use SimpleFormResource;
    protected static function getChamberIdentificationSection($form): Section
    {
        $isEdit = $form->getOperation() === 'edit';

        return
            Section::make('Chamber Identification')
            ->collapsible()
            ->schema([

                Grid::make($isEdit ? 2 : 3)
                    ->schema([

                        //
                        self::getSelect()
                            ->hiddenOn('edit')
                            ->placeholder('Pilih No SPK'),

                        self::textInput('tipe', 'Type/Model')
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),

                        self::textInput('volume', 'Volume'),
                        // ->extraAttributes([
                        //     'readonly' => true,
                        //     'style' => 'pointer-events: none;'
                        // ]),

                    ]),

            ]);
    }

    private static function getSelect()
    {
        return
            Select::make('penyerahan_electrical_id')
            ->label('Nomor SPK / No Seri')
            ->placeholder('Pilih Serial Number')
            ->searchable()
            ->native(false)
            ->preload()
            ->reactive()
            ->required()
            ->options(
                fn() =>
                PenyerahanElectrical::with([
                    'pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk',
                    'pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.identifikasiProduks',
                ])
                    ->latest()
                    ->limit(20)
                    ->get()
                    ->mapWithKeys(function ($item) {

                        $jadwal = $item->pengecekanSS
                            ->kelengkapanMaterial
                            ->standarisasiDrawing
                            ->serahTerimaWarehouse
                            ->peminjamanAlat
                            ->spkVendor
                            ->permintaanBahanProduksi
                            ->jadwalProduksi;

                        $spkNo = $jadwal->spk->no_spk ?? '-';

                        $seri = $jadwal->identifikasiProduks
                            ->pluck('no_seri')
                            ->filter()
                            ->implode(', ') ?: '-';

                        return [
                            $item->id => "{$spkNo} - {$seri}",
                        ];
                    })
            )
            // ->getSearchResultsUsing(function ($search) {
            //     return StandarisasiDrawing::with([
            //         'serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk',
            //         'serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.identifikasiProduks',
            //     ])
            //         ->whereHas(
            //             'serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk',
            //             fn($q) =>
            //             $q->where('no_spk', 'like', "%{$search}%")
            //         )
            //         ->limit(10)
            //         ->get()
            //         ->mapWithKeys(function ($std) {

            //             $jadwal = $std->serahTerimaWarehouse
            //                 ->peminjamanAlat
            //                 ->spkVendor
            //                 ->permintaanBahanProduksi
            //                 ->jadwalProduksi;

            //             $spkNo = $jadwal->spk->no_spk ?? '-';

            //             $seri = $jadwal->identifikasiProduks
            //                 ->pluck('no_seri')
            //                 ->filter()
            //                 ->implode(', ') ?: '-';

            //             return [
            //                 $std->id => "{$spkNo} - {$seri}",
            //             ];
            //         })
            //         ->toArray();
            // })
            // ->getOptionLabelUsing(function ($value) {
            //     $std = StandarisasiDrawing::with([
            //         'serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk',
            //         'serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.identifikasiProduks',
            //     ])->find($value);

            //     if (!$std) return '-';

            //     $jadwal = $std->serahTerimaWarehouse
            //         ->peminjamanAlat
            //         ->spkVendor
            //         ->permintaanBahanProduksi
            //         ->jadwalProduksi;

            //     $spkNo = $jadwal->spk->no_spk ?? '-';

            //     $seri = $jadwal->identifikasiProduks
            //         ->pluck('no_seri')
            //         ->filter()
            //         ->implode(', ') ?: '-';

            //     return "{$spkNo} - {$seri}";
            // })
            ->afterStateUpdated(function ($state, callable $set) {
                if (!$state) return;

                $penyerahan = PenyerahanElectrical::with([
                    'pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk'
                ])->find($state);

                $tipe =
                    $penyerahan
                    ?->pengecekanSS
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

                $set('tipe', $tipe);
            });
    }

    public static function getNote()
    {
        return Card::make('')
            ->schema([

                Textarea::make('note')
                    ->required()
                    ->label('Note')
                    ->columnSpanFull()

            ]);
    }
}
