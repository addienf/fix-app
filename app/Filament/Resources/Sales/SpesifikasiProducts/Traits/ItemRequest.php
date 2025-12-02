<?php

namespace App\Filament\Resources\Sales\SpesifikasiProducts\Traits;

use App\Models\General\Product;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Wallo\FilamentSelectify\Components\ButtonGroup;

trait ItemRequest
{
    protected static function itemRequestSection(): Section
    {
        return Section::make('Item Request')
            ->collapsible()
            ->schema([
                Repeater::make('details')
                    ->lazy()
                    ->label('')
                    ->relationship('details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('product_id')
                                    ->label('Pilih Produk')
                                    ->options(fn() => Product::orderBy('id')->pluck('name', 'id')->toArray())
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                self::textInput('quantity', 'Banyak Produk')
                                    ->numeric()
                                    ->required()
                            ]),

                        Grid::make()
                            ->relationship('file')
                            ->schema([
                                self::uploadField(
                                    'file_path',
                                    'File Spesifikasi',
                                    'Sales/Spesifikasi/Files',
                                    'Hanya file PDF yang diperbolehkan (maks 10 MB).',
                                    types: ['application/pdf'],
                                    maxSize: 10240,
                                ),
                            ]),

                        TableRepeater::make('specification')
                            ->label('Spesifikasi')
                            ->visible(function ($get) {
                                static $cache = [];
                                $id = $get('product_id');

                                if (!$id) return false;

                                if (!isset($cache[$id])) {
                                    $cache[$id] = Product::find($id)?->category_id;
                                }

                                return $cache[$id] === 1;
                            })
                            ->schema([
                                Select::make('name')
                                    ->reactive()
                                    ->required()
                                    ->label('')
                                    ->options(config('spec.spesifikasi'))
                                    ->columnSpan(1)
                                    ->placeholder('Pilih Jenis Spesifikasi'),

                                ButtonGroup::make('value_bool')
                                    ->label('')
                                    ->required()
                                    ->options(function (callable $get) {
                                        $name = $get('name');

                                        if ($name === 'Tipe Chamber') {
                                            return [
                                                'knockdown' => 'Knockdown',
                                                'regular' => 'Regular',
                                            ];
                                        } elseif ($name === 'Software') {
                                            return [
                                                'with' => 'With Software',
                                                'without' => 'Without Software',
                                            ];
                                        } else {
                                            return [
                                                '1' => 'Yes',
                                                '0' => 'No',
                                            ];
                                        }
                                    })
                                    ->reactive()
                                    ->onColor('primary')
                                    ->offColor('gray')
                                    ->gridDirection('row')
                                    ->visible(fn($get) => in_array(
                                        $get('name'),
                                        [
                                            'Water Feeding System',
                                            'Software',
                                            'Tipe Chamber',
                                        ]
                                    )),

                                TextInput::make('value_str')
                                    ->required()
                                    ->label('')
                                    ->placeholder('Masukkan Nilai')
                                    ->visible(fn($get) => !in_array(
                                        $get('name'),
                                        [
                                            'Water Feeding System',
                                            'Software',
                                            'Tipe Chamber',
                                        ]
                                    ))
                                    ->columnSpan(1),
                            ])
                            ->columns(2)
                            ->defaultItems(1)
                            ->columnSpanFull()
                            ->addActionLabel('Tambah Spesifikasi'),

                        Repeater::make('specification_mecmesin')
                            ->lazy()
                            ->label('Spesifikasi Mecmesin')
                            ->visible(function ($get) {
                                static $cache = [];
                                $id = $get('product_id');

                                if (!$id) return false;

                                if (!isset($cache[$id])) {
                                    $cache[$id] = Product::find($id)?->category_id;
                                }

                                return $cache[$id] === 2;
                            })
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Select::make('test_type')
                                            ->label('Test Type')
                                            ->options([
                                                'tensile' => 'Tensile Test',
                                                'compression' => 'Compression Test',
                                                'torque' => 'Torque Test',
                                            ])
                                            ->required()
                                            ->reactive(),

                                        ButtonGroup::make('jenis_tes')
                                            ->label('Jenis Tes')
                                            ->gridDirection('row')
                                            ->options([
                                                'digital' => 'Digital',
                                                'computerised' => 'Computerised'
                                            ]),
                                    ]),
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('capacity')->label('Capacity'),
                                        TextInput::make('sample')->label('Sample to test'),
                                    ])
                            ])
                            ->columns(2)
                            ->addActionLabel('Tambah Test'),
                    ])
                    ->defaultItems(1)
                    ->reorderable()
                    ->collapsible()
                    ->columnSpanFull()
                    ->addActionLabel('Tambah Data Detail Produk'),
            ]);
    }
}
