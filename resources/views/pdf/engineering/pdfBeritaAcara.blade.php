@extends('pdf.layout.layout')
@section('title', 'Berita Acara Penyelesaian Service dan Maintenance PDF')
@section('content')
    <div id="export-area" class="p-2 text-black bg-white">
        <!-- Header Table -->
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
                {{-- Nomor Surat --}}
                <div class="flex items-center">
                    <label class="w-32 font-medium">Nomor Surat :</label>
                    <input type="text" readonly value="{{ $berita->no_surat }}"
                        class="flex-1 px-3 text-black cursor-not-allowed" />
                </div>

                {{-- Tanggal --}}
                <div class="flex items-center">
                    <label class="w-32 font-medium">Tanggal :</label>
                    <input type="text" readonly
                        value="{{ \Carbon\Carbon::parse($berita->tanggal)->translatedFormat('d F Y') }}"
                        class="flex-1 px-3 text-black cursor-not-allowed" />
                </div>

                {{-- Status PO --}}
                <div class="flex items-center">
                    <label class="w-32 font-medium">Status PO :</label>
                    <div class="flex items-center gap-8">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" class="text-blue-600 form-checkbox"
                                {{ strtolower($berita->status_po) === 'yes' ? 'checked' : '' }} disabled>
                            <span>Received</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" class="text-blue-600 form-checkbox"
                                {{ strtolower($berita->status_po) === 'no' ? 'checked' : '' }} disabled>
                            <span>Not Received</span>
                        </label>
                    </div>
                </div>

                {{-- Nomor PO --}}
                <div class="flex items-center">
                    <label class="w-32 font-medium">Nomor PO :</label>
                    <input type="text" readonly value="{{ $berita->nomor_po }}"
                        class="flex-1 px-3 cursor-not-allowed" />
                </div>
            </div>

            @php
                \Carbon\Carbon::setLocale('id');
                $tanggal = \Carbon\Carbon::parse($berita->tanggal);
                $hari = $tanggal->translatedFormat('l');
                $tanggalFormatted = $tanggal->format('d/m/Y');
            @endphp

            <div class="max-w-4xl mx-auto mb-2 text-sm leading-relaxed">
                <p>
                    <span>Pada Hari {{ $hari }} Tanggal {{ $tanggalFormatted }} kami yang bertanda tangan di bawah
                        ini:</span>
                </p>
            </div>

            {{-- Pihak 1 --}}
            <h2 class="mb-4 text-sm font-bold text-start">(Pihak 1) Penyedia Jasa</h2>
            @php
                $fields = [
                    ['label' => 'Nama :', 'value' => $berita->penyediaJasa->nama],
                    ['label' => 'Perusahaan :', 'value' => $berita->penyediaJasa->perusahaan],
                    ['label' => 'Alamat :', 'value' => $berita->penyediaJasa->alamat],
                    ['label' => 'Jabatan :', 'value' => $berita->penyediaJasa->jabatan],
                ];
            @endphp

            <div class="grid gap-2 mb-6">
                @foreach ($fields as $field)
                    <div class="flex items-center">
                        <label class="w-32 font-medium">{{ $field['label'] }}</label>
                        <input type="text" readonly value="{{ $field['value'] }}"
                            class="flex-1 px-3 cursor-not-allowed" />
                    </div>
                @endforeach
            </div>

            {{-- Pihak 2 --}}
            <h2 class="mb-4 text-sm font-bold text-start">(Pihak 2) Pelanggan</h2>
            @php
                $fields = [
                    ['label' => 'Nama :', 'value' => $berita->pelanggan->nama],
                    ['label' => 'Perusahaan :', 'value' => $berita->pelanggan->perusahaan],
                    ['label' => 'Alamat :', 'value' => $berita->pelanggan->alamat],
                    ['label' => 'Jabatan :', 'value' => $berita->pelanggan->jabatan],
                ];
            @endphp

            <div class="grid gap-2 mb-6">
                @foreach ($fields as $field)
                    <div class="flex items-center">
                        <label class="w-32 font-medium">{{ $field['label'] }}</label>
                        <input type="text" readonly value="{{ $field['value'] }}"
                            class="flex-1 px-3 cursor-not-allowed" />
                    </div>
                @endforeach
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

            <div class="flex items-start justify-between gap-4 mt-6">
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
