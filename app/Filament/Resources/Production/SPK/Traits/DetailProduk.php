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

                        self::textInput('nama_produk', 'Nama Produk')
                            ->readOnly(fn($get) => filled($get('nama_produk')))
                            ->extraAttributes(
                                fn($get) =>
                                filled($get('nama_produk'))
                                    ? ['style' => 'pointer-events:none; background-color:#f3f4f6;']
                                    : []
                            ),

                        self::textInput('nomor_seri', 'Nomor Seri')
                            ->readOnly(fn($get) => filled($get('nomor_seri')))
                            ->extraAttributes(
                                fn($get) =>
                                filled($get('nomor_seri'))
                                    ? ['style' => 'pointer-events:none; background-color:#f3f4f6;']
                                    : []
                            ),

                        self::textInput('jumlah', 'Jumlah Pesanan')
                            ->readOnly(fn($get) => filled($get('jumlah')))
                            ->extraAttributes(
                                fn($get) =>
                                filled($get('jumlah'))
                                    ? ['style' => 'pointer-events:none; background-color:#f3f4f6;']
                                    : []
                            ),

                        self::textInput('no_urs', 'No URS')
                            ->readOnly(fn($get) => filled($get('no_urs')))
                            ->extraAttributes(
                                fn($get) =>
                                filled($get('no_urs'))
                                    ? ['style' => 'pointer-events:none; background-color:#f3f4f6;']
                                    : []
                            ),

                        self::textInput('rencana_pengiriman', 'Rencana Pengiriman')
                            ->readOnly(fn($get) => filled($get('rencana_pengiriman')))
                            ->extraAttributes(
                                fn($get) =>
                                filled($get('rencana_pengiriman'))
                                    ? ['style' => 'pointer-events:none; background-color:#f3f4f6;']
                                    : []
                            ),
                    ])
                    ->deletable(true)
                    ->reorderable(false)
                    ->addable(true)
                    ->addActionLabel('Tambah Detail Produk'),
            ]);
    }
}
