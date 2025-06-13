<?php

namespace App\Filament\Resources\Production\Penyerahan\PenyerahanElectrical\PenyerahanElectricalResource\Pages;

use App\Filament\Resources\Production\Penyerahan\PenyerahanElectrical\PenyerahanElectricalResource;
use Filament\Resources\Pages\Page;

class pdfPenyerahanElectrical extends Page
{
    protected static string $resource = PenyerahanElectricalResource::class;

    protected static string $view = 'filament.resources.production.penyerahan.penyerahan-electrical.penyerahan-electrical-resource.pages.pdf-penyerahan-electrical';
}
