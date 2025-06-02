<x-filament-panels::page>
    {{ $permintaan_pembelian }}
    <x-filament::section>
        <h2 class="mb-3 text-xl font-bold text-center">
            Detail Permintaan Pembelian
        </h2>
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
                <td class="text-lg font-bold text-center border border-black dark:border-white dark:bg-gray-900 align-middle"
                    style="width: 400px;">
                    Formulir Permintaan Pembelian
                </td>
                <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                    <table class="w-full text-sm dark:bg-gray-900 dark:text-white" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white">: FO-QKS-PRD-01-01</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">Tanggal Rilis</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white">: 12 Maret 2025</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2">Revisi</td>
                            <td class="px-3 py-2 font-semibold">: 0</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="max-w-4xl mx-auto mb-6 text-sm">
            <p class="mb-2">Dengan hormat,</p>
            <p class="flex flex-wrap items-center gap-1">
                <span>Berdasarkan SPK MKT No.</span>
                <input readonly
                    class="w-[400px] px-2 py-1 text-black bg-white border border-gray-300 rounded dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    value="" />
                <span>mohon bantuan untuk memenuhi kebutuhan bahan/sparepart dengan rincian sebagai berikut:</span>
            </p>
        </div>
        @php
$permintaan_alat_bahan = (object) [
    'details' => [
        ['bahan_baku' => 'Alkohol 96%', 'spesifikasi' => 'Cair, 1 Liter', 'jumlah' => '5 Botol', 'keperluan_barang' => 'Sterilisasi alat'],

    ]
];
        @endphp
        
        <div class="max-w-4xl mx-auto overflow-x-auto">
            <table class="w-full text-sm text-left border border-gray-300 dark:border-gray-600">
                <thead class="text-black bg-gray-100 dark:bg-gray-800 dark:text-white">
                    <tr>
                        <th class="px-4 py-2 border">No</th>
                        <th class="px-4 py-2 border">Kode Barang</th>
                        <th class="px-4 py-2 border">Nama Barang</th>
                        <th class="px-4 py-2 border">Quantity</th>
                        <th class="px-4 py-2 border">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 dark:text-white">
                    @foreach ($permintaan_alat_bahan->details as $index => $produk)
                        <tr>
                            <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 border">{{ $produk['bahan_baku'] }}</td>
                            <td class="px-4 py-2 border">{{ $produk['spesifikasi'] }}</td>
                            <td class="px-4 py-2 border">{{ $produk['jumlah'] }}</td>
                            <td class="px-4 py-2 border">{{ $produk['keperluan_barang'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @php
            $permintaan_alat_bahan = (object) [
                'pic' => (object) [
                    'create_signature' => '',
                    'create_name' => '',
                    'receive_signature' => '',
                    'receive_name' => '',
                ],
            ];
        @endphp
        
        <div class="max-w-4xl mx-auto mt-10 text-sm">
            <div class="flex items-start justify-between gap-4">
                <div class="flex flex-col items-center">
                    <p class="mb-2 dark:text-white">Yang Membuat</p>
                    <img src="{{ asset('storage/' . $permintaan_alat_bahan->pic->create_signature) }}" alt="Signature"
                        class="h-20 w-80 object-contain" />
                    <div class="mt-2 font-medium dark:text-white">
                        {{ $permintaan_alat_bahan->pic->create_name }}
                    </div>
                </div>
                <div class="flex flex-col items-center">
                    <p class="mb-2 dark:text-white">Yang Menerima</p>
                    <img src="{{ asset('storage/' . $permintaan_alat_bahan->pic->receive_signature) }}" alt="Signature"
                        class="h-20 w-80 object-contain" />
                    <div class="mt-2 font-medium dark:text-white">
                        {{ $permintaan_alat_bahan->pic->receive_name }}
                    </div>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-panels::page>
