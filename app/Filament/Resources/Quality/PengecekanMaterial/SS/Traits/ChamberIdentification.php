<?php

namespace App\Filament\Resources\Quality\PengecekanMaterial\SS\Traits;

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

                // Grid::make($isEdit ? 2 : 3)
                Grid::make([
                    'default' => 1,
                    'md' => $isEdit ? 2 : 3,
                    'lg' => $isEdit ? 2 : 3,
                ])
                    ->schema([

                        self::getSelect()
                            ->hiddenOn('edit'),

                        self::textInput('tipe', 'Type/Model')
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),

                        self::textInput('ref_document', 'Ref Document'),

                    ]),

            ]);
    }

    private static function getSelect()
    {
        return
            Select::make('kelengkapan_material_id')
            ->label('Nomor SPK / No Seri')
            ->placeholder('Pilih Nomor SPK / No Seri')
            ->searchable()
            ->native(false)
            ->preload()
            ->reactive()
            ->required()
            ->options(function () {

                return KelengkapanMaterialSS::with([
                    'standarisasiDrawing.serahTerimaWarehouse.perencanaanProduksi.spk',
                    'standarisasiDrawing.serahTerimaWarehouse.perencanaanProduksi.identifikasiProduks',
                ])
                    ->whereDoesntHave('pengecekanSS')
                    ->latest()
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(function ($std) {

                        $jadwal = $std->standarisasiDrawing->serahTerimaWarehouse->perencanaanProduksi;

                        $spkNo = $jadwal->spk->no_spk ?? '-';

                        $seri = $jadwal->identifikasiProduks
                            ->pluck('no_seri')
                            ->filter()
                            ->implode(', ') ?: '-';

                        return [
                            $std->id => "{$spkNo} - {$seri}",
                        ];
                    });
            })

            // 🔹 Saat user mengetik search (limit 10)
            ->getSearchResultsUsing(function ($search) {

                return KelengkapanMaterialSS::with([
                    'standarisasiDrawing.serahTerimaWarehouse.perencanaanProduksi.spk',
                    'standarisasiDrawing.serahTerimaWarehouse.perencanaanProduksi.identifikasiProduks',
                ])
                    ->whereDoesntHave('pengecekanSS')
                    ->whereHas(
                        'standarisasiDrawing.serahTerimaWarehouse.perencanaanProduksi.spk',
                        fn($q) => $q->where('no_spk', 'like', "%{$search}%")
                    )
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(function ($std) {

                        $jadwal = $std->standarisasiDrawing->serahTerimaWarehouse->perencanaanProduksi;

                        $spkNo = $jadwal->spk->no_spk ?? '-';

                        $seri = $jadwal->identifikasiProduks
                            ->pluck('no_seri')
                            ->filter()
                            ->implode(', ') ?: '-';

                        return [
                            $std->id => "{$spkNo} - {$seri}",
                        ];
                    });
            })
            ->afterStateUpdated(function ($state, callable $set) {
                if (!$state) return;

                $kelengkapan = KelengkapanMaterialSS::with([
                    'standarisasiDrawing.serahTerimaWarehouse.perencanaanProduksi.spk'
                ])->find($state);

                $no_order =
                    $kelengkapan
                    ?->standarisasiDrawing
                    ?->serahTerimaWarehouse
                    ?->perencanaanProduksi
                    ?->spk
                    ?->no_order
                    ?? '-';

                $tipe =
                    $kelengkapan
                    ?->standarisasiDrawing
                    ?->serahTerimaWarehouse
                    ?->perencanaanProduksi
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
