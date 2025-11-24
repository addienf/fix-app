<?php

namespace App\Filament\Resources\Warehouse\Pelabelan\Traits;

use App\Models\Quality\Release\ProductRelease;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
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

                self::getSelect()
                    ->hiddenOn('edit'),

                self::dateInput('tanggal', 'Tanggal'),

                self::textInput('penanggung_jawab', 'Penanggung Jawab')

            ])->columns($isEdit ? 2 : 3);
    }

    private static function getSelect()
    {
        return
            Select::make('release_id')
            ->label('Nomor SPK / No Seri')
            ->placeholder('Pilih Serial Number')
            ->searchable()
            ->native(false)
            ->preload()
            ->reactive()
            ->required()
            // ->options(
            //     fn() =>
            //     ProductRelease::with([
            //         'pengecekanPerforma.penyerahanProdukJadi.pengecekanElectrical.penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk',
            //         'pengecekanPerforma.penyerahanProdukJadi.pengecekanElectrical.penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.identifikasiProduks',
            //     ])
            //         ->where('status', 'Diketahui')
            //         ->whereDoesntHave('qcPassed')
            //         ->latest()
            //         ->limit(20)
            //         ->get()
            //         ->mapWithKeys(function ($item) {

            //             $jadwal = $item->pengecekanPerforma->penyerahanProdukJadi->pengecekanElectrical->penyerahanElectrical
            //                 ->pengecekanSS->kelengkapanMaterial->standarisasiDrawing
            //                 ->serahTerimaWarehouse->peminjamanAlat->spkVendor->permintaanBahanProduksi
            //                 ->jadwalProduksi;

            //             $spkNo = $jadwal->spk->no_spk ?? '-';

            //             $seri = $jadwal->identifikasiProduks
            //                 ->pluck('no_seri')
            //                 ->filter()
            //                 ->implode(', ') ?: '-';

            //             return [
            //                 $item->id => "{$spkNo} - {$seri}",
            //             ];
            //         })
            // )
            ->options(
                fn() =>
                ProductRelease::with([
                    'pengecekanPerforma.penyerahanProdukJadi.pengecekanElectrical.penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk',
                    'pengecekanPerforma.penyerahanProdukJadi.pengecekanElectrical.penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.identifikasiProduks',
                ])
                    ->where('status', 'Diketahui')
                    ->whereDoesntHave('qcPassed')
                    ->latest()
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(function ($item) {

                        $jadwal = $item->pengecekanPerforma->penyerahanProdukJadi->pengecekanElectrical->penyerahanElectrical
                            ->pengecekanSS->kelengkapanMaterial->standarisasiDrawing
                            ->serahTerimaWarehouse->peminjamanAlat->spkVendor->permintaanBahanProduksi
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
            ->getSearchResultsUsing(function (string $search) {

                return ProductRelease::with([
                    'pengecekanPerforma.penyerahanProdukJadi.pengecekanElectrical.penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk',
                    'pengecekanPerforma.penyerahanProdukJadi.pengecekanElectrical.penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.identifikasiProduks',
                ])
                    ->where('status', 'Diketahui')
                    ->whereDoesntHave('qcPassed')
                    ->where(function ($q) use ($search) {
                        $q->whereHas(
                            'pengecekanPerforma.penyerahanProdukJadi.pengecekanElectrical.penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk',
                            fn($spkQ) => $spkQ->where('no_spk', 'like', "%{$search}%")
                        );
                    })
                    ->orWhere(function ($q) use ($search) {
                        $q->whereHas(
                            'pengecekanPerforma.penyerahanProdukJadi.pengecekanElectrical.penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.identifikasiProduks',
                            fn($seriQ) => $seriQ->where('no_seri', 'like', "%{$search}%")
                        );
                    })
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(function ($item) {

                        $jadwal = $item->pengecekanPerforma->penyerahanProdukJadi->pengecekanElectrical->penyerahanElectrical
                            ->pengecekanSS->kelengkapanMaterial->standarisasiDrawing
                            ->serahTerimaWarehouse->peminjamanAlat->spkVendor->permintaanBahanProduksi
                            ->jadwalProduksi;

                        $spkNo = $jadwal->spk->no_spk ?? '-';

                        $seri = $jadwal->identifikasiProduks
                            ->pluck('no_seri')
                            ->filter()
                            ->implode(', ') ?: '-';

                        return [
                            $item->id => "{$spkNo} - {$seri}",
                        ];
                    });
            })
            ->afterStateUpdated(function ($state, callable $set) {
                if (!$state) return;

                $pengecekan = ProductRelease::with([
                    'pengecekanPerforma.penyerahanProdukJadi.pengecekanElectrical.penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk'
                ])->find($state);

                $model_pengecekan =
                    $pengecekan?->pengecekanPerforma?->penyerahanProdukJadi?->pengecekanElectrical
                    ?->penyerahanElectrical?->pengecekanSS?->kelengkapanMaterial
                    ?->standarisasiDrawing?->serahTerimaWarehouse?->peminjamanAlat
                    ?->spkVendor?->permintaanBahanProduksi?->jadwalProduksi ?? '-';

                $product_name = $pengecekan?->pengecekanPerforma?->penyerahanProdukJadi?->pengecekanElectrical?->penyerahanElectrical?->nama_produk ?? '-';
                $product_tipe = $pengecekan?->pengecekanPerforma?->penyerahanProdukJadi?->pengecekanElectrical?->penyerahanElectrical?->tipe ?? '-';
                $product_jumlah = $pengecekan?->pengecekanPerforma?->penyerahanProdukJadi?->pengecekanElectrical?->penyerahanElectrical?->jumlah ?? '-';
                $no_seri = $model_pengecekan?->identifikasiProduks?->first()?->no_seri ?? '-';

                $set('details', [
                    [
                        'nama_produk'   => $product_name ?? '-',
                        'tipe'          => $product_tipe ?? '-',
                        'serial_number' => $no_seri ?? '-',
                        'jumlah'        => $product_jumlah ?? '-',
                    ]
                ]);
            });
    }
}
