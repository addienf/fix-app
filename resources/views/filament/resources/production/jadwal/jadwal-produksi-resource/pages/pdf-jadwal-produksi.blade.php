<x-filament-panels::page>
    <x-filament::section>
        <h2 class="mb-3 text-xl font-bold text-center">Detail Jadwal Produksi</h2>

        <!-- HEADER DOKUMEN -->
        <table class="w-full max-w-4xl mx-auto text-sm border border-black dark:border-gray-600" style="border-collapse: collapse;">
            <tr>
                <td rowspan="3" class="p-2 text-center align-middle border border-black w-28 h-28">
                    <img src="{{ asset('asset/logo.png') }}" alt="Logo" class="object-contain mx-auto h-30" />
                </td>
                <td colspan="2" class="font-bold text-center border border-black">
                    PT. QLab Kinarya Sentosa
                </td>
            </tr>
            <tr>
                <td class="font-bold text-center border border-black" style="font-size: 20px;">
                    Jadwal Produksi
                </td>
                <td rowspan="2" class="p-0 align-top border border-black">
                    <table class="w-full text-sm" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-2">No. Dokumen</td>
                            <td class="px-3 py-2 font-semibold">: FO-QKS-PRD-01-01</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2">Tanggal Rilis</td>
                            <td class="px-3 py-2 font-semibold">: 12 Maret 2025</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2">Revisi</td>
                            <td class="px-3 py-2 font-semibold">: 0</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- A. Informasi Umum -->
        <div class="mb-3 text-lg font-bold text-start pt-4 max-w-4xl mx-auto">A. Informasi Umum</div>

        @php
            $infoUmum = [
                ['label' => 'Tanggal:', 'value' => '30 Mei 2025'],
                ['label' => 'Penanggung Jawab:', 'value' => 'Budi Santoso'],
            ];

            $produkList = [
                [
                    'nama' => 'Produk A',
                    'model' => 'Type A1',
                    'volume' => '100 L',
                    'jumlah' => 100,
                    'mulai' => '2025-06-01',
                    'selesai' => '2025-06-05',
                    'spk' => 'SPK-001',
                ],
                [
                    'nama' => 'Produk B',
                    'model' => 'Type B1',
                    'volume' => '200 L',
                    'jumlah' => 200,
                    'mulai' => '2025-06-06',
                    'selesai' => '2025-06-10',
                    'spk' => 'SPK-002',
                ],
            ];

            $mesin = ['label' => 'Mesin yang Digunakan:', 'value' => 'Mesin Cetak X100'];
            $tenagaKerja = ['label' => 'Tenaga Kerja:', 'value' => '10 Orang'];
            $bahanBaku = [
                ['nama' => 'Plastik', 'jumlah' => '50 Kg'],
                ['nama' => 'Cat', 'jumlah' => '20 Liter'],
                ['nama' => 'Perekat', 'jumlah' => '10 Kg'],
            ];
        @endphp

        <div class="grid max-w-4xl grid-cols-1 pt-2 mx-auto mb-6 text-sm md:grid-cols-2 gap-x-6 gap-y-4">
            @foreach ($infoUmum as $field)
                <div class="flex flex-col items-start gap-2 md:flex-row md:gap-4 md:items-center">
                    <label class="font-medium md:w-48">{{ $field['label'] }}</label>
                    <input type="text"
                        class="flex-1 w-full px-2 py-1 text-black bg-white border border-gray-300 rounded cursor-not-allowed dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                        value="{{ $field['value'] }}" readonly />
                </div>
            @endforeach
        </div>

        <!-- B. Detail Jadwal Produksi -->
        <div class="mb-4 text-lg font-bold text-start pt-2 max-w-4xl mx-auto">B. Detail Jadwal Produksi</div>
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
                    @foreach ($produkList as $index => $produk)
                        <tr>
                            <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 border">{{ $produk['nama'] }}</td>
                            <td class="px-4 py-2 border">{{ $produk['model'] }}</td>
                            <td class="px-4 py-2 border">{{ $produk['volume'] }}</td>
                            <td class="px-4 py-2 border">{{ $produk['jumlah'] }}</td>
                            <td class="px-4 py-2 border">{{ $produk['mulai'] }}</td>
                            <td class="px-4 py-2 border">{{ $produk['selesai'] }}</td>
                            <td class="px-4 py-2 border">{{ $produk['spk'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- C. Sumber Daya yang Digunakan -->
        <div class="mb-3 text-lg font-bold text-start pt-4 max-w-4xl mx-auto">C. Sumber Daya yang Digunakan</div>

        <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 text-sm">
            <!-- Mesin -->
            <div class="flex flex-col items-start gap-2">
                <label class="font-medium md:w-full">{{ $mesin['label'] }}</label>
                <input type="text"
                    class="w-full px-2 py-1 text-black bg-white border border-gray-300 rounded cursor-not-allowed dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    value="{{ $mesin['value'] }}" readonly />
            </div>

            <!-- Tenaga Kerja -->
            <div class="flex flex-col items-start gap-2">
                <label class="font-medium md:w-full">{{ $tenagaKerja['label'] }}</label>
                <input type="text"
                    class="w-full px-2 py-1 text-black bg-white border border-gray-300 rounded cursor-not-allowed dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    value="{{ $tenagaKerja['value'] }}" readonly />
            </div>
        </div>

        <!-- Bahan Baku (Tabel) -->
        <div class="max-w-4xl mx-auto overflow-x-auto text-sm">
            <table class="w-full border border-gray-300 dark:border-gray-600">
                <thead class="bg-gray-100 dark:bg-gray-800 text-black dark:text-white">
                    <tr>
                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-left">Bahan Baku</th>
                       
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900">
                    @foreach ($bahanBaku as $bahan)
                        <tr>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">{{ $bahan['nama'] }}</td>
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Signature Section -->
        <div class="max-w-4xl mx-auto mt-10 text-sm">
            <div class="flex items-start justify-between gap-4">
                <!-- Yang Membuat -->
                <div class="flex flex-col items-center">
                    <p class="mb-2 dark:text-white">Yang Membuat</p>
                    <img src="" alt="Signature Pembuat" class="h-20 w-80" />
                </div>
                <!-- Yang Menerima -->
                <div class="flex flex-col items-center">
                    <p class="mb-2 dark:text-white">Yang Menerima</p>
                    <img src="" alt="Signature Penerima" class="h-20 w-80" />
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-panels::page>
