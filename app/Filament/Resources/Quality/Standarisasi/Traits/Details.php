<?php

namespace App\Filament\Resources\Quality\Standarisasi\Traits;

use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Get;
use Illuminate\Support\Facades\Cache;
use Wallo\FilamentSelectify\Components\ButtonGroup;

trait Details
{
    use SimpleFormResource, HasAutoNumber;
    protected static function detailsSection()
    {
        return Section::make('Detail')
            ->relationship('detail')
            ->collapsible()
            ->schema([

                self::uploadField(
                    'lampiran',
                    'Lampiran',
                    'Quality/StandarisasiDrawing/Files',
                    '*Hanya file gambar (PNG, JPG, JPEG) yang diperbolehkan. Maksimal ukuran 10 MB.',
                    types: ['image/png', 'image/jpeg'],
                    maxSize: 10240,
                ),

                Textarea::make('catatan')
                    ->label('Catatan atau Koreksi yang Dibutuhkan')
                    ->required(),

            ]);
    }
}
