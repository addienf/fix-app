<?php

namespace App\Filament\Resources\Engineering\Maintenance\WalkinChamber\Traits;

use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

trait TabelChecklist
{
    use SimpleFormResource, HasAutoNumber;
    public static function getTabelChecklistSection()
    {
        $defaultParts = collect(config('walkinChamber'))
            ->map(function ($group) {
                return [
                    'mainPart' => $group['mainPart'],
                    'parts' => collect($group['parts'])->map(function ($part) {

                        if (is_array($part) && isset($part['info'])) {
                            return [
                                'part' => $part['info'],
                                'type' => 'info',
                                'show_value' => true,
                            ];
                        }

                        if (is_array($part)) {
                            return [
                                'part' => $part['text'],
                                'type' => 'check',
                                'show_value' => $part['show'] ?? true,
                            ];
                        }

                        return [
                            'part' => $part,
                            'type' => 'check',
                            'show_value' => true,
                        ];
                    })->toArray(),
                ];
            })
            ->toArray();

        return
            Section::make('Tabel Checklist')
            ->collapsible()
            ->relationship('detail')
            ->schema([

                Repeater::make('checklist')
                    ->default($defaultParts)
                    ->label('')
                    ->schema([

                        Grid::make(7)
                            ->schema([
                                TextInput::make('mainPart')
                                    ->label('Main Part')
                                    ->hidden(fn(callable $get) => blank($get('mainPart')))
                                    ->extraAttributes([
                                        'readonly' => true,
                                        'style' => 'pointer-events: none;'
                                    ])
                                    ->columnSpan(7),
                            ]),

                        Repeater::make('parts')
                            ->extraAttributes([
                                'class' => 'bg-gray-100 dark:bg-gray-800 rounded-lg p-4'
                            ])
                            ->schema([

                                Hidden::make('type'),
                                Hidden::make('show_value'),

                                Textarea::make('part')
                                    ->rows(1)
                                    ->columnSpan([
                                        'default' => 1,
                                        'md' => 2,
                                        'lg' => fn(callable $get) => $get('type') === 'info' ? 7 : 3,
                                    ])
                                    ->readOnly(fn(callable $get) => $get('type') === 'info')
                                    ->extraAttributes(
                                        fn(callable $get) =>
                                        $get('type') === 'info'
                                            ? [
                                                'style' => 'background:#f9fafb; border:none; resize:none;',
                                            ]
                                            : []
                                    )
                                    ->required(),

                                Grid::make(
                                    [
                                        'default' => 2,
                                        'md' => 2,
                                        'lg' => 2,
                                    ]
                                )
                                    ->schema([
                                        Textarea::make('before')
                                            ->rows(1)
                                            ->columnSpan([
                                                'default' => 1,
                                                'md' => 1,
                                                'lg' => 1,
                                            ]),
                                        // ->hidden(
                                        //     fn($get) =>
                                        //     $get('type') === 'info' || !$get('show_value')
                                        // ),


                                        Textarea::make('after')
                                            ->rows(1)
                                            ->columnSpan([
                                                'default' => 1,
                                                'md' => 1,
                                                'lg' => 1,
                                            ])
                                        // ->hidden(
                                        //     fn($get) =>
                                        //     $get('type') === 'info' || !$get('show_value')
                                        // ),
                                    ])->columnSpan([
                                        'default' => 1,
                                        'md' => 2,
                                        'lg' => 2,
                                    ])
                                    ->hidden(
                                        fn($get) =>
                                        $get('type') === 'info' || !$get('show_value')
                                    ),

                                Select::make('accepted')
                                    ->options([
                                        'yes' => 'Yes',
                                        'no' => 'No',
                                        'na' => 'NA',
                                    ])
                                    ->columnSpan([
                                        'default' => 1,
                                        'md' => 1,
                                        'lg' => fn($get) => $get('show_value') ? 1 : 2,
                                    ])
                                    ->required(fn($get) => $get('type') === 'check')
                                    ->hidden(fn($get) => $get('type') === 'info'),


                                TextInput::make('remark')
                                    ->columnSpan([
                                        'default' => 1,
                                        'md' => 1,
                                        'lg' => fn($get) => $get('show_value') ? 1 : 2,
                                    ])
                                    ->hidden(fn($get) => $get('type') === 'info'),
                            ])
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false)
                            ->columns([
                                'default' => 1,
                                'md' => 2,
                                'lg' => 7,
                            ]),

                        Repeater::make('extra')
                            ->default([])
                            ->schema([

                                // self::textInput('part', 'Part')
                                //     ->columnSpan(3)
                                //     ->required(false),

                                // self::textInput('before', 'Before')
                                //     ->columnSpan([
                                //         'default' => 1,
                                //         'md' => 1,
                                //         'lg' => 1,
                                //     ])
                                //     ->required(false),


                                // self::textInput('after', 'After')
                                //     ->columnSpan([
                                //         'default' => 1,
                                //         'md' => 1,
                                //         'lg' => 1,
                                //     ])
                                //     ->required(false),

                                // Select::make('accepted')
                                //     ->options([
                                //         'yes' => 'Yes',
                                //         'no' => 'No',
                                //         'na' => 'NA',
                                //     ])
                                //     ->columnSpan([
                                //         'default' => 1,
                                //         'md' => 1,
                                //         'lg' => 1,
                                //     ])
                                //     ->required(false),


                                // self::textInput('remark', 'Remark')
                                //     ->columnSpan([
                                //         'default' => 1,
                                //         'md' => 1,
                                //         'lg' => 1,
                                //     ])
                                //     ->required(false),

                                Textarea::make('part')
                                    ->rows(1)
                                    ->columnSpan([
                                        'default' => 1,
                                        'md' => 2,
                                        'lg' => 3,
                                    ]),

                                Grid::make(
                                    [
                                        'default' => 2,
                                        'md' => 2,
                                        'lg' => 2,
                                    ]
                                )
                                    ->schema([
                                        Textarea::make('before')
                                            ->rows(1)
                                            ->columnSpan([
                                                'default' => 1,
                                                'md' => 1,
                                                'lg' => 1,
                                            ]),

                                        Textarea::make('after')
                                            ->rows(1)
                                            ->columnSpan([
                                                'default' => 1,
                                                'md' => 1,
                                                'lg' => 1,
                                            ])
                                    ])->columnSpan([
                                        'default' => 1,
                                        'md' => 2,
                                        'lg' => 2,
                                    ]),

                                Select::make('accepted')
                                    ->options([
                                        'yes' => 'Yes',
                                        'no' => 'No',
                                        'na' => 'NA',
                                    ])
                                    ->columnSpan([
                                        'default' => 1,
                                        'md' => 1,
                                        'lg' => 1,
                                    ]),

                                TextInput::make('remark')
                                    ->columnSpan([
                                        'default' => 1,
                                        'md' => 1,
                                        'lg' => 1,
                                    ]),
                            ])
                            ->addActionLabel('Tambah Checklist')
                            ->reorderable(false)
                            ->columns([
                                'default' => 1,
                                'md' => 2,
                                'lg' => 7,
                            ]),
                    ])
                    ->addable(false)
                    ->deletable(false)
                    ->reorderable(false)
            ]);
    }
}
