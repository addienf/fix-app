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
                    Surat Perinta Kerja Service <br> dan Maintenance
                </td>
                <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                    <table class="w-full text-sm dark:bg-gray-900 dark:text-white" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> :
                                FO-QKS-CC-01-02</td>
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
        <div class="max-w-4xl p-6 mx-auto text-sm text-black bg-white">
            <!-- Header -->
            <div class="flex justify-start mb-3">
                <label class="font-semibold">Complaint Form. No : {{ $service->complain->form_no }}</label>
            </div>

            <h2 class="mb-6 font-bold text-center uppercase">
                Formulir Surat Perintah Kerja (SPK) Service & Maintenance
            </h2>

            <!-- Informasi Umum -->
            @php
                $infoUmumFields = [
                    ['label' => 'Nomor SPK :', 'value' => $service->no_spk_service],
                    [
                        'label' => 'Tanggal :',
                        'value' => \Carbon\Carbon::parse($service->tanggal)->translatedFormat('d M Y'),
                    ],
                    ['label' => 'Alamat :', 'value' => $service->alamat],
                    ['label' => 'Perusahaan :', 'value' => $service->perusahaan],
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

            <!-- Deskripsi Pekerjaan -->
            <h3 class="mb-2 font-bold">A. Deskripsi Pekerjaan</h3>

            {{-- <div class="mb-3 space-y-2">
                                    <label><input type="checkbox" class="mr-1"> Service</label><br>
                                    <label><input type="checkbox" class="mr-1"> Maintenance</label><br>
                                    <label><input type="checkbox" class="mr-1"> Lainnya</label>
                                </div> --}}

            <div class="mb-3 space-y-2">
                <label>
                    <input type="checkbox" name="deskripsi_pekerjaan[]" value="service"
                        {{ in_array('service', $service->deskripsi_pekerjaan) ? 'checked' : '' }} disabled>
                    Service
                </label><br>

                <label>
                    <input type="checkbox" name="deskripsi_pekerjaan[]" value="maintenance"
                        {{ in_array('maintenance', $service->deskripsi_pekerjaan) ? 'checked' : '' }} disabled>
                    Maintenance
                </label><br>

                <label>
                    <input type="checkbox" name="deskripsi_pekerjaan[]" value="lainya"
                        {{ in_array('lainya', $service->deskripsi_pekerjaan) ? 'checked' : '' }} disabled>
                    Lainnya
                </label>
            </div>
            <div class="mb-6 space-y-2">
                <div class="flex items-center gap-2">
                    <label class="w-52">Jadwal Pelaksanaan : </label>
                    <input type="text" class="flex-1 px-2 py-1 border rounded"
                        value="{{ \Carbon\Carbon::parse($service->jadwal_pelaksana)->translatedFormat('d M Y') }}" />
                </div>
                {{-- <div class="flex items-center gap-2">
                                        <label class="w-52">Estimasi Waktu Selesai : </label>
                                        <input type="text" class="flex-1 px-2 py-1 border rounded"
                                            value="{{ \Carbon\Carbon::parse($service->waktu_selesai)->translatedFormat('d M Y') }}" />
                                    </div> --}}
                <div class="flex items-center gap-2">
                    <label class="w-52">Estimasi Waktu Selesai : </label>
                    <input type="text" class="flex-1 px-2 py-1 border rounded"
                        value="{{ \Carbon\Carbon::parse($service->waktu_selesai)->diffInDays(\Carbon\Carbon::parse($service->jadwal_pelaksana)) }} Hari" />
                </div>
                <div class="flex items-center gap-2">
                    <label class="w-52">Petugas yang ditugaskan : </label>
                </div>
            </div>

            <!-- Tabel Teknisi -->
            <table class="w-full mb-4 text-center border border-collapse border-black">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-2 py-1 border border-black ">No</th>
                        <th class="w-40 px-2 py-1 border border-black">Nama Teknisi</th>
                        <th class="w-40 px-2 py-1 border border-black">Jabatan</th>
                        <th class="px-2 py-1 border border-black">Tanda Tangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($service->petugas as $index => $data)
                        <tr>
                            <td class="px-4 py-2 border border-black">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 border border-black">{{ $data['nama_teknisi'] }}</td>
                            <td class="px-4 py-2 border border-black">{{ $data['jabatan'] }}</td>
                            <td class="px-4 py-2 border border-black"><img src="{{ asset('storage/' . $data['ttd']) }}"
                                    alt="Create Signature" class="mx-auto h-50" /></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- <label class="inline-flex items-center gap-2">
                                    <input type="checkbox" {{ $kondisi === 'perlu_perbaikan' ? 'checked' : '' }} disabled
                                        class="w-4 h-4 border border-gray-400 checked:bg-blue-600 checked:border-blue-600">
                                    <span>Perlu Perbaikan</span>
                                </label> --}}

            @php
                $kondisi = $service->pemeriksaanPersetujuan->status_pekerjaan;
            @endphp

            <!-- Pemeriksaan -->
            <h3 class="font-bold">B. Pemeriksaan dan Persetujuan</h3>
            <ol class="pl-5 mb-4 list-decimal">
                <li class="mb-2">
                    Apakah pekerjaan telah selesai sesuai permintaan?
                    <span class="ml-2">
                        <input type="radio" name="selesai" value="Ya" {{ $kondisi === 'ya' ? 'checked' : '' }}
                            disabled> Ya
                        <input type="radio" name="selesai" value="Tidak" class="ml-4"
                            {{ $kondisi === 'tidak' ? 'checked' : '' }} disabled> Tidak
                    </span>
                </li>
                <li>
                    Catatan Tambahan:
                    <div id="note" readonly
                        class="w-full min-h-[75px] px-3 py-2 mt-2 text-sm leading-relaxed text-left border rounded-md text-black border-black">
                        {{ trim($service->pemeriksaanPersetujuan->catatan_tambahan) }}
                    </div>
                </li>
            </ol>

            <div class="grid grid-cols-2 gap-2 text-center">

                <div>
                    <p class="mb-2">Dikonfirmasi Oleh,</p>

                    <div class="flex items-center justify-center w-40 h-24 mx-auto mb-2 bg-white ">
                        {{-- <span class="text-sm text-gray-400">Tanda Tangan</span> --}}
                        <img src="{{ asset('storage/' . $service->pic->dikonfirmasi_ttd) }}" alt="Product Signature"
                            class="h-20 w-80" />
                    </div>
                    <p class="font-semibold underline">{{ $service->pic->dikonfirmasiNama->name }}</p>
                </div>
                <div>
                    <p class="mb-2">Diketahui Oleh,</p>
                    <div class="flex items-center justify-center w-40 h-24 mx-auto mb-2 bg-white ">
                        {{-- <span class="text-sm text-gray-400">Tanda Tangan</span> --}}
                        <img src="{{ asset('storage/' . $service->pic->diketahui_ttd) }}" alt="Product Signature"
                            class="h-20 w-80" />
                    </div>
                    <p class="font-semibold underline">{{ $service->pic->diketahuiNama->name }}</p>
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
