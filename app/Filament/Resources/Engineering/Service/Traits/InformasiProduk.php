<?php

namespace App\Filament\Resources\Engineering\Service\Traits;

use App\Models\General\Product;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Wallo\FilamentSelectify\Components\ButtonGroup;

trait InformasiProduk
{
    use SimpleFormResource, HasAutoNumber;
    public static function getInformasiProdukSection()
    {
        return Section::make('Informasi Produk')
            ->collapsible()
            ->schema([
                Repeater::make('serviceProduk')
                    ->relationship('produkServices')
                    ->label('')
                    ->schema([
                        Select::make('produk_name')
                            ->label('Pilih Produk')
                            ->options(Product::pluck('name', 'name'))
                            ->searchable(),

                        TextInput::make('type')
                            ->label('Type/Model')
                            ->required(),

                        TextInput::make('serial_number')
                            ->label('Nomor Seri')
                            ->required(),

                        ButtonGroup::make('status_warranty')
                            ->required()
                            ->label('Status Warranty')
                            ->gridDirection('row')
                            ->options([
                                1 => 'Yes',
                                0 => 'No',
                            ])
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
