<x-filament-panels::page>
    <x-filament::section>
    <h2 class="mb-3 text-xl font-bold text-center">
        Detail SPK Quality
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
            <td class="text-lg font-bold text-center align-middle border border-black dark:border-white dark:bg-gray-900"
                style="width: 400px;">
                Formulir SPK Quality
            </td>
            <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                <table class="w-full text-sm dark:bg-gray-900 dark:text-white" style="border-collapse: collapse;">
                    <tr>
                        <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                        <td class="px-3 py-2 font-semibold border-b border-black dark:border-white">:
                            FO-QKS-PRD-01-01</td>
                    </tr>
                    <tr>
                        <td class="px-3 py-2 border-b border-black dark:border-white">Tanggal Rilis</td>
                        <td class="px-3 py-2 font-semibold border-b border-black dark:border-white">: 12 Maret 2025
                        </td>
                    </tr>
                    <tr>
                        <td class="px-3 py-2">Revisi</td>
                        <td class="px-3 py-2 font-semibold">: 0</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    @php
$fields = [
    ['label' => 'No SPK  :', 'value' => 'SPK-2025-001'],
    ['label' => 'No SPK MKT :', 'value' => '05 Juni 2025'],
    ['label' => 'Dari :', 'value' => '05 Juni 2025'],
    ['label' => 'Kepada :', 'value' => '05 Juni 2025'],
];
    @endphp
    
    <div class="w-full max-w-4xl mx-auto pt-4 text-sm grid grid-cols-2 gap-x-6 gap-y-4">
        @foreach ($fields as $field)
            <div class="flex items-center">
                <label class="w-32 font-medium">{{ $field['label'] }}</label>
                <input type="text" readonly value="{{ $field['value'] }}"
                    class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
            </div>
        @endforeach
    </div>
    <div class="w-full max-w-4xl mx-auto mt-6 text-sm overflow-x-auto">
        <table class="w-full table-fixed border border-black text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="w-1/12 border border-black px-2 py-1">No</th>
                    <th class="w-3/12 border border-black px-2 py-1">Nama Produk yang Dipesan</th>
                    <th class="w-2/12 border border-black px-2 py-1">Jumlah Pesanan</th>
                    <th class="w-3/12 border border-black px-2 py-1">URS No.</th>
                    <th class="w-3/12 border border-black px-2 py-1">Rencana Pengiriman</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 1; $i <= 5; $i++)
                    <tr>
                        <td class="border border-black px-2 py-1 text-center">{{ $i }}</td>
                        <td class="border border-black px-2 py-1">-</td>
                        <td class="border border-black px-2 py-1">-</td>
                        <td class="border border-black px-2 py-1">-</td>
                        <td class="border border-black px-2 py-1">-</td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
    <div class="max-w-4xl mx-auto mt-10 text-sm">
        <div class="flex items-start justify-between gap-4">
            <!-- Kolom Kiri -->
            <div class="flex flex-col items-center w-1/2">
                <p class="mb-2 font-medium dark:text-white">Yang Membuat</p>
                <img src="" alt="Signature" class="object-contain h-20 w-64 border" />
                <p class="mt-4 font-semibold dark:text-white">Produksi</p>
            </div>
    
            <!-- Kolom Kanan -->
            <div class="flex flex-col items-center w-1/2">
                <p class="mb-2 font-medium dark:text-white">Yang Menerima</p>
                <img src="" alt="Signature" class="object-contain h-20 w-64 border" />
                <p class="mt-4 font-semibold dark:text-white">QC</p>
            </div>
        </div>
    </div>
    
</x-filament::section>
</x-filament-panels::page>
