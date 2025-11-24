<?php

namespace App\Filament\Resources\Warehouse\Pelabelan\Traits;

use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Section;

trait SyaratDanKetentuan
{
    use SimpleFormResource, HasAutoNumber;
    protected static function getSyaratDanKetentuanSection(): Section
    {

        return
            Section::make('Syarat dan Ketentuan')
            ->collapsible()
            ->schema([

                self::textInput('total_masuk', 'Total Masuk'),

                self::textInput('total_keluar', 'Total Keluar'),

                self::textInput('sisa_stock', 'Sisa Stock')

            ])->columns(3);
    }
}
