<?php

namespace App\Http\Controllers;

use App\Models\Production\Jadwal\JadwalProduksi;
use App\Models\Production\PermintaanBahanProduksi\PermintaanAlatDanBahan;
use App\Models\Purchasing\Permintaan\PermintaanPembelian;
use App\Models\Quality\IncommingMaterial\MaterialNonSS\IncommingMaterialNonSS;
use App\Models\Quality\IncommingMaterial\MaterialSS\IncommingMaterialSS;
use App\Models\Quality\Standarisasi\StandarisasiDrawing;
use App\Models\Sales\SpesifikasiProducts\SpesifikasiProduct;
use App\Models\Sales\SPKMarketings\SPKMarketing;
use App\Models\Warehouse\Incomming\IncommingMaterial;
use App\Models\Warehouse\PermintaanBahanWBB\PermintaanBahan;
use App\Models\Warehouse\SerahTerima\SerahTerimaBahan;
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

    public function pdfPermintaanBahan($id)
    {
        $permintaan_bahan = PermintaanBahan::with(['permintaanBahanPro', 'details', 'pic'])->findOrFail($id);

        return view('pdf.warehouse.pdfPermintaanBahan', compact('permintaan_bahan'));
    }

    public function pdfPermintaanPembelian($id)
    {
        $permintaan_pembelian = PermintaanPembelian::with(['permintaanBahanWBB', 'details', 'pic'])->findOrFail($id);

        return view('pdf.purchasing.pdfPermintaanPembelian', compact('permintaan_pembelian'));
    }

    public function pdfIncomingMaterialSS($id)
    {
        $incomingSS = IncommingMaterialSS::with(['permintaanPembelian', 'summary', 'detail', 'pic'])->findOrFail($id);

        return view('pdf.quality.pdfIncomingMaterialSS', compact('incomingSS'));
    }

    public function pdfIncomingMaterialNonSS($id)
    {
        $incomingNonSS = IncommingMaterialNonSS::with(['permintaanPembelian', 'summary', 'detail', 'pic'])->findOrFail($id);

        return view('pdf.quality.pdfIncomingMaterialNonSS', compact('incomingNonSS'));
    }

    public function pdfIncomingMaterial($id)
    {
        $incomingMaterial = IncommingMaterial::with(['permintaanPembelian', 'details', 'pic'])->findOrFail($id);

        return view('pdf.warehouse.pdfIncomingMaterial', compact('incomingMaterial'));
    }

    public function pdfSerahTerima($id)
    {
        $serah_terima = SerahTerimaBahan::with(['permintaanBahanPro', 'details', 'pic'])->findOrFail($id);

        return view('pdf.warehouse.pdfSerahTerima', compact('serah_terima'));
    }

    public function pdfStandarisasiDrawing($id)
    {
        $standarisasi = StandarisasiDrawing::with(['spk', 'identitas', 'detail', 'pic'])->findOrFail($id);

        return view('pdf.quality.pdfStandarisasiDrawing', compact('standarisasi'));
    }

    public function pdfPelabelanQCPassed()
    {
        return view('pdf.warehouse.pdfPelabelanQCPassed');
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

    public function pdfSPKVendor()
    {
        return view('pdf.production.pdfSPKVendor');
    }



    public function pdfDefectStatus()
    {
        return view('pdf.quality.pdfDefectStatus');
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

}