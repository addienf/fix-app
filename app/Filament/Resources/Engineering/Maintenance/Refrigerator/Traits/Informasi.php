<?php

namespace App\Filament\Resources\Engineering\Maintenance\Refrigerator\Traits;

use App\Models\Engineering\Maintenance\Refrigerator\Refrigerator;
use App\Models\Engineering\SPK\SPKService;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Cache;

trait Informasi
{
    use SimpleFormResource, HasAutoNumber;
    public static function getInformasiSection($form)
    {
        $lastValue = Refrigerator::latest('tag_no')->value('tag_no');
        $isEdit = $form->getOperation() === 'edit';

        return Section::make('Informasi')
            ->label('')
            ->schema([
                self::textInput('tag_no', 'Name/TAG No')
                    ->hint('Format: TAG No.')
                    ->placeholder($lastValue ? "Data Terakhir : {$lastValue}" : 'Data Belum Tersedia')
                    ->unique(ignoreRecord: true),

                Select::make('spk_service_id')
                    ->label('Nomor SPK Service')
                    ->options(function () {
                        return Cache::rememberForever(SPKService::$CACHE_KEYS['refrigerator'], function () {
                            return SPKService::where('status_penyelesaian', 'Selesai')
                                ->whereDoesntHave('refrigerator')
                                ->get()
                                ->pluck('no_spk_service', 'id');
                        });
                    })
                    ->native(false)
                    ->searchable()
                    ->preload()
                    ->required()
                    ->hiddenOn(operations: 'edit'),
            ])
            ->columns($isEdit ? 1 : 2);
    }

    public static function getRemarksSection()
    {
        return Section::make('Remarks')
            ->label('')
            ->schema([
                self::textareaInput('remarks', 'Remarks'),
            ]);
    }
}
