<?php

namespace App\Filament\Resources\Warehouse\Incomming\Traits;

use App\Models\Purchasing\Permintaan\PermintaanPembelian;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Cache;

trait InformasiUmum
{
    use SimpleFormResource, HasAutoNumber;
    protected static function informasiUmumSection(): Section
    {
        return Section::make('Informasi Umum')
            ->collapsible()
            ->schema([

                self::select()
                    ->placeholder('Pilih Nomor Surat Permintaan Bahan')
                    ->hiddenOn('edit')
                    ->required(),

                self::dateInput('tanggal', 'Tanggal Penerimaan')
                    ->required(),

            ]);
    }

    protected static function select(): Select
    {
        return
            Select::make('permintaan_pembelian_id')
            ->label('Permintaan Pembelian')
            ->searchable()
            ->options(function () {
                return PermintaanPembelian::query()
                    ->with('permintaanBahanWBB')
                    ->whereDoesntHave('incommingMaterial')
                    ->orderBy('id', 'desc')
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(function ($item) {
                        $noSurat = $item->permintaanBahanWBB->no_surat ?? '-';
                        return [$item->id => "{$item->id} - {$noSurat}"];
                    });
            })
            ->getSearchResultsUsing(function (string $search) {
                return PermintaanPembelian::query()
                    ->with('permintaanBahanWBB')
                    ->whereDoesntHave('incommingMaterial')
                    ->where(function ($q) use ($search) {
                        $q->where('id', 'like', "%{$search}%")
                            ->orWhereHas(
                                'permintaanBahanWBB',
                                fn($wbb) =>
                                $wbb->where('no_surat', 'like', "%{$search}%")
                            );
                    })
                    ->orderBy('id', 'desc')
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(function ($item) {
                        $noSurat = $item->permintaanBahanWBB->no_surat ?? '-';
                        return [$item->id => "{$item->id} - {$noSurat}"];
                    });
            })
            ->native(false)
            ->searchable()
            ->preload()
            ->required()
            ->reactive()
            ->afterStateUpdated(function ($state, callable $set) {
                if (!$state) return;

                $pembelian = PermintaanPembelian::with(
                    'permintaanBahanWBB',
                    'details',
                    'materialSS',
                    'materialNonSS'
                )->find($state);

                if (!$pembelian) return;

                $no_batch = $pembelian?->materialNonSS?->batch_no;

                $details = $pembelian->details->map(function ($detail) use ($no_batch) {
                    return [
                        'nama_material' => $detail->nama_barang ?? '-',
                        'jumlah' => $detail->jumlah ?? '-',
                        'batch_no' => $no_batch ?? '-',
                    ];
                })->toArray();

                $set('details', $details);
            });
    }
}
