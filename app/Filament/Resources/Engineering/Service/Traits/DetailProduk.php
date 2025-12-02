<?php

namespace App\Filament\Resources\Engineering\Service\Traits;

use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Wallo\FilamentSelectify\Components\ButtonGroup;

trait DetailProduk
{
    use SimpleFormResource, HasAutoNumber;
    public static function getDetailProdukSection()
    {
        return Section::make('Detail Produk')
            ->collapsible()
            ->schema([
                Repeater::make('details')
                    ->relationship('details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('remark')
                                    ->label('Remark')
                                    ->required(),

                                ButtonGroup::make('service_status')
                                    ->required()
                                    ->label('Service Status')
                                    ->gridDirection('row')
                                    ->options([
                                        1 => 'Yes',
                                        0 => 'No',
                                    ]),

                                Textarea::make('taken_item')
                                    ->label('Taken Item')
                                    ->columnSpanFull()
                                    ->required(),

                                FileUpload::make('upload_file')
                                    ->label('Lampiran')
                                    ->directory('Engineering/ServiceReport/Files')
                                    ->acceptedFileTypes(['image/png', 'image/jpeg'])
                                    ->helperText('*Hanya file gambar (PNG, JPG, JPEG) yang diperbolehkan. Maksimal ukuran 10 MB.')
                                    ->multiple()
                                    ->image()
                                    ->downloadable()
                                    ->reorderable()
                                    ->maxSize(10240)
                                    ->columnSpanFull()
                                    ->required(),
                            ]),
                    ])
                    ->columnSpanFull()
                    ->columns(2)
                    ->addable(false)
                    ->reorderable(false)
                    ->deletable(false),
            ]);
    }
}
