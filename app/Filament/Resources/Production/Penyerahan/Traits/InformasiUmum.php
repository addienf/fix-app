<?php

namespace App\Filament\Resources\Production\Penyerahan\Traits;

use App\Models\Production\Penyerahan\PenyerahanElectrical\PenyerahanElectrical;
use App\Models\Quality\PengecekanMaterial\Electrical\PengecekanMaterialElectrical;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;

trait InformasiUmum
{
    use SimpleFormResource, HasAutoNumber;
    protected static function getInformasiUmumSection($form): Section
    {
        $isEdit = $form->getOperation() === 'edit';

        return
            Section::make('Informasi Umum')
            ->collapsible()
            ->schema([

                Grid::make($isEdit ? 3 : 2)
                    ->schema([

                        self::getSelect()
                            ->hiddenOn('edit'),

                        self::dateInput('tanggal', 'Tanggal'),

                        self::textInput('penanggug_jawab', 'Penanggung Jawab'),

                        self::textInput('penerima', 'Penerima'),

                    ]),

            ]);
    }

    private static function getKondisiProduk()
    {
        return
            Section::make('Kondisi Produk')
            ->collapsible()
            ->schema([

                self::selectKondisi()
                    ->placeholder('Pilih Kondisi')
                    ->columnSpanFull()

            ]);
    }

    private static function getCatatanPenting()
    {
        return
            Section::make('Catatan Tambahan')
            ->collapsible()
            ->schema([

                self::textareaInput('catatan_tambahan', 'Catatan Tambahan')
                    ->columnSpanFull()

            ]);
    }

    private static function getSelect(): Select
    {
        return
            Select::make('pengecekan_electrical_id')
            ->label('No SPK / Nomor Seri')
            ->placeholder('Pilih No SPK / Nomor Seri')
            ->required()
            ->searchable()
            ->reactive()
            // ->options(
            //     fn() =>
            //     PengecekanMaterialElectrical::with([
            //         'penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk',
            //         'penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.identifikasiProduks',
            //     ])
            //         ->whereDoesntHave('penyerahanProdukJadi')
            //         ->latest()
            //         ->limit(10)
            //         ->get()
            //         ->mapWithKeys(function ($std) {

            //             $jadwal = $std->penyerahanElectrical
            //                 ->pengecekanSS
            //                 ->kelengkapanMaterial
            //                 ->standarisasiDrawing
            //                 ->serahTerimaWarehouse
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
            // )
            ->options(function () {
                return PengecekanMaterialElectrical::with([
                    'penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk',
                    'penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.identifikasiProduks',
                ])
                    ->whereDoesntHave('penyerahanProdukJadi')
                    ->latest()
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(function ($std) {

                        $jadwal = $std->penyerahanElectrical
                            ->pengecekanSS
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
                            $std->id => "{$spkNo} - {$seri}",
                        ];
                    });
            })
            ->getSearchResultsUsing(function ($search) {
                return PengecekanMaterialElectrical::with([
                    'penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk',
                    'penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.identifikasiProduks',
                ])
                    ->whereDoesntHave('penyerahanProdukJadi')
                    ->whereHas(
                        'penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk',
                        fn($q) => $q->where('no_spk', 'like', "%{$search}%")
                    )
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(function ($std) {

                        $jadwal = $std->penyerahanElectrical
                            ->pengecekanSS
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
                            $std->id => "{$spkNo} - {$seri}",
                        ];
                    });
            })
            // ->getOptionLabelUsing(function ($value) {
            //     $std = PengecekanMaterialElectrical::with([
            //         'penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk',
            //         'penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.identifikasiProduks',
            //     ])->find($value);

            //     if (!$std) return '-';

            //     $jadwal = $std->penyerahanElectrical
            //         ->pengecekanSS
            //         ->kelengkapanMaterial
            //         ->standarisasiDrawing
            //         ->serahTerimaWarehouse
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

                $pengecekan =
                    PengecekanMaterialElectrical::with('penyerahanElectrical')->find($state);

                if (!$pengecekan || !$pengecekan->penyerahanElectrical) {
                    $set('details', []);
                    return;
                }

                $detail = $pengecekan->penyerahanElectrical;

                $set('details', [
                    [
                        'nama_produk' => $detail->nama_produk ?? '-',
                        'jumlah'      => $detail->jumlah ?? '-',
                        'no_spk'      => $detail->no_spk ?? '-',
                        'tipe'        => $detail->tipe ?? '-',
                        'volume'      => $pengecekan->volume ?? '-',
                    ]
                ]);
            });
    }

    protected static function selectKondisi(): Select
    {
        return
            Select::make('kondisi_produk')
            ->label('Kondisi Produk')
            ->required()
            ->placeholder('Pilih Kondisi Produk')
            ->options([
                'baik' => 'Baik',
                'rusak' => 'Rusak',
                'perlu_perbaikan' => 'Perlu Perbaikan'
            ]);
    }
}
