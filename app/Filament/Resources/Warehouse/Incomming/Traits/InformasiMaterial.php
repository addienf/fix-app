<?php

namespace App\Filament\Resources\Warehouse\Incomming\Traits;

use App\Models\Purchasing\Permintaan\PermintaanPembelian;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Support\Facades\Cache;

trait InformasiMaterial
{
    use SimpleFormResource, HasAutoNumber;
    protected static function informasiMaterialSection(): Section
    {
        return Section::make('Informasi Material')
            ->collapsible()
            ->schema([

                TableRepeater::make('details')
                    ->relationship('details')
                    ->schema([
                        self::textInput('nama_material', 'Nama Material'),

                        self::textInput('batch_no', 'Batch No'),

                        self::textInput('jumlah', 'Jumlah Diterima'),

                        self::textInput('satuan', 'Satuan'),

                        self::textInput('kondisi_material', 'Kondisi Material'),

                        self::selectStatusLabel(),
                    ])
                    ->deletable(true)
                    ->reorderable(false)
                    ->addActionLabel('Tambah Data'),

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
