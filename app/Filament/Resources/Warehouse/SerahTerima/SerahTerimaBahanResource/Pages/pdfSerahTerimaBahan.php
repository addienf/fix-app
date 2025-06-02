<?php

namespace App\Filament\Resources\Warehouse\SerahTerima\SerahTerimaBahanResource\Pages;

use App\Filament\Resources\Warehouse\SerahTerima\SerahTerimaBahanResource;
use App\Models\Warehouse\SerahTerima\SerahTerimaBahan;
use Filament\Resources\Pages\Page;

class pdfSerahTerimaBahan extends Page
{
    protected static string $resource = SerahTerimaBahanResource::class;

    protected static string $view = 'filament.resources.warehouse.serah-terima.serah-terima-bahan-resource.pages.pdf-serah-terima-bahan';

    protected static ?string $title = 'Serah Terima Bahan';

    public $record;

    public $serah_terima;

    public function mount($record)
    {
        $this->record = $record;
        $this->serah_terima = SerahTerimaBahan::with(['permintaanBahanPro', 'details', 'pic'])->find($record);
    }
}
