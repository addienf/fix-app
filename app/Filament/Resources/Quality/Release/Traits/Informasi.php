<?php

namespace App\Filament\Resources\Quality\Release\Traits;

use App\Models\Quality\Pengecekan\PengecekanPerforma;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;

trait Informasi
{
    use SimpleFormResource, HasAutoNumber;
    protected static function getInformasiSection()
    {
        return
            Section::make('Informasi Umum')
            ->schema([
                self::getSelect()
                    ->hiddenOn('edit')
                    ->placeholder('Pilih Nomor SPK'),

                self::autoNumberField2('no_order_release', 'Release Order No', [
                    'prefix' => 'QKS',
                    'section' => 'QA',
                    'type' => 'PR',
                    'table' => 'product_releases',
                ])
                    ->hiddenOn('edit'),

                self::textInput('product', 'The Product'),

                self::textInput('batch', 'Batch No'),

                self::textareaInput('remarks', 'Remark')->columnSpanFull(),
            ])
            ->columns([
                'default' => 1,
                'md' => 2,
                'lg' => 2,
            ])
            ->collapsible();
    }

    private static function getSelect()
    {
        return
            Select::make('pengecekan_performa_id')
            ->label('Nomor SPK / No Seri')
            ->placeholder('Pilih Serial Number')
            ->searchable()
            ->native(false)
            ->preload()
            ->reactive()
            ->required()
            ->options(
                PengecekanPerforma::with([
                    'spkQC:id,spk_marketing_id',
                    'spkQC.spkMarketing:id,no_spk'
                ])
                    ->latest()
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(fn($item) => [
                        $item->id => $item->spkQC?->spkMarketing?->no_spk
                    ])
            )
            ->getSearchResultsUsing(function (string $search) {
                return PengecekanPerforma::with([
                    'spkQC:id,spk_marketing_id',
                    'spkQC.spkMarketing:id,no_spk'
                ])
                    ->whereHas('spkQC.spkMarketing', function ($q) use ($search) {
                        $q->where('no_spk', 'like', "%{$search}%");
                    })
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(fn($item) => [
                        $item->id => $item->spkQC?->spkMarketing?->no_spk
                    ]);
            })
            ->afterStateUpdated(function ($state, callable $set) {
                if (!$state) return;

                $pengecekan = PengecekanPerforma::with('spkQC.spkMarketing')->find($state);

                $product_name = $pengecekan?->spkQC?->spkMarketing?->spesifikasiProduct?->details?->first()?->product?->name ?? '-';

                $set('product', $product_name);
            });
    }
}
