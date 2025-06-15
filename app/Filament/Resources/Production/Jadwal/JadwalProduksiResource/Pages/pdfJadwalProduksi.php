<?php

namespace App\Filament\Resources\Production\Jadwal\JadwalProduksiResource\Pages;

use App\Filament\Resources\Production\Jadwal\JadwalProduksiResource;
use App\Models\Production\Jadwal\JadwalProduksi as JadwalJadwalProduksi;
use App\Models\Production\JadwalProduksi;
use Filament\Resources\Pages\Page;

class pdfJadwalProduksi extends Page
{
    protected static string $resource = JadwalProduksiResource::class;

    protected static string $view = 'filament.resources.production.jadwal.jadwal-produksi-resource.pages.pdf-jadwal-produksi';

    protected static ?string $title = 'Jadwal Produksi';

    public $record;

    public $jadwal;

    public function mount($record)
    {
        $this->record = $record;
        $this->jadwal = JadwalJadwalProduksi::with(['spk', 'details', 'pic', 'sumber'])->find($record);
    }

    public function getBreadcrumb(): string
    {
        return 'Lihat PDF';
    }
}
