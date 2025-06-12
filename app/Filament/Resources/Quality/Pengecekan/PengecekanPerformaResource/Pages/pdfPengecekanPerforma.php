<?php

namespace App\Filament\Resources\Quality\Pengecekan\PengecekanPerformaResource\Pages;

use App\Filament\Resources\Quality\Pengecekan\PengecekanPerformaResource;
use Filament\Resources\Pages\Page;

class pdfPengecekanPerforma extends Page
{
    protected static string $resource = PengecekanPerformaResource::class;

    protected static string $view = 'filament.resources.quality.pengecekan.pengecekan-performa-resource.pages.pdf-pengecekan-performa';
}
