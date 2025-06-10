<?php

namespace App\Filament\Resources\Quality\Defect\DefectStatusResource\Pages;

use App\Filament\Resources\Quality\Defect\DefectStatusResource;
use App\Models\Quality\Defect\DefectStatus;
use Filament\Resources\Pages\Page;

class pdfDefectStatus extends Page
{
    protected static string $resource = DefectStatusResource::class;

    protected static string $view = 'filament.resources.quality.defect.defect-status-resource.pages.pdf-defect-status';

    protected static ?string $title = 'Defect Status';

    public $record;

    public $defect;

    public function mount($record)
    {
        $this->record = $record;
        $this->defect = DefectStatus::with(['spk', 'defectable', 'pic', 'detail'])->find($record);
    }
}
