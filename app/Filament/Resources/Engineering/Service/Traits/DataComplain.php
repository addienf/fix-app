<?php

namespace App\Filament\Resources\Engineering\Service\Traits;

use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

trait DataComplain
{
    use SimpleFormResource, HasAutoNumber;
    public static function getDataComplainSection()
    {
        return Section::make('Data Complain')
            ->collapsible()
            ->schema([
                Grid::make(2)
                    ->schema([
                        TextInput::make('name_complaint')
                            ->required()
                            ->label('Who Complaint'),

                        TextInput::make('company_name')
                            ->required()
                            ->label('Company Name'),

                        TextInput::make('address')
                            ->required()
                            ->label('Address'),

                        PhoneInput::make('phone_number')
                            // ->defaultCountry('US')
                            ->label('Phone Number')
                            ->required(),
                    ])
            ]);
    }
}
