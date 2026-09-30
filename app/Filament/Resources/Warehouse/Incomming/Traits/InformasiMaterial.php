<?php

namespace App\Filament\Resources\Warehouse\Incomming\Traits;

use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;

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
                        static::textInput('nama_material', 'Nama Material'),

                        static::textInput('batch_no', 'Batch No'),

                        static::textInput('jumlah', 'Jumlah Diterima'),

                        static::textInput('satuan', 'Satuan'),

                        static::textInput('kondisi_material', 'Kondisi Material'),

                        static::selectStatusLabel(),
                    ])
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
            ->placeholder('Pilih Status Label QC')
            ->options([
                1 => 'Ada',
                0 => 'Tidak Ada',
            ]);
    }
}
