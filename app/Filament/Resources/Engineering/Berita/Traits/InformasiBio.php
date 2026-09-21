<?php

namespace App\Filament\Resources\Engineering\Berita\Traits;

use App\Models\General\Company;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Set;

trait InformasiBio
{
    use SimpleFormResource;
    public static function getInformasiBioSection()
    {
        return
            Split::make([
                Section::make('Data Penyedia Jasa')
                    ->collapsible()
                    ->relationship('penyediaJasa')
                    ->schema([
                        TextInput::make('nama')
                            ->required(),

                        TextInput::make('perusahaan')
                            ->default('PT Qlab Kinarya Sentosa')
                            ->required(),

                        Grid::make([
                            'default' => 2,
                            'md' => 2,
                            'lg' => 2,
                        ])
                            ->schema([
                                TextInput::make('alamat')
                                    ->default('Jl. Haji Basyar Raya no. 15 C-D, RT.003/RW.003, Jaticempaka, Pondok Gede, Bekasi, West Java 17411')
                                    ->required(),

                                TextInput::make('jabatan')
                                    ->required(),
                            ]),
                    ])
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                        'lg' => 2,
                    ]),

                Section::make('Data Pelanggan')
                    ->collapsible()
                    ->relationship('pelanggan')
                    ->schema([
                        TextInput::make('nama')
                            ->required(),

                        Select::make('perusahaan')
                            ->label('Perusahaan')
                            ->options(Company::pluck('name', 'name'))
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(function ($state, Set $set) {
                                $company = Company::where('name', $state)->first();

                                if ($company) {
                                    $set('alamat', $company->address);
                                }
                            }),

                        Grid::make([
                            'default' => 2,
                            'md' => 2,
                            'lg' => 2,
                        ])
                            ->schema([
                                TextInput::make('alamat')
                                    ->required(),

                                TextInput::make('jabatan')
                                    ->required(),
                            ])
                    ])
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                        'lg' => 2,
                    ]),
            ])->from('md')
            ->columnSpanFull();
    }
}
