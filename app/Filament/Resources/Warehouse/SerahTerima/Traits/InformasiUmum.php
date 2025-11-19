<?php

namespace App\Filament\Resources\Warehouse\SerahTerima\Traits;

use App\Models\Production\PermintaanBahanProduksi\PermintaanAlatDanBahan;
use App\Models\Warehouse\Peminjaman\PeminjamanAlat;
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
    protected static function informasiUmumSection($form): Section
    {
        $isEdit = $form->getOperation() === 'edit';

        return Section::make('Informasi Umum')
            ->collapsible()
            ->schema([

                Grid::make($isEdit ? 3 : 2)
                    ->schema([
                        self::select()
                            ->columnSpanFull()
                            ->hiddenOn('edit'),

                        self::autoNumberField2('no_surat', 'No Surat', [
                            'prefix' => 'QKS',
                            'section' => 'WBB',
                            'type' => 'SERAHTERIMA',
                            'table' => 'serah_terima_bahans',
                        ])->hiddenOn('edit'),

                        self::dateInput('tanggal', 'Tanggal')
                            ->required(),

                        self::textInput('dari', 'Dari')
                            ->placeholder('Warehouse'),

                        self::textInput('kepada', 'Kepada'),
                    ])
            ]);
    }

    private static function select(): Select
    {
        return
            Select::make('peminjaman_alat_id')
            ->label('Nomor Surat / No Seri')
            ->placeholder('Pilih Nomor Peminjaman')
            ->searchable()
            ->native(false)
            ->preload()
            ->required()
            ->reactive()
            ->options(function () {
                return PeminjamanAlat::with([
                    'spkVendor.permintaanBahanProduksi.jadwalProduksi.spk',
                    'spkVendor.permintaanBahanProduksi.jadwalProduksi.identifikasiProduks'
                ])
                    ->whereDoesntHave('serahTerimaBahan')
                    ->latest()
                    ->get()
                    ->mapWithKeys(function ($pinjam) {

                        $jadwal = $pinjam->spkVendor->permintaanBahanProduksi->jadwalProduksi;

                        $spkNo = $jadwal->spk->no_spk ?? '-';
                        $noSeri = $jadwal->identifikasiProduks
                            ->pluck('no_seri')
                            ->filter()
                            ->implode(', ') ?: '-';

                        return [
                            $pinjam->id => "{$spkNo} - {$noSeri}",
                        ];
                    });
            })
            ->afterStateUpdated(function ($state, callable $set) {
                if (!$state) return;

                $pinjam = PeminjamanAlat::with('spkVendor.permintaanBahanProduksi.details')->find($state);
                if (!$pinjam) return;

                $details = $pinjam->spkVendor->permintaanBahanProduksi->details
                    ->map(fn($d) => [
                        'bahan_baku' => $d->bahan_baku ?? '',
                        'spesifikasi' => $d->spesifikasi ?? '',
                        'jumlah' => $d->jumlah ?? 0,
                        'keperluan_barang' => $d->keperluan_barang ?? '',
                    ])
                    ->toArray();

                $set('details', $details);
            });
    }
}
