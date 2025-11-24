<?php

namespace App\Filament\Resources\Production\Penyerahan\PenyerahanElectrical\Traits;

use App\Models\Quality\PengecekanMaterial\SS\PengecekanMaterialSS;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;

trait InformasiProduk
{
    use SimpleFormResource, HasAutoNumber;
    protected static function getInformasiProdukSection()
    {
        // $isEdit = $form->getOperation() === 'edit';

        return
            Section::make('Informasi Produk')
            ->collapsible()
            ->schema([
                self::selectMaterialID()
                    ->hiddenOn('edit')
                    ->columnSpanFull(),

                self::textInput('nama_produk', 'Nama Produk')
                    ->extraAttributes([
                        'readonly' => true,
                        'style' => 'pointer-events: none;'
                    ]),

                self::textInput('tipe', 'Tipe/Model')
                    ->extraAttributes([
                        'readonly' => true,
                        'style' => 'pointer-events: none;'
                    ]),

                self::textInput('no_spk', 'No SPK MKT')
                    ->extraAttributes([
                        'readonly' => true,
                        'style' => 'pointer-events: none;'
                    ]),

                self::textInput('tanggal_selesai', 'Tanggal Produksi Selesai')
                    ->formatStateUsing(function ($state) {
                        return $state
                            ? \Carbon\Carbon::parse($state)->format('d M Y')
                            : '-';
                    })
                    ->extraAttributes([
                        'readonly' => true,
                        'style' => 'pointer-events: none;'
                    ])
                    ->required(),

                self::textInput('jumlah', 'Jumlah Unit')
                    ->extraAttributes([
                        'readonly' => true,
                        'style' => 'pointer-events: none;'
                    ]),

                self::selectKondisi(),

                self::textareaInput('deskripsi_kondisi', 'Deskripsi Produk')
                    ->columnSpanFull(),

            ])->columns(3);
    }

    private static function selectMaterialID(): Select
    {
        return
            Select::make('pengecekan_material_id')
            ->label('No SPK / Nomor Seri')
            ->placeholder('Pilih No SPK / Nomor Seri')
            ->required()
            ->searchable()
            ->reactive()
            // ->options(
            //     fn() =>
            //     PengecekanMaterialSS::with([
            //         'kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk',
            //         'kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.identifikasiProduks',
            //     ])
            //         ->whereDoesntHave('penyerahan')
            //         ->latest()
            //         ->limit(10)
            //         ->get()
            //         ->mapWithKeys(function ($std) {

            //             $jadwal = $std->kelengkapanMaterial
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
                return PengecekanMaterialSS::with([
                    'kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk',
                    'kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.identifikasiProduks',
                ])
                    ->whereDoesntHave('penyerahan')
                    ->latest()
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(function ($std) {

                        $jadwal = $std->kelengkapanMaterial
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

            // search
            ->getSearchResultsUsing(function ($search) {
                return PengecekanMaterialSS::with([
                    'kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk',
                    'kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.identifikasiProduks',
                ])
                    ->whereDoesntHave('penyerahan')
                    ->whereHas(
                        'kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk',
                        fn($q) => $q->where('no_spk', 'like', "%{$search}%")
                    )
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(function ($std) {

                        $jadwal = $std->kelengkapanMaterial
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

            //     $std = PengecekanMaterialSS::with([
            //         'kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk',
            //         'kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.identifikasiProduks',
            //     ])->find($value);

            //     if (!$std) return '-';

            //     $jadwal = $std->kelengkapanMaterial
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

                $pengecekan = PengecekanMaterialSS::with([
                    'kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi' => function ($q) {
                        $q->with([
                            'spk:id,no_spk,spesifikasi_product_id',
                            'identifikasiProduks:id,jadwal_produksi_id,tipe,jumlah',
                            'timelines:id,jadwal_produksi_id,tanggal_selesai'
                        ]);
                    }
                ])->find($state);

                $jadwal = $pengecekan
                    ->kelengkapanMaterial
                    ->standarisasiDrawing
                    ->serahTerimaWarehouse
                    ->peminjamanAlat
                    ->spkVendor
                    ->permintaanBahanProduksi
                    ->jadwalProduksi;

                $namaProduk = $jadwal->spk?->spesifikasiProduct?->details->first()?->product?->name ?? '-';
                $spkNo = $jadwal->spk?->no_spk ?? '-';
                $tipe = $jadwal->identifikasiProduks->implode('tipe', ', ') ?: '-';
                $jumlah = $jadwal->identifikasiProduks->implode('jumlah', ', ') ?: '-';
                $selesai = optional($jadwal->timelines->first()?->tanggal_selesai)->format('d F Y') ?? '-';

                $set('nama_produk', $namaProduk);
                $set('jumlah', $jumlah);
                $set('tanggal_selesai', $selesai);
                $set('tipe', $tipe);
                $set('no_spk', $spkNo);
            });
    }
}
