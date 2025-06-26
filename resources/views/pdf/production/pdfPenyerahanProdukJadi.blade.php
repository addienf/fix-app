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
                            Formulir Penyerahan Produk Jadi
                        </td>
                        <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                            <table class="w-full text-sm dark:bg-gray-900 dark:text-white" style="border-collapse: collapse;">
                                <tr>
                                    <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                                    <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> :
                                        FO-QKS-PRO-01-02</td>
                                </tr>
                                <tr>
                                    <td class="px-3 py-2 border-b border-black dark:border-white">Tanggal Rilis</td>
                                    <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> : 17 Juni 2025
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-3 py-2">Revisi</td>
                                    <td class="px-3 py-2 font-semibold"> : 01</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                @php
    $fields = [
        ['label' => 'Tanggal  :', 'value' => '19 Juni 2025'],
        ['label' => 'Penanggung Jawab :', 'value' => 'Budi Santoso'],
        ['label' => 'Penerima :', 'value' => 'Rina Sari'],
    ];

    $produkJadiDetails = [
        [
            'nama_produk' => 'Panel Kontrol',
            'tipe' => 'CTRL-A12',
            'volume' => '1 unit',
            'no_seri' => 'SN-001',
            'jumlah' => 10,
            'no_spk' => 'SPK/ME/2025/011',
        ],
        [
            'nama_produk' => 'Modul Inverter',
            'tipe' => 'INV-X3',
            'volume' => '1 unit',
            'no_seri' => 'SN-002',
            'jumlah' => 5,
            'no_spk' => 'SPK/ME/2025/012',
        ],
    ];

    $kondisi = 'perlu_perbaikan';
    $catatanTambahan = 'Beberapa konektor tidak sesuai dengan spesifikasi awal.';
    $pic = [
        'submit_name' => 'Dewi Lestari',
        'submit_signature' => 'dummy-submit.png',
        'receive_name' => 'Hendra Wijaya',
        'receive_signature' => 'dummy-receive.png',
    ];
                @endphp

                <h2 class="w-full max-w-4xl col-span-1 pt-4 mx-auto text-xl font-bold text-start">
                    A. Informasi Umum
                </h2>

                <div class="grid w-full max-w-4xl grid-cols-1 pt-4 mx-auto text-sm gap-y-4">
                    @foreach ($fields as $field)
                        <div class="flex flex-col gap-1">
                            <label class="font-medium">{{ $field['label'] }}</label>
                            <input type="text" readonly value="{{ $field['value'] }}"
                                class="w-full px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                        </div>
                    @endforeach      
                </div>

                <h2 class="w-full max-w-4xl col-span-1 pt-4 mx-auto mb-3 text-xl font-bold text-start">
                    B. Detail Produk
                </h2>

                <div class="w-full max-w-4xl mx-auto mt-6 overflow-x-auto text-sm">
                    <table class="w-full text-sm text-left border border-gray-300 dark:border-gray-600">
                        <thead class="text-black bg-gray-100 dark:bg-gray-800 dark:text-white">
                            <tr>
                                <th class="px-2 py-1 border border-gray">No</th>
                                <th class="px-2 py-1 border border-gray">Nama Produk</th>
                                <th class="px-2 py-1 border border-gray">Model/Type</th>
                                <th class="px-2 py-1 border border-gray">Volume</th>
                                <th class="px-2 py-1 border border-gray">Jumlah</th>
                                <th class="px-2 py-1 border border-gray">SPK MKT No.</th>
                            </tr>
                        </thead>
                        <tbody class="text-black bg-white dark:bg-gray-900 dark:text-white">
                            @foreach ($produkJadiDetails as $index => $item)
                                <tr>
                                    <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">{{ $item['nama_produk'] }}</td>
                                    <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">{{ $item['tipe'] }}</td>
                                    <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">{{ $item['volume'] }}</td>
                                    <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">{{ $item['jumlah'] }}</td>
                                    <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">{{ $item['no_spk'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <h2 class="w-full max-w-4xl col-span-1 pt-4 mx-auto mb-3 text-xl font-bold text-start">
                    C. Kondisi Produk
                </h2>

                <div class="flex flex-col items-start w-full max-w-4xl mx-auto space-y-2 text-sm">
    
                    <label class="inline-flex items-center space-x-2">
                        <input type="checkbox" {{ $kondisi === 'baik' ? 'checked' : '' }} disabled
                            class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600">
                        <span>Baik</span>
                    </label>

                    <label class="inline-flex items-center space-x-2">
                        <input type="checkbox" {{ $kondisi === 'rusak' ? 'checked' : '' }} disabled
                            class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600">
                        <span>Rusak</span>
                    </label>

                    <label class="inline-flex items-center space-x-2">
                        <input type="checkbox" {{ $kondisi === 'perlu_perbaikan' ? 'checked' : '' }} disabled
                            class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600">
                        <span>Perlu Perbaikan</span>
                    </label>
                </div>

                <h2 class="w-full max-w-4xl col-span-1 pt-4 mx-auto text-xl font-bold text-start">
                    D. Catatan Tambahan
                </h2>

                <div class="max-w-4xl pt-4 mx-auto text-sm">
                    <label class="block mb-1 font-semibold">Catatan</label>
                    <div
                        class="w-full min-h-[75px] px-3 py-2 text-sm leading-relaxed text-left border rounded-md text-black border-black">
                        {{ $catatanTambahan }}
                    </div>
                </div>

                <div class="max-w-4xl mx-auto mt-10 text-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex flex-col items-center">
                            <p class="mb-2 dark:text-white">Diserahkan Oleh</p>
                            <img src="{{ asset('storage/' . $pic['submit_signature']) }}" alt="Signature"
                                class="h-20 w-80 object-contain bg-white border rounded border-gray-300" />
                            <p class="mt-4 font-semibold dark:text-white">{{ $pic['submit_name'] }}</p>
                        </div>

                        <div class="flex flex-col items-center">
                            <p class="mb-2 dark:text-white">Diterima Oleh</p>
                            <img src="{{ asset('storage/' . $pic['receive_signature']) }}" alt="Signature"
                                class="h-20 w-80 object-contain bg-white border rounded border-gray-300" />
                            <p class="mt-4 font-semibold dark:text-white">{{ $pic['receive_name'] }}</p>
                        </div>
                    </div>
                </div>

            </div>
@endsection