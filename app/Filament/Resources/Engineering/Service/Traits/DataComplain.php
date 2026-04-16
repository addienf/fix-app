<?php

namespace App\Filament\Resources\Engineering\Service\Traits;

use App\Models\General\Company;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Set;

trait DataComplain
{
    use SimpleFormResource, HasAutoNumber;
    public static function getDataComplainSection()
    {
        return Section::make('Data Complain')
            ->collapsible()
            ->schema([
                Grid::make([
                    'default' => 1,
                    'md' => 2,
                    'lg' => 2,
                ])
                    ->schema([
                        TextInput::make('name_complaint')
                            ->required()
                            ->label('Who Complaint'),

                        Select::make('company_name')
                            ->label('Company Name')
                            ->options(Company::pluck('name', 'name'))
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(function ($state, Set $set) {
                                $company = Company::where('name', $state)->first();

                                if ($company) {
                                    $set('address', $company->address);
                                }
                            }),

                        TextInput::make('address')
                            ->required()
                            ->label('Address'),

                        TextInput::make('phone_number')
                            ->required()
                            ->label('Phone Number'),
                    ])
            ]);
    }
}
