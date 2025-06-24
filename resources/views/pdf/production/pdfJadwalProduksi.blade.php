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
                        Jadwal Produksi
                    </td>
                    <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                        <table class="w-full text-sm dark:bg-gray-900 dark:text-white" style="border-collapse: collapse;">
                            <tr>
                                <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                                <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> :
                                    FO-QKS-PRO-01-01</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 border-b border-black dark:border-white">Tanggal Rilis</td>
                                <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> : 17 Juni 2025
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
        ['label' => 'Tanggal:', 'value' => '19 Juni 2025'],
        ['label' => 'Penanggung Jawab:', 'value' => 'Budi Santoso'],
    ];

    $mesin = ['label' => 'Mesin yang Digunakan:', 'value' => 'Mesin CNC A123'];
    $tenagaKerja = ['label' => 'Tenaga Kerja:', 'value' => '6 Orang'];
    @endphp

    <div class="max-w-4xl pt-6 mx-auto text-lg font-bold text-start">A. Informasi Umum</div>

    <div class="grid w-full max-w-4xl grid-cols-1 pt-2 mx-auto mb-4 text-sm gap-y-4">
        @foreach ($infoUmum as $field)
            <div class="flex flex-col gap-1">
                <label class="font-medium">{{ $field['label'] }}</label>
                <input type="text"
                    class="w-full px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    value="{{ $field['value'] }}" readonly />
            </div>
        @endforeach
    </div>

    <!-- B. Detail Jadwal Produksi -->
    <div class="max-w-4xl pt-2 mx-auto mb-4 text-lg font-bold text-start">B. Detail Jadwal Produksi</div>
    <div class="max-w-4xl mx-auto overflow-x-auto">
        <table class="w-full text-sm text-left border border-gray-300 dark:border-gray-600">
            <thead class="text-black bg-gray-100 dark:bg-gray-800 dark:text-white">
                <tr>
                    <th class="px-4 py-2 border">No</th>
                    <th class="px-4 py-2 border">Nama Produk</th>
                    <th class="px-4 py-2 border">Model/Type</th>
                    <th class="px-4 py-2 border">Volume</th>
                    <th class="px-4 py-2 border">Jumlah</th>
                    <th class="px-4 py-2 border">Tanggal Mulai</th>
                    <th class="px-4 py-2 border">Tanggal Selesai</th>
                    <th class="px-4 py-2 border">SPK MKT No.</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900">
                @foreach ([
        ['nama_produk' => 'Pompa Air Listrik', 'tipe' => 'PML-100', 'volume' => '500 L', 'jumlah' => '20', 'tanggal_mulai' => '2025-06-15', 'tanggal_selesai' => '2025-06-18'],
        ['nama_produk' => 'Kipas Angin Dinding', 'tipe' => 'KAD-250', 'volume' => 'N/A', 'jumlah' => '30', 'tanggal_mulai' => '2025-06-19', 'tanggal_selesai' => '2025-06-22'],
    ] as $index => $produk)
                    <tr>
                        <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                        <td class="px-4 py-2 border">{{ $produk['nama_produk'] }}</td>
                        <td class="px-4 py-2 border">{{ $produk['tipe'] }}</td>
                        <td class="px-4 py-2 border">{{ $produk['volume'] }}</td>
                        <td class="px-4 py-2 border">{{ $produk['jumlah'] }}</td>
                        <td class="px-4 py-2 border">{{ \Carbon\Carbon::parse($produk['tanggal_mulai'])->translatedFormat('d M Y') }}</td>
                        <td class="px-4 py-2 border">{{ \Carbon\Carbon::parse($produk['tanggal_selesai'])->translatedFormat('d M Y') }}</td>
                        <td class="px-4 py-2 border">SPK-2025/001</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Signature -->
    <div class="max-w-4xl mx-auto mt-10 text-sm">
        <div class="flex items-start justify-between gap-4">
            <div class="flex flex-col items-center">
                <p class="mb-2 dark:text-white">Dibuat Oleh</p>
                <img src="{{ asset('storage/dummy-signature-create.png') }}" alt="Signature Pembuat"
                    class="h-20 w-80" />
                <div class="mt-2 font-medium">Andi Nugraha</div>
            </div>
            <div class="flex flex-col items-center">
                <p class="mb-2 dark:text-white">Disetujui Oleh</p>
                <img src="{{ asset('storage/dummy-signature-approve.png') }}" alt="Signature Penerima"
                    class="h-20 w-80" />
                <div class="mt-2 font-medium">Fitri Rahmadani</div>
            </div>
        </div>
    </div>
    </div>
@endsection