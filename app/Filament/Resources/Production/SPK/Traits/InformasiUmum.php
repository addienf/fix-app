<?php

namespace App\Filament\Resources\Production\SPK\Traits;

use App\Models\Production\Penyerahan\PenyerahanElectrical\PenyerahanElectrical;
use App\Models\Sales\SpesifikasiProducts\SpesifikasiProduct;
use App\Models\Sales\SPKMarketings\SPKMarketing;
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
        // $lastValue = SPKMarketing::latest('no_spk')->value('no_spk');
        // $isEdit = $form->getOperation() === 'edit';

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
            ->reactive()
            ->options(
                fn() =>
                PenyerahanElectrical::with([
                    'pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk',
                    'pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.identifikasiProduks',
                ])
                    // ->whereDoesntHave('spkQC')
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
                    })
            )
            ->afterStateUpdated(function ($state, callable $set) {

                if (!$state) return;

                $penyerahan = PenyerahanElectrical::with([
                    'pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi' => function ($q) {
                        $q->with([
                            'spk:id,no_spk,spesifikasi_product_id,no_order,tanggal,dari,kepada',
                            'identifikasiProduks:id,jadwal_produksi_id,tipe,jumlah',
                            'timelines:id,jadwal_produksi_id,tanggal_selesai'
                        ]);
                    }
                ])->find($state);

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

                $noUrs = $spk?->spesifikasiProduct?->urs?->no_urs ?? '-';
                $rencanaPengiriman = optional($spk?->tanggal)->format('d F Y') ?? '-';
                $dari = $spk?->dari;
                $kepada = $spk?->kepada;

                $details = $spk?->spesifikasiProduct?->details->map(function ($detail) use ($noUrs, $rencanaPengiriman) {
                    return [
                        'nama_produk' => $detail->product?->name ?? '-',
                        'jumlah' => $detail?->quantity ?? '-',
                        'no_urs' => $noUrs ?? '-',
                        'rencana_pengiriman' => $rencanaPengiriman ?? '-',
                    ];
                })->toArray();

                $set('details', $details);
                $set('dari', $dari ?? '-');
                $set('kepada', $kepada ?? '-');
            });
    }
}
