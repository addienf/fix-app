<?php

namespace App\Filament\Resources\Engineering\Service\Traits;

use App\Models\Engineering\Complain\Complain;
use App\Models\Engineering\Service\ServiceReport;
use App\Models\Engineering\SPK\SPKService;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Cache;

trait InformasiUmum
{
    use SimpleFormResource, HasAutoNumber;
    public static function getInformasiUmumSection($form)
    {
        $lastValue2 = ServiceReport::latest('form_no')->value('form_no');
        $isEdit = $form->getOperation() === 'edit';

        return Section::make('Informasi Umum')
            ->collapsible()
            ->schema([
                Select::make('spk_service_id')
                    ->label('Nomor SPK Service')
                    // ->options(function () {
                    //     return Cache::rememberForever(SPKService::$CACHE_KEYS['service'], function () {
                    //         return SPKService::where('status_penyelesaian', 'Selesai')
                    //             ->whereDoesntHave('service')
                    //             ->get()
                    //             ->pluck('no_spk_service', 'id');
                    //     });
                    // })
                    ->options(function () {
                        return SPKService::query()
                            ->where('status', 'Selesai')
                            ->whereDoesntHave('service')
                            ->limit(10)
                            ->pluck('no_spk_service', 'id');
                    })
                    ->getSearchResultsUsing(function (string $search) {
                        return SPKService::query()
                            ->where('status', 'Selesai')
                            ->whereDoesntHave('service')
                            ->where('no_spk_service', 'like', "%{$search}%")
                            ->limit(10)
                            ->pluck('no_spk_service', 'id');
                    })
                    ->native(false)
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if (!$state)
                            return;

                        $spkS = SPKService::with('complain')->find($state);
                        if (!$spkS)
                            return;

                        $details = $spkS->complain->details->map(function ($detail) {
                            return [
                                'produk_name' => $detail->unit_name ?? '-',
                                'type' => $detail?->tipe_model ?? '-',
                                'status_warranty' => $detail?->status_warranty  ?? '-',
                            ];
                        })->toArray();

                        $formNo = $spkS->complain->form_no;
                        $namaComplain = $spkS->complain->name_complain;
                        $companyName = $spkS->complain->company_name;
                        $alamat = $spkS->complain->spkService->alamat;
                        $number = $spkS->complain->phone_number;

                        $set('form_no', $formNo);
                        $set('name_complaint', $namaComplain);
                        $set('company_name', $companyName);
                        $set('address', $alamat);
                        $set('phone_number', $number);
                        $set('serviceProduk', $details);
                    }),
                Grid::make($isEdit ? 1 : 2)
                    ->schema([
                        TextInput::make('form_no')
                            ->label('Nomor Form')
                            ->placeholder($lastValue2 ? "Data Terakhir : {$lastValue2}" : 'Data Belum Tersedia')
                            ->hiddenOn('edit')
                            ->unique(ignoreRecord: true)
                            // ->columnSpanFull()
                            ->required()
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),

                        self::dateInput('tanggal', 'Tanggal'),
                        // DatePicker::make('tanggal')
                        //     ->required()
                    ])
            ]);
    }

    private static function select()
    {
        return Select::make('spk_service_id')
            ->label('Nomor SPK Service')
            // ->options(function () {
            //     return SPKService::where('status_penyelesaian', 'Selesai')
            //         ->whereDoesntHave('service')
            //         ->pluck('no_spk_service', 'id');
            // })
            ->options(function () {
                return Cache::rememberForever(SPKService::$CACHE_KEYS['service'], function () {
                    return SPKService::where('status_penyelesaian', 'Selesai')
                        ->whereDoesntHave('service')
                        ->get()
                        ->pluck('no_spk_service', 'id');
                });
            })
            ->native(false)
            ->searchable()
            ->preload()
            ->required()
            ->reactive()
            ->afterStateUpdated(function ($state, callable $set) {
                if (!$state)
                    return;

                $complain = Complain::with('spkService', 'details')->find($state);
                if (!$complain)
                    return;

                $details = $complain->details->map(function ($detail) {
                    return [
                        'produk_name' => $detail->unit_name ?? '-',
                        'type' => $detail?->tipe_model ?? '-',
                        'status_warranty' => $detail?->status_warranty  ?? '-',
                    ];
                })->toArray();

                $formNo = $complain->form_no;
                $namaComplain = $complain->name_complain;
                $companyName = $complain->company_name;
                $alamat = $complain->spkService->alamat;
                $number = $complain->phone_number;

                $set('form_no', $formNo);
                $set('name_complaint', $namaComplain);
                $set('company_name', $companyName);
                $set('address', $alamat);
                $set('phone_number', $number);
                $set('serviceProduk', $details);
            });
    }
}
