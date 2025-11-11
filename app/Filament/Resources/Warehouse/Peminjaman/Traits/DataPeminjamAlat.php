<?php

namespace App\Filament\Resources\Warehouse\Peminjaman\Traits;

use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;

trait DataPeminjamAlat
{
    use SimpleFormResource, HasAutoNumber;
    protected static function dataPeminjamanSection(): Section
    {
        return Section::make('Data Peminjaman Alat')
            ->schema([
                Grid::make(2)
                    ->schema([
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
}
