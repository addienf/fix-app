@extends ('pdf.layout.layout')
@section('title', 'Permintaan Bahan dan Alat Produksi PDF')
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
                    Formulir Permintaan Bahan Baku <br> dan Alat Kerja untuk Produksi
                </td>
                <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                    <table class="w-full text-sm dark:bg-gray-900 dark:text-white" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> :
                                FO-QKS-PRO-01-03</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">Tanggal Rilis</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> : 17 Juni 2025
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
                ['label' => 'Nomor Surat :', 'value' => $permintaan_alat_bahan->no_surat],
                [
                    'label' => 'Tanggal : ',
                    'value' => \Carbon\Carbon::parse($permintaan_alat_bahan->date)->translatedFormat('d M Y'),
                ],
                ['label' => 'Dari :', 'value' => $permintaan_alat_bahan->dari],

                ['label' => 'Kepada :', 'value' => $permintaan_alat_bahan->kepada],
            ];
        @endphp
        <div class="grid max-w-4xl grid-cols-1 pt-2 pt-4 pt-6 mx-auto mb-6 text-sm md:grid-cols-2 gap-x-6 gap-y-4">
            @foreach ($infoUmum as $field)
                <div class="flex flex-col items-start gap-2 md:flex-row md:gap-4 md:items-center">
                    <label class="font-medium md:w-48">{{ $field['label'] }}</label>
                    <input disabled
                        class="w-full h-[32px] px-2 py-1 text-black bg-white border border-gray-300 rounded dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                        value="{{ $field['value'] }}" />
                </div>
            @endforeach
        </div>

        <!-- PARAGRAF PERMINTAAN -->
        <div class="max-w-4xl mx-auto mb-6 text-sm">
            <p class="mb-2">Dengan hormat,</p>
            <p class="flex flex-wrap items-center gap-1">
                <span>Berdasarkan SPK MKT No. {{ $permintaan_alat_bahan->spk->no_spk }} mohon bantuan untuk memenuhi
                    kebutuhan bahan/sparepart dengan rincian sebagai berikut:</span>
            </p>
        </div>

        <!-- TABEL PRODUK -->
        <div class="max-w-4xl mx-auto overflow-x-auto">
            <table class="w-full text-sm text-left border border-gray-300 dark:border-gray-600">
                <thead class="text-black bg-gray-100 dark:bg-gray-800 dark:text-white">
                    <tr>
                        <th class="px-4 py-2 border">No</th>
                        <th class="px-4 py-2 border">Nama Bahan Baku</th>
                        <th class="px-4 py-2 border">Spesifikasi</th>
                        <th class="px-4 py-2 border">Jumlah</th>
                        <th class="px-4 py-2 border">Keperluan Barang</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900">
                    @foreach ($permintaan_alat_bahan->details as $produk)
                        <tr>
                            <td class="px-4 py-2 border">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2 border">{{ $produk['bahan_baku'] }}</td>
                            <td class="px-4 py-2 border">{{ $produk['spesifikasi'] }}</td>
                            <td class="px-4 py-2 border">{{ $produk['jumlah'] }}</td>
                            <td class="px-4 py-2 border">{{ $produk['keperluan_barang'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- TANDA TANGAN -->
        {{-- <div class="max-w-4xl mx-auto mt-10 text-sm">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex flex-col items-center">
                                        <p class="mb-2 dark:text-white">Yang Membuat</p>
                                        <img src="{{ asset('storage/signatures/dummy-submit.png') }}" alt="Product Signature"
                                            class="h-20 w-80" />
                                        <div class="mt-2 font-medium">Rudi Hartono</div>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <p class="mb-2 dark:text-white">Yang Menerima</p>
                                        <img src="{{ asset('storage/signatures/dummy-receive.png') }}" alt="Product Signature"
                                            class="h-20 w-80" />
                                        <div class="mt-2 font-medium">Siti Maemunah</div>
                                    </div>
                                </div>
                            </div> --}}

        @php
            $roles = [
                'Dibuat Oleh' => [
                    'name' => $permintaan_alat_bahan->pic->dibuatName->name ?? '-',
                    'signature' => $permintaan_alat_bahan->pic->dibuat_signature ?? null,
                ],
                'Diketahui Oleh' => [
                    'name' => $permintaan_alat_bahan->pic->diketahuiName->name ?? '-',
                    'signature' => $permintaan_alat_bahan->pic->diketahui_signature ?? null,
                ],
                'Diserahkan Kepada' => [
                    'name' => $permintaan_alat_bahan->pic->diserahkanName->name ?? '-',
                    'signature' => $permintaan_alat_bahan->pic->diserahkan_signature ?? null,
                ],
            ];
        @endphp
        <p class="w-full max-w-4xl pt-4 mx-auto"> Terimakasih. </p>
        <div class="max-w-4xl pt-2 mx-auto text-sm">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                @foreach ($roles as $role => $data)
                    <div>
                        <label class="block mb-1">{{ $role }}</label>

                        <div class="flex items-center justify-center w-full h-24 mb-2 bg-white">
                            @if ($data['signature'])
                                <img src="{{ asset('storage/' . $data['signature']) }}" alt="Signature"
                                    class="object-contain h-full" />
                            @else
                                <span class="text-sm text-gray-400">No Signature</span>
                            @endif
                        </div>

                        <input type="text" value="{{ $data['name'] }}" readonly
                            class="w-full p-2 mb-2 text-center text-black" />
                    </div>
                @endforeach
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
