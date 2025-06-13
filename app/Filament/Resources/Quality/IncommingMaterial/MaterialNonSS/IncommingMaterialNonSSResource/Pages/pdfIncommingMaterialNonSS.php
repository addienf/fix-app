<?php

namespace App\Filament\Resources\Quality\IncommingMaterial\MaterialNonSS\IncommingMaterialNonSSResource\Pages;

use App\Filament\Resources\Quality\IncommingMaterial\MaterialNonSS\IncommingMaterialNonSSResource;
use Filament\Resources\Pages\Page;

class pdfIncommingMaterialNonSS extends Page
{
    protected static string $resource = IncommingMaterialNonSSResource::class;

    protected static string $view = 'filament.resources.quality.incomming-material.material-non-s-s.incomming-material-non-s-s-resource.pages.pdf-incomming-material-non-s-s';
}
