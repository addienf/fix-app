<?php

namespace App\Filament\Resources\Sales\SpesifikasiProducts\Traits;

use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Wallo\FilamentSelectify\Components\ButtonGroup;

trait InformasiUmum
{
    protected static function informasiUmumSection(bool $isEdit): Section
    {
        return Section::make('Informasi Umum')
            ->schema([
                self::selectInputCache('urs_id', 'No URS', 'urs', 'no_urs')
                    ->placeholder('Pilih Data URS')
                    ->hiddenOn('edit'),

                self::textInput('delivery_address', 'Alamat Pengiriman'),

                self::buttonGroup('is_stock', 'Untuk Stock ?'),
            ])
            ->columns($isEdit ? 2 : 3)
            ->collapsible();
    }

    protected static function ursFormSchema(): array
    {
        return [
            Fieldset::make('')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            self::autoNumberField('no_urs', 'Nomor URS', [
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

    protected static function buttonGroup(string $fieldName, string $label): ButtonGroup
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
}
