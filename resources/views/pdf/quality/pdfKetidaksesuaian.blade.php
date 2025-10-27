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
                    Formulir Ketidaksesuaian <br> Produk & Material
                </td>
                <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                    <table class="w-full text-sm dark:bg-gray-900 dark:text-white" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> :
                                FO-QKS-QA-02-01</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">Tanggal Rilis</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> : 29 September 2025
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
            $infoUmum = [
                [
                    'label' => 'Tanggal',
                    'value' => \Carbon\Carbon::parse($ketidaksesuaian->tanggal)->translatedFormat('d F Y'),
                ],
                ['label' => 'Nama Perusahaan', 'value' => $ketidaksesuaian->nama_perusahaan],
                ['label' => 'Department', 'value' => $ketidaksesuaian->department],
                ['label' => 'Pelapor', 'value' => $ketidaksesuaian->pelapor],
            ];
        @endphp

        <div class="max-w-4xl pt-6 mx-auto text-lg font-bold text-start">A. Informasi Umum</div>
        <div class="grid w-full max-w-4xl grid-cols-1 pt-2 mx-auto mb-4 text-sm gap-y-4">
            @foreach ($infoUmum as $field)
                <div class="flex items-center gap-4">
                    <label class="w-40 font-medium">{{ $field['label'] }}</label>
                    <input type="text"
                        class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                        value="{{ $field['value'] }}" readonly />
                </div>
            @endforeach
        </div>

        <div class="max-w-4xl pt-2 mx-auto mb-4 text-lg font-bold text-start">B. Detail Ketidaksesuaian Produk & Material
        </div>
        <div class="max-w-4xl mx-auto overflow-x-auto">
            <table class="w-full text-sm text-left border border-gray-300 dark:border-gray-600">
                <thead class="text-black bg-gray-100 dark:bg-gray-800 dark:text-white">
                    <tr>
                        <th class="px-4 py-2 border">No</th>
                        <th class="px-4 py-2 border">Nama Produk/Material</th>
                        <th class="px-4 py-2 border">Serial Number</th>
                        <th class="px-4 py-2 border">Ketidaksesuaian</th>
                        <th class="px-4 py-2 border">Jumlah</th>
                        <th class="px-4 py-2 border">Satuan</th>
                        <th class="px-4 py-2 border">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900">
                    @foreach ($ketidaksesuaian->details as $index => $produk)
                        <tr>
                            <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 border">{{ $produk['nama_produk'] }}</td>
                            <td class="px-4 py-2 border">{{ $produk['serial_number'] }}</td>
                            <td class="px-4 py-2 border">{{ $produk['ketidaksesuaian'] }}</td>
                            <td class="px-4 py-2 border">{{ $produk['jumlah'] }}</td>
                            <td class="px-4 py-2 border">{{ $produk['satuan'] }}</td>
                            <td class="px-4 py-2 border">{{ $produk['keterangan'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @php
            $SnK = [
                ['label' => 'Penyebab Ketidaksesuaian', 'value' => $ketidaksesuaian->snk->penyebab],
                ['label' => 'Tindakan Kolektif', 'value' => $ketidaksesuaian->snk->tindakan_kolektif],
                ['label' => 'Tindakan Pencegahan', 'value' => $ketidaksesuaian->snk->tindakan_pencegahan],
            ];
        @endphp

        <div class="max-w-4xl pt-6 mx-auto text-lg font-bold text-start">C. Syarat dan Ketetuan</div>
        <div class="grid w-full max-w-4xl grid-cols-1 pt-2 mx-auto mb-4 text-sm gap-y-4">
            @foreach ($SnK as $field)
                <div class="flex items-center gap-4">
                    <label class="w-40 font-medium">{{ $field['label'] }}</label>
                    <input type="text"
                        class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                        value="{{ $field['value'] }}" readonly />
                </div>
            @endforeach
        </div>

        <div class="max-w-4xl mx-auto mt-10 text-sm page-break-inside: avoid;">
            <div class="flex items-start justify-between gap-4">
                <!-- Yang Membuat -->
                <div class="flex flex-col items-center">
                    <p class="mb-2 dark:text-white">Dilaporkan Oleh,</p>
                    <img src="{{ asset('storage/' . $ketidaksesuaian->pic->pelapor_signature) }}" alt="Signature Pelapor"
                        class="h-20 w-80" />
                    <div class="mt-2 font-medium">
                        {{ $ketidaksesuaian->pic->pelaporName->name }}
                    </div>
                </div>
                <!-- Yang Menerima -->
                <div class="flex flex-col items-center">
                    <p class="mb-2 dark:text-white">Diterima Oleh,</p>
                    <img src="{{ asset('storage/' . $ketidaksesuaian->pic->diterima_signature) }}" alt="Signature Penerima"
                        class="h-20 w-80" />
                    <div class="mt-2 font-medium">
                        {{ $ketidaksesuaian->pic->diterimaName->name }}
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="mt-6 mb-3 text-center">
        <button onclick="exportPDF()"
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
    function exportPDF() {
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
                filename: "ketidaksesuaian-produk.pdf",
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
            }).from(element).save();
        }
    }
</script>
