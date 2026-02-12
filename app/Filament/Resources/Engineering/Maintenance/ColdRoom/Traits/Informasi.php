<?php

namespace App\Filament\Resources\Engineering\Maintenance\ColdRoom\Traits;

use App\Models\Engineering\Maintenance\ColdRoom\ColdRoom;
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
        $lastValue = ColdRoom::latest('tag_no')->value('tag_no');
        $isEdit = $form->getOperation() === 'edit';

        return Section::make('Informasi')
            ->label('')
            ->schema([
                self::textInput('tag_no', 'Unit Name/TAG No')
                    ->hint('Format: TAG No.')
                    ->placeholder($lastValue ? "Data Terakhir : {$lastValue}" : 'Data Belum Tersedia')
                    ->unique(ignoreRecord: true),

                Select::make('spk_service_id')
                    ->label('Nomor SPK Service')
                    // ->options(function () {
                    //     return Cache::rememberForever(SPKService::$CACHE_KEYS['coldRoom'], function () {
                    //         return SPKService::where('status_penyelesaian', 'Selesai')
                    //             ->whereDoesntHave('coldRoom')
                    //             ->get()
                    //             ->pluck('no_spk_service', 'id');
                    //     });
                    // })
                    ->options(function () {
                        return SPKService::query()
                            ->where('jenis_spk', 'Maintenance')
                            ->where('status', 'Selesai')
                            ->whereDoesntHave('walkinChamber')
                            ->whereDoesntHave(relation: 'chamberR2')
                            ->whereDoesntHave(relation: 'refrigerator')
                            ->whereDoesntHave(relation: 'coldRoom')
                            ->whereDoesntHave(relation: 'rissing')
                            ->whereDoesntHave(relation: 'walkinG2')
                            ->whereDoesntHave(relation: 'chamberG2')
                            ->limit(10)
                            ->pluck('no_spk_service', 'id');
                    })
                    ->getSearchResultsUsing(function (string $search) {
                        return SPKService::query()
                            ->where('status', 'Selesai')
                            ->whereDoesntHave('walkinChamber')
                            ->whereDoesntHave(relation: 'chamberR2')
                            ->whereDoesntHave(relation: 'refrigerator')
                            ->whereDoesntHave(relation: 'coldRoom')
                            ->whereDoesntHave(relation: 'rissing')
                            ->whereDoesntHave(relation: 'walkinG2')
                            ->whereDoesntHave(relation: 'chamberG2')
                            ->where('no_spk_service', 'like', "%{$search}%")
                            ->limit(10)
                            ->pluck('no_spk_service', 'id');
                    })
                    ->native(false)
                    ->searchable()
                    ->preload()
                    ->required()
                    ->hiddenOn(operations: 'edit'),
            ])
            ->columns([
                'default' => 1,
                'md' => 2,
                'lg' => $isEdit ? 1 : 2,
            ]);
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
