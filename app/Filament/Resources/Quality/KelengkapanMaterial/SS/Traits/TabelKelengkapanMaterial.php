<?php

namespace App\Filament\Resources\Quality\KelengkapanMaterial\SS\Traits;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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
                    ->default($defaultParts)
                    ->columns([
                        'default' => 1,
                        'md' => 3,
                        'lg' => 3,
                    ])
                    ->addable(true)
                    ->reorderable(false)
                    ->deletable(false)
                    ->addActionLabel('Tambah Detail Kelengkapan Material'),
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
