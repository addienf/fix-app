<x-filament-panels::page>
    <x-filament::section>
    {{-- {{ $standarisasi }} --}}
    <h2 class="mb-3 text-xl font-bold text-center">Detail Standarisasi Gambar Kerja</h2>
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
               Standarisasi Gambar Kerja
            </td>
            <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                <table class="w-full text-sm dark:bg-gray-900" style="border-collapse: collapse;">
                    <tr>
                        <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                        <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> : FO-QKS-QA-01-01</td>
                    </tr>
                    <tr>
                        <td class="px-3 py-2 border-b border-black dark:border-white">Tanggal Rilis</td>
                        <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> : 12 Maret 2025</td>
                    </tr>
                    <tr>
                        <td class="px-3 py-2">Revisi</td>
                        <td class="px-3 py-2 font-semibold"> : 0</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <div class="w-full max-w-4xl mx-auto pt-6 mb-6 text-sm grid grid-cols-1 gap-y-4">
        @php
$fields = [
    ['label' => 'No SPK Produksi :', 'value' => 'SPK-2025-001'],
    ['label' => 'Tanggal Pemeriksaan :', 'value' => '05 Juni 2025'],
];
        @endphp
    
        @foreach ($fields as $field)
            <div class="flex flex-col">
                <label class="font-medium mb-1">{{ $field['label'] }}</label>
                <input type="text" readonly value="{{ $field['value'] }}"
                    class="w-full px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
            </div>
        @endforeach
    </div>

    <div class="w-full max-w-4xl mx-auto pt-2 mb-6 text-sm grid grid-cols-1 gap-y-4">
        <h2 class="mb-3 text-xl font-bold text-start col-span-1">
           I. Detail Standarisasi Gambar Kerja
        </h2>
        @php
$fields = [
    ['label' => 'Judul Gambar :', 'value' => 'SPK-2025-001'],
    ['label' => 'Nomor Gambar :', 'value' => '05 Juni 2025'],
    ['label' => 'Tanggal Pembuatan :', 'value' => 'Gambar Kerja Standarisasi'],
    ['label' => 'Revisi :', 'value' => 'Tim QA'],
    ['label' => 'Nama Pembuat Gambar :', 'value' => 'Tim Produksi'],
    ['label' => 'Nama Pemeriksa :', 'value' => 'Tim Produksi'],
];
        @endphp
    
        @foreach ($fields as $field)
            <div class="flex flex-col">
                <label class="font-medium mb-1">{{ $field['label'] }}</label>
                <input type="text" readonly value="{{ $field['value'] }}"
                    class="w-full px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
            </div>
        @endforeach
    </div>
    <div class="w-full max-w-4xl mx-auto pt-2 mb-6 text-sm grid grid-cols-1 gap-y-4">
        <h2 class="mb-3 text-xl font-bold text-start col-span-1">
            II. Spesifikasi Teknis
        </h2>
        @php
$fields = [
    ['label' => 'Jenis Gambar :', 'value' => 'SPK-2025-001'],
    ['label' => 'Format Gambar :', 'value' => '05 Juni 2025'],
];
        @endphp
        @foreach ($fields as $field)
            <div class="flex flex-col">
                <label class="font-medium mb-1">{{ $field['label'] }}</label>
                <input type="text" readonly value="{{ $field['value'] }}"
                    class="w-full px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
            </div>
        @endforeach
    </div>
    <div class="w-full max-w-4xl mx-auto pt-2 mb-6 text-sm grid grid-cols-1 gap-y-4">
        <h2 class="mb-3 text-xl font-bold text-start col-span-1">
            III. Komponen Gambar yang Diperiksa
        </h2>
        @php
$fields = [
    ['label' => '', 'value' => 'SPK-2025-001'],
];
        @endphp
        @foreach ($fields as $field)
            <div class="flex flex-col">
                <label class="font-medium mb-1">{{ $field['label'] }}</label>
                <input type="text" readonly value="{{ $field['value'] }}"
                    class="w-full px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
            </div>
        @endforeach
    </div>

    <div class="w-full max-w-4xl mx-auto pt-2 mb-6 grid grid-cols-1 gap-y-4">
        <h2 class="mb-3 text-xl font-bold text-start col-span-1">
            IV. Catatan dan Koreksi yang Dibutuhkan
        </h2>
        <p> 1. Keselarasan dengan spesifikasi</p>
        <p> 2. Ketepatan dimensi dengan skala</p>  
        <p> 3. Kesesuaian dengan gambar dan produk</p>

    <div class="w-full max-w-4xl mx-auto pt-2 text-sm grid grid-cols-1 gap-y-4">
        <h2 class="mb-3 text-xl font-bold text-start col-span-1">
            V. Lampiran
        </h2>
    </div>
    <div class="max-w-4xl mx-auto px-6">
        <div class="flex items-start justify-between gap-8">
            <!-- Kiri -->
            <div class="flex flex-col items-center w-1/2">
                <p class="mb-2 dark:text-white">Dibuat Oleh</p>
                <img src="https://via.placeholder.com/300x80?text=Signature+Marketing" alt="Signature Marketing"
                    class="h-20 w-80" />
                <p class="mt-1 font-semibold dark:text-white">Budi Santoso</p>
            </div>
            <!-- Kanan -->
            <div class="flex flex-col items-center w-1/2">
                <p class="mb-2 dark:text-white">Diperiksa Oleh</p>
                <img src="https://via.placeholder.com/300x80?text=Signature+Produksi" alt="Signature Produksi"
                    class="h-20 w-80" />
                <p class="mt-1 font-semibold dark:text-white">Sari Wulandari</p>
            </div>
        </div>
    </div>
</x-filament::section>
</x-filament-panels::page>
