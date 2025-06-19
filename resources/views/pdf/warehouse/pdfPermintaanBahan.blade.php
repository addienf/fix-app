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
                    Formulir Permintaan Bahan
                </td>
                <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                    <table class="w-full text-sm dark:bg-gray-900 dark:text-white" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> :
                                FO-QKS-WRH-01-01</td>
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
        @php
            $infoUmum = [
                ['label' => 'Nomor :', 'value' => 'PB-2025-001'],
                ['label' => 'Tanggal :', 'value' => '19 Juni 2025'],
                ['label' => 'Dari : ', 'value' => 'Departemen Produksi'],
                ['label' => 'Kepada :', 'value' => 'Departemen Gudang'],
            ];
        @endphp

        <div class="grid max-w-4xl grid-cols-1 pt-6 mx-auto mb-6 text-sm md:grid-cols-2 gap-x-6 gap-y-4">
            @foreach ($infoUmum as $field)
                <div class="flex flex-col items-start gap-2 md:flex-row md:gap-4 md:items-center">
                    <label class="font-medium md:w-48">{{ $field['label'] }}</label>
                    <input type="text"
                        class="w-full px-2 py-1 text-black bg-white border border-gray-300 rounded dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                        value="{{ $field['value'] }}" />
                </div>
            @endforeach
        </div>

        <!-- PARAGRAF PERMINTAAN -->
        <div class="max-w-4xl mx-auto mb-6 text-sm">
            <p class="mb-2">Dengan hormat,</p>
            <p class="flex flex-wrap items-center gap-1">
                <span>Berdasarkan Permintaan Barang No</span>
                <input disabled class="px-2 py-1 text-sm align-middle bg-transparent border-none w-45 h-7"
                    value="PRB-2025-023" />
                <span>Dari Departemen</span>
                <input disabled class="w-32 px-2 py-1 text-sm align-middle bg-transparent border-none h-7"
                    value="Produksi" />
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
                    <tr>
                        <td class="px-4 py-2 border">1</td>
                        <td class="px-4 py-2 border">Kabel Listrik</td>
                        <td class="px-4 py-2 border">NYA 2.5mm, 100m</td>
                        <td class="px-4 py-2 border">3 Roll</td>
                        <td class="px-4 py-2 border">Instalasi Panel</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 border">2</td>
                        <td class="px-4 py-2 border">Pipa PVC</td>
                        <td class="px-4 py-2 border">4 inch, SDR 11</td>
                        <td class="px-4 py-2 border">20 Batang</td>
                        <td class="px-4 py-2 border">Saluran Limbah</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="max-w-4xl mx-auto mt-10 text-sm">
            <div class="flex items-start justify-between gap-4">
                <div class="flex flex-col items-center">
                    <p class="mb-2 dark:text-white">Terima Kasih</p>
                    <p class="mb-2 dark:text-white">Dibuat Oleh</p>
                    <img src="{{ asset('asset/tanda-tangan-dummy1.png') }}" alt="Create Signature" class="h-20 w-80" />
                    <div class="mt-2 font-medium">
                        Budi Prasetyo
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection