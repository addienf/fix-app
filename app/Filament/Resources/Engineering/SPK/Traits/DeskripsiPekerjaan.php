<?php

namespace App\Filament\Resources\Engineering\SPK\Traits;

use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;

trait DeskripsiPekerjaan
{
    use SimpleFormResource;
    public static function getDeskripsiPekerjaanSection()
    {

        return Section::make('Deskripsi Pekerjaan')
            ->collapsible()
            ->schema([
                Select::make('deskripsi_pekerjaan')
                    ->multiple()
                    ->options([
                        'service' => 'Service',
                        'maintenance' => 'Maintenance',
                        'lainya' => 'Lainnya'
                    ]),

                self::dateInput('jadwal_pelaksana', 'Jadwal Pelaksana'),

                self::dateInput('waktu_selesai', 'Waktu Selesai'),

            ])
            ->columns(3);
    }
}
