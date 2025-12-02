<?php

namespace App\Filament\Resources\Production\Penyerahan\PenyerahanElectrical\PenyerahanElectricalResource\Pages;

use App\Filament\Resources\Production\Penyerahan\PenyerahanElectrical\PenyerahanElectricalResource;
use App\Models\Production\Penyerahan\PenyerahanElectrical\PenyerahanElectrical;
use Filament\Resources\Pages\Page;

class pdfPenyerahanElectrical extends Page
{
    protected static string $resource = PenyerahanElectricalResource::class;

    protected static string $view = 'filament.resources.production.penyerahan.penyerahan-electrical.penyerahan-electrical-resource.pages.pdf-penyerahan-electrical';

    protected static ?string $title = 'Serah Terima Electrical';

    public $record;

    public $serahElectrical;

    public function mount($record)
    {
        $this->record = $record;
        $this->serahElectrical = PenyerahanElectrical::with(['pengecekanSS', 'sebelumSerahTerima', 'pic', 'penerimaElectrical'])->find($record);
    }

    public function getBreadcrumb(): string
    {
        return 'Lihat PDF';
    }
}
