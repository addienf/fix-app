<?php

namespace App\Filament\Resources\Production\Jadwal\Traits;

use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\CreateRecord;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;

trait KebutuhanBahan
{
    use SimpleFormResource;
    protected static function kebutuhanBahanSection(): Section
    {
        return
            Section::make('Kebutuhan bahan/alat')
            ->collapsible()
            ->schema([
                TableRepeater::make('sumbers')
                    ->relationship('sumbers')
                    ->label('')
                    ->default(
                        fn($livewire) =>
                        $livewire instanceof CreateRecord
                            ? collect(
                                json_decode(
                                    file_get_contents(
                                        storage_path('app/template/kebutuhan_dua.json')
                                    ),
                                    true
                                )
                            )
                            ->flatten(1)
                            ->map(fn($i) => [
                                'bahan_baku'  => $i[0] ?? null,
                                'spesifikasi' => $i[1] ?? null,
                                'jumlah'      => $i[2] ?? null,
                                'status'      => 'Belum Diterima',
                                'keperluan'   => '-',
                                'kategori'    => $i[3] ?? null,
                            ])
                            ->toArray()
                            : []
                    )
                    ->schema([

                        self::textInput('bahan_baku', 'Nama Bahan Baku'),

                        self::textareaInput('spesifikasi', 'Spesifikasi')->rows(2)->required(false),

                        self::textareaInput('jumlah', 'Quantity')->rows(2),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'Diterima' => 'Diterima',
                                'Belum Diterima' => 'Belum Diterima',
                            ]),

                        self::textInput('keperluan', 'Keperluan'),

                        Select::make(name: 'kategori')
                            ->label('Kategori')
                            ->options([
                                'Electrical' => 'Electrical',
                                'UPS' => 'UPS',
                                'Mechanical' => 'Mechanical',
                            ]),
                    ])
                    ->columns(7)
                    ->reorderable(false)
                    ->columnSpanFull()
                    ->addActionLabel('Tambah Kebutuhan'),
            ]);
    }
}
