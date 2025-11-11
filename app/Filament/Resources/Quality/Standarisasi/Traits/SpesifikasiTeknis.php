<?php

namespace App\Filament\Resources\Quality\Standarisasi\Traits;

use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Illuminate\Support\Facades\Cache;
use Wallo\FilamentSelectify\Components\ButtonGroup;

trait SpesifikasiTeknis
{
    use SimpleFormResource, HasAutoNumber;
    protected static function spesifikasiTeknisSection()
    {
        return Section::make('Spesifikasi Teknis')
            ->collapsible()
            ->schema([

                self::selectInputOptions('jenis_gambar', 'Jenis Gambar', 'standarisasi.jenis_gambar')
                    ->placeholder('Pilih Jenis Gambar')
                    ->multiple()
                    ->required(),

                self::selectInputOptions('format_gambar', 'Format Gambar', 'standarisasi.format_gambar')
                    ->placeholder('Pilih Format Gambar')
                    ->multiple()
                    ->required(),

            ])->columns(2);
    }

    protected static function selectInputOptions(string $fieldName, string $label, string $config): Select
    {
        return
            Select::make($fieldName)
            ->options(config($config))
            ->label($label)
            ->native(false)
            ->searchable()
            ->preload()
            ->required()
            ->reactive();
    }
}
