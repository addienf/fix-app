@extends('pdf.layout.layout')
@section('title', 'Permintaan Sparepart dan Alat Kerja PDF')
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
                    Formulir Permintaan <br> Sparepart dan Alat Kerja
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
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white">: 17 Juni 2025</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2">Revisi</td>
                            <td class="px-3 py-2 font-semibold">: 1</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Informasi Umum -->
        <div class="w-full max-w-4xl pt-4 mx-auto text-sm">
            @php
                $fields = [
                    ['label' => 'No Surat :', 'value' => $sparepart->no_surat],
                    [
                        'label' => 'Tanggal :',
                        'value' => \Carbon\Carbon::parse($sparepart->tanggal)->translatedFormat('d M Y'),
                    ],
                    ['label' => 'Dari :', 'value' => $sparepart->dari],
                    ['label' => 'Kepada :', 'value' => $sparepart->kepada],
                ];
            @endphp

            <div class="grid grid-cols-2 gap-4 mb-6">
                @foreach ($fields as $field)
                    <div class="flex items-center">
                        <label class="w-32 font-medium">{{ $field['label'] }}</label>
                        <input type="text" readonly value="{{ $field['value'] }}"
                            class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                    </div>
                @endforeach
            </div>

            <!-- Kalimat Pembuka -->
            <div class="max-w-4xl mx-auto mb-6 text-sm leading-relaxed">
                <p class="mb-2">Dengan hormat,</p>
                <p>
                    <span>Berdasarkan SPK No {{ $sparepart->spkService->no_spk_service }} mohon bantuan untuk memenuhi
                        kebutuhan sparepart & alat kerja dengan rincian sebagai
                        berikut:</span>
                </p>
            </div>
        </div>

        <!-- Tabel Rincian Barang -->
        <div class="max-w-4xl mx-auto overflow-x-auto">
            <table class="w-full text-sm text-left border border-gray-300 dark:border-gray-600">
                <thead class="text-black bg-gray-100 dark:bg-gray-800 dark:text-white">
                    <tr>
                        <th class="px-4 py-2 border">No</th>
                        <th class="px-4 py-2 border">Nama Barang</th>
                        <th class="px-4 py-2 border">Spesifikasi</th>
                        <th class="px-4 py-2 border">Jumlah</th>
                        <th class="px-4 py-2 border">Keperluan Barang</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900">
                    @php
                        $dummyDetails = [
                            [
                                'bahan_baku' => 'Kabel NYA 1.5mm',
                                'spesifikasi' => 'Merah, 100m',
                                'jumlah' => '3',
                                'keperluan_barang' => 'Instalasi Panel',
                            ],
                            [
                                'bahan_baku' => 'MCB 10A',
                                'spesifikasi' => '1 Phase, Schneider',
                                'jumlah' => '5',
                                'keperluan_barang' => 'Pengganti rusak',
                            ],
                            [
                                'bahan_baku' => 'Pipa Conduit',
                                'spesifikasi' => 'PVC 20mm',
                                'jumlah' => '10',
                                'keperluan_barang' => 'Jalur kabel',
                            ],
                        ];
                    @endphp

                    @foreach ($sparepart->details as $index => $data)
                        <tr>
                            <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 border">{{ $data['bahan_baku'] }}</td>
                            <td class="px-4 py-2 border">{{ $data['spesifikasi'] }}</td>
                            <td class="px-4 py-2 border">{{ $data['jumlah'] }}</td>
                            <td class="px-4 py-2 border">{{ $data['keperluan_barang'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @php
            $roles = [
                'Dibuat Oleh,' => [
                    'name' => $sparepart->pic->dibuat_name,
                    'signature' => $sparepart->pic->dibuat_ttd,
                ],
                'Diketahui Oleh,' => [
                    'name' => $sparepart->pic->diketahui_name,
                    'signature' => $sparepart->pic->diketahui_ttd,
                ],
                'Diserahkan Kepada,' => [
                    'name' => $sparepart->pic->diserahkan_name,
                    'signature' => $sparepart->pic->diserahkan_ttd,
                ],
            ];
        @endphp

        <div class="max-w-4xl p-4 pt-10 mx-auto mb-6">
            <p> Terima kasih.</p>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                @foreach ($roles as $role => $data)
                    <div>
                        <label class="block mb-1 font-semibold">{{ $role }}</label>
                        <input type="text" value="{{ $data['name'] }}" readonly class="w-full p-2 mb-2 text-black" />

                        <label class="block mb-1 font-semibold">Signature</label>
                        <div class="flex items-center justify-center w-full h-24 mb-2 bg-white">
                            @if ($data['signature'])
                                <img src="{{ asset('storage/' . $data['signature']) }}" alt="Signature"
                                    class="object-contain h-full" />
                            @else
                                <span class="text-sm text-gray-400">No Signature</span>
                            @endif
                        </div>
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
                filename: "permintaan-sparepart-dan-alat-kerja.pdf",
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
