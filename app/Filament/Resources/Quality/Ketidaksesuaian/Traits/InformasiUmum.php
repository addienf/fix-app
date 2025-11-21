<?php

namespace App\Filament\Resources\Quality\Ketidaksesuaian\Traits;

use App\Models\Quality\Pengecekan\PengecekanPerforma;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Models\Warehouse\SerahTerima\SerahTerimaBahan;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Cache;

trait InformasiUmum
{
    use SimpleFormResource, HasAutoNumber;
    protected static function informasiUmumSection($form)
    {
        $isEdit = $form->getOperation() === 'edit';

        return
            Section::make('A. Informasi Umum')
            ->schema([
                self::getSelect()
                    ->hiddenOn('edit')
                    ->placeholder('Pilih Nomor SPK')
                    ->columnSpanFull(),
                self::dateInput('tanggal', 'Tanggal'),
                self::textInput('nama_perusahaan', 'Nama Perusahaan'),
                self::textInput('department', 'Department'),
                self::textInput('pelapor', 'Pelapor'),
            ])
            ->columns(2)
            ->collapsible();
    }

    private static function getSelect()
    {
        return
            Select::make('pengecekan_electrical_id')
            ->label('Nomor SPK / No Seri')
            ->placeholder('Pilih Serial Number')
            ->searchable()
            ->native(false)
            ->preload()
            ->reactive()
            ->required()
            ->options(
                fn() =>
                PengecekanPerforma::with([
                    // 'pengecekanElectrical.penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk',
                    // 'pengecekanElectrical.penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.identifikasiProduks',
                    'pengecekanElectrical',
                    'pengecekanElectrical',
                ])
                    ->latest()
                    ->limit(20)
                    ->get()
                    ->mapWithKeys(function ($item) {

                        $tes = $item->pengecekanElectrical;

                        dd($item);

                        // $jadwal = $item
                        //     ->pengecekanElectrical->penyerahanElectrical->pengecekanSS->kelengkapanMaterial->standarisasiDrawing
                        //     ->serahTerimaWarehouse->peminjamanAlat->spkVendor->permintaanBahanProduksi->jadwalProduksi;

                        // $spkNo = $jadwal->spk->no_spk ?? '-';

                        // $seri = $jadwal->identifikasiProduks
                        //     ->pluck('no_seri')
                        //     ->filter()
                        //     ->implode(', ') ?: '-';

                        // return [
                        //     $item->id => "{$spkNo} - {$seri}",
                        // ];
                    })
            );
        // ->afterStateUpdated(function ($state, callable $set) {
        //     if (!$state) return;

        //     $pengecekan = PengecekanPerforma::with([
        //         'penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk'
        //     ])->find($state);

        //     $model_pengecekan =
        //         $pengecekan?->penyerahanElectrical?->pengecekanSS?->kelengkapanMaterial?->standarisasiDrawing
        //         ?->serahTerimaWarehouse?->peminjamanAlat?->spkVendor?->permintaanBahanProduksi
        //         ?->jadwalProduksi ?? '-';

        //     $tipe = $model_pengecekan?->identifikasiProduks?->first()?->tipe ?? '-';

        //     $volume = $pengecekan?->volume;

        //     $no_seri = $model_pengecekan?->identifikasiProduks?->first()?->no_seri ?? '-';

        //     // dd($no_seri);

        //     $set('tipe', $tipe);
        //     $set('volume', $volume);
        //     $set('serial_number', $no_seri);
        // });
    }
}
