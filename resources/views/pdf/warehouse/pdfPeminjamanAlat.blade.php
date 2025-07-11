@extends ('pdf.layout.layout')
@section('title', 'Peminjaman Alat PDF')
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
                    Formulir Peminjaman Alat & <br> Sparepat
                </td>
                <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                    <table class="w-full text-sm dark:bg-gray-900 dark:text-white" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> :
                                FO-QKS-WBB-03-02</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">Tanggal Rilis</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> : 21 Mei 2025
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
        <div class="max-w-4xl pt-4 mx-auto overflow-x-auto">
            <table class="w-full text-sm text-left border border-gray-300 dark:border-gray-600">
                <thead class="text-black bg-gray-100 dark:bg-gray-800 dark:text-white">
                    <tr>
                        <th class="px-4 py-2 border">No</th>
                        <th class="px-4 py-2 border">Tanggal Peminjaman</th>
                        <th class="px-4 py-2 border">Departemen</th>
                        <th class="px-4 py-2 border">Nama Alat/Sparepart</th>
                        <th class="px-4 py-2 border">Model</th>
                        <th class="px-4 py-2 border">Jumlah</th>
                        <th class="px-4 py-2 border">Tanggal Kembali</th>
                        <th class="px-4 py-2 border">Nama Peminjam</th>
                        <th class="px-4 py-2 border">Tanda Tangan</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900">
                    @foreach ($peminjaman->details as $index => $produk)
                        <tr>
                            <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 border">
                                {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->translatedFormat('d M Y') }}</td>
                            <td class="px-4 py-2 border">{{ $peminjaman->pic->department }}</td>
                            <td class="px-4 py-2 border">{{ $produk['nama_sparepart'] }}</td>
                            <td class="px-4 py-2 border">{{ $produk['model'] }}</td>
                            <td class="px-4 py-2 border">{{ $produk['jumlah'] }}</td>
                            <td class="px-4 py-2 border">
                                {{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->translatedFormat('d M Y') }}</td>
                            <td class="px-4 py-2 border">{{ $peminjaman->pic->nama_peminjam }}</td>
                            <td class="px-4 py-2 border"><img src="{{ asset('storage/' . $peminjaman->pic->signature) }}"
                                    alt="Create Signature" class="h-20 w-80" /></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
                filename: "peminjaman-alat.pdf",
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
