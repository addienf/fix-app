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
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;
use Wallo\FilamentSelectify\Components\ButtonGroup;

trait InformasiBio
{
    use SimpleFormResource;
    public static function getInformasiBioSection()
    {
        return Split::make([
            Section::make('Data Penyedia Jasa')
                ->collapsible()
                ->relationship('penyediaJasa')
                ->schema([
                    TextInput::make('nama')
                        ->required(),

                    TextInput::make('perusahaan')
                        ->required(),

                    TextInput::make('alamat')
                        ->required(),

                    TextInput::make('jabatan')
                        ->required(),
                ])
                ->columns(2),

            Section::make('Data Pelanggan')
                ->collapsible()
                ->relationship('pelanggan')
                ->schema([
                    TextInput::make('nama')
                        ->required(),

                    TextInput::make('perusahaan')
                        ->required(),

                    TextInput::make('alamat')
                        ->required(),

                    TextInput::make('jabatan')
                        ->required(),
                ])
                ->columns(2),
        ])
            ->columnSpanFull();
    }
}
