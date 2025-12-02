<?php

namespace App\Filament\Resources\Engineering\Complain\Traits;

use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

trait DataComplain
{
    use SimpleFormResource;
    public static function getDataComplainSection()
    {
        return Section::make('Data Complain')
            ->collapsible()
            ->schema([
                Grid::make(2)
                    ->schema([

                        self::textInput('name_complain', 'Who Complain'),

                        self::textInput('company_name', 'Company Name'),

                        self::textInput('department', 'Department'),

                        PhoneInput::make('phone_number')
                            ->label('Phone Number')
                            ->required(),

                        self::textInput('receive_by', 'Receive By')
                            ->columnSpanFull(),
                    ])
            ]);
    }
}
