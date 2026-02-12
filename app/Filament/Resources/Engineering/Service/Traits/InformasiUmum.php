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

        return
            Section::make('Informasi Umum')
            ->collapsible()
            ->schema([
                Select::make('spk_service_id')
                    ->label('Nomor SPK Service')
                    ->options(function () {
                        // return SPKService::whereHas('permintaanSparepart', function ($query) {
                        //     $query->where('status', 'Selesai');
                        // })
                        return SPKService::whereDoesntHave('service')
                            ->where('jenis_spk', 'Service')
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
                    ->hiddenOn('edit'),
                // ->afterStateUpdated(function ($state, callable $set) {
                //     if (!$state)
                //         return;

                //     $spkS = SPKService::with('pelayananPelanggan.complain')->find($state);
                //     if (!$spkS)
                //         return;

                //     $serialNumber = $spkS->pelayananPelanggan
                //         ?->details
                //         ?->first()
                //         ?->nomor_seri ?? '-';

                //     $details = $spkS->pelayananPelanggan->complain->details->map(function ($detail) use ($serialNumber) {
                //         return [
                //             'produk_name' => $detail->unit_name ?? '-',
                //             'type' => $detail?->tipe_model ?? '-',
                //             'status_warranty' => $detail?->status_warranty  ?? '-',
                //             'serial_number' => $serialNumber  ?? '-',
                //         ];
                //     })->toArray();

                //     $formNo = $spkS->pelayananPelanggan->complain->form_no ?? '-';
                //     $namaComplain = $spkS->pelayananPelanggan->complain->name_complain ?? '-';
                //     $companyName = $spkS->pelayananPelanggan->companies?->first()?->name ?? '-';
                //     $alamat = $spkS->pelayananPelanggan->alamat ?? '-';
                //     $number = $spkS->pelayananPelanggan->complain->phone_number ?? '-';

                //     $set('form_no', $formNo);
                //     $set('name_complaint', $namaComplain);
                //     $set('company_name', $companyName);
                //     $set('address', $alamat);
                //     $set('phone_number', $number);
                //     $set('serviceProduk', $details);
                // }),

                Grid::make([
                    'default' => 1,
                    'md' => 2,
                    'lg' => $isEdit ? 1 : 2,
                ])
                    ->schema([
                        TextInput::make('form_no')
                            ->label('Nomor Form')
                            ->placeholder($lastValue2 ? "Data Terakhir : {$lastValue2}" : 'Data Belum Tersedia')
                            ->hiddenOn('edit')
                            ->unique(ignoreRecord: true)
                            ->required(),
                        // ->extraAttributes([
                        //     'readonly' => true,
                        //     'style' => 'pointer-events: none;'
                        // ]),

                        self::dateInput('tanggal', 'Tanggal'),
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
                return SPKService::where('status_penyelesaian', 'Selesai')
                    ->whereDoesntHave('service')
                    ->get()
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
