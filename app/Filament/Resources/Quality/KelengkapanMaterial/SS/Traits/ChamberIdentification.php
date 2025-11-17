<?php

namespace App\Filament\Resources\Quality\KelengkapanMaterial\SS\Traits;

use App\Models\Sales\SPKMarketings\SPKMarketing;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Cache;

trait ChamberIdentification
{
    protected static function getChamberIdentificationSection($form): Section
    {
        $isEdit = $form->getOperation() === 'edit';

        return Section::make('Chamber Identification')
            ->collapsible()
            ->schema([
                //

                self::getSelectedSPK()
                    ->hiddenOn('edit'),

                self::textInput('tipe', 'Type/Model')
                    ->extraAttributes([
                        'readonly' => true,
                        'style' => 'pointer-events: none;'
                    ]),

                self::textInput('ref_document', 'Ref Document'),

                self::textInput('no_order_temp', 'No Order')
                    ->columnSpanFull()
                    ->hiddenOn('edit')
                    ->extraAttributes([
                        'readonly' => true,
                        'style' => 'pointer-events: none;'
                    ]),

            ])->columns($isEdit ? 2 : 3);
    }

    private static function getSelectedSPK()
    {
        return Select::make('spk_marketing_id')
            ->label('Nomor SPK')
            ->relationship(
                'spk',
                'no_spk',
                fn($query) => $query->whereIn('id', Cache::rememberForever(
                    SPKMarketing::$CACHE_KEYS['kelengkapanSS'],
                    fn() => SPKMarketing::whereHas('standarisasi', function ($query) {
                        $query->where('status_pemeriksaan', 'Diperiksa');
                    })
                        ->whereDoesntHave('kelengkapanSS')
                        ->pluck('id')
                        ->toArray()
                ))
            )
            ->native(false)
            ->searchable()
            ->placeholder('Pilin No SPK')
            ->preload()
            ->required()
            ->reactive()
            ->afterStateUpdated(function ($state, callable $set) {
                if (!$state) return;

                $spk = SPKMarketing::with('jadwalProduksi.identifikasiProduks', 'kelengkapanSS')->find($state);
                if (!$spk) return;

                $no_order = $spk->no_order ?? '-';
                $tipe = $spk->jadwalProduksi->identifikasiProduks->first()?->tipe ?? '-';

                $set('no_order_temp', $no_order);
                $set('tipe', $tipe);

                $produkList = $spk->jadwalProduksi->identifikasiProduks->map(function ($produk) {
                    return [
                        'nama_alat' => $produk->nama_alat,
                        'no_seri'   => $produk->no_seri,
                        'details'   => collect(config('kelengkapanSS.parts'))
                            ->map(fn($part) => [
                                'part' => $part,
                                'select' => null,
                                'result' => null,
                            ])
                            ->toArray(),
                    ];
                })->toArray();

                $set('details', $produkList);
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
