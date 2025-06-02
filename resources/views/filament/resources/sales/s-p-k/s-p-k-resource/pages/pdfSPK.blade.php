<x-filament-panels::page>
    <x-filament::section>
        <h2 class="mb-3 text-xl font-bold text-center">Detail Surat Perintah Kerja</h2>
        <!-- HEADER DOKUMEN MIRIP EXCEL -->
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
                    Surat Perintah Kerja
                </td>
                <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                    <table class="w-full text-sm dark:bg-gray-900" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> : FO-QKS-PRD-01-01</td>
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
    
        <!-- Form Input -->
        <div class="grid max-w-4xl grid-cols-1 pt-6 mx-auto mb-6 text-sm md:grid-cols-2 gap-x-6 gap-y-4">
            @php
$fields = [
    [
        'label' => 'Tanggal :',
        'value' => \Carbon\Carbon::parse($spk_mkt->tanggal)->translatedFormat('d F Y'),
    ],
    ['label' => 'No SPK :', 'value' => $spk_mkt->no_spk],
    ['label' => 'Customer :', 'value' => $spk_mkt->spesifikasiProduct->urs->customer->name],
    ['label' => 'Dari :', 'value' => $spk_mkt->dari],
    ['label' => 'No Order :', 'value' => $spk_mkt->no_order],
    ['label' => 'Kepada :', 'value' => $spk_mkt->kepada],
];
            @endphp

            @foreach ($fields as $field)
                <div class="flex flex-col items-start gap-2 md:flex-row md:gap-4 md:items-center">
                    <label class="font-medium md:w-40">{{ $field['label'] }}</label>
                    <input type="text"
                        class="w-[400px] px-2 py-1 text-black bg-white border border-gray-300 rounded cursor-not-allowed dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                        value="{{ $field['value'] }}" />
                </div>
            @endforeach
        </div>

        <!-- Tabel Produk -->
        <div class="max-w-4xl mx-auto overflow-x-auto">
            <table class="w-full text-sm text-left border border-gray-300 dark:border-gray-600">
                <thead class="text-black bg-gray-100 dark:bg-gray-800 dark:text-white">
                    <tr>
                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">Nomor</th>
                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">Nama Produk</th>
                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">Jumlah Pesanan</th>
                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">No URS</th>
                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">Rencana Pengiriman</th>
                    </tr>
                </thead>
                <tbody class="text-black bg-white dark:bg-gray-900 dark:text-white">
                    @foreach ($spk_mkt->spesifikasiProduct->details as $item)
                        <tr>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">{{ $loop->iteration }}
                            </td>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                {{ $item->product->name }}</td>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">{{ $item->quantity }}</td>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                {{ $spk_mkt->spesifikasiProduct->urs->no_urs }}</td>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                {{ \Carbon\Carbon::parse($spk_mkt->spesifikasiProduct->date)->translatedFormat('d F Y') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Catatan & Tanda Tangan -->
        <div class="max-w-4xl mx-auto mt-10 text-sm">
            <p class="mb-4 dark:text-white">*Salinan URS Wajib diberikan kepada Departemen Produksi</p>
            <div class="flex items-start justify-between gap-4">
                <!-- Kiri -->
                <div class="flex flex-col items-center">
                    <p class="mb-2 dark:text-white">Yang Membuat</p>
                    <img src="{{ asset('storage/' . $spk_mkt->pic->create_signature) }}" alt="Product Signature"
                        class="h-20 w-80" />
                    <p class="mt-1 font-semibold dark:text-white">{{ $spk_mkt->dari }}</p>
                    <p class="mt-1 font-semibold dark:text-white">Marketing</p>
                </div>
                <!-- Kanan -->
                <div class="flex flex-col items-center">
                    <p class="mb-2 dark:text-white">Yang Menerima</p>
                    <img src="{{ asset('storage/' . $spk_mkt->pic->receive_signature) }}" alt="Product Signature"
                        class="h-20 w-80" />
                    <p class="mt-1 font-semibold dark:text-white">{{ $spk_mkt->kepada }}</p>
                    <p class="mt-1 font-semibold dark:text-white">Produksi</p>
                </div>
            </div>
        </div>

    </x-filament::section>
</x-filament-panels::page>
