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
                            ->required(false),
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
                return SPKVendor::query()
                    ->with([
                        'permintaanBahanProduksi.jadwalProduksi.spk:id,no_spk',
                        'permintaanBahanProduksi.jadwalProduksi.identifikasiProduks:id,jadwal_produksi_id,no_seri'
                    ])
                    ->whereDoesntHave('peminjamanAlat')
                    ->latest()
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(function ($spkVendor) {

                        $jadwal = $spkVendor->permintaanBahanProduksi->jadwalProduksi;

                        $spkNo = $jadwal->spk->no_spk ?? '-';

                        $noSeri = $jadwal->identifikasiProduks
                            ->pluck('no_seri')
                            ->filter()
                            ->implode(', ') ?: '-';

                        return [
                            $spkVendor->id => "{$spkNo} - {$noSeri}",
                        ];
                    });
            })
            ->getSearchResultsUsing(function (string $search) {
                return SPKVendor::query()
                    ->with([
                        'permintaanBahanProduksi.jadwalProduksi.spk:id,no_spk',
                        'permintaanBahanProduksi.jadwalProduksi.identifikasiProduks:id,jadwal_produksi_id,no_seri'
                    ])
                    ->whereDoesntHave('peminjamanAlat')
                    ->where(function ($q) use ($search) {
                        // Search by SPK No
                        $q->whereHas('permintaanBahanProduksi.jadwalProduksi.spk', function ($spk) use ($search) {
                            $spk->where('no_spk', 'like', "%{$search}%");
                        });

                        $q->orWhereHas('permintaanBahanProduksi.jadwalProduksi.identifikasiProduks', function ($prod) use ($search) {
                            $prod->where('no_seri', 'like', "%{$search}%");
                        });
                    })
                    ->latest()
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(function ($spkVendor) {

                        $jadwal = $spkVendor->permintaanBahanProduksi->jadwalProduksi;

                        $spkNo = $jadwal->spk->no_spk ?? '-';

                        $noSeri = $jadwal->identifikasiProduks
                            ->pluck('no_seri')
                            ->filter()
                            ->implode(', ') ?: '-';

                        return [
                            $spkVendor->id => "{$spkNo} - {$noSeri}",
                        ];
                    });
            });
    }
}
