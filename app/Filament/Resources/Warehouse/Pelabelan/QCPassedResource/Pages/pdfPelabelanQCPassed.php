<?php

namespace App\Filament\Resources\Warehouse\Pelabelan\QCPassedResource\Pages;

use App\Filament\Resources\Warehouse\Pelabelan\QCPassedResource;
use Filament\Resources\Pages\Page;

class pdfPelabelanQCPassed extends Page
{
    protected static string $resource = QCPassedResource::class;

    protected static string $view = 'filament.resources.warehouse.pelabelan.q-c-passed-resource.pages.pdf-pelabelan-q-c-passed';
}
