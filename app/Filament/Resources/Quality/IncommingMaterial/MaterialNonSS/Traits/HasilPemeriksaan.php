<?php

namespace App\Filament\Resources\Quality\IncommingMaterial\MaterialNonSS\Traits;

use App\Models\Purchasing\Permintaan\PermintaanPembelian;
use App\Models\Quality\IncommingMaterial\MaterialNonSS\IncommingMaterialNonSS;
use App\Models\Warehouse\PermintaanBahanWBB\PermintaanBahan;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Support\Facades\Cache;
use Wallo\FilamentSelectify\Components\ButtonGroup;

trait HasilPemeriksaan
{
    use SimpleFormResource, HasAutoNumber;
    protected static function hasilPemeriksaanSection(): Section
    {
        $defaultParts = collect(config('incommingMaterialNonSS.parts'))
            ->map(fn($part) => ['part' => $part])
            ->toArray();

        return Section::make('Hasil Pemeriksaan')
            ->collapsible()
            ->relationship('detail')
            ->schema([
                TableRepeater::make('details')
                    ->label('')
                    ->schema([
                        self::textInput('part', 'Description')
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),
                        ButtonGroup::make('result')
                            ->options([
                                '1' => 'Pass',
                                '0' => 'Fail',
                            ])
                            ->onColor('primary')
                            ->offColor('gray')
                            ->gridDirection('row')
                            ->default('individual'),

                        self::textInput('remark', 'Remark')

                    ])
                    ->deletable(false)
                    ->reorderable(false)
                    ->addable(false)
                    ->default($defaultParts)
                    ->columns(3),

                TableRepeater::make('details_tambahan')
                    ->label('')
                    ->schema([

                        self::textInput('part', 'Description'),

                        ButtonGroup::make('result')
                            ->options([
                                '1' => 'Pass',
                                '0' => 'Fail',
                            ])
                            ->onColor('primary')
                            ->offColor('gray')
                            ->gridDirection('row')
                            ->default('individual'),

                        self::textInput('remark', 'Remark')

                    ])
                    ->columnSpanFull()
                    ->default([])
                    ->reorderable(false)
                    ->addActionLabel('Tambah Checklist')
                    ->columns(3)

            ]);
    }
}
