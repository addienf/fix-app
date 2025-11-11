<?php

namespace App\Filament\Resources\Engineering\Maintenance\ChamberWalkinG2\Traits;

use App\Models\Engineering\Maintenance\ChamberWalkinG2\ChamberWalkinG2;
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
        $lastValue = ChamberWalkinG2::latest('tag_no')->value('tag_no');
        $isEdit = $form->getOperation() === 'edit';

        return Section::make('Informasi')
            ->label('')
            ->schema([
                self::textInput('tag_no', 'WTC Name/TAG No')
                    ->hint('Format: TAG No.')
                    ->placeholder($lastValue ? "Data Terakhir : {$lastValue}" : 'Data Belum Tersedia')
                    // ->hiddenOn('edit')
                    ->unique(ignoreRecord: true),

                Select::make('spk_service_id')
                    ->label('Nomor SPK Service')
                    ->options(function () {
                        return Cache::rememberForever(SPKService::$CACHE_KEYS['walkinG2'], function () {
                            return SPKService::where('status_penyelesaian', 'Selesai')
                                ->whereDoesntHave('walkinG2')
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
