@extends ('pdf.layout.layout')
@section('title', 'Standarisasi Gambar Kerja PDF')
@section('content')
    <div id="export-area" class="p-2 text-black bg-white">
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
                    Standarisasi Gambar Kerja
                </td>
                <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                    <table class="w-full text-sm dark:bg-gray-900 dark:text-white" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> :
                                FO-QKS-QA-01-09</td>
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
            $fields = [
                [
                    'label' => 'No SPK Produksi :',
                    'value' => $no_spk,
                ],
                [
                    'label' => 'Tanggal Pemeriksaan :',
                    'value' => \Carbon\Carbon::parse($standarisasi->tanggal)->format('d M Y'),
                ],
            ];
        @endphp

        <!-- Informasi Umum -->
        <div class="flex flex-col w-full max-w-4xl gap-4 pt-6 mx-auto text-sm">
            <div class="flex items-center gap-4">
                <label class="w-48 font-medium">No SPK Produksi :</label>
                <input type="text" readonly value="{{ $no_spk }}"
                    class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
            </div>
            <div class="flex items-center gap-4">
                <label class="w-48 font-medium">Tanggal Pemeriksaan :</label>
                <input type="text" readonly value="{{ \Carbon\Carbon::parse($standarisasi->tanggal)->format('d M Y') }}"
                    class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
            </div>
        </div>

        <!-- Detail Standarisasi -->
        <div class="flex flex-col w-full max-w-4xl gap-4 pt-6 mx-auto text-sm">
            <h2 class="mb-4 text-xl font-bold">I. Detail Standarisasi Gambar Kerja</h2>
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
        <div class="flex flex-col w-full max-w-4xl gap-4 pt-6 mx-auto mb-6 text-sm">
            <h2 class="mb-4 text-xl font-bold">II. Spesifikasi Teknis</h2>
            <div class="flex items-center gap-4">
                <label class="w-48 font-medium">Jenis Gambar :</label>
                <input type="text" readonly
                    value="{{ is_array($standarisasi->jenis_gambar)
                        ? implode(', ', array_map('ucwords', $standarisasi->jenis_gambar))
                        : implode(', ', array_map('ucwords', json_decode($standarisasi->jenis_gambar, true))) }}
"
                    class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
            </div>
            <div class="flex items-center gap-4">
                <label class="w-48 font-medium">Format Gambar :</label>
                <input type="text" readonly
                    value="{{ is_array($standarisasi->format_gambar)
                        ? implode(', ', $standarisasi->format_gambar)
                        : implode(', ', json_decode($standarisasi->format_gambar, true)) }}"
                    class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
            </div>
        </div>

        <!-- Komponen Gambar -->
        @php
            $komponenLabels = [
                'keselarasan_spesifikasi' => 'Keselarasan dengan spesifikasi',
                'ketepatan_skala' => 'Ketepatan dimensi dengan skala',
                'kesesuaian' => 'Kesesuaian dengan gambar dan produk',
            ];

            $data = $standarisasi->pemeriksaan->pemeriksaan_komponen ?? [];
        @endphp

        <div class="w-full max-w-4xl pt-6 mx-auto text-sm">
            <h2 class="mb-4 text-xl font-bold">III. Komponen Gambar yang Diperiksa</h2>
            <div class="mb-6 space-y-2">
                @foreach ($komponenLabels as $key => $label)
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="pemeriksaan_komponen[]" value="{{ $key }}"
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded"
                            {{ in_array($key, $data) ? 'checked' : '' }} disabled>
                        <span>{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Catatan -->
        <div class="w-full max-w-4xl pt-6 mx-auto text-sm">
            <h2 class="mb-3 text-xl font-bold">IV. Catatan dan Koreksi yang Dibutuhkan</h2>
            <div readonly id="note"
                class="w-full px-3 py-2 overflow-hidden text-sm leading-relaxed border border-black rounded-md cursor-not-allowed resize-none">
                {{ trim($standarisasi->detail->catatan) }}
            </div>
        </div>

        <div class="w-full max-w-4xl pt-6 mx-auto">
            <h2 class="mb-4 text-xl font-bold">V. Lampiran</h2>

            @if (!empty($standarisasi->detail->lampiran))
                <div class="mt-4 mb-5 text-sm text-gray-700">
                    📎 Lampiran tersedia:
                    <a href="{{ route('pdf.StandarisasiDrawingLampiran', $standarisasi->id) }}" target="_blank"
                        class="text-blue-600 hover:text-blue-800">
                        FO-QKS-QA-01-08
                    </a>
                </div>
            @endif

            <div class="flex flex-col justify-between gap-8 sm:flex-row">
                <div class="flex flex-col items-center w-full sm:w-1/2">
                    <p class="mb-2">Dibuat Oleh</p>
                    <img src="{{ asset('storage/' . $standarisasi->pic->create_signature) }}" alt="ttd"
                        class="object-contain w-64 h-20" />
                    <p class="mt-1 font-semibold">{{ $standarisasi->pic->createName->name }}</p>
                </div>
                <div class="flex flex-col items-center w-full sm:w-1/2">
                    <p class="mb-2">Diperiksa Oleh</p>
                    <img src="{{ asset('storage/' . $standarisasi->pic->check_signature) }}" alt="ttd"
                        class="object-contain w-64 h-20" />
                    <p class="mt-1 font-semibold">{{ $standarisasi->pic->checkName->name }}</p>
                </div>
            </div>
        </div>

    </div>

    <div class="mt-6 mb-3 text-center">
        <button onclick="exportPDF('{{ $standarisasi->id }}')"
            class="inline-flex items-center gap-2 py-3 text-sm font-semibold text-black text-white bg-blue-600 border rounded border-animated px-7 border-black-400 hover:bg-purple-600 hover:text-white">
            <!-- Icon download SVG -->
            <svg class="w-5 h-5 transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4">
                </path>
            </svg>
            Download PDF
        </button>
    </div>
@endsection

<script>
    function exportPDF(id) {
        window.scrollTo(0, 0);

        const element = document.getElementById("export-area");
        const images = element.getElementsByTagName("img");
        const totalImages = images.length;
        let loadedImages = 0;

        for (let img of images) {
            if (img.complete) {
                loadedImages++;
            } else {
                img.onload = () => {
                    loadedImages++;
                    if (loadedImages === totalImages) renderPDF();
                };
            }
        }

        if (loadedImages === totalImages) {
            renderPDF();
        }

        function renderPDF() {
            html2pdf().set({
                margin: [0.2, 0.2, 0.2, 0.2],
                filename: "standarisasi-gambar-kerja.pdf",
                image: {
                    type: "jpeg",
                    quality: 1
                },
                html2canvas: {
                    scale: 3,
                    useCORS: true,
                    letterRendering: true
                },
                jsPDF: {
                    unit: "in",
                    format: "a4",
                    orientation: "portrait"
                },
                pagebreak: {
                    mode: ["avoid", "css"]
                }
            }).from(element).save().then(() => {
                window.location.href = `/quality/standarisasi-gambar-kerja/${id}/download-zip`;
            });
        }
    }
</script>
