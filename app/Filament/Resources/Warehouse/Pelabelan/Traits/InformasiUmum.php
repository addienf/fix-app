<?php

namespace App\Filament\Resources\Warehouse\Pelabelan\Traits;

use App\Models\Quality\Pengecekan\PengecekanPerforma;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Illuminate\Support\Str;

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
            Select::make('pengecekan_performa_id')
            ->label('Nomor SPK / No Seri')
            ->placeholder('Pilih Serial Number')
            ->searchable()
            ->native(false)
            ->preload()
            ->reactive()
            ->required()
            ->options(
                fn() =>
                PengecekanPerforma::with([
                    'penyerahanProdukJadi.pengecekanElectrical.penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk',
                    'penyerahanProdukJadi.pengecekanElectrical.penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.identifikasiProduks',
                ])
                    ->latest()
                    ->limit(20)
                    ->get()
                    ->mapWithKeys(function ($item) {

                        $jadwal = $item->penyerahanProdukJadi->pengecekanElectrical->penyerahanElectrical
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
            ->afterStateUpdated(function ($state, callable $set) {
                if (!$state) return;

                $pengecekan = PengecekanPerforma::with([
                    'penyerahanProdukJadi.pengecekanElectrical.penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk'
                ])->find($state);

                $model_pengecekan =
                    $pengecekan?->penyerahanProdukJadi?->pengecekanElectrical
                    ?->penyerahanElectrical?->pengecekanSS?->kelengkapanMaterial
                    ?->standarisasiDrawing?->serahTerimaWarehouse?->peminjamanAlat
                    ?->spkVendor?->permintaanBahanProduksi?->jadwalProduksi ?? '-';

                $product_name = $pengecekan?->penyerahanProdukJadi?->pengecekanElectrical?->penyerahanElectrical?->nama_produk ?? '-';
                $product_tipe = $pengecekan?->penyerahanProdukJadi?->pengecekanElectrical?->penyerahanElectrical?->tipe ?? '-';
                $product_jumlah = $pengecekan?->penyerahanProdukJadi?->pengecekanElectrical?->penyerahanElectrical?->jumlah ?? '-';
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
