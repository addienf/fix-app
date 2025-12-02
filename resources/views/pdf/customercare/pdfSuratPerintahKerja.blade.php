@extends('pdf.layout.layout')
@section('title', 'Surat Perintah Kerja Pelayanan Pelanggan ')

@section('content')
    <div id="export-area" class="p-2 text-black bg-white">
        <!-- Header Table -->
        <table class="w-full max-w-4xl mx-auto text-sm border border-black" style="border-collapse: collapse;">
            <tr>
                <td rowspan="3" class="p-2 text-center align-middle border border-black w-28 h-28">
                    <img src="{{ asset('asset/logo.png') }}" alt="Logo" class="object-contain mx-auto h-30" />
                </td>
                <td colspan="2" class="font-bold text-center border border-black">
                    PT. QLab Kinarya Sentosa
                </td>
            </tr>
            <tr>
                <td class="font-bold text-center border border-black text-[20px]">
                    Surat Perintah Kerja <br> Pelayanan Pelanggan
                </td>
                <td rowspan="2" class="p-0 align-top border border-black">
                    <table class="w-full text-sm" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-2 border-b border-black">No. Dokumen</td>
                            <td class="px-3 py-2 font-semibold border-b border-black">: FO-QKS-CC-01-05</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 border-b border-black">Tanggal Rilis</td>
                            <td class="px-3 py-2 font-semibold border-b border-black">: 13 Oktober 2025</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2">Revisi</td>
                            <td class="px-3 py-2 font-semibold">: 01</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Form Section -->
        <div class="w-full max-w-4xl pt-4 mx-auto text-sm">
            <h2 class="mb-4 text-xl font-bold text-center">
                Formulir Surat Perintah Kerja (SPK)
            </h2>

            @php
$fields = [
    ['label' => 'Nomor SPK :', 'value' => '10/11/2025'],
    ['label' => 'Perusahaan:', 'value' => 'Jl. Contoh No. 123'],
    ['label' => 'Alamat :', 'value' => 'PT. Contoh Makmur'],
];
            @endphp

            <div class="grid gap-3 mb-6">
                @foreach ($fields as $field)
                    <div class="flex items-center gap-2">
                        <label class="w-40 font-medium text-gray-700">{{ $field['label'] }}</label>
                        <input type="text" readonly value="{{ $field['value'] }}"
                            class="flex-1 min-w-[200px] px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Deskripsi Pekerjaan -->
        <div class="w-full max-w-4xl pt-4 mx-auto text-sm">
            <h2 class="mb-4 text-xl font-bold">A. Deskripsi Pekerjaan</h2>
            <div class="grid grid-cols-3 gap-x-8 text-md">
                <div class="space-y-3">
                    @foreach (['Maintenance', 'Service', 'Kalibrasi'] as $item)
                        <label class="flex items-center gap-4">
                            <input type="checkbox" class="w-4 h-4"> {{ $item }}
                        </label>
                    @endforeach
                    <label class="flex items-center gap-4">
                        <input type="checkbox" class="w-4 h-4"> Lainnya :
                        <span class="flex-1 border-b border-dotted border-gray-500"></span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Identitas Alat -->
        <div class="w-full max-w-4xl pt-4 mx-auto text-sm">
            <h2 class="mb-4 text-xl font-bold">B. Identitas Alat</h2>
            <table class="w-full text-sm border border-gray-300 border-collapse">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        @foreach (['No', 'Nama Alat', 'Tipe', 'Nomor Serial', 'Deskripsi Pembuatan', 'QTY'] as $head)
                            <th class="border border-gray-300 px-2 py-1 text-left">{{ $head }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach (range(1, 3) as $i)
                        <tr class="odd:bg-white even:bg-gray-50">
                            <td class="border border-gray-300 px-2 py-1 text-center">{{ $i }}</td>
                            <td class="border border-gray-300 px-2 py-1">Nama Alat {{ $i }}</td>
                            <td class="border border-gray-300 px-2 py-1">Tipe {{ $i }}</td>
                            <td class="border border-gray-300 px-2 py-1">SN00{{ $i }}</td>
                            <td class="border border-gray-300 px-2 py-1">Deskripsi {{ $i }}</td>
                            <td class="border border-gray-300 px-2 py-1">1</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pelaksanaan -->
        <div class="w-full max-w-4xl pt-4 mx-auto text-sm">
            <h2 class="mb-4 text-xl font-bold">C. Pelaksanaan</h2>

            @php
$fields = [
    ['label' => 'Tanggal Pelaksanaan :', 'value' => '10/11/2025'],
    ['label' => 'Tempat Pelaksanaan :', 'value' => 'Jl. Contoh No. 123'],
    ['label' => 'Petugas yang Ditugaskan:', 'value' => 'PT. Contoh Makmur'],
];
            @endphp

            <div class="grid gap-3 mb-6">
                @foreach ($fields as $field)
                    <div class="flex items-center gap-2">
                        <label class="w-40 font-medium text-gray-700">{{ $field['label'] }}</label>
                        <input type="text" readonly value="{{ $field['value'] }}"
                            class="flex-1 min-w-[200px] px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                    </div>
                @endforeach
            </div>

            <table class="w-full text-sm border border-gray-300 border-collapse">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        @foreach (['No', 'Nama Teknisi', 'Jabatan'] as $head)
                            <th class="border border-gray-300 px-2 py-1 text-left">{{ $head }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach (range(1, 3) as $i)
                        <tr class="odd:bg-white even:bg-gray-50">
                            <td class="border border-gray-300 px-2 py-1 text-center">{{ $i }}</td>
                            <td class="border border-gray-300 px-2 py-1">Nama Alat {{ $i }}</td>
                            <td class="border border-gray-300 px-2 py-1">Tipe {{ $i }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <p class="pt-3">Demikian Surat Perintah Kerja ini dibuat agar dapat dipergunakan sebagai mestinya. Terima kasih.
            </p>

            <p class="flex justify-end items-end pt-3">
                <span>Bekasi,</span>
                <input type="text" class="ml-2 border-b border-gray-500 outline-none w-40 text-center" />
            </p>
        </div>

        <!-- Tanda Tangan -->
        <div class="max-w-4xl mx-auto mt-10 text-sm">
            <div class="flex items-start justify-between gap-4">
                <div class="flex flex-col items-center">
                    <p class="mb-2">Di Konfirmasi Oleh</p>
                    <p class="mt-1 font-semibold">( )</p>
                </div>
                <div class="flex flex-col items-center">
                    <p class="mb-2">Di Buat Oleh</p>
                    <p class="mt-1 font-semibold">( )</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 mb-3 text-center">
        <button onclick="exportPDF()"
            class="inline-flex items-center gap-2 py-3 text-sm font-semibold text-white bg-blue-600 border rounded px-7 hover:bg-purple-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
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
                filename: "surat-perintah-kerja-pelayanan-pelanggan .pdf",
                image: { type: "jpeg", quality: 1 },
                html2canvas: { scale: 3, useCORS: true, letterRendering: true },
                jsPDF: { unit: "in", format: "a4", orientation: "portrait" },
                pagebreak: { mode: ["avoid", "css"] }
            }).from(element).save();
        }
    }
</script>