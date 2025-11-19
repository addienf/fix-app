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

trait InformasiUmum
{
    use SimpleFormResource, HasAutoNumber;
    protected static function informasiUmumSection($form)
    {
        $isEdit = $form->getOperation() === 'edit';

        return Section::make('Informasi Umum')
            ->collapsible()
            ->schema([
                Grid::make($isEdit ? 1 : 2)
                    ->schema([
                        static::select()
                            ->hiddenOn('edit'),

                        static::dateInput('tanggal', 'Tanggal'),
                    ]),
            ]);
    }

    // protected static function selectInputSPK(): Select
    // {
    //     return
    //         Select::make('spk_marketing_id')
    //         ->label('Nomor SPK')
    //         ->relationship(
    //             'spk',
    //             'no_spk',
    //             fn($query) => $query->whereIn('id', Cache::rememberForever(
    //                 SPKMarketing::$CACHE_KEYS['standarisasi'],
    //                 fn() => SPKMarketing::whereHas('permintaan.serahTerimaBahan', function ($query) {
    //                     $query->where('status_penerimaan', 'Diterima');
    //                 })
    //                     ->whereDoesntHave('standarisasi')
    //                     ->pluck('id')
    //                     ->toArray()
    //             ))
    //         )
    //         ->placeholder('Pilin No SPK')
    //         ->native(false)
    //         ->searchable()
    //         ->preload()
    //         ->required()
    //         ->reactive();
    // }

    private static function select()
    {
        return
            Select::make('serah_terima_bahan_id')
            ->label('Nomor SPK / No Seri')
            ->placeholder('Pilih Data Serah Terima')
            ->searchable()
            ->native(false)
            ->preload()
            ->required()
            ->options(function () {

                return SerahTerimaBahan::with([
                    'peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk',
                    'peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.identifikasiProduks',
                ])
                    ->whereDoesntHave('standarisasiDrawing')
                    ->latest()
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(function ($serah) {

                        $jadwal = $serah->peminjamanAlat
                            ->spkVendor
                            ->permintaanBahanProduksi
                            ->jadwalProduksi;

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
                    'peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk',
                    'peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.identifikasiProduks',
                ])
                    ->whereDoesntHave('standarisasiDrawing')
                    ->where(function ($query) use ($search) {
                        $query->whereHas('peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk', function ($q) use ($search) {
                            $q->where('no_spk', 'LIKE', "%{$search}%");
                        })
                            ->orWhereHas('peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.identifikasiProduks', function ($q) use ($search) {
                                $q->where('no_seri', 'LIKE', "%{$search}%");
                            });
                    })
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(function ($serah) {

                        $jadwal = $serah->peminjamanAlat
                            ->spkVendor
                            ->permintaanBahanProduksi
                            ->jadwalProduksi;

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
            })
            ->getOptionLabelUsing(function ($value) {

                $serah = SerahTerimaBahan::with([
                    'peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk',
                    'peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.identifikasiProduks',
                ])->find($value);

                if (!$serah) return '-';

                $jadwal = $serah->peminjamanAlat
                    ->spkVendor
                    ->permintaanBahanProduksi
                    ->jadwalProduksi;

                $spkNo = $jadwal->spk->no_spk ?? '-';

                $noSeri = $jadwal->identifikasiProduks
                    ->pluck('no_seri')
                    ->filter()
                    ->implode(', ') ?: '-';

                return "{$spkNo} - {$noSeri}";
            });
    }
}
