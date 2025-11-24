<?php

namespace App\Filament\Resources\Quality\Release\Traits;

use App\Models\Quality\Pengecekan\PengecekanPerforma;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;

trait Informasi
{
    use SimpleFormResource, HasAutoNumber;
    protected static function getInformasiSection()
    {
        return
            Section::make('Informasi Umum')
            ->schema([
                self::getSelect()
                    ->hiddenOn('edit')
                    ->placeholder('Pilih Nomor SPK'),

                self::autoNumberField2('no_order_release', 'Release Order No', [
                    'prefix' => 'QKS',
                    'section' => 'QA',
                    'type' => 'PR',
                    'table' => 'product_releases',
                ])
                    ->hiddenOn('edit'),

                self::textInput('product', 'The Product'),

                self::textInput('batch', 'Batch No'),

                self::textareaInput('remarks', 'Remark')->columnSpanFull(),
            ])
            ->columns(2)
            ->collapsible();
    }

    private static function getSelect()
    {
        return
            Select::make('pengecekan_performa_id')
            ->label('Nomor SPK / No Seri')
            ->placeholder('Pilih Serial Number')
            ->searchable()
            ->native(false)
            ->preload()
            ->reactive()
            ->required()
            // ->options(
            //     fn() =>
            //     PengecekanPerforma::with([
            //         'penyerahanProdukJadi.pengecekanElectrical.penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk',
            //         'penyerahanProdukJadi.pengecekanElectrical.penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.identifikasiProduks',
            //     ])
            //         ->latest()
            //         ->limit(20)
            //         ->get()
            //         ->mapWithKeys(function ($item) {

            //             $jadwal = $item->penyerahanProdukJadi->pengecekanElectrical->penyerahanElectrical
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
                PengecekanPerforma::with([
                    'penyerahanProdukJadi.pengecekanElectrical.penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk',
                    'penyerahanProdukJadi.pengecekanElectrical.penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.identifikasiProduks',
                ])
                    ->latest()
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(fn($item) => [
                        $item->id => ($jadwal = $item->penyerahanProdukJadi->pengecekanElectrical->penyerahanElectrical
                            ->pengecekanSS->kelengkapanMaterial->standarisasiDrawing
                            ->serahTerimaWarehouse->peminjamanAlat->spkVendor->permintaanBahanProduksi
                            ->jadwalProduksi)
                            ? (($jadwal->spk->no_spk ?? '-') . ' - ' . (
                                $jadwal->identifikasiProduks->pluck('no_seri')->filter()->implode(', ') ?: '-'
                            ))
                            : '-'
                    ])
            )
            ->getSearchResultsUsing(function (string $search) {
                return PengecekanPerforma::with([
                    'penyerahanProdukJadi.pengecekanElectrical.penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk',
                    'penyerahanProdukJadi.pengecekanElectrical.penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.identifikasiProduks',
                ])
                    ->whereHas('penyerahanProdukJadi.pengecekanElectrical.penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk', function ($q) use ($search) {
                        $q->where('no_spk', 'like', "%{$search}%");
                    })
                    ->orWhereHas('penyerahanProdukJadi.pengecekanElectrical.penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.identifikasiProduks', function ($q) use ($search) {
                        $q->where('no_seri', 'like', "%{$search}%");
                    })
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(fn($item) => [
                        $item->id => ($jadwal = $item->penyerahanProdukJadi->pengecekanElectrical->penyerahanElectrical
                            ->pengecekanSS->kelengkapanMaterial->standarisasiDrawing
                            ->serahTerimaWarehouse->peminjamanAlat->spkVendor->permintaanBahanProduksi
                            ->jadwalProduksi)
                            ? (($jadwal->spk->no_spk ?? '-') . ' - ' . (
                                $jadwal->identifikasiProduks->pluck('no_seri')->filter()->implode(', ') ?: '-'
                            ))
                            : '-'
                    ]);
            })
            ->afterStateUpdated(function ($state, callable $set) {
                if (!$state) return;

                $pengecekan = PengecekanPerforma::with([
                    'penyerahanProdukJadi.pengecekanElectrical.penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk'
                ])->find($state);

                $product_name = $pengecekan?->penyerahanProdukJadi?->pengecekanElectrical?->penyerahanElectrical?->nama_produk ?? '-';

                $set('product', $product_name);
            });
    }
}
