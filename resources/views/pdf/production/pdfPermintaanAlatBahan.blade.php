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
                    Formulir Permintaan Bahan Baku <br> dan Alat Kerja untuk Produksi
                </td>
                <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                    <table class="w-full text-sm dark:bg-gray-900 dark:text-white" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> :
                                FO-QKS-PRD-01-01</td>
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
                ['label' => 'Nomor Surat :', 'value' => 'NS-001/PK/VI/2025'],
                ['label' => 'Dari :', 'value' => 'Divisi Produksi'],
                ['label' => 'Tanggal : ', 'value' => '19 Jun 2025'],
                ['label' => 'Kepada :', 'value' => 'Divisi Gudang'],
            ];
        @endphp
        <div class="grid max-w-4xl grid-cols-1 pt-2 pt-4 pt-6 mx-auto mb-6 text-sm md:grid-cols-2 gap-x-6 gap-y-4">
            @foreach ($infoUmum as $field)
                <div class="flex flex-col items-start gap-2 md:flex-row md:gap-4 md:items-center">
                    <label class="font-medium md:w-48">{{ $field['label'] }}</label>
                    <input disabled
                        class="w-full h-[32px] px-2 py-1 text-black bg-white border border-gray-300 rounded dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                        value="{{ $field['value'] }}" />
                </div>
            @endforeach
        </div>

        <!-- PARAGRAF PERMINTAAN -->
        <div class="max-w-4xl mx-auto mb-6 text-sm">
            <p class="mb-2">Dengan hormat,</p>
            <p class="flex flex-wrap items-center gap-1">
                <span>Berdasarkan SPK MKT No.</span>
                <input disabled class="px-2 py-1 text-sm align-middle bg-transparent border-none w-45 h-7"
                    value="SPK-MKT/0123/VI/2025" />
                <span>mohon bantuan untuk memenuhi kebutuhan bahan/sparepart dengan rincian sebagai berikut:</span>
            </p>
        </div>

        <!-- TABEL PRODUK -->
        <div class="max-w-4xl mx-auto overflow-x-auto">
            <table class="w-full text-sm text-left border border-gray-300 dark:border-gray-600">
                <thead class="text-black bg-gray-100 dark:bg-gray-800 dark:text-white">
                    <tr>
                        <th class="px-4 py-2 border">No</th>
                        <th class="px-4 py-2 border">Nama Bahan Baku</th>
                        <th class="px-4 py-2 border">Spesifikasi</th>
                        <th class="px-4 py-2 border">Jumlah</th>
                        <th class="px-4 py-2 border">Keperluan Barang</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900">
                    <tr>
                        <td class="px-4 py-2 border">1</td>
                        <td class="px-4 py-2 border">Pipa PVC</td>
                        <td class="px-4 py-2 border">1/2 inch, 3 meter</td>
                        <td class="px-4 py-2 border">20</td>
                        <td class="px-4 py-2 border">Instalasi air produksi</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 border">2</td>
                        <td class="px-4 py-2 border">Kabel NYY</td>
                        <td class="px-4 py-2 border">3x2.5mm</td>
                        <td class="px-4 py-2 border">50 meter</td>
                        <td class="px-4 py-2 border">Listrik mesin baru</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 border">3</td>
                        <td class="px-4 py-2 border">Oli Pelumas</td>
                        <td class="px-4 py-2 border">Shell Tellus 46</td>
                        <td class="px-4 py-2 border">10 liter</td>
                        <td class="px-4 py-2 border">Pemeliharaan mesin</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- TANDA TANGAN -->
        <div class="max-w-4xl mx-auto mt-10 text-sm">
            <div class="flex items-start justify-between gap-4">
                <div class="flex flex-col items-center">
                    <p class="mb-2 dark:text-white">Yang Membuat</p>
                    <img src="{{ asset('storage/signatures/dummy-submit.png') }}" alt="Product Signature" class="h-20 w-80" />
                    <div class="mt-2 font-medium">Rudi Hartono</div>
                </div>
                <div class="flex flex-col items-center">
                    <p class="mb-2 dark:text-white">Yang Menerima</p>
                    <img src="{{ asset('storage/signatures/dummy-receive.png') }}" alt="Product Signature" class="h-20 w-80" />
                    <div class="mt-2 font-medium">Siti Maemunah</div>
                </div>
            </div>
        </div>
    </div>
@endsection
