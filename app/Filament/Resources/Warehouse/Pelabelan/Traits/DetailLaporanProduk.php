<?php

namespace App\Filament\Resources\Warehouse\Pelabelan\Traits;

use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Illuminate\Support\Str;

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
                        // Grid untuk 6 kolom
                        self::textInput('nama_produk', 'Nama Produk')
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),

                        self::textInput('tipe', 'Tipe/Model')
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),

                        self::textInput('serial_number', 'S/N')
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),

                        self::selectJenis(), // Asumsi ini dropdown

                        self::textInput('jumlah', 'Jumlah')
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),

                        self::textInput('keterangan', 'Keterangan'),
                    ])
                    ->columns(6)
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
