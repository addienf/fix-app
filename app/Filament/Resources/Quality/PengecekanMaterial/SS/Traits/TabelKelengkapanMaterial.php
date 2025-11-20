<?php

namespace App\Filament\Resources\Quality\PengecekanMaterial\SS\Traits;

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
        $defaultParts = collect(config('pengecekanSS'))
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
                    ->label('')
                    ->default($defaultParts)
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('mainPart')
                                    ->label('Main Part')
                                    ->readonly(fn($get) => filled($get('mainPart')))
                                    ->extraAttributes(
                                        fn($get) => filled($get('mainPart'))
                                            ? ['style' => 'pointer-events: none; background-color: #f3f4f6;']
                                            : []
                                    ),

                                ButtonGroup::make('mainPart_result')
                                    ->label('Result')
                                    ->options([
                                        1 => 'Yes',
                                        0 => 'No',
                                    ])
                                    ->onColor('primary')
                                    ->offColor('gray')
                                    ->gridDirection('row'),

                                Select::make('mainPart_status')
                                    ->label("Attachment Defect and\nRepaired Status")
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
                                    ->readonly(fn($get) => filled($get('part')))
                                    ->extraAttributes(
                                        fn($get) => filled($get('part'))
                                            ? ['style' => 'pointer-events: none; background-color: #f3f4f6;']
                                            : []
                                    ),

                                ButtonGroup::make('result')
                                    ->label('Result')
                                    ->options([
                                        1 => 'Yes',
                                        0 => 'No',
                                    ])
                                    ->onColor('primary')
                                    ->offColor('gray')
                                    ->gridDirection('row'),

                                Select::make('status')
                                    ->label('Attachment Defect and Repaired Status')
                                    ->options([
                                        'ok' => 'OK',
                                        'h' => 'Hold',
                                        'r' => 'Repaired',
                                    ])
                                    ->required(),
                            ])
                            ->addActionLabel('Tambah Detail Part Pengecekan Material')
                            ->reorderable(false),
                    ])
                    ->addActionLabel('Tambah Detail Main Part Pengecekan Material')
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
