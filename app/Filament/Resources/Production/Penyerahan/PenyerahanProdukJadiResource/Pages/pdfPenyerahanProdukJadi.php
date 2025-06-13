<?php

namespace App\Filament\Resources\Production\Penyerahan\PenyerahanProdukJadiResource\Pages;

use App\Filament\Resources\Production\Penyerahan\PenyerahanProdukJadiResource;
use Filament\Resources\Pages\Page;

class pdfPenyerahanProdukJadi extends Page
{
    protected static string $resource = PenyerahanProdukJadiResource::class;
    protected static ?string $title = 'PDF Penyerahan Produk Jadi';

    protected static string $view = 'filament.resources.production.penyerahan.penyerahan-produk-jadi-resource.pages.pdf-penyerahan-produk-jadi';
}