<x-filament-panels::page>
    <x-filament::section>
    {{ $permintaan_bahan }}

    <h2 class="mb-3 text-xl font-bold text-center">Detail Permintaan Bahan</h2>
    <!-- HEADER DOKUMEN MIRIP EXCEL -->
    <table class="w-full max-w-4xl mx-auto text-sm border border-black dark:border-gray-600"
        style="border-collapse: collapse;">
        <tr>
            <td rowspan="3" class="p-2 text-center align-middle border border-black w-28 h-28">
                <img src="{{ asset('asset/logo.png') }}" alt="Logo" class="object-contain mx-auto h-30" />
            </td>
            <td colspan="2" class="font-bold text-center border border-black">
                PT. QLab Kinarya Sentosa
            </td>
        </tr>
        <tr>
            <td class="font-bold text-center border border-black" style="font-size: 20px;">
                Formulir Permintaan Bahan  
            </td>
            <td rowspan="2" class="p-0 align-top border border-black">
                <table class="w-full text-sm" style="border-collapse: collapse;">
                    <tr>
                        <td class="px-3 py-2">No. Dokumen</td>
                        <td class="px-3 py-2 font-semibold"> : FO-QKS-PRD-01-01</td>
                    </tr>
                    <tr>
                        <td class="px-3 py-2">Tanggal Rilis</td>
                        <td class="px-3 py-2 font-semibold"> : 12 Maret 2025</td>
                    </tr>
                    <tr>
                        <td class="px-3 py-2">Revisi</td>
                        <td class="px-3 py-2 font-semibold"> : 0</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    @php
$infoUmum = [
    ['label' => 'Nomor :', 'value' => '30 Mei 2025'],
    ['label' => 'Tanggal :', 'value' => ''],
    ['label' => 'Dari : ', 'value' => ''],
    ['label' => 'Kepada :', 'value' => ''],
];

$produkList = [
    [
        'nama' => 'Produk A',
        'model' => 'Type A1',
        'volume' => '100 L',
        'jumlah' => 100,
        'mulai' => '2025-06-01',
        'selesai' => '2025-06-05',
        'spk' => 'SPK-001',
    ],
];
    @endphp
    <div class="grid max-w-4xl grid-cols-1 pt-2 mx-auto mb-6 text-sm md:grid-cols-2 gap-x-6 gap-y-4 pt-4">
        @foreach ($infoUmum as $field)
            <div class="flex flex-col items-start gap-2 md:flex-row md:gap-4 md:items-center">
                <label class="font-medium md:w-48">{{ $field['label'] }}</label>
                <input type="text"
                    class="flex-1 w-full px-2 py-1 text-black bg-white border border-gray-300 rounded dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    value="{{ $field['value'] }}" />
            </div>
        @endforeach
    </div>
    
    <!-- PARAGRAF PERMINTAAN -->
    <div class="max-w-4xl mx-auto text-sm mb-6">
        <p class="mb-2">Dengan hormat,</p>
        <p class="flex flex-wrap items-center gap-1">
            <span>Berdasarkan Permintaan Barang No</span>
            <input type="disabled"
                class="w-32 h-7 px-2 py-1 text-sm align-middle bg-transparent rounded outline-none focus:outline-none" />
                <span>Dari Departemen</span>
                <input type="disabled"
                    class="w-32 h-7 px-2 py-1 text-sm align-middle bg-transparent rounded outline-none focus:outline-none" />
            <span>mohon bantuan untuk memenuhi kebutuhan bahan/sparepart dengan rincian sebagai berikut:</span>
        </p>
    </div>
    
    <!-- TABEL PRODUK -->
    <div class="max-w-4xl mx-auto overflow-x-auto">
        <table class="w-full text-sm text-left border border-gray-300 dark:border-gray-600">
            <thead class="text-black bg-gray-100 dark:bg-gray-800 dark:text-white">
                <tr>
                    <th class="px-4 py-2 border">No</th>
                    <th class="px-4 py-2 border">Nama Bahan</th>
                    <th class="px-4 py-2 border">Spesifikasi</th>
                    <th class="px-4 py-2 border">Jumlah</th>
                    <th class="px-4 py-2 border">Keperluan Barang</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900">
                @foreach ($produkList as $index => $produk)
                    <tr>
                        <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                        <td class="px-4 py-2 border">{{ $produk['nama'] }}</td>
                        <td class="px-4 py-2 border">{{ $produk['model'] }}</td>
                        <td class="px-4 py-2 border">{{ $produk['volume'] }}</td>
                        <td class="px-4 py-2 border">{{ $produk['jumlah'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
        <div class="max-w-4xl mx-auto mt-10 text-sm">
            <div class="flex items-start justify-between gap-4">
                <div class="flex flex-col items-center">
                    <p class="mb-2 dark:text-white">Yang Membuat</p>
                    <img src="" alt="Signature Pembuat" class="h-20 w-80" />
                </div>
                <div class="flex flex-col items-center">
                    <p class="mb-2 dark:text-white">Yang Menerima</p>
                    <img src="" alt="Signature Penerima" class="h-20 w-80" />
                </div>
            </div>
        </div>
</x-filament::section>
</x-filament-panels::page>
