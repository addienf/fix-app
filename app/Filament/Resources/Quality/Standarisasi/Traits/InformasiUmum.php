<?php

namespace App\Filament\Resources\Quality\Standarisasi\Traits;

use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Models\Warehouse\SerahTerima\SerahTerimaBahan;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Cache;
use Wallo\FilamentSelectify\Components\ButtonGroup;

trait InformasiUmum
{
    use SimpleFormResource, HasAutoNumber;
    protected static function informasiUmumSection($form)
    {
        $isEdit = $form->getOperation() === 'edit';

        return Section::make('Informasi Umum')
            ->collapsible()
            ->schema([
                Grid::make($isEdit ? 3 : 3)
                    ->schema([
                        static::getSumber(),

                        static::selectSerah()
                            ->hidden(fn($get) => $get('sumber') !== 'serah'),

                        static::selectSpk()
                            ->hidden(fn($get) => $get('sumber') !== 'spk'),

                        static::dateInput('tanggal', 'Tanggal'),
                    ]),
            ]);
    }

    private static function selectSerah()
    {
        return
            Select::make('serah_terima_bahan_id')
            ->label('Nomor SPK / No Seri')
            ->placeholder('Pilih Nomor SPK / No Seri')
            ->searchable()
            ->native(false)
            ->preload()
            ->required()
            ->options(function () {

                return SerahTerimaBahan::with([
                    'perencanaanProduksi.spk',
                    'perencanaanProduksi.identifikasiProduks',
                ])
                    ->whereDoesntHave('standarisasiDrawing')
                    ->latest()
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(function ($serah) {

                        $jadwal = $serah->perencanaanProduksi;

                        $spkNo = $jadwal->spk->no_spk ?? '-';

                        $noSeri = $jadwal->identifikasiProduks
                            ->pluck('no_seri')
                            ->filter()
                            ->implode(', ') ?: '-';

                        return [
                            $serah->id => "{$spkNo} - {$noSeri}",
                        ];
                    });
            })
            ->getSearchResultsUsing(function (string $search) {

                return SerahTerimaBahan::with([
                    'perencanaanProduksi.spk',
                    'perencanaanProduksi.identifikasiProduks',
                ])
                    ->whereDoesntHave('standarisasiDrawing')
                    ->where(function ($query) use ($search) {
                        $query->whereHas('perencanaanProduksi.spk', function ($q) use ($search) {
                            $q->where('no_spk', 'LIKE', "%{$search}%");
                        })
                            ->orWhereHas('perencanaanProduksi.identifikasiProduks', function ($q) use ($search) {
                                $q->where('no_seri', 'LIKE', "%{$search}%");
                            });
                    })
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(function ($serah) {

                        $jadwal = $serah->perencanaanProduksi;

                        $spkNo = $jadwal->spk->no_spk ?? '-';

                        $noSeri = $jadwal->identifikasiProduks
                            ->pluck('no_seri')
                            ->filter()
                            ->implode(', ') ?: '-';

                        return [
                            $serah->id => "{$spkNo} - {$noSeri}",
                        ];
                    })
                    ->toArray();
            });
        // ->getOptionLabelUsing(function ($value) {

        //     $serah = SerahTerimaBahan::with([
        //         'perencanaanProduksi.spk',
        //         'perencanaanProduksi.identifikasiProduks',
        //     ])->find($value);

        //     if (!$serah) return '-';

        //     $jadwal = $serah->peminjamanAlat
        //         ->spkVendor
        //         ->permintaanBahanProduksi
        //         ->jadwalProduksi;

        //     $spkNo = $jadwal->spk->no_spk ?? '-';

        //     $noSeri = $jadwal->identifikasiProduks
        //         ->pluck('no_seri')
        //         ->filter()
        //         ->implode(', ') ?: '-';

        //     return "{$spkNo} - {$noSeri}";
        // });
    }

    private static function getSumber()
    {
        return ButtonGroup::make('sumber')
            ->label('Sumber Data')
            ->options([
                'serah' => 'Dari Serah Terima',
                'spk'   => 'Langsung dari SPK',
            ])
            ->required()
            ->reactive()
            // ->columnSpanFull()
            ->onColor('primary')
            ->offColor('gray')
            ->gridDirection('row')
            ->afterStateUpdated(function ($state, callable $set) {
                $set('serah_terima_bahan_id', null);
                $set('spk_marketing_id', null);
            });
    }

    private static function selectSpk(): Select
    {
        return Select::make('spk_marketing_id')
            ->label('Nomor SPK / No Seri')
            ->placeholder('Pilih SPK / No Seri')
            ->searchable()
            ->native(false)
            ->preload()
            ->required(fn($get) => $get('sumber') === 'spk')
            ->options(function () {
                return SPKMarketing::query()
                    ->latest()
                    ->limit(10)
                    ->pluck('no_spk', 'id');
            })
            ->getSearchResultsUsing(function (string $search) {
                return SPKMarketing::query()
                    ->where('no_spk', 'like', "%{$search}%")
                    ->limit(10)
                    ->pluck('no_spk', 'id');
            });
    }
}
