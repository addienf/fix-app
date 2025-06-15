<?php

namespace App\Filament\Resources\Sales\SpesifikasiProducts\SpesifikasiProductResource\Pages;

use App\Filament\Resources\Sales\SpesifikasiProducts\SpesifikasiProductResource;
use App\Models\Sales\SpesifikasiProducts\SpesifikasiProduct;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;

class viewPDF extends Page
{
    protected static string $resource = SpesifikasiProductResource::class;

    protected static string $view = 'filament.resources.sales.spesifikasi-products.spesifikasi-product-resource.pages.pdfSpecProduct';

    protected static ?string $title = 'Spesifikasi Produk PDF';

    public $record;

    public $spesifikasi;

    public function mount($record)
    {
        $this->record = $record;
        $this->spesifikasi = SpesifikasiProduct::with(['urs', 'pic', 'details'])->find($record);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print')
                ->label('Download')
                ->icon('heroicon-o-printer')
                ->color('info')
                ->requiresConfirmation()
                ->url(route('pdf.specProduct', ['id' => $this->record]))
        ];
    }

    public function getBreadcrumb(): string
    {
        return 'Lihat PDF';
    }
}
