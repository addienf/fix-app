<?php

namespace App\Filament\Resources\Engineering\Berita\Traits;

use App\Models\Engineering\Berita\BeritaAcara;
use App\Models\Engineering\SPK\SPKService;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Cache;
use Wallo\FilamentSelectify\Components\ButtonGroup;

trait InformasiUmum
{
    use SimpleFormResource;
    public static function getInformasiUmumSection()
    {
        $lastValue = BeritaAcara::latest('no_surat')->value('no_surat');

        return Section::make('Informasi Umum')
            ->collapsible()
            ->schema([
                self::select(),
                Grid::make(2)
                    ->schema([
                        TextInput::make('no_surat')
                            ->label('Nomor Surat')
                            ->hint('Format: No Surat')
                            ->placeholder($lastValue ? "Data Terakhir : {$lastValue}" : 'Data Belum Tersedia')
                            ->hiddenOn('edit')
                            ->unique(ignoreRecord: true)
                            ->required(),

                        DatePicker::make('tanggal')
                            ->required(),

                        ButtonGroup::make('status_po')
                            ->label('Status PO')
                            ->required()
                            ->options([
                                'yes' => 'Received',
                                'no' => 'Not Received',
                            ])
                            ->reactive()
                            ->onColor('primary')
                            ->offColor('gray')
                            ->gridDirection('row'),

                        TextInput::make('nomor_po')
                            ->label('Nomor PO')
                            ->unique(ignoreRecord: true)
                            ->required(),
                    ])
            ]);
    }

    private static function select()
    {
        return Select::make('spk_service_id')
            ->label('Nomor SPK Service')
            ->options(function () {
                return Cache::rememberForever(SPKService::$CACHE_KEYS['beritaAcara'], function () {
                    return
                        SPKService::whereHas('permintaanSparepart', function ($query) {
                            $query->where('status_penyerahan', 'Diserahkan');
                        })
                        ->whereDoesntHave('beritaAcara')
                        ->where(function ($query) {
                            $query->whereHas('walkinChamber')
                                ->orWhereHas('chamberR2')
                                ->orWhereHas('refrigerator')
                                ->orWhereHas('coldRoom')
                                ->orWhereHas('rissing')
                                ->orWhereHas('walkinG2')
                                ->orWhereHas('chamberG2')
                                ->orWhereHas('service');
                        })
                        ->get()
                        ->pluck('no_spk_service', 'id');
                });
            })
            ->native(false)
            ->searchable()
            ->preload()
            ->required()
            ->reactive()
            ->columnSpanFull()
            ->hiddenOn(operations: 'edit')
            ->afterStateUpdated(function ($state, callable $set) {
                if (!$state)
                    return;

                $service = SPKService::with('petugas', 'complain')->find($state);
                // $complain = Complain::with('spkService')->find($state);
                if (!$service)
                    return;

                $namaPetugas = $service->petugas->pluck('nama_teknisi')->toArray();
                $nama_teknisi = implode(', ', $namaPetugas);
                $namaComplain = $service->complain->name_complain;
                $companyName = $service->complain->company_name;
                $alamat = $service->complain->spkService->alamat;
                $department = $service->complain->department;

                $set('detail.nama_teknisi', $nama_teknisi);
                $set('pelanggan.nama', $namaComplain);
                $set('pelanggan.perusahaan', $companyName);
                $set('pelanggan.alamat', $alamat);
                $set('pelanggan.jabatan', $department);
            });
    }
}
