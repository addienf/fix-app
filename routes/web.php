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
Route::get('/sales/spk/{record}/pdf-spk-marketing', [PDFController::class, 'pdfSPKMarketing'])->name('pdf.SPKMarketing');

// Produksi
Route::get('/produksi/jadwal-produksi/{record}/pdf-jadwal-produksi', [PDFController::class, 'pdfJadwalProduksi'])->name('pdf.jadwalProduksi');
Route::get('/produksi/permintaan-alat-dan-bahan/{record}/pdf-permintaan-alat-dan-bahan', [PDFController::class, 'pdfPermintaanAlatBahan'])->name('pdf.permintaanAlatBahan');

// Warehouse
Route::get('/warehouse/permintaan-bahan/{record}/pdf-permintaan-bahan', [PDFController::class, 'pdfPermintaanBahan'])->name('pdf.permintaanBahan');
Route::get('/warehouse/incoming-material/{record}/pdf-incoming-material', [PDFController::class, 'pdfIncomingMaterial'])->name('pdf.IncomingMaterial');
Route::get('/warehouse/serah-terima-bahan/{record}/pdf-serah-terima-bahan', [PDFController::class, 'pdfSerahTerima'])->name('pdf.serahTerima');

// Purchasing
Route::get('/purchasing/permintaan-pembelian/{record}/pdf-permintaan-pembelian', [PDFController::class, 'pdfPermintaanPembelian'])->name('pdf.PermintaanPembelian');

// Quality
Route::get('/quality/incoming-material-ss/{record}/pdf-incoming-material-ss', [PDFController::class, 'pdfIncomingMaterialSS'])->name('pdf.incomingMaterialSS');
Route::get('/quality/incoming-material-non-ss/{record}/pdf-incoming-material-non-ss', [PDFController::class, 'pdfIncomingMaterialNonSS'])->name('pdf.incomingMaterialNonSS');
Route::get('/quality/standarisasi-gambar-kerja/{record}/pdf-standarisasi-gambar-kerja', [PDFController::class, 'pdfStandarisasiDrawing'])->name('pdf.StandarisasiDrawing');



Route::get('/warehouse/pelabelanqcpassed', [PDFController::class, 'pdfPelabelanQCPassed'])->name('pdf.PelabelanQCPassed');



Route::get('/warehouse/peminjamanalat', [PDFController::class, 'pdfPeminjamanAlat'])->name('pdf.PeminjamanAlat');

Route::get('/production/penyerahanelectrical', [PDFController::class, 'pdfPenyerahanElectrical'])->name('pdf.penyerahanElectrical');

Route::get('/production/penyerahanprodukjadi', [PDFController::class, 'pdfPenyerahanProdukJadi'])->name('pdf.PenyerahanProdukJadi');

Route::get('/production/spkquality', [PDFController::class, 'pdfSPKQuality'])->name('pdf.spkQuality');

Route::get('/quality/defectstatus', [PDFController::class, 'pdfDefectStatus'])->name('pdf.defectStatus');





Route::get('/quality/kelengkapanmaterialss', [PDFController::class, 'pdfKelengkapanMaterialSS'])->name('pdf.kelengkapanMaterialSS');

Route::get('/quality/pengecekanperforma', [PDFController::class, 'pdfPengecekanPerforma'])->name('pdf.pengecekanPerforma');

Route::get('/quality/pengecekanelectrical', [PDFController::class, 'pdfPengecekanElectrical'])->name('pdf.pengecekanElectrical');

Route::get('/quality/pengecekanmaterialss', [PDFController::class, 'pdfPengecekanMaterialSS'])->name('pdf.pengecekanMaterialSS');
