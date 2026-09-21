<?php

namespace App\Filament\Resources\Engineering\Maintenance\ChamberWalkinG2\Traits;

use App\Models\Engineering\Maintenance\WalkinChamber\WalkinChamber;
use App\Models\Engineering\Maintenance\ChamberG2\ChamberG2;
use App\Models\Engineering\Maintenance\ChamberR2\ChamberR2;
use App\Models\Engineering\Maintenance\ChamberWalkinG2\ChamberWalkinG2;
use App\Models\Engineering\Maintenance\ColdRoom\ColdRoom;
use App\Models\Engineering\Maintenance\Refrigerator\Refrigerator;
use App\Models\Engineering\Maintenance\RissingPipette\RissingPipette;
use App\Models\Engineering\SPK\SPKService\Pivot\PemeriksaanPersetujuan;
use App\Traits\HasAutoNumber;
use App\Traits\HasModelFilter;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Set;
use Wallo\FilamentSelectify\Components\ButtonGroup;

trait Informasi
{
    use SimpleFormResource, HasAutoNumber, HasModelFilter;
    public static function getInformasiSection($form)
    {
        $lastValue = ChamberWalkinG2::latest('tag_no')->value('tag_no');
        $isEdit = $form->getOperation() === 'edit';

        return Section::make('Informasi')
            ->label('')
            ->schema([
                Select::make('spk_selection')
                    ->label('Nomor SPK Service')
                    ->options(function () {

                        $usedKeys = self::getUsedSpkTagKeys();

                        return PemeriksaanPersetujuan::query()
                            ->whereHas('spkService', function ($query) {
                                $query->where('jenis_spk', 'Maintenance')
                                    ->where('status', 'Selesai');
                            })
                            ->with('spkService')
                            ->get()

                            ->reject(function ($detail) use ($usedKeys) {

                                $key =
                                    $detail->spkService?->no_spk_service .
                                    '|' .
                                    $detail->nomor_seri;

                                return $usedKeys->contains($key);
                            })

                            ->take(20)

                            ->mapWithKeys(function ($detail) {
                                return [
                                    $detail->id =>
                                    $detail->spkService->no_spk_service .
                                        ' - ' .
                                        $detail->nomor_seri
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
                    ->required(),

                self::textInput('project', 'Nama Project'),
            ])
            ->columns([
                'default' => 1,
                'md' => 2,
                'lg' => $isEdit ? 2 : 3,
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
