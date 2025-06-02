<x-filament-panels::page>
    <x-filament::section>

        <h2 class="mb-3 text-xl font-bold text-center">Detail Serah Terima Barang</h2>
        <!-- HEADER DOKUMEN MIRIP EXCEL -->
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
                    Formulir Serah Terima Barang
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
        @php
            $fields = [
                ['label' => 'Nomor:', 'value' => $serah_terima->permintaanBahanPro->no_surat],
                [
                    'label' => 'Tanggal :',
                    'value' => \Carbon\Carbon::parse($serah_terima->tanggal)->translatedFormat('d F Y'),
                ],
                ['label' => 'Dari :', 'value' => $serah_terima->dari],
                ['label' => 'Kepada :', 'value' => $serah_terima->kepada],
            ];
        @endphp

        <div class="grid max-w-4xl grid-cols-1 pt-6 mx-auto mb-6 text-sm md:grid-cols-2 gap-x-6 gap-y-4">
            @foreach ($fields as $field)
                <div class="flex flex-col items-start gap-2 md:flex-row md:gap-4 md:items-center">
                    <label class="font-medium md:w-40">{{ $field['label'] }}</label>
                    <input type="text"
                        class="w-[400px] px-2 py-1 text-black bg-white border border-gray-300 rounded cursor-not-allowed dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                        value="{{ $field['value'] }}" />
                </div>
            @endforeach
        </div>
        <div class="max-w-4xl mx-auto mb-6 text-sm">
            <p class="mb-2">Dengan hormat,</p>
            <p class="flex flex-wrap items-center gap-1">
                <span>Berdasarkan Permintaan Barang No</span>
                <input type="disabled"
                    class="w-32 px-2 py-1 text-sm align-middle bg-transparent rounded outline-none h-7 focus:outline-none"
                    value="{{ $serah_terima->permintaanBahanPro->no_surat }}" />
                <span>Dari Departemen</span>
                <input type="disabled"
                    class="w-32 px-2 py-1 text-sm align-middle bg-transparent rounded outline-none h-7 focus:outline-none"
                    value="{{ $serah_terima->dari }}" />
                <span>mohon bantuan untuk memenuhi kebutuhan bahan untuk sparepart dengan rincian sebagai
                    berikut:</span>
            </p>
        </div>

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
                    @foreach ($serah_terima->details as $index => $produk)
                        <tr>
                            <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 border">{{ $produk['bahan_baku'] }}</td>
                            <td class="px-4 py-2 border">{{ $produk['spesifikasi'] }}</td>
                            <td class="px-4 py-2 border">{{ $produk['jumlah'] }}</td>
                            <td class="px-4 py-2 border">{{ $produk['keperluan_barang'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="max-w-4xl mx-auto mt-10 text-sm">
            <div class="flex items-start justify-between gap-4">
                <div class="flex flex-col items-center">
                    <p class="mb-2 dark:text-white">Yang Membuat</p>
                    <img src="{{ asset('storage/' . $serah_terima->pic->submit_signature) }}" alt="Signature"
                        class="object-contain h-20 w-80" />
                    <div class="mt-2 font-medium dark:text-white">
                        {{ $serah_terima->pic->submit_name }}
                    </div>
                </div>
                <div class="flex flex-col items-center">
                    <p class="mb-2 dark:text-white">Yang Menerima</p>
                    <img src="{{ asset('storage/' . $serah_terima->pic->receive_signature) }}" alt="Signature"
                        class="object-contain h-20 w-80" />
                    <div class="mt-2 font-medium dark:text-white">
                        {{ $serah_terima->pic->receive_name }}
                    </div>
                </div>
            </div>
        </div>

    </x-filament::section>
</x-filament-panels::page>
