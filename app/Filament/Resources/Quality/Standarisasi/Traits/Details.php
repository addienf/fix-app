<?php

namespace App\Filament\Resources\Quality\Standarisasi\Traits;

use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;

trait Details
{
    use SimpleFormResource, HasAutoNumber;
    protected static function detailsSection()
    {
        return Section::make('Detail')
            ->relationship('detail')
            ->collapsible()
            ->schema([

                self::uploadField2(
                    'lampiran',
                    'Lampiran',
                    'Quality/StandarisasiDrawing/Files',
                    'Hanya file PDF yang diperbolehkan (maks 10 MB).',
                    ['application/pdf'],
                    10240,
                    true,
                    true,
                    true
                ),

                Textarea::make('catatan')
                    ->label('Catatan atau Koreksi yang Dibutuhkan')
                    ->required(),

            ]);
    }
}
