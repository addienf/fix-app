@extends ('pdf.layout.layout')

@section('content')
    <div id="export-area" class="p-2 bg-white text-black">
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
            <div class="pt-4 text-center font-bold text-lg mb-1">SURAT PERINTAH KERJA (SPK)</div>
            <div class="text-center mb-6">Nomor: 001/QKS/PRO/SV/I/25</div>

            <div class="mb-6">
                <p class="mb-1">Kepada Yth,</p>
                <div class="flex items-center space-x-2">
                    <label class="whitespace-nowrap">PT:</label>
                    <div class=" px-3 py-1 w-80 ">
                        ..
                    </div>
                </div>      
            </div>

            <p class="mb-4">Dengan hormat,</p>

            <div class="mb-6 text-justify leading-relaxed">
                <span>
                    Sehubungan dengan kebutuhan produksi kami untuk pembuatan Climatic Chamber, bersama ini kami memberikan Surat
                    Perintah Kerja kepada pihak PT
                </span>

                <div class="inline-flex items-center">
                    <div class="px-3 py-1 w-64 rounded-md">
                        ...
                    </div>
                </div>
                <span>
                    untuk melakukan pekerjaan Cutting dan Bending dengan ketentuan sebagai berikut:
                </span>
            </div>


            <ol class="list-decimal pl-5 mb-4 space-y-2">
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
                    <div class="border-t border-gray-400 w-56 mx-auto pt-2 text-sm text-gray-600">
                    </div>
                </div>
                <div class="mt-20">
                    <div class="border-t border-gray-400 w-56 mx-auto pt-2 text-sm text-gray-600">
                    </div>
                </div>      
            </div>
        </div>
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

        const today = new Date();
        const tanggal = today.toISOString().split('T')[0]; // hasil: "2025-06-25"
        const filename = `spesifikasi-produk-${tanggal}.pdf`;

        function renderPDF() {
            html2pdf().set({
                margin: [0.2, 0.2, 0.2, 0.2],
                filename: "permintaan-bahan-dan-alat-produksi.pdf",
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