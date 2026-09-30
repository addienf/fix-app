<?php

namespace App\Filament\Resources\Production\SPK\Traits;

use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Section;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;

trait DetailProduk
{
    use SimpleFormResource;
    protected static function getDetailProdukSection(): Section
    {
        return
            Section::make('Detail Produk Yang Dipesan')
            ->collapsible()
            ->schema([
                TableRepeater::make('details')
                    ->relationship('details')
                    ->label('')
                    ->schema([
                        self::textInput('nama_produk', 'Nama Produk'),
                        self::textInput('nomor_seri', 'Nomor Seri'),
                        self::textInput('jumlah', 'Jumlah Pesanan'),
                        self::textInput('no_urs', 'No URS'),
                        self::textInput('rencana_pengiriman', 'Rencana Pengiriman'),
                    ])
                    ->columns([
                        'default' => 1,
                        'md' => 3,
                        'lg' => 3,
                    ])
                    ->deletable(true)
                    ->reorderable(false)
                    ->addable(true)
                    ->addActionLabel('Tambah Detail Produk'),
            ]);
    }
}
