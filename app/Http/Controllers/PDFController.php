<?php

namespace App\Http\Controllers;

use App\Models\Sales\SpesifikasiProducts\SpesifikasiProduct;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    //

    // public function pdfSpesifikasiProduct($id)
    // {
    //     $spesifikasi = SpesifikasiProduct::with(['urs.customer', 'pic', 'details.product'])->findOrFail($id);

    //     $pdf = Pdf::loadView('pdf.pdfSpecProduct', compact('spesifikasi'));
    //     return $pdf->stream('spesifikasi-produk.pdf');
    // }
    public function pdfSpesifikasiProduct()
    {
        return view('pdf.pdfSpecProduct');
    }
    public function previewSpesifikasiProduct($id)
    {
        $spesifikasi = SpesifikasiProduct::with(['urs.customer', 'pic', 'details.product'])->findOrFail($id);

        return view('pdf.sales.pdfSpecProduct', compact('spesifikasi'));
    }
    public function pdfSPKMarketing()
    {
        return view('pdf.sales.pdfSPKMarketing');
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
    public function pdfJadwalProduksi()
    {
        return view('pdf.production.pdfJadwalProduksi');
    }
    public function pdfPenyerahanElectrical()
    {
        return view('pdf.production.pdfPenyerahanElectrical');
    }
    public function pdfPenyerahanProdukJadi()
    {
        return view('pdf.production.pdfPenyerahanProdukJadi');
    }
    public function pdfPermintaanAlatBahan()
    {
        return view('pdf.production.pdfPermintaanAlatBahan');
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