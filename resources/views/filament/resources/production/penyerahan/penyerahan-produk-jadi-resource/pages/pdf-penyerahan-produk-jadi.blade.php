<x-filament-panels::page>
    <x-filament::section>
    <h2 class="mb-3 text-xl font-bold text-center">
        Detail Penyerahan Produk Jadi
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
                Formulir Penyerahan Produk Jadi
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
    <h2 class="w-full max-w-4xl mx-auto pt-4 mb-3 text-xl font-bold text-start col-span-1">
        A. Detail Standarisasi Gambar Kerja
    </h2>
    @php
$fields = [
    ['label' => 'Tanggal  :', 'value' => 'SPK-2025-001'],
    ['label' => 'Penanggung Jawab :', 'value' => '05 Juni 2025'],
    ['label' => 'Penerima :', 'value' => '05 Juni 2025'],
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
    <h2 class="w-full max-w-4xl mx-auto pt-4 mb-3 text-xl font-bold text-start col-span-1">
        B. Detail Jadwal Produksi
    </h2>
    <div class="max-w-4xl mx-auto mt-6 overflow-x-auto text-sm">
        <table class="w-full border border-black border-collapse text-center">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border border-black px-2 py-1">No</th>
                    <th class="border border-black px-2 py-1">Nomor Produk</th>
                    <th class="border border-black px-2 py-1">Model/Type</th>
                    <th class="border border-black px-2 py-1">Volume</th>
                    <th class="border border-black px-2 py-1">No Seri</th>
                    <th class="border border-black px-2 py-1">Jumlah </th>
                    <th class="border border-black px-2 py-1">SPK MKT No.</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 1; $i <= 5; $i++)
                    <tr>
                        <td class="border border-black px-2 py-1">{{ $i }}</td>
                        <td class="border border-black px-2 py-1">Contoh Barang {{ $i }}</td>
                        <td class="border border-black px-2 py-1">10</td>
                        <td class="border border-black px-2 py-1">pcs</td>
                        <td class="border border-black px-2 py-1">10.000</td>
                        <td class="border border-black px-2 py-1">100.000</td>
                        <td class="border border-black px-2 py-1">-</td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
    <h2 class="w-full max-w-4xl mx-auto pt-4 mb-3 text-xl font-bold text-start col-span-1">
        C. Kondisi Produk 
    </h2>
    <div class="w-full max-w-4xl mx-auto flex flex-col items-start space-y-2 text-sm">
        <label class="font-medium mb-1">Kondisi</label>
    
        <label class="inline-flex items-center space-x-2">
            <input type="checkbox" name="kondisi" value="Baik"
                class="appearance-none w-4 h-4 border border-gray-400 checked:bg-blue-600 checked:border-blue-600">
            <span>Baik</span>
        </label>
    
        <label class="inline-flex items-center space-x-2">
            <input type="checkbox" name="kondisi" value="Rusak"
                class="appearance-none w-4 h-4 border border-gray-400 checked:bg-blue-600 checked:border-blue-600">
            <span>Rusak</span>
        </label>
    
        <label class="inline-flex items-center space-x-2">
            <input type="checkbox" name="kondisi" value="Perlu Perbaikan"
                class="appearance-none w-4 h-4 border border-gray-400 checked:bg-blue-600 checked:border-blue-600">
            <span>Perlu Perbaikan</span>
        </label>
    </div>
    <h2 class="w-full max-w-4xl mx-auto pt-4 text-xl font-bold text-start col-span-1">
        C. Catatan Tambahan 
    </h2>
    <div class="max-w-4xl mx-auto pt-4 text-sm">
        <label for="note" class="block font-medium mb-2">Catatan</label>
        <textarea id="note" rows="4"
            class="w-full px-4 py-2 border border-gray-300 rounded-md resize-y focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-900 dark:text-white dark:border-gray-600"
            placeholder="Tulis catatan di sini..."></textarea>
    </div>
    <div class="max-w-4xl mx-auto mt-10 text-sm">
        <div class="flex items-start justify-between gap-4">
            <!-- Kolom Kiri -->
            <div class="flex flex-col items-center w-1/2">
                <p class="mb-2 font-medium dark:text-white">Yang Membuat</p>
                <img src="" alt="Signature" class="object-contain h-20 w-64 border" />
            </div>
    
            <!-- Kolom Kanan -->
            <div class="flex flex-col items-center w-1/2">
                <p class="mb-2 font-medium dark:text-white">Yang Menerima</p>
                <img src="" alt="Signature" class="object-contain h-20 w-64 border" />
            </div>
        </div>
    </div>
</x-filament::section>
</x-filament-panels::page>
