<?php

namespace App\Filament\Resources\Warehouse\Peminjaman\Traits;

use App\Models\Production\PermintaanBahanProduksi\PermintaanAlatDanBahan;
use App\Models\Production\SPK\SPKVendor;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;

trait DataPeminjamAlat
{
    use SimpleFormResource, HasAutoNumber;
    protected static function dataPeminjamanSection(): Section
    {
        return
            Section::make('Data Peminjaman Alat')
            ->schema([
                Grid::make(2)
                    ->schema([
                        self::select()
                            ->columnSpanFull(),
                        self::dateInput('tanggal_pinjam', 'Tanggal Pinjam')
                            ->required(),
                        self::dateInput('tanggal_kembali', 'Tanggal Kembali')
                            ->required(),
                        TableRepeater::make('details')
                            ->relationship('details')
                            ->label('Barang')
                            ->schema([
                                self::textInput('nama_sparepart', 'Nama Sparepart'),
                                self::textInput('model', 'Model'),
                                self::textInput('jumlah', 'Jumlah')->numeric(),
                            ])
                            ->columns(3)
                            ->defaultItems(1)
                            ->addActionLabel('Tambah Barang')
                            ->columnSpanFull(),
                    ])
            ]);
    }

    private static function select(): Select
    {
        return
            Select::make('spk_vendor_id')
            ->label('Nomor SPK Vendor')
            ->placeholder('Pilih Nomor SPK Vendor')
            ->searchable()
            ->native(false)
            ->lazy()
            ->preload()
            ->required()
            ->options(function () {

                return SPKVendor::with([
                    'permintaanBahanProduksi.jadwalProduksi.spk:id,no_spk',
                    'permintaanBahanProduksi.jadwalProduksi.identifikasiProduks:id,jadwal_produksi_id,no_seri'
                ])
                    ->whereDoesntHave('peminjamanAlat')
                    ->latest()
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(function ($spk_vendor) {

                        $jadwal = $spk_vendor->permintaanBahanProduksi->jadwalProduksi;

                        $spkNo = $jadwal->spk->no_spk ?? '-';

                        $noSeri = $jadwal->identifikasiProduks
                            ->pluck('no_seri')
                            ->filter()
                            ->implode(', ') ?: '-';

                        return [
                            $spk_vendor->id => "{$spkNo} - {$noSeri}",
                        ];
                    });
            });
        // ->getSearchResultsUsing(function (string $search) {

        //     if ($search === '') {
        //         return [];
        //     }

        //     return PermintaanAlatDanBahan::with([
        //         'jadwalProduksi.spk',
        //         'jadwalProduksi.identifikasiProduks'
        //     ])
        //         ->whereDoesntHave('spkVendor')
        //         ->where(function ($query) use ($search) {
        //             $query->whereHas(
        //                 'jadwalProduksi',
        //                 fn($q) =>
        //                 $q->where('no_surat', 'LIKE', "%{$search}%")
        //             )
        //                 ->orWhereHas(
        //                     'jadwalProduksi.spk',
        //                     fn($q) =>
        //                     $q->where('no_spk', 'LIKE', "%{$search}%")
        //                 )
        //                 ->orWhereHas(
        //                     'jadwalProduksi.identifikasiProduks',
        //                     fn($q) =>
        //                     $q->where('no_seri', 'LIKE', "%{$search}%")
        //                 );
        //         })
        //         ->latest()
        //         ->limit(20)
        //         ->get()
        //         ->mapWithKeys(function ($permintaan) {

        //             $jadwal = $permintaan->jadwalProduksi;
        //             $spkNo = $jadwal->spk->no_spk ?? '-';
        //             $noSurat = $jadwal->no_surat ?? '-';
        //             $noSeri = $jadwal->identifikasiProduks
        //                 ->pluck('no_seri')
        //                 ->filter()
        //                 ->implode(', ') ?: '-';

        //             return [
        //                 $permintaan->id => "{$noSurat} - {$spkNo} - {$noSeri}",
        //             ];
        //         })
        //         ->toArray();
        // })
        // ->getOptionLabelUsing(function ($value) {

        //     $permintaan = PermintaanAlatDanBahan::with([
        //         'jadwalProduksi.spk',
        //         'jadwalProduksi.identifikasiProduks'
        //     ])
        //         ->find($value);

        //     if (!$permintaan) return '-';

        //     $jadwal = $permintaan->jadwalProduksi;
        //     $spkNo = $jadwal->spk->no_spk ?? '-';
        //     $noSurat = $jadwal->no_surat ?? '-';
        //     $noSeri = $jadwal->identifikasiProduks
        //         ->pluck('no_seri')
        //         ->filter()
        //         ->implode(', ') ?: '-';

        //     return "{$noSurat} - {$spkNo} - {$noSeri}";
        // });
        // ->afterStateUpdated(function ($state, callable $set) {
        //     if (!$state) return;
        //     $permintaan = PermintaanAlatDanBahan::with([
        //         'jadwalProduksi:id,spk_marketing_id',
        //         'jadwalProduksi.spk:id,spesifikasi_product_id',
        //         'jadwalProduksi.spk.spesifikasiProduct:id,urs_id',
        //         'jadwalProduksi.spk.spesifikasiProduct.urs:id,customer_id',
        //         'jadwalProduksi.spk.spesifikasiProduct.urs.customer:id,company_name',
        //         'jadwalProduksi.sumbers:id,jadwal_produksi_id,bahan_baku,spesifikasi,jumlah,keperluan'
        //     ])->find($state);

        //     if (!$permintaan) return;

        //     $company = optional(
        //         $permintaan->jadwalProduksi?->spk?->spesifikasiProduct?->urs?->customer
        //     )->company_name ?? '-';

        //     $details = $permintaan->details->map(fn($d) => [
        //         'bahan_baku' => $d->bahan_baku,
        //         'spesifikasi' => $d->spesifikasi,
        //         'jumlah' => $d->jumlah,
        //         'keperluan_barang' => $d->keperluan_barang,
        //     ])->toArray();

        //     $set('nama_perusahaan', $company);
        //     $set('details', $details);
        // });
    }
}
