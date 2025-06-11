<?php

namespace App\Filament\Resources\Quality\PengecekanMaterial\Electrical\PengecekanElectricalResource\Pages;

use App\Filament\Resources\Quality\PengecekanMaterial\Electrical\PengecekanElectricalResource;
use App\Models\Quality\PengecekanMaterial\Electrical\PengecekanMaterialElectrical;
use Filament\Resources\Pages\Page;

class pdfPengecekanElectrical extends Page
{
    protected static string $resource = PengecekanElectricalResource::class;

    protected static string $view = 'filament.resources.quality.pengecekan-material.electrical.pengecekan-electrical-resource.pages.pdf-pengecekan-electrical';

    protected static ?string $title = 'Pengecekan Material Electrical';

    public $record;

    public $electrical;

    public function mount($record)
    {
        $this->record = $record;
        $this->electrical = PengecekanMaterialElectrical::with(['spk', 'pic', 'detail'])->find($record);
    }
}