<?php

namespace App\Filament\Resources\Engineering\Maintenance\Refrigerator\Traits;

use App\Models\Engineering\Maintenance\ChamberR2\ChamberR2;
use App\Models\Engineering\Maintenance\WalkinChamber\WalkinChamber;
use App\Models\Engineering\Maintenance\ChamberG2\ChamberG2;
use App\Models\Engineering\Maintenance\ChamberWalkinG2\ChamberWalkinG2;
use App\Models\Engineering\Maintenance\ColdRoom\ColdRoom;
use App\Models\Engineering\Maintenance\Refrigerator\Refrigerator;
use App\Models\Engineering\Maintenance\RissingPipette\RissingPipette;
use App\Models\Engineering\SPK\SPKService\Pivot\PemeriksaanPersetujuan;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Set;

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
                // self::textInput('tag_no', 'Name/TAG No')
                //     ->hint('Format: TAG No.')
                //     ->placeholder($lastValue ? "Data Terakhir : {$lastValue}" : 'Data Belum Tersedia')
                //     ->unique(ignoreRecord: true),

                // Select::make('spk_service_id')
                //     ->label('Nomor SPK Service')
                //     ->options(function () {
                //         return SPKService::query()
                //             ->where('jenis_spk', 'Maintenance')
                //             ->where('status', 'Selesai')
                //             ->whereDoesntHave('walkinChamber')
                //             ->whereDoesntHave(relation: 'chamberR2')
                //             ->whereDoesntHave(relation: 'refrigerator')
                //             ->whereDoesntHave(relation: 'coldRoom')
                //             ->whereDoesntHave(relation: 'rissing')
                //             ->whereDoesntHave(relation: 'walkinG2')
                //             ->whereDoesntHave(relation: 'chamberG2')
                //             ->limit(10)
                //             ->pluck('no_spk_service', 'id');
                //     })
                //     ->getSearchResultsUsing(function (string $search) {
                //         return SPKService::query()
                //             ->where('status', 'Selesai')
                //             ->whereDoesntHave('walkinChamber')
                //             ->whereDoesntHave(relation: 'chamberR2')
                //             ->whereDoesntHave(relation: 'refrigerator')
                //             ->whereDoesntHave(relation: 'coldRoom')
                //             ->whereDoesntHave(relation: 'rissing')
                //             ->whereDoesntHave(relation: 'walkinG2')
                //             ->whereDoesntHave(relation: 'chamberG2')
                //             ->where('no_spk_service', 'like', "%{$search}%")
                //             ->limit(10)
                //             ->pluck('no_spk_service', 'id');
                //     })
                //     ->native(false)
                //     ->searchable()
                //     ->preload()
                //     ->required()
                //     ->hiddenOn(operations: 'edit'),

                Select::make('spk_selection')
                    ->label('Nomor SPK Service')
                    ->options(function () {
                        $usedTagNos = collect()
                            ->merge(WalkinChamber::pluck('tag_no'))
                            ->merge(ChamberR2::pluck('tag_no'))
                            ->merge(Refrigerator::pluck('tag_no'))
                            ->merge(ColdRoom::pluck('tag_no'))
                            ->merge(RissingPipette::pluck('tag_no'))
                            ->merge(ChamberWalkinG2::pluck('tag_no'))
                            ->merge(ChamberG2::pluck('tag_no'))
                            ->filter()
                            ->unique()
                            ->toArray();

                        return PemeriksaanPersetujuan::query()
                            ->whereHas('spkService', function ($query) {
                                $query->where('jenis_spk', 'Maintenance')
                                    ->where('status', 'Selesai');
                            })
                            ->whereNotIn('nomor_seri', $usedTagNos)
                            ->with('spkService')
                            ->limit(20)
                            ->get()
                            ->mapWithKeys(function ($detail) {
                                return [
                                    $detail->id => $detail->spkService->no_spk_service . ' - ' . $detail->nomor_seri
                                ];
                            });
                    })
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set) {
                        if (!$state) return;

                        $detail = PemeriksaanPersetujuan::find($state);

                        if (!$detail) return;

                        $set('spk_service_id', $detail->spk_service_id);
                        $set('tag_no', $detail->nomor_seri);
                    })
                    ->hiddenOn('edit'),

                Hidden::make('spk_service_id')
                    ->required(),

                TextInput::make('tag_no')
                    ->label('WTC Name/TAG No')
                    ->readOnly()
                    ->required()
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
