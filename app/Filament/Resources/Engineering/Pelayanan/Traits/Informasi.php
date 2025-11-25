<?php

namespace App\Filament\Resources\Engineering\Pelayanan\Traits;

use App\Models\Engineering\Complain\Complain;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

trait Informasi
{
    use SimpleFormResource;
    public static function getInformasiSection()
    {

        return Section::make('Informasi Umum')
            ->collapsible()
            ->schema([
                Grid::make()
                    ->schema([

                        self::select()
                            ->hiddenOn('edit')
                            ->columnSpanFull(),

                        self::textInput('no_form', 'Form No'),

                        self::dateInput('tanggal', 'Tanggal'),

                        self::textInput('alamat', 'Alamat'),

                        self::textInput('perusahaan', 'Perusahaan'),
                    ]),
            ]);
    }

    private static function select(): Select
    {
        return
            Select::make('complain_id')
            ->label('Nomor Complaint Form')
            ->placeholder('Pilih Nomor Complaint Form')
            ->reactive()
            ->required()
            ->searchable()
            ->options(function () {
                return Complain::query()
                    ->whereDoesntHave('pelayananPelanggan')
                    ->latest()
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [
                            $item->id => ($item->form_no ?? '-') . ' - ' . ($item->name_complain ?? '-')
                        ];
                    });
            })
            ->getSearchResultsUsing(function (string $search) {
                return Complain::query()
                    ->whereDoesntHave('pelayananPelanggan')
                    ->when($search, function ($query) use ($search) {
                        $query->where('form_no', 'like', "%{$search}%")
                            ->orWhere('name_complain', 'like', "%{$search}%");
                    })
                    ->limit(10)
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [
                            $item->id => ($item->form_no ?? '-') . ' - ' . ($item->name_complain ?? '-')
                        ];
                    })
                    ->toArray();
            })
            ->afterStateUpdated(function ($state, callable $set) {
                if (!$state) return;

                $complain = Complain::find($state);

                if (!$complain) return;

                $companyName = $complain->company_name ?? '-';
                $no_form = $complain->form_no ?? '-';

                $details = $complain->details->map(function ($detail) {
                    return [
                        'nama_alat'     => $detail?->unit_name ?? '-',
                        'tipe' => $detail?->tipe_model ?? '-',
                        'deskripsi' => $detail?->deskripsi ?? '-'
                    ];
                })->toArray();

                // dd($details);
                $set('perusahaan', $companyName);
                $set('no_form', $no_form);
                $set('details', $details);
            });
    }

    protected static function getJenisPermintaan()
    {
        return
            Section::make('A. Jenis Permintaan')
            ->schema([
                Select::make('jenis_permintaan')
                    ->label('Jenis Permintaan')
                    ->multiple()
                    ->reactive()
                    ->options([
                        'pengiriman' => 'Pengiriman',
                        'perakitan' => 'Perakitan',
                        'kualifikasi' => 'Kualifikasi',
                        'service' => 'Service',
                        'maintenance' => 'Maintenance',
                        'kalibrasi' => 'Kalibrasi',
                        'lainnya' => 'Lainnya',
                    ]),

                self::textInput('jenis_permintaan_lainnya', 'Jenis Permintaan Lainnya')
                    ->visible(fn($get) => in_array('lainnya', (array) $get('jenis_permintaan')))
                    ->required(fn($get) => in_array('lainnya', (array) $get('jenis_permintaan'))),
            ]);
    }

    protected static function getPelaksanaan()
    {
        return
            Section::make('C. Pelaksanaan')
            ->schema([
                self::dateInput('tanggal_pelaksanaan', 'Tanggal Pelaksanaan'),

                self::textInput('tempat_pelaksanaan', 'Tempat Pelaksanaan'),

                self::textInput('no_kontak', 'PIC/No. Kontak/Dept')
            ]);
    }
}
