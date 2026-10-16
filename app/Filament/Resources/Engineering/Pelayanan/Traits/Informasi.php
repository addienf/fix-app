<?php

namespace App\Filament\Resources\Engineering\Pelayanan\Traits;

use App\Models\Engineering\Complain\Complain;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;

trait Informasi
{
    use SimpleFormResource;
    public static function getInformasiSection()
    {

        return Section::make('Informasi Umum')
            ->collapsible()
            ->schema([
                Grid::make()
                    ->schema([
                        self::textInput('no_form', 'Form No'),
                        self::dateInput('tanggal', 'Tanggal'),
                        self::textInput('alamat', 'Alamat'),
                        self::textInput('perusahaan', 'Perusahaan'),
                    ]),
            ]);
    }

    protected static function getJenisPermintaan()
    {
        return
            Section::make('A. Jenis Permintaan')
            ->schema([
                Select::make('jenis_permintaan')
                    ->label('Jenis Permintaan')
                    ->multiple()
                    ->reactive()
                    ->options([
                        'pengiriman' => 'Pengiriman',
                        'perakitan' => 'Perakitan',
                        'kualifikasi' => 'Kualifikasi',
                        'service' => 'Service',
                        'maintenance' => 'Maintenance',
                        'kalibrasi' => 'Kalibrasi',
                        'lainnya' => 'Lainnya',
                    ]),

                self::textInput('jenis_permintaan_lainnya', 'Jenis Permintaan Lainnya')
                    ->visible(fn($get) => in_array('lainnya', (array) $get('jenis_permintaan')))
                    ->required(fn($get) => in_array('lainnya', (array) $get('jenis_permintaan'))),
            ]);
    }

    protected static function getPelaksanaan()
    {
        return
            Section::make('C. Pelaksanaan')
            ->schema([
                self::dateInput('tanggal_pelaksanaan', 'Tanggal Pelaksanaan'),

                self::textInput('tempat_pelaksanaan', 'Tempat Pelaksanaan'),

                self::textInput('no_kontak', 'PIC/No. Kontak/Dept')
            ])
            ->columns(3);
    }
}
