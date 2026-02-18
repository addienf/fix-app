<?php

namespace App\Filament\Resources\Engineering\Maintenance\RissingPipette\Traits;

use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

trait TabelChecklist
{
    use SimpleFormResource, HasAutoNumber;
    public static function getTabelChecklistSection()
    {
        $parts = collect(config('rissingPipette.parts'))
            ->map(fn($part) => ['part' => $part])
            ->toArray();

        return Section::make('Tabel Checklist')
            ->collapsible()
            ->relationship('detail')
            ->schema([

                Repeater::make('checklist')
                    ->default($parts)
                    ->label('')
                    ->schema([

                        TextInput::make('part')
                            ->columnSpan([
                                'default' => 1,
                                'md' => 1,
                                'lg' => 3,
                            ])
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ])
                            ->required(),

                        Select::make('accepted')
                            ->options([
                                'yes' => 'Yes',
                                'no' => 'No',
                                'na' => 'NA',
                            ])
                            ->columnSpan([
                                'default' => 1,
                                'md' => 1,
                                'lg' => 2,
                            ])
                            ->required(),

                        TextInput::make('remark')
                            ->columnSpan([
                                'default' => 1,
                                'md' => 1,
                                'lg' => 2,
                            ])

                    ])
                    // ->columns(7)
                    ->columns([
                        'default' => 1,
                        'md' => 3,
                        'lg' => 7,
                    ])
                    // ->addable(false)
                    ->addActionLabel('Tambah Checklist')
                    ->deletable(false)
                    ->reorderable(false),

            ]);
    }
}
