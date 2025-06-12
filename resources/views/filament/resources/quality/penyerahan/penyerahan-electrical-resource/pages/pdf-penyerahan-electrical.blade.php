<x-filament-panels::page>
<x-filament::section>

<h2 class="mb-3 text-xl font-bold text-center">Detail Serah Terima Electrical</h2>

{{-- Tabel Header --}}
<table class="w-full max-w-4xl mx-auto text-sm border border-black dark:border-white dark:bg-gray-900 dark:text-white"
    style="border-collapse: collapse;">
    <tr>
        <td rowspan="3"
            class="p-2 text-center align-middle border border-black dark:border-white w-28 h-28 dark:bg-gray-900">
            <img src="{{ asset('asset/logo.png') }}" alt="Logo" class="object-contain mx-auto h-30" />
        </td>
        <td colspan="2" class="font-bold text-center border border-black dark:border-white dark:bg-gray-900">
            PT. QLab Kinarya Sentosa
        </td>
    </tr>
    <tr>
        <td class="font-bold text-center border border-black dark:border-white dark:bg-gray-900"
            style="font-size: 20px;">
            Formulir Serah Terima<br>Produksi Mekanik ke <br> Produksi Elektrikal
        </td>
        <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
            <table class="w-full text-sm dark:bg-gray-900" style="border-collapse: collapse;">
                <tr>
                    <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                    <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> :
                        FO-QKS-PRO-01-01</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 border-b border-black dark:border-white">Tanggal Rilis</td>
                    <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> : 12 Maret 2025
                    </td>
                </tr>
                <tr>
                    <td class="px-3 py-2">Revisi</td>
                    <td class="px-3 py-2 font-semibold"> : 0</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<h2 class="text-xl font-bold text-start max-w-4xl mx-auto pt-4">Dari Divisi Mekanik ke Divisi Elektrikal</h2>

<h2 class="text-xl font-bold text-start max-w-4xl mx-auto pt-4">A.Informasi Produk</h2>
<div class="w-full max-w-4xl mx-auto pt-6 mb-3 text-sm space-y-3">
    @php
$fields = [
    ['label' => '1. Nama Produk :', 'value' => 'SPK-2025-001'],
    ['label' => '2. Kode Produk :', 'value' => '05 Juni 2025'],
    ['label' => '3. Nomor Batch/Seri :', 'value' => 'ABC123'],
    ['label' => '4. Tanggal Produksi Mekanik Selesai :', 'value' => '06 Juni 2025'],
    ['label' => '5. Jumlah Unit :', 'value' => '10 Unit'],
];
    @endphp

    @foreach ($fields as $field)
        <div class="flex items-center">
            <label class="w-64 font-medium">{{ $field['label'] }}</label>
            <input type="text" readonly value="{{ $field['value'] }}"
                class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
        </div>
    @endforeach  

    <div class="flex flex-col">
        <label class="font-medium mb-1">6. Kondisi Produk :</label>
        <div class="flex flex-col gap-2">
            <label class="flex items-center gap-2">
                <input type="radio" name="kondisi_produk" value="Baik" class="text-blue-600" />
                Baik
            </label>
            <label class="flex items-center gap-2">
                <input type="radio" name="kondisi_produk" value="Cukup Baik" class="text-blue-600" />
                Cukup Baik
            </label>
            <label class="flex items-center gap-2">
                <input type="radio" name="kondisi_produk" value="Perlu Perbaikan" class="text-blue-600" />
                Perlu Perbaikan
            </label>
        </div>
    </div>
    
</div>

<div class="w-full max-w-4xl mx-auto mb-4">
    <label for="note" class="block text-sm font-medium text-gray-700 mb-1">Jelaskan:</label>
    <textarea id="note" name="note" rows="4"
        class="w-full border border-black px-3 py-2 text-sm resize-none"></textarea>
