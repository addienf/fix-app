<x-filament-panels::page>
    <x-filament::section>
        <h2 class="mb-3 text-xl font-bold text-center">Detail Formulir Laporan Produk Jadi Masuk dan Keluar</h2>
        <table
            class="w-full max-w-4xl mx-auto text-sm border border-black dark:border-white dark:bg-gray-900 dark:text-white"
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
                    Formulir Laporan <br> Produk Jadi Masuk <br> dan Keluar
                </td>
                <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                    <table class="w-full text-sm dark:bg-gray-900" style="border-collapse: collapse;">
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
        <div class="w-full max-w-4xl mx-auto pt-2 mb-6 text-sm grid grid-cols-1 gap-y-4">
            <h2 class="text-xl font-bold text-start col-span-1">
                A. Informasi Umum
            </h2>
            <div class="w-full max-w-4xl mx-auto pt-4 space-y-2 text-sm">
                @php
$fields = [
    ['label' => 'Tanggal :', 'value' => 'SPK-2025-001'],
    ['label' => 'Penanggung Jawab :', 'value' => '05 Juni 2025'],
];
                @endphp

                @foreach ($fields as $field)
                    <div class="flex items-center">
                        <label class="w-64 font-medium">{{ $field['label'] }}</label>
                        <input type="text" readonly value="{{ $field['value'] }}"
                            class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                    </div>
                @endforeach
            </div>


            <h2 class="text-xl font-bold text-start col-span-1">
                B. Detail Laporan Produk
            </h2>
        </div>
        <div class="max-w-4xl mx-auto overflow-x-auto">
            <table class="w-full text-sm text-left border border-gray-300">
                <thead class="text-black bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 border">No</th>
                        <th class="px-4 py-2 border">Nama Produk</th>
                        <th class="px-4 py-2 border">Model/Type</th>
                        <th class="px-4 py-2 border">S/N</th>
                        <th class="px-4 py-2 border">Jenis Transaksi <br> (Masuk atau Keluar)</th>
                        <th class="px-4 py-2 border">Jumlah</th>
                        <th class="px-4 py-2 border">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <tr>
                        <td class="px-4 py-2 border">1</td>
                        <td class="px-4 py-2 border">Kabel NYM</td>
                        <td class="px-4 py-2 border">2x1.5 mm</td>
                        <td class="px-4 py-2 border">100 meter</td>
                        <td class="px-4 py-2 border">Instalasi Listrik</td>
                        <td class="px-4 py-2 border">Instalasi Listrik</td>
                        <td class="px-4 py-2 border">Instalasi Listrik</td>
                    </tr>
                </tbody>
            </table>
            <h2 class="pt-4 text-xl font-bold text-start col-span-1 mb-4">
                C. Syarat dan Ketentuan
            </h2>
            <div class="w-full max-w-4xl mx-auto pt-4 space-y-2 text-sm">
                @php
$fields = [
    ['label' => 'Total Produk Masuk :', 'value' => 'SPK-2025-001'],
    ['label' => 'Total Produk Keluar :', 'value' => '05 Juni 2025'],
    ['label' => 'Sisa Stock :', 'value' => '05 Juni 2025'],
];
                @endphp

                @foreach ($fields as $field)
                    <div class="flex items-center">
                        <label class="w-64 font-medium">{{ $field['label'] }}</label>
                        <input type="text" readonly value="{{ $field['value'] }}"
                            class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                    </div>
                @endforeach
            </div>
            <div class="max-w-4xl mx-auto mt-10 text-sm">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex flex-col items-center">
                        <p class="mb-2 dark:text-white">Yang Membuat</p>
                        <div class="h-20 w-80 "></div>
                        <div class="mt-2 font-medium dark:text-white">
                            Nama Pembuat
                        </div>
                    </div>
                    <div class="flex flex-col items-center">
                        <p class="mb-2 dark:text-white">Yang Menerima</p>
                        <div class="h-20 w-80 "></div>
                        <div class="mt-2 font-medium dark:text-white">
                            Nama Penerima
                        </div>
                    </div>
                </div>
            </div>
            

    </x-filament::section>
</x-filament-panels::page>