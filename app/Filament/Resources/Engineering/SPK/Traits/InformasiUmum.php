<?php

namespace App\Filament\Resources\Engineering\SPK\Traits;

use App\Models\Engineering\Complain\Complain;
use App\Traits\HasAutoNumber;
use App\Traits\SimpleFormResource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Cache;

trait InformasiUmum
{
    use SimpleFormResource, HasAutoNumber;
    public static function getInformasiUmumSection($form)
    {
        $isEdit = $form->getOperation() === 'edit';

        return Section::make('Informasi Umum')
            ->collapsible()
            ->schema([
                self::select()
                    ->columnSpanFull()
                    ->hiddenOn('edit'),

                self::autoNumberField2('no_spk_service', 'Nomor SPK Service', [
                    'prefix' => 'QKS',
                    'section' => 'ENG',
                    'type' => 'SPK',
                    'table' => 'spk_services',
                ])
                    ->hiddenOn('edit'),

                self::dateInput('tanggal', 'Tanggal'),

                self::textInput('alamat', 'Alamat'),

                self::textInput('perusahaan', 'Nama Perusahaan'),

            ])->columns($isEdit ? 3 : 2);
    }

    protected static function select(): Select
    {
        return
            Select::make('complain_id')
            ->label('Nomor Complaint Form')
            ->placeholder('Pilih Nomor Complaint Form')
            ->reactive()
            ->required()
            ->options(function () {
                return Cache::rememberForever(Complain::$CACHE_KEYS['spkService'], function () {
                    return Complain::whereDoesntHave('spkService')
                        ->get()
                        ->mapWithKeys(function ($item) {
                            $noForm = $item->form_no ?? '-';
                            $customerName = $item->name_complain ?? '-';
                            return [$item->id => "{$noForm} - {$customerName}"];
                        });
                });
            })
            ->afterStateUpdated(function ($state, callable $set) {
                if (!$state) return;

                $complain = Complain::find($state);
                if (!$complain) return;

                $companyName = $complain->company_name ?? '-';

                $set('perusahaan', $companyName);
            });
    }
}
