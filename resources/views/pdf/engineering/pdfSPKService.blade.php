@extends ('pdf.layout.layout')
@section('title', 'SPK Service and Maintenance PDF')
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
                    Surat Perinta Kerja <br> Pelayanan Pelanggan
                </td>
                <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                    <table class="w-full text-sm dark:bg-gray-900 dark:text-white" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> :
                                FO-QKS-CC-01-05</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">Tanggal Rilis</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> : 13 Oktober 2025
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
        <div class="max-w-4xl p-6 mx-auto text-sm text-black bg-white">

            <h2 class="mb-6 font-bold text-center uppercase">
                FORMULIR SURAT PERINTAH KERJA (SPK)
            </h2>

            <!-- Informasi Umum -->
            @php
                $infoUmumFields = [
                    ['label' => 'Nomor SPK :', 'value' => $service->no_spk_service],
                    ['label' => 'Perusahaan :', 'value' => $service->perusahaan],
                    ['label' => 'Alamat :', 'value' => $service->alamat],
                ];
            @endphp

            <div class="mb-2 space-y-2">
                @foreach ($infoUmumFields as $field)
                    <div class="flex items-center gap-2">
                        <label class="w-40">{{ $field['label'] }}</label>
                        <input value="{{ $field['value'] }}" class="flex-1 px-2 py-1 border rounded" />
                    </div>
                @endforeach
            </div>

            @php
                $jenis = $service->deskripsi_pekerjaan ?? [];
                $lainnya = $service->deskripsi_pekerjaan_lainnya ?? null;
            @endphp

            <h3 class="mb-2 font-bold">A. Deskripsi Pekerjaan</h3>
            <div class="mb-3 space-y-2">

                <label class="flex items-center gap-2">
                    <input type="checkbox" disabled {{ in_array('service', $jenis) ? 'checked' : '' }}>
                    <span>Service</span>
                </label>

                <label class="flex items-center gap-2">
                    <input type="checkbox" disabled {{ in_array('maintenance', $jenis) ? 'checked' : '' }}>
                    <span>Maintenance</span>
                </label>

                <label class="flex items-center gap-2">
                    <input type="checkbox" disabled {{ in_array('kalibrasi', $jenis) ? 'checked' : '' }}>
                    <span>Kalibrasi</span>
                </label>

                <div class="flex items-center col-span-2 gap-2">
                    <input type="checkbox" disabled {{ in_array('lainnya', $jenis) ? 'checked' : '' }}>
                    <span>Lainnya :</span>

                    <input type="text" class="flex-1 px-1 py-0.5 bg-transparent" readonly value="{{ $lainnya ?? '' }}"
                        placeholder="...................................................." />
                </div>
            </div>

            <h3 class="mt-4 font-bold">B. IDENTITAS ALAT</h3>
            <table class="w-full text-sm text-left border border-gray-300 dark:border-gray-600">
                <thead class="text-black bg-gray-100 dark:bg-gray-800 dark:text-white">
                    <tr>
                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">Nomor</th>
                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">Nama Alat</th>
                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">Tipe</th>
                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">Nomor Seri</th>
                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">Resolusi</th>
                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">Titik Ukur</th>
                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">Quantity</th>
                    </tr>
                </thead>
                <tbody class="text-black bg-white dark:bg-gray-900 dark:text-white">
                    @foreach ($service->details as $item)
                        <tr>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">{{ $loop->iteration }}
                            </td>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">{{ $item->nama_alat }}</td>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">{{ $item->tipe }}</td>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">{{ $item->nomor_seri }}</td>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">{{ $item->resolusi }}</td>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">{{ $item->titik_ukur }}</td>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">{{ $item->quantity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <h3 class="mt-4 font-bold">C. PELAKSANAAN</h3>
            @php
                $pelaksanaan = [
                    [
                        'label' => 'Tanggal Pelaksanaan :',
                        'value' => \Carbon\Carbon::parse($service->tanggal_pelaksanaan)->translatedFormat('d F Y'),
                    ],
                    ['label' => 'Tempat Pelaksanaan :', 'value' => $service->tempat_pelaksanaan],
                ];
            @endphp

            <div class="mb-2 space-y-2">
                @foreach ($pelaksanaan as $field)
                    <div class="flex items-center gap-2">
                        <label class="w-40">{{ $field['label'] }}</label>
                        <input value="{{ $field['value'] }}" class="flex-1 px-2 py-1 border rounded" />
                    </div>
                @endforeach
                <div class="flex items-center gap-2">
                    <label class="w-52">Petugas yang ditugaskan : </label>
                </div>
            </div>

            <table class="w-full mb-4 text-center border border-collapse border-black">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="w-10 px-2 py-1 border border-black">No</th>
                        <th class="w-40 px-2 py-1 border border-black">Nama Teknisi</th>
                        <th class="w-40 px-2 py-1 border border-black">Jabatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($service->petugas as $index => $data)
                        <tr>
                            <td class="w-10 px-1 py-1 border border-black">{{ $index + 1 }}</td>
                            <td class="px-2 py-1 border border-black">{{ $data['nama_teknisi'] }}</td>
                            <td class="px-2 py-1 border border-black">{{ $data['jabatan'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="grid grid-cols-2 gap-2 text-center">
                <div>
                    <p class="mb-2">Dikonfirmasi Oleh,</p>

                    <div class="flex items-center justify-center w-40 h-24 mx-auto mb-2 bg-white ">
                        <img src="{{ asset('storage/' . $service->pic->dikonfirmasi_signature) }}" alt="Product Signature"
                            class="h-20 w-80" />
                    </div>
                    <p class="font-semibold underline">{{ $service->pic->dikonfirmasiNama->name }}</p>
                </div>
                <div>
                    <p class="mb-2">Dibuat Oleh,</p>
                    <div class="flex items-center justify-center w-40 h-24 mx-auto mb-2 bg-white ">
                        <img src="{{ asset('storage/' . $service->pic->dibuat_signature) }}" alt="Product Signature"
                            class="h-20 w-80" />
                    </div>
                    <p class="font-semibold underline">{{ $service->pic->dibuatNama->name }}</p>
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
