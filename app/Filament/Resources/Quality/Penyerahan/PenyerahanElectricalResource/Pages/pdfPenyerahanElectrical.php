<?php

namespace App\Filament\Resources\Quality\Penyerahan\PenyerahanElectricalResource\Pages;

use App\Filament\Resources\Quality\Penyerahan\PenyerahanElectricalResource;
use Filament\Resources\Pages\Page;

class pdfPenyerahanElectrical extends Page
{
    protected static string $resource = PenyerahanElectricalResource::class;

    protected static string $view = 'filament.resources.quality.penyerahan.penyerahan-electrical-resource.pages.pdf-penyerahan-electrical';
}
