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
        return Section::make('Informasi Produk')
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

                        TextInput::make('field_category')
                            ->label('Field Category')
                            ->required(),

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
