<?php

namespace App\Filament\Resources\Engineering\SPK\Traits;

use App\Models\Engineering\Complain\Complain;
use App\Models\Engineering\Pelayanan\PermintaanPelayananPelanggan;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;

trait InformasiUmum
{
    use SimpleFormResource, HasAutoNumber;
    public static function getInformasiUmumSection()
    {
        return Section::make('Informasi Umum')
            ->collapsible()
            ->schema([
                self::select()
                    ->hiddenOn('edit'),

                self::autoNumberField2('no_spk_service', 'Nomor SPK Service', [
                    'prefix' => 'QKS',
                    'section' => 'ENG',
                    'type' => 'SPK',
                    'table' => 'spk_services',
                ])
                    ->hiddenOn('edit'),

                self::textInput('perusahaan', 'Nama Perusahaan'),

                self::textInput('alamat', 'Alamat'),

            ])->columns(2);
    }

    protected static function select(): Select
    {
        return
            Select::make('pelayanan_id')
            ->label('Nomor Complaint Form')
            ->placeholder('Pilih Nomor Complaint Form')
            ->reactive()
            ->required()
            ->options(function () {
                return PermintaanPelayananPelanggan::whereDoesntHave('spkService')
                    ->get()
                    ->mapWithKeys(function ($item) {
                        $noForm = $item->no_form ?? '-';
                        $customerName = $item->complain->name_complain ?? '-';
                        return [$item->id => "{$noForm} - {$customerName}"];
                    });
            })
            ->afterStateUpdated(function ($state, callable $set) {
                if (!$state) return;

                $pelayanan = PermintaanPelayananPelanggan::find($state);
                if (!$pelayanan) return;

                $companyName = $pelayanan?->perusahaan ?? '-';
                $alamat = $pelayanan?->alamat ?? '-';
                $tempat = $pelayanan?->tempat_pelaksanaan ?? '-';

                $details = $pelayanan->details->map(function ($detail) {
                    return [
                        'nama_alat'   => $detail?->nama_alat ?? '-',
                        'tipe'        => $detail?->tipe ?? '-',
                        'nomor_seri'  => $detail?->nomor_seri ?? '-',
                        'quantity'    => $detail?->quantity ?? '-'
                    ];
                })->toArray();

                $set('perusahaan', $companyName);
                $set('alamat', $alamat);
                $set('tempat_pelaksanaan', $tempat);
                $set('details', $details);
            });
    }
}
