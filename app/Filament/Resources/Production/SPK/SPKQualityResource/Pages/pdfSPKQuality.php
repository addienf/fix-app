<?php

namespace App\Filament\Resources\Production\SPK\SPKQualityResource\Pages;

use App\Filament\Resources\Production\SPK\SPKQualityResource;
use Filament\Resources\Pages\Page;

class pdfSPKQuality extends Page
{
    protected static string $resource = SPKQualityResource::class;

    protected static string $view = 'filament.resources.production.s-p-k.s-p-k-quality-resource.pages.pdf-s-p-k-quality';
}
