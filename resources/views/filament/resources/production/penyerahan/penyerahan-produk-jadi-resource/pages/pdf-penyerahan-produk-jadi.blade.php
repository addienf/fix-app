<x-filament-panels::page>
    <x-filament::section>

        <h2 class="mb-3 text-xl font-bold text-center">
            Detail Penyerahan Produk Jadi
        </h2>

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
                <td class="text-lg font-bold text-center align-middle border border-black dark:border-white dark:bg-gray-900"
                    style="width: 400px;">
                    Formulir Penyerahan Produk Jadi
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

        <h2 class="w-full max-w-4xl col-span-1 pt-4 mx-auto mb-3 text-xl font-bold text-start">
            A. Detail Standarisasi Gambar Kerja
        </h2>

        @php
            $fields = [
                [
                    'label' => 'Tanggal  :',
                    'value' => \Carbon\Carbon::parse($produkJadi->tanggal)->translatedFormat('d M Y'),
                ],
                ['label' => 'Penanggung Jawab :', 'value' => $produkJadi->penanggug_jawab],
                ['label' => 'Penerima :', 'value' => $produkJadi->penerima],
            ];
        @endphp

        <div class="grid w-full max-w-4xl grid-cols-2 pt-4 mx-auto text-sm gap-x-6 gap-y-4">
            @foreach ($fields as $field)
                <div class="flex items-center">
                    <label class="w-32 font-medium">{{ $field['label'] }}</label>
                    <input type="text" readonly value="{{ $field['value'] }}"
                        class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                </div>
            @endforeach
        </div>

        <h2 class="w-full max-w-4xl col-span-1 pt-4 mx-auto mb-3 text-xl font-bold text-start">
            B. Detail Jadwal Produksi
        </h2>

        {{-- <div class="max-w-4xl mx-auto mt-6 overflow-x-auto text-sm">
            <table class="w-full text-center border border-collapse border-black">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-2 py-1 border border-black">No</th>
                        <th class="px-2 py-1 border border-black">Nomor Produk</th>
                        <th class="px-2 py-1 border border-black">Model/Type</th>
                        <th class="px-2 py-1 border border-black">Volume</th>
                        <th class="px-2 py-1 border border-black">No Seri</th>
                        <th class="px-2 py-1 border border-black">Jumlah </th>
                        <th class="px-2 py-1 border border-black">SPK MKT No.</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 1; $i <= 5; $i++)
                        <tr>
                            <td class="px-2 py-1 border border-black">{{ $i }}</td>
                            <td class="px-2 py-1 border border-black">Contoh Barang {{ $i }}</td>
                            <td class="px-2 py-1 border border-black">10</td>
                            <td class="px-2 py-1 border border-black">pcs</td>
                            <td class="px-2 py-1 border border-black">10.000</td>
                            <td class="px-2 py-1 border border-black">100.000</td>
                            <td class="px-2 py-1 border border-black">-</td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div> --}}

        <div class="w-full max-w-4xl mx-auto mt-6 overflow-x-auto text-sm">
            <table class="w-full text-sm text-left border border-gray-300 dark:border-gray-600">
                <thead class="text-black bg-gray-100 dark:bg-gray-800 dark:text-white">
                    <tr>
                        <th class="px-2 py-1 border border-black">No</th>
                        <th class="px-2 py-1 border border-black">Nama Produk</th>
                        <th class="px-2 py-1 border border-black">Model/Type</th>
                        <th class="px-2 py-1 border border-black">Volume</th>
                        <th class="px-2 py-1 border border-black">No Seri</th>
                        <th class="px-2 py-1 border border-black">Jumlah </th>
                        <th class="px-2 py-1 border border-black">SPK MKT No.</th>
                    </tr>
                </thead>
                <tbody class="text-black bg-white dark:bg-gray-900 dark:text-white">
                    @foreach ($produkJadi->details as $item)
                        <tr>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">{{ $loop->iteration }}
                            </td>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                {{ $item->nama_produk }}</td>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">{{ $item->tipe }}</td>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                {{ $item->volume }}</td>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                {{ $item->no_seri }}</td>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                {{ $item->jumlah }}</td>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                {{ $item->no_spk }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <h2 class="w-full max-w-4xl col-span-1 pt-4 mx-auto mb-3 text-xl font-bold text-start">
            C. Kondisi Produk
        </h2>

        @php
            $kondisi = $produkJadi->kondisi_produk ?? null;
        @endphp

        <div class="flex flex-col items-start w-full max-w-4xl mx-auto space-y-2 text-sm">
            <label class="mb-1 font-medium">Kondisi</label>

            <label class="inline-flex items-center space-x-2">
                <input type="checkbox" name="kondisi" value="baik"
                    class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600"
                    {{ $kondisi === 'baik' ? 'checked' : '' }} disabled>
                <span>Baik</span>
            </label>

            <label class="inline-flex items-center space-x-2">
                <input type="checkbox" name="kondisi" value="rusak"
                    class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600"
                    {{ $kondisi === 'rusak' ? 'checked' : '' }} disabled>
                <span>Rusak</span>
            </label>

            <label class="inline-flex items-center space-x-2">
                <input type="checkbox" name="kondisi" value="perlu_perbaikan"
                    class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600"
                    {{ $kondisi === 'perlu_perbaikan' ? 'checked' : '' }} disabled>
                <span>Perlu Perbaikan</span>
            </label>
        </div>

        <h2 class="w-full max-w-4xl col-span-1 pt-4 mx-auto text-xl font-bold text-start">
            C. Catatan Tambahan
        </h2>

        <div class="max-w-4xl pt-4 mx-auto text-sm">
            {{-- <label for="note" class="block mb-1 text-sm font-medium text-gray-700">Catatan</label> --}}
            <textarea id="note" readonly
                class="w-full px-3 py-2 overflow-hidden text-sm leading-relaxed text-gray-800 bg-gray-100 border cursor-not-allowed resize-none border-black-600">
                {{ trim($produkJadi->catatan_tambahan) }}
            </textarea>
        </div>

        <div class="max-w-4xl mx-auto mt-10 text-sm">
            <div class="flex items-start justify-between gap-4">
                <!-- Kiri -->
                <div class="flex flex-col items-center">
                    <p class="mb-2 dark:text-white">Diserahkan Oleh</p>
                    <img src="{{ asset('storage/' . $produkJadi->pic->submit_signature) }}" alt="Product Signature"
                        class="h-20 w-80" />
                    <p class="mt-4 font-semibold dark:text-white">{{ $produkJadi->pic->submit_name }}</p>
                </div>
                <!-- Kanan -->
                <div class="flex flex-col items-center">
                    <p class="mb-2 dark:text-white">Diterima Oleh</p>
                    <img src="{{ asset('storage/' . $produkJadi->pic->receive_signature) }}" alt="Product Signature"
                        class="h-20 w-80" />
                    <p class="mt-4 font-semibold dark:text-white">{{ $produkJadi->pic->receive_name }}</p>
                </div>
            </div>
        </div>

        <script>
            window.addEventListener('DOMContentLoaded', () => {
                const note = document.getElementById('note');
                if (note) {
                    note.style.height = 'auto'; // reset dulu
                    note.style.height = note.scrollHeight + 'px'; // sesuaikan isi
                }
            });
        </script>

    </x-filament::section>
</x-filament-panels::page>
