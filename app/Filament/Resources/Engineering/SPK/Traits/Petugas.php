<?php

namespace App\Filament\Resources\Engineering\SPK\Traits;

use App\Services\SignatureUploader;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;

trait Petugas
{
    use SimpleFormResource;
    public static function getPetugasSection()
    {

        return Section::make('Petugas')
            ->collapsible()
            ->schema([
                Repeater::make('petugas')
                    ->label('')
                    ->relationship('petugas')
                    ->schema([

                        self::textInput('nama_teknisi', 'Nama Teknisi'),

                        self::textInput('jabatan', 'Jabatan'),
                    ])
                    ->columns(2)
                    ->defaultItems(1)
                    ->collapsible()
                    ->columnSpanFull()
                    ->addActionLabel('Tambah Data Petugas'),
            ]);
    }
}
