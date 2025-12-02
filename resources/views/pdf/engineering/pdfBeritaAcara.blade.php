@extends('pdf.layout.layout')
@section('title', 'Berita Acara Penyelesaian Service dan Maintenance PDF')
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
                    Berita Acara Penyelesaian <br> Service dan Maintenance
                </td>
                <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                    <table class="w-full text-sm dark:bg-gray-900 dark:text-white" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white">: FO-QKS-ENG-01-10
                            </td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">Tanggal Rilis</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white">: 29 September 2025
                            </td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2">Revisi</td>
                            <td class="px-3 py-2 font-semibold">: 02</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="w-full max-w-4xl pt-4 mx-auto text-sm">
            <h2 class="mb-4 text-xl font-bold text-center">BERITA ACARA PENYELESAIAN SERVICE & MAINTENANCE</h2>

            <div class="grid gap-2 mb-3">
                <div class="flex items-center">
                    <label class="w-40 font-medium">Nomor Surat</label>
                    <span>: {{ $berita->no_surat }}</span>
                </div>

                <div class="flex items-center">
                    <label class="w-40 font-medium">Tanggal</label>
                    <span>: {{ \Carbon\Carbon::parse($berita->tanggal)->translatedFormat('d F Y') }}</span>
                </div>

                <div class="flex items-start">
                    <label class="w-40 font-medium">Status PO</label>
                    <span>:</span>

                    <div class="ml-4 space-x-8">
                        <label>
                            <input type="checkbox" {{ strtolower($berita->status_po) === 'yes' ? 'checked' : '' }} disabled>
                            Received
                        </label>

                        <label>
                            <input type="checkbox" {{ strtolower($berita->status_po) === 'no' ? 'checked' : '' }} disabled>
                            Not Received
                        </label>
                    </div>
                </div>

                <div class="flex items-center">
                    <label class="w-40 font-medium">Nomor PO</label>
                    <span>: {{ $berita->nomor_po }}</span>
                </div>
            </div>

            @php
                \Carbon\Carbon::setLocale('id');
                $tanggal = \Carbon\Carbon::parse($berita->tanggal);
                $hari = $tanggal->translatedFormat('l');
                $tanggalFormatted = $tanggal->format('d/m/Y');
            @endphp

            <div class="max-w-4xl mx-auto text-sm leading-relaxed">
                <p>
                    <span>Pada Hari {{ $hari }} Tanggal {{ $tanggalFormatted }} kami yang bertanda tangan di bawah
                        ini:</span>
                </p>
            </div>

            {{-- Pihak 1 --}}
            <h3 class="max-w-4xl mx-auto mt-3 mb-2 text-sm font-bold">(Pihak 1) Penyedia Jasa</h3>

            <div class="max-w-4xl mx-auto space-y-2 text-sm">
                <div class="flex"><label class="w-40">Nama</label><span>: {{ $berita->penyediaJasa->nama }}</span></div>
                <div class="flex"><label class="w-40">Perusahaan</label><span>:
                        {{ $berita->penyediaJasa->perusahaan }}</span></div>
                <div class="flex"><label class="w-40">Alamat</label><span>: {{ $berita->penyediaJasa->alamat }}</span>
                </div>
                <div class="flex"><label class="w-40">Jabatan</label><span>:
                        {{ $berita->penyediaJasa->jabatan }}</span></div>
            </div>

            {{-- Pihak 2 --}}
            <h3 class="max-w-4xl mx-auto mt-3 mb-2 text-sm font-bold">(Pihak 2) Pelanggan</h3>

            <div class="max-w-4xl mx-auto space-y-2 text-sm">
                <div class="flex"><label class="w-40">Nama</label><span>: {{ $berita->pelanggan->nama }}</span></div>
                <div class="flex"><label class="w-40">Perusahaan</label><span>:
                        {{ $berita->pelanggan->perusahaan }}</span></div>
                <div class="flex"><label class="w-40">Alamat</label><span>: {{ $berita->pelanggan->alamat }}</span>
                </div>
                <div class="flex"><label class="w-40">Jabatan</label><span>: {{ $berita->pelanggan->jabatan }}</span>
                </div>
            </div>

            @php
                $jenis = strtolower($berita->detail->jenis_pekerjaan);
                $status = strtolower($berita->detail->status_barang);
            @endphp

            <p class="mt-2">
                Dengan ini menyatakan bahwa pekerjaan
                {!! $jenis === 'service'
                    ? 'Service/<del>Maintenance</del>'
                    : ($jenis === 'maintenance'
                        ? '<del>Service</del>/Maintenance'
                        : 'Service/Maintenance') !!}
                telah diselesaikan dengan rincian sebagai berikut: (*coret yang tidak perlu)
            </p>

            <ul class="mt-2 ml-6 space-y-1 list-disc">
                <li>Produk/Model<span class="ml-2">:</span>{{ $berita->detail->produk }}</li>
                <li>Serial Number<span class="ml-2">:</span>{{ $berita->detail->serial_number }}</li>
                <li>Deskripsi Pekerjaan/Barang<span class="ml-2">:</span>{{ $berita->detail->desc_pekerjaan }}</li>
                <li class="flex items-center gap-4">
                    Status Barang<span class="ml-2">:</span>
                    <div class="flex items-center gap-6">
                        <label class="flex items-center gap-1">
                            <input type="checkbox" class="text-blue-600 form-checkbox"
                                {{ $status === 'yes' ? 'checked' : '' }} disabled>
                            <span>Installed</span>
                        </label>
                        <label class="flex items-center gap-1">
                            <input type="checkbox" class="text-blue-600 form-checkbox"
                                {{ $status === 'wait' ? 'checked' : '' }} disabled>
                            <span>Delivered</span>
                        </label>
                        <label class="flex items-center gap-1">
                            <input type="checkbox" class="text-blue-600 form-checkbox"
                                {{ $status === 'na' ? 'checked' : '' }} disabled>
                            <span>N/A</span>
                        </label>
                    </div>
                </li>
            </ul>

            <p class="mt-3">
                Setelah dilakukan pemeriksaan, kedua belah pihak sepakat bahwa pekerjaan telah selesai sesuai
                dengan ketentuan yang disepakati dan dalam kondisi baik. Demikian berita acara ini dibuat untuk
                dipergunakan sebagaimana mestinya.
            </p>

            {{-- <div class="flex items-start justify-between gap-4 mt-6">
                <!-- Pihak 1 -->
                <div class="flex flex-col items-center">
                    <p class="mb-2 dark:text-white">( Pihak 1 Penyedia Jasa )</p>
                    <img src="{{ asset('storage/' . $berita->pic->jasa_ttd) }}" alt="Tanda Tangan Penyedia Jasa"
                        class="h-20 w-80" />
                    <p class="mt-4 font-semibold dark:text-white">( {{ $berita->penyediaJasa->nama }} )</p>
                </div>

                <!-- Pihak 2 -->
                <div class="flex flex-col items-center">
                    <p class="mb-20 dark:text-white">( Pihak 2 Pelanggan )</p>
                    <p class="mt-4 font-semibold dark:text-white">( {{ $berita->pelanggan->nama }} )</p>
                </div>
            </div> --}}

            <div class="flex justify-between max-w-4xl mx-auto mt-6 text-center">

                <div class="w-1/2">
                    <p>( Pihak 1 Penyedia Jasa )</p>
                    <img src="{{ asset('storage/' . $berita->pic->jasa_ttd) }}" class="h-20 mx-auto mt-2" />
                    <p class="mt-3 font-semibold">( {{ $berita->penyediaJasa->nama }} )</p>
                </div>

                <div class="w-1/2">
                    <p>( Pihak 2 Pelanggan )</p>
                    <div class="h-20"></div> <!-- space for tanda tangan -->
                    <p class="mt-3 font-semibold">( {{ $berita->pelanggan->nama }} )</p>
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
                filename: "berita-Acara.pdf",
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
