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
use Wallo\FilamentSelectify\Components\ButtonGroup;

trait InformasiUmum
{
    use SimpleFormResource;
    public static function getInformasiUmumSection($form)
    {
        $lastValue = BeritaAcara::latest('no_surat')->value('no_surat');
        $isEdit = $form->getOperation() === 'edit';

        return Section::make('Informasi Umum')
            ->collapsible()
            ->schema([
                self::select(),
                // Grid::make(2)
                Grid::make([
                    'default' => 1,
                    'md' => $isEdit ? 3 : 2,
                    'lg' => 2,
                ])
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
                // return SPKService::whereHas('permintaanSparepart', function ($query) {
                //     $query->where('status', 'Selesai');
                // })
                return SPKService::whereDoesntHave('beritaAcara')
                    // ->where('jenis_spk', 'Service')
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
                    ->limit(10)
                    ->pluck('no_spk_service', 'id');
            })
            ->getSearchResultsUsing(function (string $search) {
                return SPKService::whereHas('permintaanSparepart', function ($query) {
                    $query->where('status', 'Selesai');
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
                    ->where('no_spk_service', 'like', "%{$search}%")
                    ->limit(10)
                    ->pluck('no_spk_service', 'id');
            })
            ->native(false)
            ->searchable()
            ->preload()
            ->required()
            ->reactive()
            ->columnSpanFull()
            ->hiddenOn(operations: 'edit');
        // ->afterStateUpdated(function ($state, callable $set) {
        //     if (!$state)
        //         return;

        //     $service = SPKService::with('petugas', 'pelayananPelanggan')->find($state);

        //     if (!$service)
        //         return;

        //     $namaPetugas = $service->petugas->pluck('nama_teknisi')->toArray();
        //     $nama_teknisi = implode(', ', $namaPetugas);
        //     $namaComplain = $service->pelayananPelanggan->complain->name_complain;
        //     $companyName = $service->pelayananPelanggan->complain->companies->first()?->name;
        //     $alamat = $service->pelayananPelanggan->alamat;
        //     $department = $service->pelayananPelanggan->complain->department;

        //     $detail = $service->pelayananPelanggan
        //         ?->details
        //         ?->first();

        //     $produk = $detail?->nama_alat ?? '-';
        //     $noSeri = $detail?->nomor_seri ?? '-';

        //     $set('detail.nama_teknisi', $nama_teknisi);
        //     $set('pelanggan.nama', $namaComplain);
        //     $set('pelanggan.perusahaan', $companyName);
        //     $set('pelanggan.alamat', $alamat);
        //     $set('pelanggan.jabatan', $department);

        //     $set('detail.nama_teknisi', $nama_teknisi);
        //     $set('detail.produk', $produk);
        //     $set('detail.serial_number', $noSeri);
        // });
    }
}
