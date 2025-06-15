<?php

namespace App\Filament\Resources\Quality\Pengecekan\PengecekanPerformaResource\Pages;

use App\Filament\Resources\Quality\Pengecekan\PengecekanPerformaResource;
use App\Models\Quality\Pengecekan\PengecekanPerforma;
use Filament\Resources\Pages\Page;

class pdfPengecekanPerforma extends Page
{
    protected static string $resource = PengecekanPerformaResource::class;
    protected static ?string $title = 'PDF Pengecekan Performa';
    protected static string $view = 'filament.resources.quality.pengecekan.pengecekan-performa-resource.pages.pdf-pengecekan-performa';
    public $record;

    public $performa;

    public function mount($record)
    {
        $this->record = $record;
        $this->performa = PengecekanPerforma::with(['spk', 'pic', 'detail'])->find($record);
    }

    public function getBreadcrumb(): string
    {
        return 'Lihat PDF';
    }
}
