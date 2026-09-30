<?php

namespace App\Filament\Resources\Engineering\Maintenance\WalkinChamber\Traits;

use App\Models\Engineering\Maintenance\WalkinChamber\WalkinChamber;
use App\Models\Engineering\Maintenance\ChamberG2\ChamberG2;
use App\Models\Engineering\Maintenance\ChamberR2\ChamberR2;
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
use Wallo\FilamentSelectify\Components\ButtonGroup;

trait Informasi
{
    use SimpleFormResource, HasAutoNumber;
    public static function getInformasiSection($form)
    {
        $isEdit = $form->getOperation() === 'edit';

        return
            Section::make('Informasi')
            ->label('')
            ->schema([
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
        return
            Section::make('Remarks')
            ->collapsible()
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
