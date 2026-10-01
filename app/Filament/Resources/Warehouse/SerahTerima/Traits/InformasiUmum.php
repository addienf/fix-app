<?php

namespace App\Filament\Resources\Warehouse\SerahTerima\Traits;

use App\Models\Engineering\Permintaan\PermintaanSparepart;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Wallo\FilamentSelectify\Components\ButtonGroup;

trait InformasiUmum
{
    use SimpleFormResource, HasAutoNumber;
    protected static function informasiUmumSection(Form $form): Section
    {
        $isEdit = $form->getOperation() === 'edit';

        return Section::make('Informasi Umum')
            ->collapsible()
            ->schema([

                Grid::make([
                    'default' => 1,
                    'md' => $isEdit ? 3 : 2,
                    'lg' => $isEdit ? 3 : 2,
                ])
                    ->schema([
                        static::getJenisSerahTerima()->label('Jenis Serah Terima'),

                        static::select()
                            ->hiddenOn('edit')
                            ->hidden(fn(Get $get) => $get('jenis_serahterima') !== 'Engineer'),

                        static::newAutoNmberField('no_surat', 'No.', [
                            'prefix' => 'QKS',
                            'section' => 'WBB',
                            'type' => 'STBS',
                            'table' => 'serah_terima_bahans',
                        ])->hiddenOn('edit'),

                        static::dateInput('tanggal', 'Tanggal')
                            ->required(),

                        static::textInput('dari', 'Dari')
                            ->default('Warehouse'),

                        static::textInput('kepada', 'Kepada'),
                    ])
            ]);
    }

    protected static function select(): Select
    {
        return
            Select::make('permintaan_sparepart_id')
            ->label('Nomor Permintaan Spareparts')
            ->placeholder('Pilih Nomor Permintaan Spareparts')
            ->searchable()
            ->columnSpanFull()
            ->required(fn(Get $get) => $get('jenis_serahterima') === 'Engineer')
            ->live()
            ->options(
                fn() => static::permintaanQuery()
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(fn($s) => static::permintaanOption($s))
            )
            ->getSearchResultsUsing(
                fn(string $search) => static::permintaanQuery()
                    ->where(
                        fn($q) => $q
                            ->where('no_surat', 'like', "%{$search}%")
                            ->orWhereHas(
                                'spkService',
                                fn($sq) =>
                                $sq->where('perusahaan', 'like', "%{$search}%")
                            )
                    )
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(fn($s) => static::permintaanOption($s))
            )
            ->getOptionLabelUsing(function ($value) {
                $s = PermintaanSparepart::with('spkService')->find($value);
                return $s ? static::permintaanLabel($s) : $value;
            })
            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                $currentDetails = $get('details') ?? [];

                if (! $state) {
                    $set('details', static::getEmptyDetails($currentDetails));
                    return;
                }
                $record = PermintaanSparepart::with('details')->find($state);
                $set(
                    'details',
                    $record && $record->details->isNotEmpty()
                        ? $record->details->map(fn($d) => [
                            'bahan_baku'       => $d->bahan_baku ?? '',
                            'spesifikasi'      => $d->spesifikasi ?? '',
                            'jumlah'           => $d->jumlah ?? 0,
                            'keperluan_barang' => $d->keperluan_barang ?? '',
                        ])->values()->toArray()
                        : static::getEmptyDetails($currentDetails)
                );
            });
    }

    protected static function permintaanQuery(): Builder
    {
        return
            PermintaanSparepart::with('spkService')
            ->whereDate('tanggal', '>', '2026-09-30')
            ->whereDoesntHave('serahTerima')
            ->where('status_penyerahan', 'Diserahkan')
            ->latest();
    }

    protected static function permintaanLabel(PermintaanSparepart $sparepart): string
    {
        return sprintf(
            '%s - %s - %s',
            $sparepart->no_surat ?? '-',
            $sparepart->tanggal ?? '-',
            $sparepart->spkService?->perusahaan ?? '-'
        );
    }

    protected static function permintaanOption(PermintaanSparepart $sparepart): array
    {
        return [$sparepart->id => static::permintaanLabel($sparepart)];
    }

    protected static function getJenisSerahTerima()
    {
        return
            ButtonGroup::make('jenis_serahterima')
            ->options([
                'Produksi' => 'Produksi',
                'Engineer' => 'Engineer',
            ])
            ->live()
            ->columnSpanFull()
            ->onColor('primary')
            ->offColor('gray')
            ->gridDirection('row')
            ->disabledOn('edit')
            ->afterStateUpdated(function ($state, Get $get, Set $set) {
                if ($state !== 'Engineer') {
                    $set('permintaan_sparepart_id', null);
                }

                $currentDetails = $get('details') ?? [];
                $set('details', static::getEmptyDetails($currentDetails));
            });
    }

    protected static function getEmptyDetails(array $currentDetails = []): array
    {
        if (empty($currentDetails)) {
            return [[
                'bahan_baku'       => '',
                'spesifikasi'      => '',
                'jumlah'           => null,
                'keperluan_barang' => '',
            ]];
        }
        return array_map(fn($row) => [
            'bahan_baku'       => '',
            'spesifikasi'      => '',
            'jumlah'           => null,
            'keperluan_barang' => '',
        ], $currentDetails);
    }
}
