@extends ('pdf.layout.layout')

@section('content')
    <div id="export-area" class="p-2 bg-white text-black">
        <table
            class="w-full max-w-4xl mx-auto text-sm border border-black dark:border-white dark:bg-gray-900 dark:text-white"
            style="border-collapse: collapse;">
            <tr>
                <td rowspan="3"
                    class="p-2 text-center align-middle border border-black w-28 h-28 dark:border-white dark:bg-gray-900">
                    <img src="{{ asset('asset/logo.png') }}" alt="Logo" class="object-contain mx-auto h-30" />
                </td>
                <td colspan="2" class="font-bold text-center border border-black dark:border-white dark:bg-gray-900">
                    PT. QLab Kinarya Sentosa
                </td>
            </tr>
            <tr>
                <td class="font-bold text-center border border-black dark:border-white dark:bg-gray-900"
                    style="font-size: 20px;">
                    Standarisasi Gambar Kerja
                </td>
                <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                    <table class="w-full text-sm dark:bg-gray-900 dark:text-white" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> :
                                FO-QKS-QA-01-01</td>
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
        <!-- Informasi Umum -->
        <div class="flex flex-col gap-4 w-full max-w-4xl mx-auto text-sm pt-6">
            <div class="flex items-center gap-4">
                <label class="w-48 font-medium">No SPK Produksi :</label>
                <input type="text" readonly value="SPK-2024-002"
                    class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
            </div>
            <div class="flex items-center gap-4">
                <label class="w-48 font-medium">Tanggal Pemeriksaan :</label>
                <input type="text" readonly value="05 Jun 2025"
                    class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
            </div>
        </div>

        <!-- Detail Standarisasi -->
        <div class="flex flex-col gap-4 w-full max-w-4xl mx-auto pt-6 text-sm">
            <h2 class="text-xl font-bold mb-4">I. Detail Standarisasi Gambar Kerja</h2>
            @php
                $fields = [
                    ['label' => 'Judul Gambar :', 'value' => 'Desain Sistem Elektrikal'],
                    ['label' => 'Nomor Gambar :', 'value' => 'ELEK-2025-03'],
                    ['label' => 'Tanggal Pembuatan :', 'value' => '01 Jun 2025'],
                    ['label' => 'Revisi :', 'value' => 'Revisi'],
                    ['label' => 'Revisi Ke :', 'value' => 'Tim QA'],
                    ['label' => 'Nama Pembuat Gambar :', 'value' => 'Rahmat Hidayat'],
                    ['label' => 'Nama Pemeriksa :', 'value' => 'Siti Mariam'],
                ];
            @endphp
            @foreach ($fields as $field)
                <div class="flex items-center gap-4">
                    <label class="w-48 font-medium">{{ $field['label'] }}</label>
                    <input type="text" readonly value="{{ $field['value'] }}"
                        class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                </div>
            @endforeach
        </div>

        <!-- Spesifikasi Teknis -->
        <div class="flex flex-col gap-4 w-full max-w-4xl mx-auto pt-6 text-sm mb-6">
            <h2 class="text-xl font-bold mb-4">II. Spesifikasi Teknis</h2>
            <div class="flex items-center gap-4">
                <label class="w-48 font-medium">Jenis Gambar :</label>
                <input type="text" readonly value="Teknis"
                    class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
            </div>
            <div class="flex items-center gap-4">
                <label class="w-48 font-medium">Format Gambar :</label>
                <input type="text" readonly value="PDF A3"
                    class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
            </div>
        </div>

        <!-- Komponen Gambar -->
        <div class="w-full max-w-4xl pt-6 mx-auto text-sm">
            <h2 class="mb-4 text-xl font-bold">III. Komponen Gambar yang Diperiksa</h2>
            <ul class="list-decimal list-inside mb-6">
                <li>Keselarasan dengan spesifikasi</li>
                <li>Ketepatan dimensi dengan skala</li>
                <li>Kesesuaian dengan gambar dan produk</li>
            </ul>
        </div>

        <!-- Catatan -->
        <div class="w-full max-w-4xl pt-6 mx-auto text-sm">
            <h2 class="mb-3 text-xl font-bold">IV. Catatan dan Koreksi yang Dibutuhkan</h2>
            <textarea readonly id="note"
                class="w-full px-3 py-2 overflow-hidden text-sm leading-relaxed border cursor-not-allowed resize-none border-black rounded-md">Perlu penyesuaian dimensi pada bagian konektor listrik untuk kompatibilitas modul baru.</textarea>
        </div>

        <!-- Lampiran dan Tanda Tangan -->
        <div class="w-full max-w-4xl pt-6 mx-auto">
            <h2 class="mb-3 text-xl font-bold">V. Lampiran</h2>
            <div class="flex items-start justify-between gap-8">
                <div class="flex flex-col items-center w-1/2">
                    <p class="mb-2">Dibuat Oleh</p>
                    <img src="{{ asset('storage/dummy_signature_create.png') }}" alt="ttd" class="h-20 w-80" />
                    <p class="mt-1 font-semibold">Rahmat Hidayat</p>
                </div>
                <div class="flex flex-col items-center w-1/2">
                    <p class="mb-2">Diperiksa Oleh</p>
                    <img src="{{ asset('storage/dummy_signature_check.png') }}" alt="ttd" class="h-20 w-80" />
                    <p class="mt-1 font-semibold">Siti Mariam</p>
                </div>
            </div>
        </div>
    </div>
@endsection