@extends ('pdf.layout.layout')
@section('title', 'SPK vendor PDF')
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
                    Formulir Surat Perintah Kerja Vendor
                </td>
                <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                    <table class="w-full text-sm dark:bg-gray-900 dark:text-white" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> :
                                FO-QKS-PRO-01-06</td>
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

        <div class="w-full max-w-4xl mx-auto text-justify text-black">
            <div class="pt-4 mb-1 text-lg font-bold text-center">SURAT PERINTAH KERJA (SPK)</div>
            <div class="mb-6 text-center">Nomor: 001/QKS/PRO/SV/I/25</div>

            <div class="mb-6">
                <p class="mb-1">Kepada Yth,</p>
                <div class="flex items-center space-x-2">
                    <label class="whitespace-nowrap">PT. {{ $vendor->nama_perusahaan }}</label>
                </div>
            </div>

            <p class="mb-4">Dengan hormat,</p>

            <div class="mb-6 leading-relaxed text-justify">
                <span>
                    Sehubungan dengan kebutuhan produksi kami untuk pembuatan Climatic Chamber, bersama ini kami memberikan
                    Surat
                    Perintah Kerja kepada pihak PT. {{ $vendor->nama_perusahaan }} untuk melakukan pekerjaan Cutting dan
                    Bending dengan ketentuan sebagai berikut:
                </span>
            </div>


            <ol class="pl-5 mb-4 space-y-2 list-decimal">
                <li>
                    <strong>Deskripsi Pekerjaan</strong><br>
                    Melakukan proses cutting dan bending terhadap material sesuai dengan gambar teknik dan
                    spesifikasi yang telah diberikan oleh PT. QLab Kinarya Sentosa
                </li>
                <li>
                    <strong>Jumlah & Jenis Material</strong><br>
                    Jenis Material: Plat SS 304 / Mild Steel / Aluminium<br>
                    Ketebalan: 1.5 mm / 2 mm<br>
                    Jumlah: Sesuai dokumen terlampir
                </li>
                <li>
                    <strong>Dokumen Pendukung</strong><br>
                    Gambar teknik (drawing) terlampir (wajib dikembalikan)
                </li>
                <li>
                    <strong>Ketentuan Tambahan</strong><br>
                    Vendor wajib menjaga kualitas dan ketepatan ukuran sesuai gambar.<br>
                    Apabila terdapat ketidaksesuaian, vendor wajib melakukan perbaikan tanpa biaya tambahan.<br>
                    Semua hasil pekerjaan menjadi milik PT. QLab Kinarya Sentosa
                </li>
            </ol>

            <p class="mb-6">
                Demikian surat perintah kerja ini dibuat untuk dilaksanakan sebagaimana mestinya. Atas perhatian dan
                kerja sama yang baik, kami ucapkan terima kasih.
            </p>

            <div class="grid grid-cols-2 gap-4 mt-6 text-center">
                <div>
                    Hormat kami,<br>
                    <strong>PT. QLab Kinarya Sentosa</strong>
                </div>
                <div>
                    Pihak Vendor
                </div>
                <div class="mt-20">
                    <div class="w-56 pt-2 mx-auto text-sm text-gray-600 border-t border-gray-400">
                    </div>
                </div>
                <div class="mt-20">
                    <div class="w-56 pt-2 mx-auto text-sm text-gray-600 border-t border-gray-400">
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
                filename: "spk-vendor.pdf",
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
