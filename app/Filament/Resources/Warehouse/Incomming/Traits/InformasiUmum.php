<?php

namespace App\Filament\Resources\Warehouse\Incomming\Traits;

use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Illuminate\Support\Facades\Cache;

trait InformasiUmum
{
    use SimpleFormResource, HasAutoNumber;
    protected static function informasiUmumSection(): Section
    {
        return
            Section::make('Informasi Umum')
            ->collapsible()
            ->schema([

                Grid::make(2)
                    ->schema([
                        self::autoNumberField2('no_surat', 'No.', [
                            'prefix' => 'QKS',
                            'section' => 'WBB',
                            'type' => 'PM',
                            'table' => 'incomming_materials',
                        ])
                            ->hiddenOn('edit'),

                        self::dateInput('tanggal', 'Tanggal Penerimaan')
                            ->required(),
                    ])

            ]);
    }
}