</div>
<div class="max-w-4xl mx-auto text-sm space-y-6">
    <!-- B. PENGECEKAN SEBELUM SERAH TERIMA -->
    <h2 class="text-xl font-bold text-start max-w-4xl mx-auto   ">B.Pengecekan Sebelum Serah Terima</h2>

    <!-- 1. Kondisi Fisik Produk -->
    <div>
        <p class="font-medium">1. Kondisi Fisik Produk <span class="italic">(Centang sesuai kondisi)</span></p>
        <div class="pl-4 space-y-2 mt-1">
            <label class="flex items-start gap-2"><input type="checkbox" class="mt-1" /> Tidak ada kerusakan
                fisik</label>
            <label class="flex items-start gap-2"><input type="checkbox" class="mt-1" /> Ada sedikit cacat visual (tidak
                mempengaruhi fungsi)</label>
            <label class="flex items-start gap-2">
                <input type="checkbox" class="mt-1" />
                <span>
                    Ada kerusakan signifikan
                </span>
            </label>
            <div class="w-full max-w-4xl mx-auto mb-4">
                <label for="note" class="block text-sm font-medium text-gray-700 mb-1">Jelaskan:</label>
                <textarea id="note" name="note" rows="4"
                    class="w-full border border-black px-3 py-2 text-sm resize-none"></textarea>     
            </div>
        </div>
    </div>

    <!-- 2. Kelengkapan Komponen -->
    <div>
        <p class="font-medium">2. Kelengkapan Komponen <span class="italic">(Centang sesuai kondisi)</span></p>
        <div class="pl-4 space-y-2 mt-1">
            <label class="flex items-start gap-2"><input type="checkbox" class="mt-1" /> Semua komponen mekanik
                terpasang dengan benar</label>
            <label class="flex items-start gap-2">
                <input type="checkbox" class="mt-1" />
                <span>
                    Ada komponen yang kurang
                </span>
            </label>
            <label class="flex items-start gap-2"><input type="checkbox" class="mt-1" /> Ada komponen yang perlu
                diperbaiki atau diganti
            </label>
            <div class="w-full max-w-4xl mx-auto mb-4">
                <label for="note" class="block text-sm font-medium text-gray-700 mb-1">Sebutkan Komponen yang Kurang :</label>
                <textarea id="note" name="note" rows="4"
                    class="w-full border border-black px-3 py-2 text-sm resize-none"></textarea>
            </div>
        </div>
    </div>

    <!-- 3. Dokumen Pendukung -->
    <div class="text-sm space-y-3">
        <p class="font-medium">
            3. Dokumen Pendukung <span class="italic">(Lampirkan jika ada)</span>
        </p>
    
        <div class="pl-4 space-y-2">
            <label class="flex items-start gap-2">
                <input type="checkbox" class="mt-1" />
                Gambar Teknis
            </label>
            <label class="flex items-start gap-2">
                <input type="checkbox" class="mt-1" />
                SOP atau Instruksi Perakitan
            </label>
            <label class="flex items-start gap-2">
                <input type="checkbox" class="mt-1" />
                Laporan QC (Quality Control)
            </label>
        </div>
    
        <div>
            <label class="block font-medium mb-1">Upload Dokumen Pendukung:</label>
            <input type="file" disabled
                class="w-full border border-gray-300 p-2 rounded bg-gray-100 text-gray-500 cursor-not-allowed" />
        </div>
    </div>
    
<!-- C. PENERIMAAN OLEH PRODUKSI ELEKTRIKAL -->
<h2 class="text-xl font-bold text-start max-w-4xl mx-auto mb-2">
    C. Penerimaan Oleh Produksi Elektrikal
</h2>

<div class="w-full max-w-4xl mx-auto text-sm space-y-3">
    @php
$fields = [
    ['label' => 'Tanggal Serah Terima :', 'value' => 'SPK-2025-001'],
    ['label' => 'Diterima Oleh (Nama & Jabatan) :', 'value' => '05 Juni 2025'],
    ['label' => 'Catatan Tambahan :', 'value' => 'ABC123'],
];
    @endphp

    @foreach ($fields as $field)
        <div class="flex items-center">
            <label class="w-64 font-medium">{{ $field['label'] }}</label>
            <input type="text" readonly value="{{ $field['value'] }}"
                class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
        </div>
    @endforeach

    <!-- Status Penerimaan -->
    <div class="pt-2">
        <p class="font-semibold">• Status Penerimaan:</p>
        <div class="pl-6 space-y-3 mt-1">
            <div class="flex items-center gap-2">
                <input type="checkbox" class="mt-1" />
                <span>Diterima tanpa catatan</span>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" class="mt-1" />
                <span>Diterima dengan catatan:</span>
                <input type="text" class="flex-1 border border-gray-300 rounded-md px-3 py-2"
                    placeholder="Jelaskan..." />
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" class="mt-1" />
                <span>Ditolak dan dikembalikan ke divisi mekanik (Alasan):</span>
                <input type="text" class="flex-1 border border-gray-300 rounded-md px-3 py-2" placeholder="Alasan..." />
            </div>
        </div>
    </div>
</div>
</div>
<h2 class="text-xl font-bold text-start max-w-4xl mx-auto pt-3 mb-2">
    D. Tanda Tangan
</h2>

<div class="p-4 mb-6 max-w-4xl mx-auto">
    <div class="grid grid-cols-3 gap-4 text-sm">
        @foreach (['Checked By', 'Accepted By', 'Approved By'] as $role)
            <div>
                <label class="font-semibold block mb-1">{{ $role }}</label>
                <input type="text" value="{{ $role }} User" readonly
                    class="w-full mb-2 border border-gray-300 p-2 rounded bg-gray-100 text-gray-500 cursor-not-allowed" />

                <label class="block mb-1">Signature</label>
                <div class="w-full h-24 mb-2 border border-black rounded bg-white"></div>

                <label class="block mb-1">Date</label>
                <input type="date" readonly value="{{ now()->format('Y-m-d') }}"
                    class="w-full border border-gray-300 p-2 rounded bg-gray-100 text-gray-500 cursor-not-allowed" />
            </div>
        @endforeach
    </div>
</div>


</x-filament::section>   
</x-filament-panels::page>
