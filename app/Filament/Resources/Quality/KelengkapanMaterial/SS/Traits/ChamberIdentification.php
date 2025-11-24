<?php

namespace App\Filament\Resources\Quality\KelengkapanMaterial\SS\Traits;

use App\Models\Quality\Standarisasi\StandarisasiDrawing;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;

trait ChamberIdentification
{
    protected static function getChamberIdentificationSection($form): Section
    {
        $isEdit = $form->getOperation() === 'edit';

        return Section::make('Chamber Identification')
            ->collapsible()
            ->schema([
                //

                self::getSelectedSPK()
                    ->hiddenOn('edit'),

                self::textInput('tipe', 'Type/Model')
                    ->extraAttributes([
                        'readonly' => true,
                        'style' => 'pointer-events: none;'
                    ]),

                self::textInput('ref_document', 'Ref Document'),

                self::textInput('no_order_temp', 'No Order')
                    ->columnSpanFull()
                    ->hiddenOn('edit')
                    ->extraAttributes([
                        'readonly' => true,
                        'style' => 'pointer-events: none;'
                    ]),

            ])->columns($isEdit ? 2 : 3);
    }

    private static function getSelectedSPK()
    {
        return
            Select::make('standarisasi_drawing_id')
            ->label('Nomor SPK / No Seri')
            ->placeholder('Pilih Nomor SPK / No Seri')
            ->searchable()
            ->native(false)
            ->preload()
            ->reactive()
            ->required()
            ->options(
                fn() =>
                StandarisasiDrawing::with([
                    'serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk',
                    'serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.identifikasiProduks',
                ])
                    ->whereDoesntHave('kelengkapanMaterial')
                    ->latest()
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(function ($std) {

                        $jadwal = $std->serahTerimaWarehouse
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
                            $std->id => "{$spkNo} - {$seri}",
                        ];
                    })
            )
            ->getSearchResultsUsing(function ($search) {
                return StandarisasiDrawing::with([
                    'serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk',
                    'serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.identifikasiProduks',
                ])
                    ->whereHas(
                        'serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk',
                        fn($q) =>
                        $q->where('no_spk', 'like', "%{$search}%")
                    )
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(function ($std) {

                        $jadwal = $std->serahTerimaWarehouse
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
                            $std->id => "{$spkNo} - {$seri}",
                        ];
                    })
                    ->toArray();
            })
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

                $standarisasi = StandarisasiDrawing::with([
                    'serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk'
                ])->find($state);

                $no_order =
                    $standarisasi
                    ?->serahTerimaWarehouse
                    ?->peminjamanAlat
                    ?->spkVendor
                    ?->permintaanBahanProduksi
                    ?->jadwalProduksi
                    ?->spk
                    ?->no_order
                    ?? '-';

                $tipe =
                    $standarisasi
                    ?->serahTerimaWarehouse
                    ?->peminjamanAlat
                    ?->spkVendor
                    ?->permintaanBahanProduksi
                    ?->jadwalProduksi
                    ?->identifikasiProduks
                    ?->first()
                    ?->tipe
                    ?? '-';

                $set('no_order_temp', $no_order);
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
