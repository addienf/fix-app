<?php

namespace App\Filament\Resources\Engineering\Berita\Traits;

use App\Models\Engineering\Berita\BeritaAcara;
use App\Models\Engineering\Complain\Complain;
use App\Models\Engineering\SPK\SPKService;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;
use Wallo\FilamentSelectify\Components\ButtonGroup;

trait DetailPekerjaan
{
    use SimpleFormResource;
    public static function getDetailPekerjaanSection()
    {
        return Section::make('Detail Pekerjaan')
            ->relationship('detail')
            ->collapsible()
            ->schema([

                Select::make('jenis_pekerjaan')
                    ->required()
                    ->label('Jenis Pekerjaan')
                    ->placeholder('Pilih Jenis Pekerjaan')
                    ->options([
                        'service' => 'Service',
                        'maintenance' => 'Maintenance'
                    ]),

                TextInput::make('produk')
                    ->required()
                    ->label('Produk'),

                TextInput::make('serial_number')
                    ->required()
                    ->label('Serial Number'),

                Select::make('status_barang')
                    ->label('Status Barang')
                    ->options([
                        'yes' => 'Installed',
                        'wait' => 'Delivered',
                        'na' => 'N/A',
                    ])
                    ->required(),

                Textarea::make('desc_pekerjaan')
                    ->required()
                    ->label('Deskripsi Pekerjaan')
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }
}
