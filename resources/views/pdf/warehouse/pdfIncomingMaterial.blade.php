@extends ('pdf.layout.layout')
@section('title', 'Incoming Material PDF')
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
                    Formulir Incoming Material
                </td>
                <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                    <table class="w-full text-sm dark:bg-gray-900 dark:text-white" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> :
                                FO-QKS-WRH-01-01</td>
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

        <div class="w-full max-w-4xl pt-4 mx-auto space-y-2 text-sm">
            @php
                $fields = [
                    [
                        'label' => 'Nomor :',
                        'value' => $incomingMaterial->permintaanPembelian->permintaanBahanWBB->no_surat,
                    ],
                    [
                        'label' => 'Tanggal Penerimaan :',
                        'value' => \Carbon\Carbon::parse($incomingMaterial->tanggal)->translatedFormat('d M Y'),
                    ],
                ];
            @endphp

            @foreach ($fields as $field)
                <div class="flex items-center">
                    <label class="w-64 font-medium">{{ $field['label'] }}</label>
                    <input type="text" readonly value="{{ $field['value'] }}"
                        class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                </div>
            @endforeach
        </div>

        <h2 class="w-full max-w-4xl col-span-1 pt-4 mx-auto mb-4 text-xl font-bold text-start">
            A. Informasi Material
        </h2>

        <div class="max-w-4xl mx-auto mt-6 overflow-x-auto text-sm">
            <table class="min-w-full text-sm text-left border border-gray">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-3 py-2 border border-gray">No</th>
                        <th class="px-3 py-2 border border-gray">Nama Material</th>
                        <th class="px-3 py-2 border border-gray">Batch No.</th>
                        <th class="px-3 py-2 border border-gray">Jumlah Diterima</th>
                        <th class="px-3 py-2 border border-gray">Satuan</th>
                        <th class="px-3 py-2 border border-gray">Kondisi Material</th>
                        <th class="px-3 py-2 border border-gray">Status</th>
                    </tr>
                </thead>
                <tbody class="text-black bg-white dark:bg-gray-900 dark:text-white">
                    @foreach ($incomingMaterial->details as $index => $item)
                        <tr>
                            <td class="px-3 py-2 text-center border border-gray">{{ $index + 1 }}</td>
                            <td class="px-3 py-2 border border-gray">{{ $item->nama_material }}</td>
                            <td class="px-3 py-2 border border-gray">{{ $item->batch_no }}</td>
                            <td class="px-3 py-2 text-center border border-gray">{{ $item->jumlah }}</td>
                            <td class="px-3 py-2 text-center border border-gray">{{ $item->satuan }}</td>
                            <td class="px-3 py-2 border border-gray">{{ $item->kondisi_material }}</td>
                            <td class="px-3 py-2 text-center border border-gray">
                                {{ $item->status_qc == '1' ? 'Ya' : 'Tidak' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>



        {{-- @php
            $laporanQc = $incomingMaterial->dokumen_pendukung ?? null;
            $kondisiMaterial = $incomingMaterial->kondisi_material ?? null;
            $penerimaan = $incomingMaterial->status_penerimaan ?? null;
        @endphp --}}

        {{-- <p>{{ $incomingMaterial->status_penerimaan }}</p> --}}

        <div class="max-w-4xl mx-auto space-y-4 text-sm">
            <h2 class="pt-4 mb-4 text-xl font-bold text-start">B. Pemeriksaan Material</h2>
            <p class="ml-4">1. Apakah material dalam kondisi baik? (Ya/Tidak)</p>

            <div class="mt-1 ml-8 space-x-4">
                <label class="inline-flex items-center">
                    <input type="checkbox"
                        class="w-4 h-4 border border-gray-400 checked:bg-blue-600 checked:border-blue-600"
                        {{ $incomingMaterial->kondisi_material == '1' ? 'checked' : '' }} disabled />
                    <span class="ml-2">Ya</span>
                </label>

                <label class="inline-flex items-center">
                    <input type="checkbox"
                        class="w-4 h-4 border border-gray-400 checked:bg-blue-600 checked:border-blue-600"
                        {{ $incomingMaterial->kondisi_material == '0' ? 'checked' : '' }} disabled />
                    <span class="ml-2">Tidak</span>
                </label>
            </div>

            <h2 class="pt-4 mb-4 text-xl font-bold text-start">C. Status Penerimaan</h2>
            <div class="ml-4 space-y-1">
                <label class="inline-flex items-center">
                    <input type="checkbox"
                        class="w-4 h-4 border border-gray-400 checked:bg-blue-600 checked:border-blue-600"
                        {{ $incomingMaterial->status_penerimaan == 1 ? 'checked' : '' }} disabled />
                    <span class="ml-2">Diterima</span>
                </label>
                <br />
                <label class="inline-flex items-center">
                    <input type="checkbox"
                        class="w-4 h-4 border border-gray-400 checked:bg-blue-600 checked:border-blue-600"
                        {{ $incomingMaterial->status_penerimaan == 0 ? 'checked' : '' }} disabled />
                    <span class="ml-2">Ditolak dan dikembalikan</span>
                </label>
            </div>

            <h2 class="pt-4 mb-4 text-xl font-bold text-start">D. Dokumen Pendukung</h2>
            <div class="ml-4">
                <label class="inline-flex items-center">
                    <input type="checkbox"
                        class="w-4 h-4 border border-gray-400 checked:bg-blue-600 checked:border-blue-600"
                        {{ $incomingMaterial->dokumen_pendukung == 1 ? 'checked' : '' }} disabled />
                    <span class="ml-2">Laporan QC (Quality Control)</span>
                </label>
            </div>
        </div>

        <div class="max-w-4xl mx-auto mt-10 text-sm">
            <div class="flex items-start justify-between gap-4">
                <!-- Kiri -->
                <div class="flex flex-col items-center">
                    <p class="mb-2 dark:text-white">Diserahkan Oleh</p>
                    <img src="{{ asset('storage/' . $incomingMaterial->pic->submited_signature) }}" alt="Product Signature"
                        class="h-20 w-80" />
                    <p class="mt-4 font-semibold dark:text-white">{{ $incomingMaterial->pic->submited_name }}</p>
                </div>
                <!-- Kanan -->
                <div class="flex flex-col items-center">
                    <p class="mb-2 dark:text-white">Diterima Oleh</p>
                    <img src="{{ asset('storage/' . $incomingMaterial->pic->received_signature) }}" alt="Product Signature"
                        class="h-20 w-80" />
                    <p class="mt-4 font-semibold dark:text-white">{{ $incomingMaterial->pic->received_name }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 mb-3 text-center">
        <button onclick="exportPDF('{{ $incomingMaterial->id }}')"
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
    function exportPDF(id) {
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
                    filename: "incoming-material.pdf",
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
                })
                // .from(element).save().then(() => {
                //     window.location.href = `/warehouse/incoming-material/${id}/download-file`;
                // });
                .from(element).save().then(() => {
                    const url = `/warehouse/incoming-material/${id}/download-file`;

                    fetch(url, {
                            method: 'GET'
                        })
                        .then(response => {
                            if (!response.ok) {
                                console.warn("File tidak ditemukan");
                                return;
                            }
                            window.location.href = url;
                        })
                        .catch(error => {
                            console.error("Error saat mengecek file:", error);
                        });
                });
        }
    }
</script>
