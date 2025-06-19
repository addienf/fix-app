@extends ('pdf.layout.layout')

@section('content')
    <div id="export-area" class="p-2 bg-white text-black">
        <table class="w-full max-w-4xl mx-auto text-sm border border-black dark:border-white dark:bg-gray-900 dark:text-white"
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
                    Formulir Permintaan Pembelian
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
            <div class="max-w-4xl mx-auto mb-6 text-sm pt-6">
                <p class="mb-2">Dengan hormat,</p>
                <p class="flex flex-wrap items-center gap-1">
                    <span>Berdasarkan Permintaan Barang No.</span>
                    <input disabled class="w-45 px-2 py-1 text-sm align-middle bg-transparent border-none h-7"
                        value="PB-2025-001" />
                    <span>mohon bantuan untuk memenuhi kebutuhan bahan/sparepart dengan rincian sebagai berikut:</span>
                </p>
            </div>

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
                        <tr>
                            <td class="px-4 py-2 border">1</td>
                            <td class="px-4 py-2 border">BRG-001</td>
                            <td class="px-4 py-2 border">Baut Stainless M6</td>
                            <td class="px-4 py-2 border">50 pcs</td>
                            <td class="px-4 py-2 border">Untuk perakitan casing</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 border">2</td>
                            <td class="px-4 py-2 border">BRG-002</td>
                            <td class="px-4 py-2 border">Kabel NYY 2x1.5mm</td>
                            <td class="px-4 py-2 border">100 m</td>
                            <td class="px-4 py-2 border">Instalasi listrik ruang produksi</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 border">3</td>
                            <td class="px-4 py-2 border">BRG-003</td>
                            <td class="px-4 py-2 border">Seal Packing 3 inch</td>
                            <td class="px-4 py-2 border">10 pcs</td>
                            <td class="px-4 py-2 border">Spare mesin utama</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="max-w-4xl mx-auto mt-10 text-sm">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex flex-col items-center">
                        <p class="mb-2 dark:text-white">Yang Membuat</p>
                        <img src="https://via.placeholder.com/200x50?text=Tanda+Tangan+A" alt="Signature"
                            class="object-contain h-20 w-80" />
                        <div class="mt-2 font-medium dark:text-white">Andi Prasetyo</div>
                    </div>
                    <div class="flex flex-col items-center">
                        <p class="mb-2 dark:text-white">Yang Menerima</p>
                        <img src="https://via.placeholder.com/200x50?text=Tanda+Tangan+B" alt="Signature"
                            class="object-contain h-20 w-80" />
                        <div class="mt-2 font-medium dark:text-white">Budi Santoso</div>
                    </div>
                </div>
            </div>
    </div>

@endsection