<?php

namespace App\Filament\Resources\Engineering\Complain\Traits;

use App\Models\General\Company;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
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

                        // self::textInput('company_name', 'Company Name'),
                        self::select(),

                        self::textInput('department', 'Department'),

                        PhoneInput::make('phone_number')
                            ->label('Phone Number')
                            ->required(),

                        self::textInput('receive_by', 'Receive By')
                            ->columnSpanFull(),
                    ])
            ]);
    }

    private static function select()
    {
        return
            Select::make(name: 'company_id')
            ->label('Company')
            ->placeholder('Pilih Data Company')
            ->searchable()
            ->reactive()
            ->getSearchResultsUsing(function (string $search) {
                return Company::query()
                    ->where('name', 'like', "%{$search}%")
                    ->orderBy('id', 'desc')
                    ->limit(10)
                    ->pluck('name', 'id');
            })
            ->options(function () {
                return Company::query()
                    ->orderBy('id', 'desc')
                    ->limit(10)
                    ->pluck('name', 'id');
            })
            ->afterStateUpdated(function ($state, callable $set) {
                if (!$state) return;

                $company = Company::find($state);

                $phone = $company?->phone ?? '-';

                $set('phone_number', $phone);
            })
            ->native(false)
            ->preload(false)
            ->required();
    }
}
