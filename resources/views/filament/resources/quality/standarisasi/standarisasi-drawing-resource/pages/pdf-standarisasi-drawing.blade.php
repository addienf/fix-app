<x-filament-panels::page>
    <x-filament::section>
        <h2 class="mb-3 text-xl font-bold text-center">Detail Standarisasi Gambar Kerja</h2>
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
                    Standarisasi Gambar Kerja
                </td>
                <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                    <table class="w-full text-sm dark:bg-gray-900" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> :
                                FO-QKS-QA-01-01</td>
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
        <div class="grid w-full max-w-4xl grid-cols-1 pt-6 mx-auto mb-6 text-sm gap-y-4">
            @php
                $fields = [
                    ['label' => 'No SPK Produksi :', 'value' => $standarisasi->spk->no_spk],
                    [
                        'label' => 'Tanggal Pemeriksaan :',
                        'value' => \Carbon\Carbon::parse($standarisasi->tanggal)->format('d M Y'),
                    ],
                ];
            @endphp

            @foreach ($fields as $field)
                <div class="flex flex-col">
                    <label class="mb-1 font-medium">{{ $field['label'] }}</label>
                    <input type="text" readonly value="{{ $field['value'] }}"
                        class="w-full px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                </div>
            @endforeach
        </div>

        <div class="grid w-full max-w-4xl grid-cols-1 pt-2 mx-auto mb-6 text-sm gap-y-4">
            <h2 class="col-span-1 mb-3 text-xl font-bold text-start">
                I. Detail Standarisasi Gambar Kerja
            </h2>
            @php
                $fields = [
                    ['label' => 'Judul Gambar :', 'value' => $standarisasi->identitas->judul_gambar],
                    ['label' => 'Nomor Gambar :', 'value' => $standarisasi->identitas->no_gambar],
                    [
                        'label' => 'Tanggal Pembuatan :',
                        'value' => \Carbon\Carbon::parse($standarisasi->identitas->tanggal_pembuatan)->format('d M Y'),
                    ],
                    [
                        'label' => 'Revisi :',
                        'value' => match ($standarisasi->identitas->revisi) {
                            0 => 'Tidak Ada Revisi',
                            1 => 'Revisi',
                            default => 'Tidak Diketahui',
                        },
                    ],
                    $standarisasi->identitas->revisi === 1
                        ? ['label' => 'Revisi Ke :', 'value' => 'Tim QA']
                        : ['label' => 'Revisi Ke :', 'value' => 'Tidak Ada Revisi'],
                    ['label' => 'Nama Pembuat Gambar :', 'value' => $standarisasi->identitas->nama_pembuat],
                    ['label' => 'Nama Pemeriksa :', 'value' => $standarisasi->identitas->nama_pemeriksa],
                ];
            @endphp

            @foreach ($fields as $field)
                <div class="flex flex-col">
                    <label class="mb-1 font-medium">{{ $field['label'] }}</label>
                    <input type="text" readonly value="{{ $field['value'] }}"
                        class="w-full px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                </div>
            @endforeach
        </div>
        <div class="grid w-full max-w-4xl grid-cols-1 pt-2 mx-auto mb-6 text-sm gap-y-4">
            <h2 class="col-span-1 mb-3 text-xl font-bold text-start">
                II. Spesifikasi Teknis
            </h2>
            @php
                $fields = [
                    ['label' => 'Jenis Gambar :', 'value' => ucfirst($standarisasi->jenis_gambar)],
                    ['label' => 'Format Gambar :', 'value' => $standarisasi->format_gambar],
                ];
            @endphp
            @foreach ($fields as $field)
                <div class="flex flex-col">
                    <label class="mb-1 font-medium">{{ $field['label'] }}</label>
                    <input type="text" readonly value="{{ $field['value'] }}"
                        class="w-full px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                </div>
            @endforeach
        </div>
        <div class="grid w-full max-w-4xl grid-cols-1 pt-2 mx-auto mb-6 text-sm gap-y-4">
            <h2 class="col-span-1 mb-3 text-xl font-bold text-start">
                III. Komponen Gambar yang Diperiksa
            </h2>
            {{-- @php
                $fields = [['label' => '', 'value' => 'SPK-2025-001']];
            @endphp
            @foreach ($fields as $field)
                <div class="flex flex-col">
                    <label class="mb-1 font-medium">{{ $field['label'] }}</label>
                    <input type="text" readonly value="{{ $field['value'] }}"
                        class="w-full px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                </div>
            @endforeach --}}
            <p> 1. Keselarasan dengan spesifikasi</p>
            <p> 2. Ketepatan dimensi dengan skala</p>
            <p> 3. Kesesuaian dengan gambar dan produk</p>
        </div>

        <div class="grid w-full max-w-4xl grid-cols-1 pt-2 mx-auto mb-6 gap-y-4">
            <h2 class="col-span-1 mb-3 text-xl font-bold text-start">
                IV. Catatan dan Koreksi yang Dibutuhkan
            </h2>
            {{-- <input type="text" value=" {{ $standarisasi->detail->catatan }}"
                class="w-full px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed"> --}}

            <div class="w-full max-w-4xl mx-auto mb-6">
                <label for="note" class="block mb-1 text-sm font-medium text-gray-700">Note:</label>
                <textarea id="note" readonly
                    class="w-full px-3 py-2 overflow-hidden text-sm leading-relaxed text-gray-800 bg-gray-100 border cursor-not-allowed resize-none border-black-600">{{ trim($standarisasi->detail->catatan) }}</textarea>
            </div>

            <div class="grid w-full max-w-4xl grid-cols-1 pt-2 mx-auto text-sm gap-y-4">
                <h2 class="col-span-1 mb-3 text-xl font-bold text-start">
                    V. Lampiran
                </h2>

            </div>

            <div class="max-w-4xl px-6 mx-auto">
                <div class="flex items-start justify-between gap-8">
                    <!-- Kiri -->
                    <div class="flex flex-col items-center w-1/2">
                        <p class="mb-2 dark:text-white">Dibuat Oleh</p>
                        <img src="{{ asset('storage/' . $standarisasi->pic->create_signature) }}"
                            alt="Standarisasi Gambar" class="h-20 w-80" />
                        <p class="mt-1 font-semibold dark:text-white">{{ $standarisasi->pic->create_name }}</p>
                    </div>
                    <!-- Kanan -->
                    <div class="flex flex-col items-center w-1/2">
                        <p class="mb-2 dark:text-white">Diperiksa Oleh</p>
                        <img src="{{ asset('storage/' . $standarisasi->pic->check_signature) }}"
                            alt="Standarisasi Gambar" class="h-20 w-80" />
                        <p class="mt-1 font-semibold dark:text-white">{{ $standarisasi->pic->check_name }}</p>
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
