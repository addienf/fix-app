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
                                Surat Perinta Kerja Service <br> dan Maintenance
                            </td>
                            <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                                <table class="w-full text-sm dark:bg-gray-900 dark:text-white" style="border-collapse: collapse;">
                                    <tr>
                                        <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                                        <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> :
                                            FO-QKS-ENG-01-03</td>
                                    </tr>
                                    <tr>
                                        <td class="px-3 py-2 border-b border-black dark:border-white">Tanggal Rilis</td>
                                        <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> : 18 Juni 2025
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
                    <div class="max-w-4xl mx-auto p-6 text-sm text-black bg-white">
                        <!-- Header -->
                        <div class="flex justify-start mb-3">
                            <label class="font-semibold">Complaint Form. No :</label>
                            <input type="text" class="border border-gray-300 rounded px-2 py-1 w-48 ml-4" placeholder="No Formulir" />      
                        </div>

                        <h2 class="text-center font-bold uppercase mb-6">
                            Formulir Surat Perintah Kerja (SPK) Service & Maintenance
                        </h2>

                        <!-- Informasi Umum -->
                        @php
    $infoUmumFields = [
        ['label' => 'Nomor SPK :', 'type' => 'text'],
        ['label' => 'Tanggal :', 'type' => 'date'],
        ['label' => 'Alamat :', 'type' => 'text'],
        ['label' => 'Perusahaan :', 'type' => 'text'],
    ];
                        @endphp

                        <div class="space-y-2 mb-2">
                            @foreach ($infoUmumFields as $field)
                                <div class="flex items-center gap-2">
                                    <label class="w-40">{{ $field['label'] }}</label>
                                    <input type="{{ $field['type'] }}" class="flex-1 border rounded px-2 py-1" />
                                </div>
                            @endforeach
                        </div>

                        <!-- Deskripsi Pekerjaan -->
                        <h3 class="font-bold mb-2">A. Deskripsi Pekerjaan</h3>

                        <div class="mb-3 space-y-2">
                            <label><input type="checkbox" class="mr-1"> Service</label><br>
                            <label><input type="checkbox" class="mr-1"> Maintenance</label><br>
                            <label><input type="checkbox" class="mr-1"> Lainnya</label>
                        </div>

                        <div class="space-y-2 mb-6">
                            <div class="flex items-center gap-2">
                                <label class="w-52">Jadwal Pelaksanaan :</label>
                                <input type="date" class="flex-1 border rounded px-2 py-1" />
                            </div>
                            <div class="flex items-center gap-2">
                                <label class="w-52">Estimasi Waktu Selesai :</label>
                                <input type="text" class="flex-1 border rounded px-2 py-1" placeholder="Contoh: 2 Hari" />
                            </div>
                            <div class="flex items-center gap-2">
                                <label class="w-52">Petugas yang ditugaskan :</label>
                                <input type="text" class="flex-1 border rounded px-2 py-1" />
                            </div>
                        </div>

                        <!-- Tabel Teknisi -->
                        <table class="w-full text-center border border-collapse border-black mb-4">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border border-black px-2 py-1">No</th>
                                    <th class="border border-black px-2 py-1">Nama Teknisi</th>
                                    <th class="border border-black px-2 py-1">Jabatan</th>
                                    <th class="border border-black px-2 py-1">Tanda Tangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border border-black px-2 py-1">1.</td>
                                    <td class="border border-black px-2 py-1"><input type="text" class="w-full px-1 border-none" /></td>
                                    <td class="border border-black px-2 py-1"><input type="text" class="w-full px-1 border-none" /></td>
                                    <td class="border border-black px-2 py-1"></td>
                                </tr>
                                <tr>
                                    <td class="border border-black px-2 py-1">2.</td>
                                    <td class="border border-black px-2 py-1"><input type="text" class="w-full px-1 border-none" /></td>
                                    <td class="border border-black px-2 py-1"><input type="text" class="w-full px-1 border-none" /></td>
                                    <td class="border border-black px-2 py-1"></td>
                                </tr>
                                <tr>
                                    <td class="border border-black px-2 py-1">3.</td>
                                    <td class="border border-black px-2 py-1"><input type="text" class="w-full px-1 border-none" /></td>
                                    <td class="border border-black px-2 py-1"><input type="text" class="w-full px-1 border-none" /></td>
                                    <td class="border border-black px-2 py-1"></td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Pemeriksaan -->
                        <h3 class="font-bold">B. Pemeriksaan dan Persetujuan</h3>
                        <ol class="list-decimal pl-5 mb-4">
                            <li class="mb-2">
                                Apakah pekerjaan telah selesai sesuai permintaan?
                                <span class="ml-2">
                                    <input type="radio" name="selesai" value="Ya"> Ya
                                    <input type="radio" name="selesai" value="Tidak" class="ml-4"> Tidak
                                </span>
                            </li>
                            <li>
                                Catatan Tambahan:
                                <textarea class="w-full mt-2 border rounded px-2 py-1" rows="2"></textarea>
                            </li>
                        </ol>

                        <div class="grid grid-cols-2 gap-2 text-center">

                            <div>
                                <p class="mb-2">Dikonfirmasi Oleh,</p>

                                <div class="w-40 h-24 mx-auto mb-2 border border-gray-400 bg-white flex items-center justify-center">
                                    <span class="text-gray-400 text-sm">Tanda Tangan</span>
                                </div>
                                <p class="font-semibold underline">Nama Lengkap</p>
                            </div>
                            <div>
                                <p class="mb-2">Diketahui Oleh,</p>
                                <div class="w-40 h-24 mx-auto mb-2 border border-gray-400 bg-white flex items-center justify-center">
                                    <span class="text-gray-400 text-sm">Tanda Tangan</span>
                                </div>
                                <p class="font-semibold underline">Nama Lengkap</p>
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
                filename: "spk-service-dan-maintence.pdf",
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