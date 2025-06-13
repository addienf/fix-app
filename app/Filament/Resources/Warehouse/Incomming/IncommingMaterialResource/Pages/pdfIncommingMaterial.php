<?php

namespace App\Filament\Resources\Warehouse\Incomming\IncommingMaterialResource\Pages;

use App\Filament\Resources\Warehouse\Incomming\IncommingMaterialResource;
use Filament\Resources\Pages\Page;

class pdfIncommingMaterial extends Page
{
    protected static string $resource = IncommingMaterialResource::class;
    protected static ?string $title = 'PDF Incomming Material';
    protected static string $view = 'filament.resources.warehouse.incomming.incomming-material-resource.pages.pdf-incomming-material';
}