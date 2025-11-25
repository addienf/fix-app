<?php

namespace App\Filament\Resources\Engineering\Complain\Traits;

use App\Models\General\Product;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Wallo\FilamentSelectify\Components\ButtonGroup;

trait InformasiProduk
{
    use SimpleFormResource;
    public static function getInformasiProdukSection()
    {
        return
            Section::make('Informasi Produk')
            ->collapsible()
            ->schema([
                Repeater::make('Service Produk')
                    ->relationship('details')
                    ->label('')
                    ->schema([

                        Select::make('unit_name')
                            ->label('Pilih Produk')
                            ->options(Product::pluck('name', 'name'))
                            ->searchable(),

                        TextInput::make('tipe_model')
                            ->label('Type/Model')
                            ->required(),

                        ButtonGroup::make('status_warranty')
                            ->required()
                            ->label('Status Warranty')
                            ->gridDirection('row')
                            ->options([
                                1 => 'Yes',
                                0 => 'No',
                            ]),

                        Select::make('field_category')
                            ->label('Field Category')
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

                        Textarea::make('deskripsi')
                            ->columnSpanFull()
                            ->required()
                    ])
                    ->columnSpanFull()
                    ->columns(2)
                    ->addable(false)
                    ->reorderable(false)
                    ->deletable(false)
            ])
            ->columns(2);
    }
}
