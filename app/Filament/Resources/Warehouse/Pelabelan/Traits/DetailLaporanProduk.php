<?php

namespace App\Filament\Resources\Warehouse\Pelabelan\Traits;

use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;

trait DetailLaporanProduk
{
    use SimpleFormResource, HasAutoNumber;
    protected static function getDetailLaporanProdukSection(): Section
    {
        return
            Section::make('Detail Laporan Produk')
            ->collapsible()
            ->schema([
                Repeater::make('details')
                    ->label('')
                    ->relationship('details')
                    ->schema([
                        self::textInput('nama_produk', 'Nama Produk'),

                        self::textInput('tipe', 'Tipe/Model'),

                        self::textInput('serial_number', 'S/N'),

                        self::selectJenis(),

                        self::textInput('jumlah', 'Jumlah'),

                        self::textInput('keterangan', 'Keterangan'),
                    ])
                    ->columns([
                        'default' => 1,
                        'md' => 3,
                        'lg' => 6,
                    ])
                    ->deletable(false)
                    ->reorderable(false)
                    ->addable(false)
            ]);
    }

    protected static function selectJenis(): Select
    {
        return
            Select::make('jenis_transaksi')
            ->label('Jenis Transaksi')
            ->required()
            ->placeholder('Pilih Jenis Transaksi')
            ->options([
                'masuk' => 'Masuk',
                'keluar' => 'Keluar',
            ]);
    }
}
