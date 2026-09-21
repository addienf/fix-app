<?php

namespace App\Filament\Resources\Purchasing\Penerimaan\PenerimaanBarangResource\Traits;

use App\Models\Warehouse\PermintaanBahanWBB\PermintaanBahan;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Wallo\FilamentSelectify\Components\ButtonGroup;

trait NoSurat
{
    use SimpleFormResource;
    protected static function infoSection(): Section
    {
        return
            Section::make('Informasi Supplier')
            ->collapsible()
            ->schema([

                Grid::make(2)
                    ->schema([
                        self::dateInput('tanggal_penerimaan', 'Tanggal Penerimaan'),
                        self::textInput('nama_supplier', 'Nama Supplier'),
                        self::textInput('alamat_supplier', 'Alamat Supplier'),
                        self::textInput('nomor_po', 'Nomor PO'),
                    ])

            ]);
    }

    protected static function pemeriksaanBarang(): Section
    {
        return
            Section::make('C. Pemeriksaan Barang')
            ->collapsible()
            ->schema([

                Grid::make(2)
                    ->schema([
                        self::getSesuai(),
                        self::getKondisi(),
                    ]),

                self::textareaInput('catatan', 'Catatan Tambahan')
            ]);
    }

    private static function getSesuai()
    {
        return
            ButtonGroup::make('sesuai')
            ->label('Apakah barang sesuai ?')
            ->options([
                1 => 'Sesuai',
                0 => 'Tidak Sesuai',
            ])
            ->required()
            ->reactive()
            // ->columnSpanFull()
            ->onColor('primary')
            ->offColor('gray')
            ->gridDirection('row');
    }

    private static function getKondisi()
    {
        return
            ButtonGroup::make('kondisi')
            ->label('Apakah barang dalam kondisi baik ?')
            ->options([
                1 => 'Baik',
                0 => 'Tidak Baik',
            ])
            ->required()
            ->reactive()
            // ->columnSpanFull()
            ->onColor('primary')
            ->offColor('gray')
            ->gridDirection('row');
    }
}
