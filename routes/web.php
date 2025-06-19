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
Route::get('/sales/spesifikasi-product/preview/{id}', [PDFController::class, 'previewSpesifikasiProduct'])->name('specProduct.preview');


// Route::get('/sales/spesifikasi-product/print-pdf/{id}', [PDFController::class, 'pdfSpesifikasiProduct'])->name('pdf.specProduct');
Route::get('/pdf', [PDFController::class, 'pdfSpesifikasiProduct'])->name('pdf.specProduct');

Route::get('/sales/spk', [PDFController::class, 'pdfSPKMarketing'])->name('pdf.SPKMarketing');

Route::get('/purchasing/permintaanpembelian', [PDFController::class, 'pdfPermintaanPembelian'])->name('pdf.PermintaanPembelian');

Route::get('/warehouse/incomingmaterial', [PDFController::class, 'pdfIncomingMaterial'])->name('pdf.IncomingMaterial');

Route::get('/warehouse/pelabelanqcpassed', [PDFController::class, 'pdfPelabelanQCPassed'])->name('pdf.PelabelanQCPassed');

Route::get('/warehouse/permintaanbahan', [PDFController::class, 'pdfPermintaanBahan'])->name('pdf.permintaanBahan');

Route::get('/warehouse/serahterima', [PDFController::class, 'pdfSerahTerima'])->name('pdf.serahTerima');

Route::get('/production/jadwalproduksi', [PDFController::class, 'pdfJadwalProduksi'])->name('pdf.jadwalProduksi');

Route::get('/production/penyerahanelectrical', [PDFController::class, 'pdfPenyerahanElectrical'])->name('pdf.penyerahanElectrical');

Route::get('/production/penyerahanprodukjadi', [PDFController::class, 'pdfPenyerahanProdukJadi'])->name('pdf.PenyerahanProdukJadi');

Route::get('/production/permintaanalatbahan', [PDFController::class, 'pdfPermintaanAlatBahan'])->name('pdf.permintaanAlatBahan');

Route::get('/production/spkquality', [PDFController::class, 'pdfSPKQuality'])->name('pdf.spkQuality');