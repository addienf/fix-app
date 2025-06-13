<x-filament-panels::page>
<x-filament::section>
    <h2 class="mb-3 text-xl font-bold text-center">Detail Incoming Material </h2>
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
                Formulir Incoming Material
            </td>
            <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                <table class="w-full text-sm dark:bg-gray-900" style="border-collapse: collapse;">
                    <tr>
                        <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                        <td class="px-3 py-2 font-semibold border-b border-black dark:border-white">
                            : FO-QKS-PRD-01-01</td>
                    </tr>
                    <tr>
                        <td class="px-3 py-2 border-b border-black dark:border-white">Tanggal Rilis</td>
                        <td class="px-3 py-2 font-semibold border-b border-black dark:border-white">
                            : 12 Maret 2025</td>
                    </tr>
                    <tr>
                        <td class="px-3 py-2">Revisi</td>
                        <td class="px-3 py-2 font-semibold">: 0</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <div class="w-full max-w-4xl mx-auto pt-4 space-y-2 text-sm">
        @php
$fields = [
    ['label' => 'Nomor :', 'value' => 'SPK-2025-001'],
    ['label' => 'Tanggal Penerimaan :', 'value' => '05 Juni 2025'],
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
    <h2 class=" w-full max-w-4xl mx-auto pt-4 text-xl font-bold text-start col-span-1 mb-4">
        A. Informasi Material
    </h2>
    <div class="max-w-4xl mx-auto mt-6 overflow-x-auto text-sm">
        <table class="min-w-full border border-black text-left text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border border-black px-3 py-2">No</th>
                    <th class="border border-black px-3 py-2">Nama Material</th>
                    <th class="border border-black px-3 py-2">Batch No.</th>
                    <th class="border border-black px-3 py-2">Jumlah Diterima</th>
                    <th class="border border-black px-3 py-2">Satuan</th>
                    <th class="border border-black px-3 py-2">Kondisi Material</th>
                    <th class="border border-black px-3 py-2">Status</th>
                </tr>
            </thead>
            <tbody>
                <!-- Contoh baris data dummy -->
                <tr>
                    <td class="border border-black px-3 py-2 text-center">1</td>
                    <td class="border border-black px-3 py-2">Contoh Barang</td>
                    <td class="border border-black px-3 py-2 text-center">10</td>
                    <td class="border border-black px-3 py-2 text-center">pcs</td>
                    <td class="border border-black px-3 py-2 text-center">Baik</td>
                    <td class="border border-black px-3 py-2">Tidak ada kerusakan</td>
                    <td class="border border-black px-3 py-2 text-center">Diterima</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="max-w-4xl mx-auto text-sm space-y-4">
        <!-- B. Pemeriksaan Material -->
        <div>
            <h2 class=" w-full max-w-4xl mx-auto pt-4 text-xl font-bold text-start col-span-1 mb-4">
                B. Pemeriksaan Material   
            </h2>
            <p class="ml-4">1 Apakah material dalam kondisi baik? (Ya/Tidak)</p>
            <div class="ml-8 mt-1 space-x-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" class="form-checkbox text-blue-600" />
                    <span class="ml-2">Ya</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" class="form-checkbox text-blue-600" />
                    <span class="ml-2">Tidak</span>
                </label>
            </div>
        </div>
    
        <!-- C. Status Penerimaan -->
        <div>
            <h2 class=" w-full max-w-4xl mx-auto pt-4 text-xl font-bold text-start col-span-1 mb-4">
                C. Status Penerimaan   </h2>
            <div class="ml-4 space-y-1">
                <label class="inline-flex items-center">
                    <input type="checkbox" class="form-checkbox text-blue-600" />
                    <span class="ml-2">Diterima</span>
                </label>
                <br />
                <label class="inline-flex items-center">
                    <input type="checkbox" class="form-checkbox text-blue-600" />
                    <span class="ml-2">Ditolak dan dikembalikan</span>
                </label>
            </div>
        </div>
    
        <!-- D. Dokumen Pendukung -->
        <div>
            <h2 class=" w-full max-w-4xl mx-auto pt-4 text-xl font-bold text-start col-span-1 mb-4">
                D. Dokumen Pendukung  </h2>
            <div class="ml-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" class="form-checkbox text-blue-600" />
                    <span class="ml-2">Laporan QC (Quality Control)</span>
                </label>
            </div>
        </div>
    </div>
    <div class="max-w-4xl mx-auto mt-10 text-sm">
        <div class="flex items-start justify-between gap-4">
            <div class="flex flex-col items-center">
                <p class="mb-2 dark:text-white">Diserahkan Oleh</p>
                <div class="h-20 w-80 "></div>
                <div class="mt-2 font-medium dark:text-white">
                    Nama Pembuat
                </div>
            </div>
            <div class="flex flex-col items-center">
                <p class="mb-2 dark:text-white">Diterima Oleh</p>
                <div class="h-20 w-80 "></div>
                <div class="mt-2 font-medium dark:text-white">
                    Nama Penerima
                </div>
            </div>
        </div>
    </div>
    
    

</x-filament::section>
</x-filament-panels::page>
