<?php

namespace App\Filament\Resources\Quality\IncommingMaterial\MaterialSS\IncommingMaterialSSResource\Pages;

use App\Filament\Resources\Quality\IncommingMaterial\MaterialSS\IncommingMaterialSSResource;
use Filament\Resources\Pages\Page;

class pdfIncommingMaterialSS extends Page
{
    protected static string $resource = IncommingMaterialSSResource::class;
    protected static ?string $title = 'Incoming Material SS';

    protected static string $view = 'filament.resources.quality.incomming-material.material-s-s.incomming-material-s-s-resource.pages.pdf-incomming-material-s-s';
}