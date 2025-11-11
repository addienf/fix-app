<?php

namespace App\Filament\Resources\Engineering\SPK\Traits;

use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Wallo\FilamentSelectify\Components\ButtonGroup;

trait Pemeriksaan
{
    use SimpleFormResource;
    public static function getPemeriksaanSection()
    {

        return Section::make('Pemeriksaan dan Persetujuan')
            ->collapsible()
            ->hiddenOn('create')
            ->relationship('pemeriksaanPersetujuan')
            ->schema([
                ButtonGroup::make('status_pekerjaan')
                    ->label('Pekerjaan Telah Selesai ? (Ya/Tidak)')
                    ->required()
                    ->gridDirection('row')
                    ->options([
                        'ya' => 'Ya',
                        'tidak' => 'Tidak'
                    ]),

                self::textareaInput('catatan_tambahan', 'Catatan Tambahan')
            ]);
    }
}
