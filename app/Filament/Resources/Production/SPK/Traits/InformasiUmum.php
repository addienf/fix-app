<?php

namespace App\Filament\Resources\Production\SPK\Traits;

use App\Models\Production\Jadwal\JadwalProduksi;
use App\Models\Production\Penyerahan\PenyerahanElectrical\PenyerahanElectrical;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

trait InformasiUmum
{
    use SimpleFormResource, HasAutoNumber;
    protected static function getInformasiUmumSection($form): Section
    {
        return
            Section::make('Informasi Umum')
            ->collapsible()
            ->schema([
                Grid::make(2)
                    ->schema([

                        self::autoNumberField2('no_spk', 'Nomor SPK', [
                            'prefix' => 'QKS',
                            'section' => 'PRO',
                            'type' => 'SPK',
                            'table' => 'spk_qualities',
                        ])
                            ->hiddenOn('edit'),

                        self::selectMaterialID()
                            ->placeholder('Pilih Nomor SPK')
                            ->hiddenOn('edit'),

                        self::textInput('dari', 'Dari')
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),

                        self::textInput('kepada', 'Kepada')
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),
                    ]),
            ]);
    }

    private static function selectMaterialID(): Select
    {
        return
            Select::make('penyerahan_electrical_id')
            ->label('No SPK / Nomor Seri')
            ->placeholder('Pilih No SPK / Nomor Seri')
            ->required()
            ->searchable()
            ->reactive()
            ->options(function () {
                return PenyerahanElectrical::with([
                    'pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk',
                    'pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.identifikasiProduks',
                ])
                    ->whereDoesntHave('spkQC')
                    ->latest()
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(function ($std) {

                        $jadwal = $std->pengecekanSS
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

                return PenyerahanElectrical::with([
                    'pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk',
                    'pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.identifikasiProduks',
                ])
                    ->whereHas(
                        'pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk',
                        fn($q) => $q->where('no_spk', 'like', "%{$search}%")
                    )
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(function ($std) {

                        $jadwal = $std->pengecekanSS
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
            // ->afterStateUpdated(function ($state, callable $set) {

            //     if (!$state) return;

            //     $penyerahan = PenyerahanElectrical::with([
            //         'pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi' => function ($q) {
            //             $q->with([
            //                 'spk:id,no_spk,spesifikasi_product_id,no_order,tanggal,dari,kepada',
            //                 'identifikasiProduks:id,jadwal_produksi_id,tipe,jumlah',
            //                 'timelines:id,jadwal_produksi_id,tanggal_selesai'
            //             ]);
            //         }
            //     ])->find($state);

            //     $spk = $penyerahan
            //         ->pengecekanSS
            //         ->kelengkapanMaterial
            //         ->standarisasiDrawing
            //         ->serahTerimaWarehouse
            //         ->peminjamanAlat
            //         ->spkVendor
            //         ->permintaanBahanProduksi
            //         ->jadwalProduksi
            //         ->spk;

            //     $noUrs = $spk?->spesifikasiProduct?->urs?->no_urs ?? '-';
            //     $rencanaPengiriman = optional($spk?->tanggal)->format('d F Y') ?? '-';
            //     $dari = $spk?->dari;
            //     $kepada = $spk?->kepada;

            //     $details = $spk?->spesifikasiProduct?->details->map(function ($detail) use ($noUrs, $rencanaPengiriman) {
            //         return [
            //             'nama_produk' => $detail->product?->name ?? '-',
            //             'jumlah' => $detail?->quantity ?? '-',
            //             'no_urs' => $noUrs ?? '-',
            //             'rencana_pengiriman' => $rencanaPengiriman ?? '-',
            //         ];
            //     })->toArray();

            //     $set('details', $details);
            //     $set('dari', $dari ?? '-');
            //     $set('kepada', $kepada ?? '-');
            // });
            ->afterStateUpdated(function ($state, callable $set) {

                if (!$state) return;

                $penyerahan = PenyerahanElectrical::with([
                    'pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk.spesifikasiProduct.details.product',
                    'pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.identifikasiProduks',
                ])->find($state);

                if (!$penyerahan) return;

                $spk = $penyerahan
                    ->pengecekanSS
                    ->kelengkapanMaterial
                    ->standarisasiDrawing
                    ->serahTerimaWarehouse
                    ->peminjamanAlat
                    ->spkVendor
                    ->permintaanBahanProduksi
                    ->jadwalProduksi
                    ->spk;

                if (!$spk) return;

                $noUrs  = $spk?->spesifikasiProduct?->urs?->no_urs ?? '-';
                $rencanaPengiriman = optional($spk?->tanggal)->format('d F Y') ?? '-';
                $dari   = $spk?->dari ?? '-';
                $kepada = $spk?->kepada ?? '-';

                $jadwalList = JadwalProduksi::where('spk_marketing_id', $spk->id)
                    ->with(['identifikasiProduks'])
                    ->get();

                $details = [];

                foreach ($jadwalList as $jadwal) {
                    foreach ($jadwal->identifikasiProduks as $alat) {
                        $details[] = [
                            'nama_produk'        => $alat->nama_alat ?? '-',
                            'jumlah'             => $alat->jumlah ?? 1,
                            'no_urs'             => $noUrs,
                            'rencana_pengiriman' => $rencanaPengiriman,

                            // field tambahan yang sudah kamu bilang ada:
                            'nomor_seri'         => $alat->no_seri ?? '-',
                            'tipe'               => $alat->tipe ?? '-',
                            'custom_standar'     => $alat->custom_standar ?? '-',

                            // opsional kalau mau simpan info jadwal
                            'jadwal_produksi_id' => $jadwal->id,
                            'tanggal_jadwal'     => $jadwal->tanggal,
                            'no_surat'           => $jadwal->no_surat,
                        ];
                    }
                }

                // 5️⃣ Set ke Filament
                $set('details', $details);
                $set('dari', $dari);
                $set('kepada', $kepada);
            });
    }
}
