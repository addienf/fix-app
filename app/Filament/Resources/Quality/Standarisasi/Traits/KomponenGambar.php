<?php

namespace App\Filament\Resources\Quality\Standarisasi\Traits;

use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Get;
use Illuminate\Support\Facades\Cache;
use Wallo\FilamentSelectify\Components\ButtonGroup;

trait KomponenGambar
{
    use SimpleFormResource, HasAutoNumber;
    protected static function kopmponenGambarSection()
    {
        return Section::make('Komponen Gambar Yang Diperiksa')
            ->collapsible()
            ->relationship('pemeriksaan')
            ->schema([
                Select::make('pemeriksaan_komponen')
                    ->label('Komponen Gamber Yang Diperksa')
                    ->multiple()
                    ->options([
                        'keselarasan_spesifikasi' => 'Keselarasan Dengan Spesifikasi',
                        'ketepatan_skala' => 'Ketepatan Dimensi Dengan Skala',
                        'kesesuaian' => 'Kesesuaian Dengan Gambar Produk'
                    ])
            ]);
    }
}
