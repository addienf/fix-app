<?php

namespace App\Filament\Resources\Sales\SpesifikasiProducts\Traits;

use App\Models\General\Product;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Wallo\FilamentSelectify\Components\ButtonGroup;

trait PenjelasanTambahan
{
    protected static function penjelasanTambahanSection(): Section
    {
        return Section::make('Penjelasan Tambahan')
            ->collapsible()
            ->schema([
                Textarea::make('detail_specification')
                    ->label('Detail Spesifikasi')
                    ->required()
                    ->columnSpanFull(),

                Grid::make(2)
                    ->schema([
                        DatePicker::make('estimasi_pengiriman')
                            ->label('Estimasi Pengiriman')
                            ->required()
                            ->displayFormat('M d Y'),

                        ButtonGroup::make('status_penerimaan_order')
                            ->label('Penerimaan Order')
                            ->gridDirection('row')
                            ->options([
                                'yes' => 'Ya',
                                'no' => 'Tidak'
                            ])
                            ->reactive(),
                    ]),

                Textarea::make('alasan')
                    ->label('Alasan Pilihan Tidak')
                    ->required(fn(callable $get) => $get('status_penerimaan_order') === 'no')
                    ->visible(fn(callable $get) => $get('status_penerimaan_order') === 'no')
                    ->columnSpanFull(),
            ]);
    }
}
