<?php

namespace App\Filament\Resources\Quality\PengecekanMaterial\Electrical\Traits;

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
        $defaultParts = collect(config('pengecekanElectrical'))
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
                    ->label('')
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
                                        1 => 'Yes',
                                        0 => 'No',
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
