<?php

namespace App\Filament\Resources\Engineering\SPK\Traits;

use App\Services\SignatureUploader;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
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

                        self::signature('ttd', 'Tanda Tangan')
                            ->hidden(fn($get) => filled($get('ttd')))
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->defaultItems(1)
                    ->collapsible()
                    ->columnSpanFull()
                    ->addActionLabel('Tambah Data Petugas'),
            ]);
    }

    protected static function signature(string $fieldName, string $labelName): SignaturePad
    {
        return
            SignaturePad::make($fieldName)
            ->label($labelName)
            ->exportPenColor('#0118D8')
            ->helperText('*Harap Tandatangan di tengah area yang disediakan.')
            ->afterStateUpdated(function ($state, $set) use ($fieldName) {
                if (blank($state))
                    return;
                $path = SignatureUploader::handle($state, 'ttd_', 'Engineering/SPK/Signatures');
                if ($path) {
                    $set($fieldName, $path);
                }
            });
    }
}
