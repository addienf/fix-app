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
                    '*Hanya file gambar (PNG, JPG, JPEG) yang diperbolehkan. Maksimal ukuran 10 MB.',
                    ['image/png', 'image/jpeg'],
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
