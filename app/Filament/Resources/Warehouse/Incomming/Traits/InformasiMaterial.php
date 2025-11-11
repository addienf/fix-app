<?php

namespace App\Filament\Resources\Warehouse\Incomming\Traits;

use App\Models\Purchasing\Permintaan\PermintaanPembelian;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Cache;

trait InformasiMaterial
{
    use SimpleFormResource, HasAutoNumber;
    protected static function informasiMaterialSection(): Section
    {
        return Section::make('Informasi Material')
            ->collapsible()
            ->schema([

                Repeater::make('details')
                    ->relationship('details')
                    ->schema([

                        Grid::make(6)
                            ->schema([

                                self::textInput('nama_material', 'Nama Material')
                                    ->extraAttributes([
                                        'readonly' => true,
                                        'style' => 'pointer-events: none;'
                                    ]),

                                self::textInput('batch_no', 'Batch No')
                                    ->extraAttributes([
                                        'readonly' => true,
                                        'style' => 'pointer-events: none;'
                                    ]),

                                self::textInput('jumlah', 'Jumlah Diterima')
                                    ->numeric(),

                                self::textInput('satuan', 'Satuan'),

                                self::textInput('kondisi_material', 'Kondisi Material'),

                                self::selectStatusLabel(),
                            ]),

                    ])
                    ->deletable(false)
                    ->reorderable(false)
                    ->addable(false),

            ]);
    }

    protected static function selectStatusLabel(): Select
    {
        return
            Select::make('status_qc')
            ->label('Status Label QC')
            ->required()
            ->reactive()
            ->placeholder('Pilih Status Label QC')
            ->options([
                1 => 'Ada',
                0 => 'Tidak Ada',
            ]);
    }
}
