<?php

namespace App\Filament\Resources\Engineering\Maintenance\WalkinChamber\Traits;

use App\Models\Engineering\Maintenance\WalkinChamber\WalkinChamber;
use App\Models\Engineering\SPK\SPKService;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Cache;
use Wallo\FilamentSelectify\Components\ButtonGroup;

trait Informasi
{
    use SimpleFormResource, HasAutoNumber;
    public static function getInformasiSection($form)
    {
        $lastValue = WalkinChamber::latest('tag_no')->value('tag_no');
        $isEdit = $form->getOperation() === 'edit';

        return
            Section::make('Informasi')
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

    public static function getJenisTTD()
    {
        return ButtonGroup::make('jenis_ttd')
            ->label('Jenis Tanda Tangan')
            ->options([
                'On Site'   => 'On Site',
                'TTD Basah' => 'TTD Basah',
            ])
            ->default('On Site')
            ->afterStateHydrated(function ($component, $state) {
                if (blank($state)) {
                    $component->state('On Site');
                }
            })
            ->hiddenOn('create')
            ->reactive()
            ->dehydrated(false)
            ->columnSpanFull()
            ->onColor('primary')
            ->offColor('gray')
            ->gridDirection('row');
    }
}
