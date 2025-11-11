<?php

namespace App\Filament\Resources\Engineering\Service\Traits;

use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;

trait Checklist
{
    use SimpleFormResource, HasAutoNumber;
    public static function getChecklistSection()
    {
        return Section::make('Checklist')
            ->label('')
            ->schema([
                Grid::make(3)
                    ->schema([
                        Select::make('service_category')
                            ->multiple()
                            ->options([
                                'installation' => 'Installation',
                                'maintenance' => 'Maintenance',
                                'repair' => 'Repair',
                                'consultation' => 'Consultation',
                            ]),

                        Select::make('actions')
                            ->multiple()
                            ->options([
                                'cleaning' => 'Cleaning',
                                'installation' => 'Installation',
                                'repairing' => 'Repairing',
                                'maintenance' => 'Maintenance',
                                'replacing' => 'Replacing',
                                'other' => 'Other',
                            ]),

                        Select::make('service_fields')
                            ->multiple()
                            ->options([
                                'controlling' => 'Controlling',
                                'air_cooling_system' => 'Air Cooling System',
                                'logging_system' => 'Logging System',
                                'server_computer' => 'Server Computer',
                                'networking' => 'Networking',
                                'water_feeding_system' => 'Water Feeding System',
                                'cooling_system' => 'Cooling System',
                                'humidifier_system' => 'Humidifier System',
                                'communication_system' => 'Communication System',
                                'air_heating_system' => 'Air Heating System',
                                'software' => 'Software',
                                'other' => 'Other',
                            ]),
                    ]),
            ]);
    }
}
