<?php

namespace App\Filament\Resources\Quality\Ketidaksesuaian\Traits;

use App\Models\Quality\Pengecekan\PengecekanPerforma;
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

        return
            Section::make('A. Informasi Umum')
            ->schema([
                self::getSelect()
                    ->hiddenOn('edit')
                    ->placeholder('Pilih Nomor SPK')
                    ->columnSpanFull(),
                self::dateInput('tanggal', 'Tanggal'),
                self::textInput('nama_perusahaan', 'Nama Perusahaan'),
                self::textInput('department', 'Department'),
                self::textInput('pelapor', 'Pelapor'),
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
            );
        // ->afterStateUpdated(function ($state, callable $set) {
        //     if (!$state) return;

        //     $pengecekan = PengecekanPerforma::with([
        //         'penyerahanProdukJadi.pengecekanElectrical.penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk'
        //     ])->find($state);

        //     $model_pengecekan =
        //         $pengecekan?->penyerahanProdukJadi?->pengecekanElectrical
        //         ?->penyerahanElectrical?->pengecekanSS?->kelengkapanMaterial
        //         ?->standarisasiDrawing?->serahTerimaWarehouse?->peminjamanAlat
        //         ?->spkVendor?->permintaanBahanProduksi?->jadwalProduksi ?? '-';

        //     $company_name = $model_pengecekan?->spk?->spesifikasiProduct?->urs?->customer?->company_name ?? '-';
        //     $department = $model_pengecekan?->spk?->spesifikasiProduct?->urs?->customer?->department ?? '-';
        //     $name = $model_pengecekan?->spk?->spesifikasiProduct?->urs?->customer?->name ?? '-';


        //     $product_name = $pengecekan?->penyerahanProdukJadi?->pengecekanElectrical?->penyerahanElectrical?->nama_produk ?? '-';
        //     $product_jumlah = $pengecekan?->penyerahanProdukJadi?->pengecekanElectrical?->penyerahanElectrical?->jumlah ?? '-';
        //     $no_seri = $model_pengecekan?->identifikasiProduks?->first()?->no_seri ?? '-';

        //     $set('details', [
        //         [
        //             'nama_produk'   => $product_name ?? '-',
        //             'serial_number' => $no_seri ?? '-',
        //             'jumlah'        => $product_jumlah ?? '-',
        //         ]
        //     ]);

        //     $set('nama_perusahaan', $company_name);
        //     $set('department', $department);
        //     $set('pelapor', $name);
        // });
    }
}
