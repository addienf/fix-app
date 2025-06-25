<?php

namespace App\Http\Controllers;

use App\Models\Production\Jadwal\JadwalProduksi;
use App\Models\Production\PermintaanBahanProduksi\PermintaanAlatDanBahan;
use App\Models\Sales\SpesifikasiProducts\SpesifikasiProduct;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    //

    public function pdfSpesifikasiProduct()
    {
        return view('pdf.pdfSpecProduct');
    }
    public function previewSpesifikasiProduct($id)
    {
        $spesifikasi = SpesifikasiProduct::with(['urs.customer', 'pic', 'details.product'])->findOrFail($id);

        return view('pdf.sales.pdfSpecProduct', compact('spesifikasi'));
    }
    public function pdfSPKMarketing($id)
    {
        $spk_mkt = SPKMarketing::with(['spesifikasiProduct', 'pic'])->findOrFail($id);

        return view('pdf.sales.pdfSPKMarketing', compact('spk_mkt'));
    }

    public function pdfJadwalProduksi($id)
    {
        $jadwal = JadwalProduksi::with(['spk', 'details', 'pic', 'sumber'])->findOrFail($id);

        return view('pdf.production.pdfJadwalProduksi', compact('jadwal'));
    }

    public function pdfPermintaanAlatBahan($id)
    {
        $permintaan_alat_bahan = PermintaanAlatDanBahan::with(['spk', 'details', 'pic'])->findOrFail($id);

        return view('pdf.production.pdfPermintaanAlatBahan', compact('permintaan_alat_bahan'));
    }

    public function pdfPermintaanPembelian()
    {


        return view('pdf.purchasing.pdfPermintaanPembelian');
    }

    public function pdfIncomingMaterial()
    {
        return view('pdf.warehouse.pdfIncomingMaterial');
    }
    public function pdfPelabelanQCPassed()
    {
        return view('pdf.warehouse.pdfPelabelanQCPassed');
    }
    public function pdfPermintaanBahan()
    {
        return view('pdf.warehouse.pdfPermintaanBahan');
    }
    public function pdfSerahTerima()
    {
        return view('pdf.warehouse.pdfSerahTerima');
    }
    public function pdfPeminjamanAlat()
    {
        return view('pdf.warehouse.pdfPeminjamanAlat');
    }

    public function pdfPenyerahanElectrical()
    {
        return view('pdf.production.pdfPenyerahanElectrical');
    }
    public function pdfPenyerahanProdukJadi()
    {
        return view('pdf.production.pdfPenyerahanProdukJadi');
    }

    public function pdfSPKQuality()
    {
        return view('pdf.production.pdfSPKQuality');
    }
    public function pdfDefectStatus()
    {
        return view('pdf.quality.pdfDefectStatus');
    }
    public function pdfIncomingMaterialNonSS()
    {
        return view('pdf.quality.pdfIncomingMaterialNonSS');
    }
    public function pdfIncomingMaterialSS()
    {
        return view('pdf.quality.pdfIncomingMaterialSS');
    }
    public function pdfKelengkapanMaterialSS()
    {
        return view('pdf.quality.pdfKelengkapanMaterialSS');
    }
    public function pdfPengecekanPerforma()
    {
        return view('pdf.quality.pdfPengecekanPerforma');
    }
    public function pdfPengecekanElectrical()
    {
        return view('pdf.quality.pdfPengecekanElectrical');
    }
    public function pdfPengecekanMaterialSS()
    {
        return view('pdf.quality.pdfPengecekanMaterialSS');
    }
    public function pdfStandarisasiDrawing()
    {
        return view('pdf.quality.pdfStandarisasiDrawing');
    }
}
