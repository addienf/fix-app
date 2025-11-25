<?php

namespace App\Filament\Resources\Engineering\SPK\Traits;

use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;

trait DeskripsiPekerjaan
{
    use SimpleFormResource, Petugas;
    public static function getDeskripsiPekerjaanSection()
    {

        return
            Section::make('A. Deskripsi Pekerjaan')
            ->collapsible()
            ->schema([
                Select::make('deskripsi_pekerjaan')
                    ->multiple()
                    ->reactive()
                    ->options([
                        'maintenance' => 'Maintenance',
                        'service' => 'Service',
                        'kalibrasi' => 'Kalibrasi',
                        'lainnya' => 'Lainnya'
                    ]),

                self::textInput('deskripsi_pekerjaan_lainnya', 'Jenis Permintaan Lainnya')
                    ->visible(fn($get) => in_array('lainnya', (array) $get('deskripsi_pekerjaan')))
                    ->required(fn($get) => in_array('lainnya', (array) $get('deskripsi_pekerjaan'))),
            ])
            ->columns(2);
    }

    public static function getPelaksanaanSection()
    {

        return
            Section::make('C. Pelaksanaan')
            ->collapsible()
            ->schema([
                self::dateInput('tanggal_pelaksanaan', 'Tanggal Pelaksana'),

                self::textInput('tempat_pelaksanaan', 'Tempat Pelaksanaan'),

                self::getPetugasSection(),
            ])
            ->columns(2);
    }
}
