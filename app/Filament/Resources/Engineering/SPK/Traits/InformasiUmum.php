<?php

namespace App\Filament\Resources\Engineering\SPK\Traits;

use App\Models\Engineering\Complain\Complain;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Wallo\FilamentSelectify\Components\ButtonGroup;

trait InformasiUmum
{
    use SimpleFormResource, HasAutoNumber;
    public static function getInformasiUmumSection()
    {
        return
            Section::make('Informasi Umum')
            ->collapsible()
            ->schema([
                self::getBTNDrop()
                    ->hiddenOn('edit'),

                // TextInput::make('no_spk_service')
                //     ->label('Nomor SPK Service')
                //     ->placeholder(function () {

                //         $last = DB::table('spk_services')
                //             ->whereNotNull('no_spk_service')
                //             ->orderByDesc('id')
                //             ->value('no_spk_service');

                //         return $last ?? 'Belum ada data';
                //     })
                //     ->hiddenOn('edit'),

                Select::make('section')
                    ->options([
                        'ENG' => 'Engineering',
                        'CC' => 'Customer Care',
                    ])
                    ->required()
                    ->reactive()
                    ->hiddenOn('edit')
                    ->afterStateUpdated(function (Set $set, Get $get) {

                        if (!$get('section')) return;

                        $set('no_spk_service', self::generateAutoNumber(
                            table: 'spk_services',
                            column: 'no_spk_service',
                            prefix: 'QKS',
                            section: $get('section'),
                            type: 'SPK'
                        ));
                    }),

                self::autoNumberField4('no_spk_service', 'Nomor SPK Service', [
                    'prefix' => 'QKS',
                    'type' => 'SPK',
                    'table' => 'spk_services',
                    'section_field' => 'section',
                ])
                    ->columnSpanFull()
                    ->hiddenOn('edit'),

                // self::autoNumberField4('no_spk_service', 'Nomor SPK Service', [
                //     'prefix' => 'QKS',
                //     'section' => 'ENG',
                //     'type' => 'SPK',
                //     'table' => 'spk_services',
                // ])
                //     ->hiddenOn('edit'),

                self::textInput('perusahaan', 'Nama Perusahaan'),

                self::textInput('alamat', 'Alamat'),

            ])
            ->columns([
                'default' => 1,
                'md' => 2,
                'lg' => 2,
            ]);
    }

    // protected static function select(): Select
    // {
    //     return
    //         Select::make('pelayanan_id')
    //         ->label('Nomor Complaint Form')
    //         ->placeholder('Pilih Nomor Complaint Form')
    //         ->reactive()
    //         ->required()
    //         ->options(function () {
    //             return PermintaanPelayananPelanggan::whereDoesntHave('spkService')
    //                 ->get()
    //                 ->mapWithKeys(function ($item) {
    //                     $noForm = $item->no_form ?? '-';
    //                     $customerName = $item->complain->name_complain ?? '-';
    //                     return [$item->id => "{$noForm} - {$customerName}"];
    //                 });
    //         })
    //         ->afterStateUpdated(function ($state, callable $set) {
    //             if (!$state) return;

    //             $pelayanan = PermintaanPelayananPelanggan::find($state);
    //             if (!$pelayanan) return;

    //             $companyName = $pelayanan?->perusahaan ?? '-';
    //             $alamat = $pelayanan?->alamat ?? '-';
    //             $tempat = $pelayanan?->tempat_pelaksanaan ?? '-';

    //             $details = $pelayanan->details->map(function ($detail) {
    //                 return [
    //                     'nama_alat'   => $detail?->nama_alat ?? '-',
    //                     'tipe'        => $detail?->tipe ?? '-',
    //                     'nomor_seri'  => $detail?->nomor_seri ?? '-',
    //                     'quantity'    => $detail?->quantity ?? '-'
    //                 ];
    //             })->toArray();

    //             $set('perusahaan', $companyName);
    //             $set('alamat', $alamat);
    //             $set('tempat_pelaksanaan', $tempat);
    //             $set('details', $details);
    //         });
    // }

    private static function getBTN()
    {
        return
            ButtonGroup::make('jenis_spk')
            ->label('Jenis SPK')
            ->options([
                'Service' => 'Service',
                'Maintenance' => 'Maintenance',
                'Kalibrasi' => 'Kalibrasi'
            ])
            ->required()
            ->reactive()
            // ->columnSpanFull()
            ->onColor('primary')
            ->offColor('gray')
            ->gridDirection('column')
        ;
    }

    private static function getBTNDrop()
    {
        return
            Select::make('jenis_spk')
            ->label('Jenis SPK')
            ->options([
                'Service' => 'Service',
                'Maintenance' => 'Maintenance',
                'Kalibrasi' => 'Kalibrasi'
            ])
            ->required()
        ;
    }
}
