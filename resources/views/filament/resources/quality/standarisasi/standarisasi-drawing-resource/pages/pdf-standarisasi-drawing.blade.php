<x-filament-panels::page>
    <x-filament::section>
        <h2 class="mb-3 text-xl font-bold text-center">Detail Standarisasi Gambar Kerja</h2>

        <!-- Header Table -->
        <table class="w-full max-w-4xl mx-auto mb-3 text-sm border border-black" style="border-collapse: collapse;">
            <tr>
                <td rowspan="3" class="p-2 text-center align-middle border border-black w-28 h-28">
                    <img src="{{ asset('asset/logo.png') }}" alt="Logo" class="object-contain mx-auto h-30" />
                </td>
                <td colspan="2" class="font-bold text-center border border-black">PT. QLab Kinarya Sentosa</td>
            </tr>
            <tr>
                <td class="font-bold text-center border border-black" style="font-size: 20px;">
                    Standarisasi Gambar Kerja
                </td>
                <td rowspan="2" class="p-0 align-top border border-black">
                    <table class="w-full text-sm" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-2 border-b border-black">No. Dokumen</td>
                            <td class="px-3 py-2 font-semibold border-b border-black"> : FO-QKS-QA-01-09</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 border-b border-black">Tanggal Rilis</td>
                            <td class="px-3 py-2 font-semibold border-b border-black"> : 12 Maret 2025</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2">Revisi</td>
                            <td class="px-3 py-2 font-semibold"> : 0</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Informasi Umum (Horizontal Layout) -->
        <div class="flex flex-col w-full max-w-4xl gap-4 mx-auto text-sm">
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
                <div class="flex items-center gap-4">
                    <label class="w-48 font-medium">{{ $field['label'] }}</label>
                    <input type="text" readonly value="{{ $field['value'] }}"
                        class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                </div>
            @endforeach
        </div>

        <!-- Detail Standarisasi -->
        <div class="flex flex-col w-full max-w-4xl gap-4 pt-6 mx-auto text-sm">
            <h2 class="text-xl font-bold">I. Detail Standarisasi Gambar Kerja</h2>
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
                <div class="flex items-center gap-4">
                    <label class="w-48 font-medium">{{ $field['label'] }}</label>
                    <input type="text" readonly value="{{ $field['value'] }}"
                        class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                </div>
            @endforeach
        </div>

        <!-- Spesifikasi Teknis -->
        <div class="flex flex-col w-full max-w-4xl gap-4 pt-6 mx-auto text-sm">
            <h2 class="text-xl font-bold">II. Spesifikasi Teknis</h2>
            @php
                $fields = [
                    ['label' => 'Jenis Gambar :', 'value' => ucfirst($standarisasi->jenis_gambar)],
                    ['label' => 'Format Gambar :', 'value' => $standarisasi->format_gambar],
                ];
            @endphp
            @foreach ($fields as $field)
                <div class="flex items-center gap-4">
                    <label class="w-48 font-medium">{{ $field['label'] }}</label>
                    <input type="text" readonly value="{{ $field['value'] }}"
                        class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                </div>
            @endforeach
        </div>

        <!-- Komponen Gambar -->
        <div class="w-full max-w-4xl pt-6 mx-auto text-sm">
            <h2 class="mb-3 text-xl font-bold">III. Komponen Gambar yang Diperiksa</h2>
            <ul class="list-decimal list-inside">
                <li>Keselarasan dengan spesifikasi</li>
                <li>Ketepatan dimensi dengan skala</li>
                <li>Kesesuaian dengan gambar dan produk</li>
            </ul>
        </div>

        <!-- Catatan -->
        <div class="w-full max-w-4xl pt-6 mx-auto text-sm">
            <h2 class="mb-3 text-xl font-bold">IV. Catatan dan Koreksi yang Dibutuhkan</h2>
            <textarea readonly id="note"
                class="w-full px-3 py-2 overflow-hidden text-sm leading-relaxed border border-black rounded-md cursor-not-allowed resize-none">
{{ trim($standarisasi->detail->catatan) }}</textarea>
        </div>

        <!-- Lampiran dan Tanda Tangan -->
        <div class="w-full max-w-4xl pt-6 mx-auto">
            <h2 class="mb-3 text-xl font-bold">V. Lampiran</h2>
            <div class="flex items-start justify-between gap-8">
                <div class="flex flex-col items-center w-1/2">
                    <p class="mb-2">Dibuat Oleh</p>
                    <img src="{{ asset('storage/' . $standarisasi->pic->create_signature) }}" alt="ttd"
                        class="h-20 w-80" />
                    <p class="mt-1 font-semibold">{{ $standarisasi->pic->create_name }}</p>
                </div>
                <div class="flex flex-col items-center w-1/2">
                    <p class="mb-2">Diperiksa Oleh</p>
                    <img src="{{ asset('storage/' . $standarisasi->pic->check_signature) }}" alt="ttd"
                        class="h-20 w-80" />
                    <p class="mt-1 font-semibold">{{ $standarisasi->pic->check_name }}</p>
                </div>
            </div>
        </div>

        <script>
            window.addEventListener('DOMContentLoaded', () => {
                const note = document.getElementById('note');
                if (note) {
                    note.style.height = 'auto';
                    note.style.height = note.scrollHeight + 'px';
                }
            });
        </script>
    </x-filament::section>
</x-filament-panels::page>
