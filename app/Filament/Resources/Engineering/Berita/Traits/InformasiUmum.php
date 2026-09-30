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
                    'lg' => $isEdit ? 3 : 2,
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
        return
            Select::make('spk_service_id')
            ->label('Nomor SPK Service')
            ->options(function () {
                return
                    SPKService::where('status', 'Selesai')
                    ->where('lama_pelaksanaan', '>', 0)
                    ->limit(10)
                    ->pluck('no_spk_service', 'id');
            })
            ->getSearchResultsUsing(function (string $search) {
                return SPKService::whereHas('permintaanSparepart', function ($query) {
                    $query->where('status', 'Selesai');
                })
                    ->where('lama_pelaksanaan', '>', 0)
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
    }
}
