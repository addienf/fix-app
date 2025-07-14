<?php

use App\Http\Controllers\PDFController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('filament.admin.auth.login');
});

// Sales
Route::get('/sales/spesifikasi-produk/{record}/pdf-spesifikasi-produk', [PDFController::class, 'previewSpesifikasiProduct'])->name('specProduct.preview');
Route::get('/sales/spesifikasi-produk/{record}/download-file', [PDFController::class, 'downloadFileSpesifikasiProduct'])->name('specProduct.file-download');
Route::get('/sales/spk/{record}/pdf-spk-marketing', [PDFController::class, 'pdfSPKMarketing'])->name('pdf.SPKMarketing');

// Produksi
Route::get('/produksi/jadwal-produksi/{record}/pdf-jadwal-produksi', [PDFController::class, 'pdfJadwalProduksi'])->name('pdf.jadwalProduksi');
Route::get('/produksi/permintaan-alat-dan-bahan/{record}/pdf-permintaan-alat-dan-bahan', [PDFController::class, 'pdfPermintaanAlatBahan'])->name('pdf.permintaanAlatBahan');
Route::get('/produksi/penyerahan-electrical/{record}/pdf-penyerahan-electrical', [PDFController::class, 'pdfPenyerahanElectrical'])->name('pdf.penyerahanElectrical');
Route::get('/produksi/penyerahan-electrical/{record}/download-file', [PDFController::class, 'downloadPenyerahanElectrical'])->name('penyerahanElectrical.file-download');
Route::get('/produksi/spk-quality/{record}/pdf-spk-quality', [PDFController::class, 'pdfSPKQuality'])->name('pdf.spkQuality');
Route::get('/produksi/penyerahan-produk-jadi/{record}/pdf-penyerahan-produk-jadi', [PDFController::class, 'pdfPenyerahanProdukJadi'])->name('pdf.PenyerahanProdukJadi');
Route::get('/production/spk-vendor/{record}/pdf-spk-vendor', [PDFController::class, 'pdfSPKVendor'])->name('pdf.spkVendor');

// Warehouse
Route::get('/warehouse/permintaan-bahan/{record}/pdf-permintaan-bahan', [PDFController::class, 'pdfPermintaanBahan'])->name('pdf.permintaanBahan');
Route::get('/warehouse/incoming-material/{record}/pdf-incoming-material', [PDFController::class, 'pdfIncomingMaterial'])->name('pdf.IncomingMaterial');
Route::get('/warehouse/incoming-material/{record}/download-file', [PDFController::class, 'downloadIncomingMaterial'])->name('IncomingMaterial.file-download');
Route::get('/warehouse/serah-terima-bahan/{record}/pdf-serah-terima-bahan', [PDFController::class, 'pdfSerahTerima'])->name('pdf.serahTerima');
Route::get('/warehouse/pelabelan-qc-passed/{record}/pdf-pelabelan-qc-passed', [PDFController::class, 'pdfPelabelanQCPassed'])->name('pdf.PelabelanQCPassed');
Route::get('/warehouse/peminjaman-alat/{record}/pdf-peminjaman-alat', [PDFController::class, 'pdfPeminjamanAlat'])->name('pdf.PeminjamanAlat');

// Purchasing
Route::get('/purchasing/permintaan-pembelian/{record}/pdf-permintaan-pembelian', [PDFController::class, 'pdfPermintaanPembelian'])->name('pdf.PermintaanPembelian');

// Quality
Route::get('/quality/incoming-material-ss/{record}/pdf-incoming-material-ss', [PDFController::class, 'pdfIncomingMaterialSS'])->name('pdf.incomingMaterialSS');
Route::get('/quality/incoming-material-non-ss/{record}/pdf-incoming-material-non-ss', [PDFController::class, 'pdfIncomingMaterialNonSS'])->name('pdf.incomingMaterialNonSS');
Route::get('/quality/standarisasi-gambar-kerja/{record}/pdf-standarisasi-gambar-kerja', [PDFController::class, 'pdfStandarisasiDrawing'])->name('pdf.StandarisasiDrawing');
Route::get('/quality/standarisasi-gambar-kerja/{record}/download-zip', [PDFController::class, 'downloadZipStandarisasiDrawing'])->name('StandarisasiDrawing.download-zip');
Route::get('/quality/kelengkapan-material-ss/{record}/pdf-kelengkapan-material-ss', [PDFController::class, 'pdfKelengkapanMaterialSS'])->name('pdf.kelengkapanMaterialSS');
Route::get('/quality/pengecekan-material-ss/{record}/pdf-kelengkapan-material-ss', [PDFController::class, 'pdfPengecekanMaterialSS'])->name('pdf.pengecekanMaterialSS');
Route::get('/quality/pengecekan-electrical/{record}/pdf-pengecekan-electrical', [PDFController::class, 'pdfPengecekanElectrical'])->name('pdf.pengecekanElectrical');
Route::get('/quality/pengecekan-performa/{record}/pdf-pengecekan-performa', [PDFController::class, 'pdfPengecekanPerforma'])->name('pdf.pengecekanPerforma');
Route::get('/quality/defect-status/{record}/pdf-defect-status', [PDFController::class, 'pdfDefectStatus'])->name('pdf.defectStatus');

//Engineering
<<<<<<< HEAD
Route::get('/engineering/spkservice', [PDFController::class, 'pdfSPKService'])->name('pdf.spkService');
Route::get('/engineering/sparepartalatkerja', [PDFController::class, 'pdfSparepartAlatKerja'])->name('pdf.sparepartAlatKerja');
Route::get('/engineering/beritaacara', [PDFController::class, 'pdfBeritaAcara'])->name('pdf.beritaAcara');
Route::get('/engineering/maintenancechamberg2', [PDFController::class, 'pdfMaintenanceChamberG2'])->name('pdf.maintenanceChamberG2');
=======
Route::get('/engineering/spk-service/{record}/pdf-spk-service', [PDFController::class, 'pdfSPKService'])->name('pdf.spkService');
Route::get('/engineering/sparepart-alat-kerja/{record}/pdf-sparepart-alat-kerja', [PDFController::class, 'pdfSparepartAlatKerja'])->name('pdf.sparepartAlatKerja');
Route::get('/engineering/berita-acara/{record}/pdf-berita-acara', [PDFController::class, 'pdfBeritaAcara'])->name('pdf.beritaAcara');
>>>>>>> 4197bd31de12fd50c7be780dc241c3229400025c
