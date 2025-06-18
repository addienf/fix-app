<x-filament-panels::page>
    <x-filament::section>
        <h2 class="mb-3 text-xl font-bold text-center">Detail Jadwal Produksi</h2>

        <!-- HEADER DOKUMEN -->
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
                    Jadwal Produksi
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


        <!-- A. Informasi Umum -->
        <div class="max-w-4xl pt-6 mx-auto mb-3 text-lg font-bold text-start">A. Informasi Umum</div>
        @php
            $infoUmum = [
                [
                    'label' => 'Tanggal:',
                    'value' => \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d M Y'),
                ],
                ['label' => 'Penanggung Jawab:', 'value' => $jadwal->pic_name],
            ];
            $mesin = ['label' => 'Mesin yang Digunakan:', 'value' => $jadwal->sumber->mesin_yang_digunakan];
            $tenagaKerja = ['label' => 'Tenaga Kerja:', 'value' => $jadwal->sumber->tenaga_kerja];
        @endphp

        <div class="grid max-w-4xl grid-cols-1 pt-2 mx-auto mb-6 text-sm md:grid-cols-2 gap-x-6 gap-y-4">
            @foreach ($infoUmum as $field)
                <div class="flex flex-col items-start gap-2 md:flex-row md:gap-4 md:items-center">
                    <label class="font-medium md:w-48">{{ $field['label'] }}</label>
                    <input type="text"
                        class="w-[400px] px-2 py-1 text-black bg-white border border-gray-300 rounded cursor-not-allowed dark:border-gray-600 dark:bg-gray-800 dark:text-white"
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
                    @foreach ($jadwal->details as $index => $produk)
                        <tr>
                            <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 border">{{ $produk['nama_produk'] }}</td>
                            <td class="px-4 py-2 border">{{ $produk['tipe'] }}</td>
                            <td class="px-4 py-2 border">{{ $produk['volume'] }}</td>
                            <td class="px-4 py-2 border">{{ $produk['jumlah'] }}</td>
                            <td class="px-4 py-2 border">
                                {{ \Carbon\Carbon::parse($produk['tanggal_mulai'])->translatedFormat('d M Y') }}</td>
                            <td class="px-4 py-2 border">
                                {{ \Carbon\Carbon::parse($produk['tanggal_selesai'])->translatedFormat('d M Y') }}</td>
                            <td class="px-4 py-2 border">{{ $jadwal->spk->no_spk }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- C. Sumber Daya yang Digunakan -->
        <div class="max-w-4xl pt-4 mx-auto mb-3 text-lg font-bold text-start">C. Sumber Daya yang Digunakan</div>

        <div class="grid max-w-4xl grid-cols-1 gap-6 mx-auto mb-6 text-sm md:grid-cols-2">
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
                <thead class="text-black bg-gray-100 dark:bg-gray-800 dark:text-white">
                    <tr>
                        <th class="px-4 py-2 text-left border border-gray-300 dark:border-gray-600">Bahan Baku</th>

                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900">
                    @foreach ($jadwal->sumber->bahan_baku as $bahan)
                        <tr>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                {{ $bahan['bahan_baku'] }}</td>

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
                    <img src="{{ asset('storage/' . $jadwal->pic->create_signature) }}" alt="Signature Pembuat"
                        class="h-20 w-80" />
                    <div class="mt-2 font-medium">
                        {{ $jadwal->pic->create_name }}
                    </div>
                </div>
                <!-- Yang Menerima -->
                <div class="flex flex-col items-center">
                    <p class="mb-2 dark:text-white">Yang Menerima</p>
                    <img src="{{ asset('storage/' . $jadwal->pic->approve_signature) }}" alt="Signature Penerima"
                        class="h-20 w-80" />
                    <div class="mt-2 font-medium">
                        {{ $jadwal->pic->approve_name }}
                    </div>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-panels::page>
