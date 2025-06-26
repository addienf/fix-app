<?php

namespace App\Http\Controllers;

use App\Models\Production\Jadwal\JadwalProduksi;
use App\Models\Production\Penyerahan\PenyerahanElectrical\PenyerahanElectrical;
use App\Models\Production\Penyerahan\PenyerahanProdukJadi;
use App\Models\Production\PermintaanBahanProduksi\PermintaanAlatDanBahan;
use App\Models\Production\SPK\SPKQuality;
use App\Models\Purchasing\Permintaan\PermintaanPembelian;
use App\Models\Quality\IncommingMaterial\MaterialNonSS\IncommingMaterialNonSS;
use App\Models\Quality\IncommingMaterial\MaterialSS\IncommingMaterialSS;
use App\Models\Quality\KelengkapanMaterial\SS\KelengkapanMaterialSS;
use App\Models\Quality\PengecekanMaterial\Electrical\PengecekanMaterialElectrical;
use App\Models\Quality\PengecekanMaterial\SS\PengecekanMaterialSS;
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

    public function pdfKelengkapanMaterialSS($id)
    {
        $kelengkapan = KelengkapanMaterialSS::with(['spk', 'pic', 'detail'])->findOrFail($id);

        return view('pdf.quality.pdfKelengkapanMaterialSS', compact('kelengkapan'));
    }

    public function pdfPengecekanMaterialSS($id)
    {
        $pengecekanSS = PengecekanMaterialSS::with(['spk', 'pic', 'detail', 'penyerahan'])->findOrFail($id);

        return view('pdf.quality.pdfPengecekanMaterialSS', compact('pengecekanSS'));
    }

    public function pdfPenyerahanElectrical($id)
    {
        $serahElectrical = PenyerahanElectrical::with(['pengecekanSS', 'sebelumSerahTerima', 'pic', 'penerimaElectrical'])->findOrFail($id);

        return view('pdf.production.pdfPenyerahanElectrical', compact('serahElectrical'));
    }

    public function pdfSPKQuality($id)
    {
        $spk_qc = SPKQuality::with(['spk', 'details', 'pic'])->findOrFail($id);

        return view('pdf.production.pdfSPKQuality', compact('spk_qc'));
    }

    public function pdfPengecekanElectrical($id)
    {
        $electrical = PengecekanMaterialElectrical::with(['spk', 'pic', 'detail'])->findOrFail($id);

        return view('pdf.quality.pdfPengecekanElectrical', compact('electrical'));
    }

    public function pdfPenyerahanProdukJadi($id)
    {
        $produkJadi = PenyerahanProdukJadi::with(['spk', 'details', 'pic'])->findOrFail($id);

        return view('pdf.production.pdfPenyerahanProdukJadi', compact('produkJadi'));
    }

    public function pdfPelabelanQCPassed()
    {
        return view('pdf.warehouse.pdfPelabelanQCPassed');
    }


    public function pdfPeminjamanAlat()
    {
        return view('pdf.warehouse.pdfPeminjamanAlat');
    }





    public function pdfDefectStatus()
    {
        return view('pdf.quality.pdfDefectStatus');
    }


    public function pdfPengecekanPerforma()
    {
        return view('pdf.quality.pdfPengecekanPerforma');
    }
}
