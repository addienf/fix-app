<?php

namespace App\Filament\Resources\Quality\KelengkapanMaterial\SS\Traits;

use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Wallo\FilamentSelectify\Components\ButtonGroup;

trait TabelKelengkapanMaterial
{
    protected static function getTabelKelengkapanMaterialSection(): Section
    {
        $defaultParts = collect(config('kelengkapanSS.parts'))
            ->map(fn($part) => ['part' => $part])
            ->toArray();

        return Section::make('Tabel Kelengkapan Material')
            ->relationship('detail')
            ->collapsible()
            ->schema([

                TableRepeater::make('details')
                    ->label('')
                    ->schema([

                        // TextInput::make('part')
                        //     ->label('Part')
                        //     ->readonly(fn($get) => filled($get('part')))
                        //     ->extraAttributes(
                        //         fn($get) => filled($get('part'))
                        //             ? ['style' => 'pointer-events: none; background-color: #f3f4f6;']
                        //             : []
                        //     ),

                        // self::buttonGroup('result', 'Result'),

                        // Select::make('select')
                        //     ->label('Keterangan')
                        //     ->options([
                        //         'ok' => 'OK',
                        //         'h' => 'Hold',
                        //         'r' => 'Repaired',
                        //     ])
                        //     ->required(),

                    ])
                    ->default($defaultParts)
                    ->columns(3)
                    ->addable(true)
                    ->reorderable(false)
                    ->deletable(false)
                    ->addActionLabel('Tambah Detail Kelengkapan Material'),
            ]);
    }

    // protected static function getTabelKelengkapanMaterialSection2(): Section
    // {
    //     $defaultParts = collect(config('kelengkapanSS.parts'))
    //         ->map(fn($part) => ['part' => $part])
    //         ->toArray();

    //     return Section::make('Tabel Kelengkapan Material')
    //         ->relationship('detail')
    //         ->collapsible()
    //         ->schema([

    //             Repeater::make('details')
    //                 ->label('Daftar Produk')
    //                 ->schema([
    //                     Grid::make(2)
    //                         ->schema([
    //                             TextInput::make('nama_alat')->label('Nama Produk')->disabled(),
    //                             TextInput::make('no_seri')->label('Nomor Seri')->disabled(),
    //                         ]),

    //                     TableRepeater::make('details')
    //                         ->label('Kelengkapan Material')
    //                         ->schema([
    //                             TextInput::make('part')
    //                                 ->label('Part')
    //                                 ->readonly(fn($get) => filled($get('part')))
    //                                 ->extraAttributes(
    //                                     fn($get) => filled($get('part'))
    //                                         ? ['style' => 'pointer-events: none; background-color: #f3f4f6;']
    //                                         : []
    //                                 ),

    //                             self::buttonGroup('result', 'Result'),

    //                             Select::make('select')
    //                                 ->label('Keterangan')
    //                                 ->options([
    //                                     'ok' => 'OK',
    //                                     'h' => 'Hold',
    //                                     'r' => 'Repaired',
    //                                 ])
    //                                 ->required(),
    //                         ])
    //                         ->default($defaultParts)
    //                         ->columns(2)
    //                         ->default([])
    //                         ->addable(false)
    //                         ->reorderable(false)
    //                         ->deletable(false),
    //                 ])
    //                 ->columns(1)
    //                 ->addable(false)
    //                 ->reorderable(false)
    //                 ->deletable(false)

    //         ]);
    // }

    protected static function getTabelKelengkapanMaterialSection2(): Section
    {
        $defaultParts = collect(config('kelengkapanSS.parts'))
            ->map(fn($part) => ['part' => $part])
            ->toArray();

        return Section::make('Tabel Kelengkapan Material')
            ->collapsible()
            ->schema([
                // 🔹 Repeater utama = relasi hasMany "details"
                Repeater::make('details')
                    ->relationship('details') // penting! ini relasi ke model detail
                    ->label('Daftar Produk')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('nama_alat')
                                    ->label('Nama Produk')
                                    ->required(),
                                TextInput::make('nomor_seri')
                                    ->label('Nomor Seri')
                                    ->required(),
                            ]),

                        // 🔹 Field JSON dalam tiap record "detail"
                        TableRepeater::make('details')
                            ->label('Kelengkapan Material')
                            ->schema([
                                TextInput::make('part')
                                    ->label('Part')
                                    ->readonly(fn($get) => filled($get('part')))
                                    ->extraAttributes(
                                        fn($get) => filled($get('part'))
                                            ? ['style' => 'pointer-events: none; background-color: #f3f4f6;']
                                            : []
                                    ),

                                self::buttonGroup('result', 'Result'),

                                Select::make('select')
                                    ->label('Keterangan')
                                    ->options([
                                        'ok' => 'OK',
                                        'h' => 'Hold',
                                        'r' => 'Repaired',
                                    ])
                                    ->required(),
                            ])
                            ->columns(2)
                            ->default($defaultParts)
                            ->addable(false)
                            ->reorderable(false)
                            ->deletable(false),
                    ])
                    ->columns(1)
                    ->addable(false)
                    ->reorderable(false)
                    ->deletable(false),
            ]);
    }



    private static function buttonGroup(string $fieldName, string $label): ButtonGroup
    {
        return
            ButtonGroup::make($fieldName)
            ->label($label)
            ->required()
            ->options([
                1 => 'Ya',
                0 => 'Tidak',
            ])
            ->onColor('primary')
            ->offColor('gray')
            ->gridDirection('row')
            ->default('individual');
    }
}
