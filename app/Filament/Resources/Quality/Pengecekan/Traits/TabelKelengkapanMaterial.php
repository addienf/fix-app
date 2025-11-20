<?php

namespace App\Filament\Resources\Quality\Pengecekan\Traits;

use App\Models\Production\Penyerahan\PenyerahanElectrical\PenyerahanElectrical;
use App\Models\Quality\KelengkapanMaterial\SS\KelengkapanMaterialSS;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Wallo\FilamentSelectify\Components\ButtonGroup;

trait TabelKelengkapanMaterial
{
    use SimpleFormResource;
    protected static function getTabelKelengkapanMaterialSection(): Section
    {
        $defaultParts = collect(config('pengecekanPerforma'))
            ->map(function ($group) {
                return [
                    'mainPart' => $group['mainPart'],
                    'parts' => collect($group['parts'])
                        ->map(fn($part) => ['part' => $part])
                        ->toArray(),
                ];
            })
            ->toArray();

        return
            Section::make('Tabel Kelengkapan Material')
            ->collapsible()
            ->relationship('detail')
            ->schema([

                Repeater::make('details')
                    ->default($defaultParts)
                    ->schema([

                        Grid::make(3)
                            ->schema([
                                TextInput::make('mainPart')
                                    ->label('Main Part')
                                    ->extraAttributes([
                                        'readonly' => true,
                                        'style' => 'pointer-events: none;'
                                    ]),

                                ButtonGroup::make('mainPart_result')
                                    ->label('Result')
                                    ->options([
                                        1 => 'Yes',
                                        0 => 'No',
                                    ])
                                    ->onColor('primary')
                                    ->offColor('gray')
                                    ->gridDirection('row')
                                    ->default('individual'),

                                Select::make('mainPart_status')
                                    ->label('Status')
                                    ->options([
                                        'ok' => 'OK',
                                        'h' => 'Hold',
                                        'r' => 'Repaired',
                                    ])
                                    ->required(),
                            ]),

                        TableRepeater::make('parts')
                            ->label('')
                            ->schema([

                                TextInput::make('part')
                                    ->label('Part')
                                    ->extraAttributes([
                                        'readonly' => true,
                                        'style' => 'pointer-events: none;'
                                    ]),

                                ButtonGroup::make('result')
                                    ->options([
                                        '1' => 'Yes',
                                        '0' => 'No',
                                    ])
                                    ->onColor('primary')
                                    ->offColor('gray')
                                    ->gridDirection('row')
                                    ->default('individual'),

                                Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'ok' => 'OK',
                                        'h' => 'Hold',
                                        'r' => 'Repaired',
                                    ])
                                    ->required(),

                            ])
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false),

                    ])
                    ->addable(false)
                    ->deletable(false)
                    ->reorderable(false)

            ]);
    }
}
