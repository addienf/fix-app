<?php

namespace App\Filament\Resources\Warehouse\Pelabelan\QCPassedResource\Pages;

use App\Filament\Resources\Warehouse\Pelabelan\QCPassedResource;
use App\Models\Warehouse\Pelabelan\QCPassed;
use Filament\Resources\Pages\Page;

class pdfPelabelanQCPassed extends Page
{
    protected static string $resource = QCPassedResource::class;

    protected static string $view = 'filament.resources.warehouse.pelabelan.q-c-passed-resource.pages.pdf-pelabelan-q-c-passed';

    protected static ?string $title = 'Pelabelan QC Passed';

    public $record;

    public $pelabelan;

    public function mount($record)
    {
        $this->record = $record;
        $this->pelabelan = QCPassed::with(['spk', 'pic', 'details'])->find($record);
    }

    public function getBreadcrumb(): string
    {
        return 'Lihat PDF';
    }
}
