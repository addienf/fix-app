<?php

namespace App\Filament\Resources\Engineering\SPK\Traits;

use App\Models\Engineering\Complain\Complain;
use App\Models\General\Company;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Wallo\FilamentSelectify\Components\ButtonGroup;

trait InformasiUmum
{
    use SimpleFormResource, HasAutoNumber;
    public static function getInformasiUmumSection()
    {
        return
            Section::make('Informasi Umum')
            ->collapsible()
            ->schema([
                self::getBTNDrop()
                    ->hiddenOn('edit'),

                Select::make('section')
                    ->options([
                        'ENG' => 'Engineering',
                        'CC' => 'Customer Care',
                    ])
                    ->required()
                    ->reactive()
                    ->hiddenOn('edit')
                    ->afterStateUpdated(function (Set $set, Get $get) {

                        if (!$get('section')) return;

                        $set('no_spk_service', self::generateAutoNumber(
                            table: 'spk_services',
                            column: 'no_spk_service',
                            prefix: 'QKS',
                            section: $get('section'),
                            type: 'SPK'
                        ));
                    }),

                self::autoNumberField4('no_spk_service', 'Nomor SPK Service', [
                    'prefix' => 'QKS',
                    'type' => 'SPK',
                    'table' => 'spk_services',
                    'section_field' => 'section',
                ])
                    ->columnSpanFull()
                    ->hiddenOn('edit'),

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

                self::textInput('alamat', 'Alamat'),
            ])
            ->columns([
                'default' => 1,
                'md' => 2,
                'lg' => 2,
            ]);
    }

    private static function getBTN()
    {
        return
            ButtonGroup::make('jenis_spk')
            ->label('Jenis SPK')
            ->options([
                'Service' => 'Service',
                'Maintenance' => 'Maintenance',
                'Kalibrasi' => 'Kalibrasi'
            ])
            ->required()
            ->reactive()
            // ->columnSpanFull()
            ->onColor('primary')
            ->offColor('gray')
            ->gridDirection('column')
        ;
    }

    private static function getBTNDrop()
    {
        return
            Select::make('jenis_spk')
            ->label('Jenis SPK')
            ->options([
                'Service' => 'Service',
                'Maintenance' => 'Maintenance',
                'Kalibrasi' => 'Kalibrasi'
            ])
            ->required()
        ;
    }
}
