<?php

namespace App\Filament\Resources\Production\SPK\SPKQualityResource\Pages;

use App\Filament\Resources\Production\SPK\SPKQualityResource;
use App\Models\Production\SPK\SPKQuality;
use Filament\Resources\Pages\Page;

class pdfSPKQuality extends Page
{
    protected static string $resource = SPKQualityResource::class;
    protected static ?string $title = 'PDF SPK Quality';
    protected static string $view = 'filament.resources.production.s-p-k.s-p-k-quality-resource.pages.pdf-s-p-k-quality';
    public $record;
    public $spk_qc;

    public function mount($record)
    {
        $this->record = $record;
        $this->spk_qc = SPKQuality::with(['spk', 'details', 'pic'])->find($record);
    }

    public function getBreadcrumb(): string
    {
        return 'Lihat PDF';
    }
}
