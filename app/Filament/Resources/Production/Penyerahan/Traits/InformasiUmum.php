<?php

namespace App\Filament\Resources\Production\Penyerahan\Traits;

use App\Models\Production\Jadwal\JadwalProduksi;
use App\Models\Production\SPK\SPKQuality;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;

trait InformasiUmum
{
    use SimpleFormResource, HasAutoNumber;
    protected static function getInformasiUmumSection($form): Section
    {
        $isEdit = $form->getOperation() === 'edit';

        return
            Section::make('Informasi Umum')
            ->collapsible()
            ->schema([

                Grid::make([
                    'default' => 1,
                    'md' => $isEdit ? 3 : 2,
                    'lg' => $isEdit ? 3 : 2,
                ])
                    ->schema([

                        self::getSelect()
                            ->hiddenOn('edit'),

                        self::dateInput('tanggal', 'Tanggal'),

                        self::textInput('penanggug_jawab', 'Penanggung Jawab'),

                        self::textInput('penerima', 'Penerima'),

                    ]),

            ]);
    }

    private static function getKondisiProduk()
    {
        return
            Section::make('Kondisi Produk')
            ->collapsible()
            ->schema([

                self::selectKondisi()
                    ->placeholder('Pilih Kondisi')
                    ->columnSpanFull()

            ]);
    }

    private static function getCatatanPenting()
    {
        return
            Section::make('Catatan Tambahan')
            ->collapsible()
            ->schema([

                self::textareaInput('catatan_tambahan', 'Catatan Tambahan')
                    ->rows(1)

            ]);
    }

    private static function getSelect(): Select
    {
        return
            Select::make('spk_qualities_id')
            ->label('No SPK QC/ Nomor Seri')
            ->placeholder('Pilih No SPK QC/ Nomor Seri')
            ->required()
            ->searchable()
            ->reactive()
            ->options(function () {
                return SPKQuality::with(['spkMarketing' => function ($query) {
                    $query->with('jadwalProduksi');
                }])
                    ->whereDoesntHave('penyerahanProdukJadi')
                    ->where('status_penerimaan', 'Diterima')
                    ->latest()
                    ->limit(10)
                    ->get()
                    ->flatMap(function ($item) {

                        $jadwalList = JadwalProduksi::where('spk_marketing_id', $item->spk_marketing_id)->get();

                        return $jadwalList->map(fn($jadwal) => [
                            'id' => $item->id,
                            'label' => "{$item->spkMarketing->no_spk} - {$jadwal->no_surat}",
                        ]);
                    })
                    ->unique('label')
                    ->mapWithKeys(fn($row) => [
                        $row['id'] => $row['label']
                    ]);
            })
            ->getSearchResultsUsing(function ($search) {
                return SPKQuality::with('spkMarketing.jadwalProduksi')
                    ->whereDoesntHave('penyerahanProdukJadi')
                    ->where('status_penerimaan', 'Diterima')
                    ->whereHas(
                        'spkMarketing.jadwalProduksi',
                        fn($q) => $q->where('no_surat', 'like', "%{$search}%")
                    )
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [
                            $item->id => $item->spkMarketing?->jadwalProduksi?->no_surat
                        ];
                    });
            })
            ->afterStateUpdated(function ($state, callable $set) {

                if (!$state) return;

                $spk =
                    SPKQuality::with('spkMarketing.spesifikasiProduct')->find($state);

                if (!$spk) {
                    $set('details', []);
                    return;
                }

                $nama_produk = $spk?->spkMarketing?->spesifikasiProduct?->details?->first()?->product?->name;
                $jumlah = $spk?->spkMarketing?->spesifikasiProduct?->details?->first()?->quantity;
                $no_spk = $spk?->spkMarketing?->no_spk;

                $set('details', [
                    [
                        'nama_produk' => $nama_produk ?? '-',
                        'jumlah'      => $jumlah ?? '-',
                        'no_spk'      => $no_spk ?? '-',
                    ]
                ]);
            });
    }

    protected static function selectKondisi(): Select
    {
        return
            Select::make('kondisi_produk')
            ->label('Kondisi Produk')
            ->required()
            ->placeholder('Pilih Kondisi Produk')
            ->options([
                'baik' => 'Baik',
                'rusak' => 'Rusak',
                'perlu_perbaikan' => 'Perlu Perbaikan'
            ]);
    }
}
