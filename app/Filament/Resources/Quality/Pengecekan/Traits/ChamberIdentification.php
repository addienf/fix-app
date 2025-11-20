<?php

namespace App\Filament\Resources\Quality\Pengecekan\Traits;

use App\Models\Quality\PengecekanMaterial\Electrical\PengecekanMaterialElectrical;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;

trait ChamberIdentification
{
    use SimpleFormResource;
    protected static function getChamberIdentificationSection($form): Section
    {
        $isEdit = $form->getOperation() === 'edit';

        return
            Section::make('Chamber Identification')
            ->schema([
                Grid::make($isEdit ? 3 : 2)
                    ->schema([

                        self::getSelect()
                            ->hiddenOn('edit')
                            ->placeholder('Pilin No SPK'),

                        self::textInput('tipe', 'Type/Model')
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),

                        self::textInput('volume', 'Volume')
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),

                        self::textInput('serial_number', 'S/N')
                            ->extraAttributes([
                                'readonly' => true,
                                'style' => 'pointer-events: none;'
                            ]),

                    ]),
            ]);
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
                PengecekanMaterialElectrical::with([
                    'penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk',
                    'penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.identifikasiProduks',
                ])
                    ->latest()
                    ->limit(20)
                    ->get()
                    ->mapWithKeys(function ($item) {

                        $jadwal = $item->penyerahanElectrical->pengecekanSS->kelengkapanMaterial->standarisasiDrawing
                            ->serahTerimaWarehouse->peminjamanAlat->spkVendor->permintaanBahanProduksi->jadwalProduksi;

                        $spkNo = $jadwal->spk->no_spk ?? '-';

                        $seri = $jadwal->identifikasiProduks
                            ->pluck('no_seri')
                            ->filter()
                            ->implode(', ') ?: '-';

                        return [
                            $item->id => "{$spkNo} - {$seri}",
                        ];
                    })
            )
            ->afterStateUpdated(function ($state, callable $set) {
                if (!$state) return;

                $pengecekan = PengecekanMaterialElectrical::with([
                    'penyerahanElectrical.pengecekanSS.kelengkapanMaterial.standarisasiDrawing.serahTerimaWarehouse.peminjamanAlat.spkVendor.permintaanBahanProduksi.jadwalProduksi.spk'
                ])->find($state);

                $model_pengecekan =
                    $pengecekan?->penyerahanElectrical?->pengecekanSS?->kelengkapanMaterial?->standarisasiDrawing
                    ?->serahTerimaWarehouse?->peminjamanAlat?->spkVendor?->permintaanBahanProduksi
                    ?->jadwalProduksi ?? '-';

                $tipe = $model_pengecekan?->identifikasiProduks?->first()?->tipe ?? '-';

                $volume = $pengecekan?->volume;

                $no_seri = $model_pengecekan?->identifikasiProduks?->first()?->no_seri ?? '-';

                // dd($no_seri);

                $set('tipe', $tipe);
                $set('volume', $volume);
                $set('serial_number', $no_seri);
            });
    }

    public static function getNote()
    {
        return Card::make('')
            ->schema([

                Textarea::make('note')
                    ->required()
                    ->label('Note')
                    ->columnSpanFull()

            ]);
    }
}
