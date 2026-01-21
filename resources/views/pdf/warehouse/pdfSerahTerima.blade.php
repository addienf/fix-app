@extends ('pdf.layout.layout')
@section('title', 'Serah Terima Bahan PDF')
@section('content')
    {{-- <div id="export-area" class="p-2 text-black bg-white">
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
                    Formulir Serah Terima Barang <br> & Sparepart
                </td>
                <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                    <table class="w-full text-sm dark:bg-gray-900 dark:text-white" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> :
                                FO-QKS-WRH-03-03</td>
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
                    'label' => 'Nomor:',
                    'value' => $serah_terima->no_surat,
                ],
                [
                    'label' => 'Tanggal :',
                    'value' => \Carbon\Carbon::parse($serah_terima->tanggal)->translatedFormat('d M Y'),
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
                        class="w-full px-2 py-1 text-black bg-white border border-gray-300 rounded cursor-not-allowed dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                        value="{{ $field['value'] }}" />
                </div>
            @endforeach
        </div>

        <div class="max-w-4xl mx-auto mb-2 text-sm">
            <p class="mb-2">Dengan hormat,</p>
            <p class="flex flex-wrap items-center gap-1">
                <span>Berdasarkan Permintaan Barang No
                    {{ $serah_terima->peminjamanAlat->spkVendor->perencanaanProduksi->no_surat }} Dari Departemen
                    {{ Str::headline($serah_terima->pic?->submitName?->roles?->first()?->name ?? '') }}, berikut di bawah
                    ini
                    material/bahan/barang yang tersedia dan telah
                    diberikan:</span>
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
                    <p class="mb-2 dark:text-white">Diserahkan Oleh,</p>
                    <img src="{{ asset('storage/' . $serah_terima->pic->submit_signature) }}" alt="Signature"
                        class="object-contain h-20 w-80" />
                    <div class="mt-2 font-medium dark:text-white">
                        {{ $serah_terima->pic->submitName->name }}
                    </div>
                </div>
                <div class="flex flex-col items-center">
                    <p class="mb-2 dark:text-white">Diterima Oleh,</p>
                    <img src="{{ asset('storage/' . $serah_terima->pic->receive_signature) }}" alt="Signature"
                        class="object-contain h-20 w-80" />
                    <div class="mt-2 font-medium dark:text-white">
                        {{ $serah_terima->pic->receiveName->name }}
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
    </div> --}}

    {{-- ================= HEADER ================= --}}
    <table style="width:100%; border-collapse:collapse; table-layout:fixed;">
        <tr>
            <td rowspan="4" style="width:15%; text-align:center; vertical-align:middle; border:0.5px solid #000;">
                <img src="{{ public_path('asset/logo.png') }}" style="height:55px;">
            </td>

            <td colspan="4" style="text-align:center; font-weight:bold; font-size:11px; border:0.5px solid #000;">
                PT. QLab Kinarya Sentosa
            </td>
        </tr>

        <tr>
            <td rowspan="3" colspan="2"
                style="text-align:center; font-size:15px; font-weight:bold; border:0.5px solid #000; vertical-align:middle;">
                FORMULIR SERAH TERIMA BARANG<br>& SPAREPART
            </td>

            <td style="border:0.5px solid #000; padding-left:8px;">No. Dokumen</td>
            <td style="border:0.5px solid #000; text-align:center;">FO-QKS-WRH-03-03</td>
        </tr>

        <tr>
            <td style="border:0.5px solid #000; padding-left:8px;">Tanggal Rilis</td>
            <td style="border:0.5px solid #000; text-align:center;">
                {{ \Carbon\Carbon::parse($serah_terima->tanggal)->translatedFormat('d F Y') }}
            </td>
        </tr>

        <tr>
            <td style="border:0.5px solid #000; padding-left:8px;">Revisi</td>
            <td style="border:0.5px solid #000; text-align:center;">00</td>
        </tr>
    </table>

    <br>

    {{-- ================= INFORMASI UMUM ================= --}}
    <table class="no-border">
        <tr>
            <td width="25%">Nomor</td>
            <td width="75%">: {{ $serah_terima->no_surat }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>: {{ \Carbon\Carbon::parse($serah_terima->tanggal)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Dari</td>
            <td>: {{ $serah_terima->dari }}</td>
        </tr>
        <tr>
            <td>Kepada</td>
            <td>: {{ $serah_terima->kepada }}</td>
        </tr>
    </table>

    <br>

    <p>
        Dengan hormat,<br>
        Berdasarkan Permintaan Barang No
        <b>{{ $serah_terima->peminjamanAlat->spkVendor->perencanaanProduksi->no_surat }}</b>
        dari Departemen
        <b>{{ Str::headline($serah_terima->pic?->submitName?->roles?->first()?->name ?? '') }}</b>,
        berikut material/bahan/barang yang telah diserahkan:
    </p>

    {{-- ================= TABEL BARANG ================= --}}
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Nama Bahan</th>
                <th width="25%">Spesifikasi</th>
                <th width="15%">Jumlah</th>
                <th width="25%">Keperluan Barang</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($serah_terima->details as $i => $item)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $item['bahan_baku'] }}</td>
                    <td>{{ $item['spesifikasi'] }}</td>
                    <td class="text-center">{{ $item['jumlah'] }}</td>
                    <td>{{ $item['keperluan_barang'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ================= SIGNATURE ================= --}}
    <table class="no-border" width="100%" style="margin-top:50px;">
        <tr>
            <td width="50%" class="text-center">
                Diserahkan Oleh,<br><br>

                <div class="signature-box">
                    @if (!empty($serah_terima->pic->submit_signature))
                        <img src="{{ public_path('storage/' . $serah_terima->pic->submit_signature) }}">
                    @endif
                </div>

                <br>
                <b>{{ $serah_terima->pic->submitName->name }}</b>
            </td>

            <td width="50%" class="text-center">
                Diterima Oleh,<br><br>

                <div class="signature-box">
                    @if (!empty($serah_terima->pic->receive_signature))
                        <img src="{{ public_path('storage/' . $serah_terima->pic->receive_signature) }}">
                    @endif
                </div>

                <br>
                <b>{{ $serah_terima->pic->receiveName->name }}</b>
            </td>
        </tr>
    </table>
@endsection

<style>
    @page {
        margin: 25px 30px;
    }

    body {
        font-family: "Times-Roman", serif;
        font-size: 11px;
        color: #000;
        line-height: 1.4;
    }

    /* ================= GLOBAL TABLE ================= */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 10px;
        table-layout: fixed;
    }

    th,
    td {
        border: 0.5px solid #000;
        padding: 4px 6px;
        vertical-align: middle;
    }

    th {
        font-weight: bold;
        text-align: center;
        background-color: #f2f2f2;
        /* soft abu audit */
    }

    /* ================= NO BORDER ================= */
    .no-border td,
    .no-border th {
        border: none !important;
        padding: 3px 2px;
    }

    /* ================= TEXT ================= */
    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }

    .text-left {
        text-align: left;
    }

    /* ================= SECTION TITLE ================= */
    .title {
        font-size: 12px;
        font-weight: bold;
        margin: 12px 0 6px;
    }

    /* ================= PARAGRAPH ================= */
    p {
        margin: 6px 0;
        text-align: justify;
    }

    /* ================= SIGNATURE ================= */
    .signature-box {
        width: 160px;
        height: 70px;
        margin: 0 auto;
        text-align: center;
    }

    .signature-box img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    /* ================= PAGE BREAK ================= */
    .page-break {
        page-break-before: always;
    }
</style>


{{-- <script>
    function exportPDF() {
        window.scrollTo(0, 0); // pastikan posisi di atas

        const element = document.getElementById("export-area");

        // Pastikan semua gambar sudah termuat sebelum render
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
                filename: "serah-terima-bahan.pdf",
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
</script> --}}
