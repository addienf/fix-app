<?php

namespace App\Filament\Resources\Sales\SpesifikasiProducts\Traits;

use App\Models\Sales\URS;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Wallo\FilamentSelectify\Components\ButtonGroup;

trait InformasiUmum
{
    use SimpleFormResource, HasAutoNumber;
    protected static function informasiUmumSection(bool $isEdit): Section
    {
        return Section::make('Informasi Umum')
            ->schema([
                self::selectNoURS()
                    ->hiddenOn('edit'),

                self::textInput('name', 'Name'),

                self::textInput('department', 'Department/Position'),

                self::textInput('phone_number', 'Phone Number'),

                self::textInput('delivery_address', 'Alamat Pengiriman'),

                self::buttonGroup('is_stock', 'Untuk Stock ?'),
            ])
            ->columns($isEdit ? 5 : 3)
            ->collapsible();
    }

    protected static function ursFormSchema(): array
    {
        return [
            Fieldset::make('')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            static::newAutoNmberField('no_urs', 'Nomor URS', [
                                'prefix' => 'QKS',
                                'section' => 'MKT',
                                'type' => 'URS',
                                'table' => 'urs',
                            ]),

                            self::selectInput('customer_id', 'Nama Customer', 'customer', 'name')
                                ->createOptionForm(fn() => self::customerFormSchema()),
                        ]),
                    self::textareaInput('permintaan_khusus', 'Remark Permintaan Khusus')
                ]),
        ];
    }

    protected static function customerFormSchema(): array
    {
        return [
            Fieldset::make('')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            self::textInput('name', 'Nama Customer'),
                            self::textInput('phone_number', 'No Telpon'),
                            self::textInput('department', 'Department'),
                            self::textInput('company_name', 'Nama Perusahaan'),
                            self::textInput('company_address', 'Alamat Perusahaan'),
                        ])
                ])
        ];
    }

    private static function buttonGroup(string $fieldName, string $label): ButtonGroup
    {
        return
            ButtonGroup::make($fieldName)
            ->label($label)
            ->required()
            ->options([
                1 => 'Yes',
                0 => 'No',
            ])
            ->onColor('primary')
            ->offColor('gray')
            ->gridDirection('row')
            ->default('individual');
    }

    private static function selectNoURS(): Select
    {
        return
            Select::make('urs_id')
            ->label('No URS')
            ->placeholder('Pilih Data URS')
            ->searchable()
            ->getSearchResultsUsing(function (string $search) {
                return URS::query()
                    ->where('no_urs', 'like', "%{$search}%")
                    ->orderBy('id', 'desc')
                    ->limit(10)
                    ->pluck('no_urs', 'id');
            })
            ->options(function () {
                return URS::orderBy('id', 'desc')
                    ->limit(10)
                    ->pluck('no_urs', 'id');
            })
            ->native(false)
            ->preload(false)
            ->required();
    }
}
