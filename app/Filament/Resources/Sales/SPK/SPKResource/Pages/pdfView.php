<?php

namespace App\Filament\Resources\Sales\SPK\SPKResource\Pages;

use App\Filament\Resources\Sales\SPK\SPKResource;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use Filament\Resources\Pages\Page;

class pdfView extends Page
{
    protected static string $resource = SPKResource::class;

    protected static string $view = 'filament.resources.sales.s-p-k.s-p-k-resource.pages.pdfSPK';

    protected static ?string $title = 'Spesifikasi Produk PDF';
    public $record;
    public $spk_mkt;

    public function mount($record)
    {
        $this->record = $record;
        $this->spk_mkt = SPKMarketing::with(['spesifikasiProduct', 'pic'])->find($record);
    }
}
