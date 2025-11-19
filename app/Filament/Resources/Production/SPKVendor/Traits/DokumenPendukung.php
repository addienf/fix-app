<?php

namespace App\Filament\Resources\Production\SPKVendor\Traits;

use App\Models\Sales\URS;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Cache;
use Wallo\FilamentSelectify\Components\ButtonGroup;

trait DokumenPendukung
{
    use SimpleFormResource;
    protected static function dokumenPendukungSection(): Section
    {
        return
            Section::make('Dokumen Pendukung')
            ->collapsible()
            ->schema([
                Grid::make(2)
                    ->schema([
                        self::uploadField(
                            'file_path',
                            'File Spesifikasi',
                            'Production/SPKVendor/Files',
                            'Hanya file PDF yang diperbolehkan. Maksimal ukuran 10 MB.',
                            types: ['application/pdf'],
                            maxSize: 10240,
                        ),

                        self::uploadField(
                            'lampiran',
                            'Lampiran',
                            'Production/SPKVendor/Files',
                            '*Hanya file gambar (PNG, JPG, JPEG) yang diperbolehkan. Maksimal ukuran 10 MB.',
                            types: ['image/png', 'image/jpeg'],
                            maxSize: 10240,
                        ),

                        // FileUpload::make('file_path')
                        //     ->label('File Pendukung')
                        //     ->directory('Production/SPKVendor/Files')
                        //     ->acceptedFileTypes(['application/pdf'])
                        //     ->maxSize(10240)
                        //     ->required()
                        //     ->columnSpanFull()
                        //     ->helperText('Hanya file PDF yang diperbolehkan. Maksimal ukuran 10 MB.'),

                        // FileUpload::make('lampiran')
                        //     ->label('Lampiran')
                        //     ->directory('Production/SPKVendor/Files')
                        //     ->acceptedFileTypes(['image/png', 'image/jpeg'])
                        //     ->helperText('*Hanya file gambar (PNG, JPG, JPEG) yang diperbolehkan. Maksimal ukuran 10 MB.')
                        //     ->multiple()
                        //     ->image()
                        //     ->downloadable()
                        //     ->reorderable()
                        //     ->maxSize(10240)
                        //     ->columnSpanFull()
                        //     ->required(),
                    ])
            ]);
    }
}
