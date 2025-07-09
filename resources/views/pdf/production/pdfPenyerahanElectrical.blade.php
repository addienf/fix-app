@extends ('pdf.layout.layout')
@section('title', 'Serah Terima Electrical PDF')
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
                    Formulir Serah Terima <br> Produksi Mekanik ke <br> Produksi Elektrikal
                </td>
                <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                    <table class="w-full text-sm dark:bg-gray-900 dark:text-white" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> :
                                FO-QKS-PRO-01-04</td>
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
            $fields2 = [
                ['label' => '1. Nama Produk :', 'value' => $serahElectrical->nama_produk],
                ['label' => '2. Type/Model :', 'value' => $serahElectrical->tipe],
                ['label' => '3. Nomor SPK. MKT :', 'value' => $serahElectrical->no_spk],
                [
                    'label' => '4. Tanggal Produksi Mekanik Selesai:',
                    'value' => \Carbon\Carbon::parse($serahElectrical->tanggal_selesai)->format('d M Y'),
                ],
                ['label' => '5. Jumlah Unit :', 'value' => $serahElectrical->jumlah],
            ];

            $kondisi = $serahElectrical->kondisi ?? null;

            $deskripsi_kondisi = $serahElectrical->deskripsi_kondisi ?? null;

            $kondisi_fisik = $serahElectrical->sebelumSerahTerima->kondisi_fisik ?? null;

            $detail_kondisi_fisik = $serahElectrical->sebelumSerahTerima->kondisi_fisik ?? null;

            $komponen = $serahElectrical->sebelumSerahTerima->kelengkapan_komponen ?? null;

            $detail_kelengkapan_komponen = $serahElectrical->sebelumSerahTerima->kelengkapan_komponen ?? null;

            $dokumen = $serahElectrical->sebelumSerahTerima->dokumen_pendukung ?? null;

            $fields3 = [
                [
                    'label' => 'Tanggal Serah Terima :',
                    'value' => \Carbon\Carbon::parse($serahElectrical->penerimaElectrical->tanggal)->format('d M Y'),
                ],
                [
                    'label' => 'Diterima oleh (Nama & Jabatan) :',
                    'value' => $serahElectrical->penerimaElectrical->diterima_oleh,
                ],
                [
                    'label' => 'Catatan Tambahan :',
                    'value' => $serahElectrical->penerimaElectrical->catatan_tambahan,
                ],
            ];

            $status_penerimaan = $serahElectrical->penerimaElectrical->status_penerimaan ?? null;

            // $penerimaan = $serahElectrical->penerimaElectrical->status_penerimaan ?? null;

            $penjelasan_status = $serahElectrical->penerimaElectrical->penjelasan_status ?? null;

            $alasan_status = $serahElectrical->penerimaElectrical->alasan_status ?? null;

            $roles = [
                'Diserahkan Oleh,' => [
                    'name' => $serahElectrical->pic->submit_name ?? '-',
                    'signature' => $serahElectrical->pic->submit_signature ?? null,
                    'date' => $serahElectrical->pic->submit_date ?? null,
                ],
                'Diterima Oleh,' => [
                    'name' => $serahElectrical->pic->receive_name ?? '-',
                    'signature' => $serahElectrical->pic->receive_signature ?? null,
                    'date' => $serahElectrical->pic->receive_date ?? null,
                ],
                'Diketahui Oleh,' => [
                    'name' => $serahElectrical->pic->knowing_name ?? '-',
                    'signature' => $serahElectrical->pic->knowing_signature ?? null,
                    'date' => $serahElectrical->pic->knowing_date ?? null,
                ],
            ];
        @endphp

        <div class="grid w-full max-w-4xl grid-cols-1 pt-2 mx-auto my-2 gap-y-2">
            <h2 class="col-span-1 text-xl font-bold text-start">
                Dari Divisi Mekanik ke Divisi Elektrikal
            </h2>
            <h2 class="col-span-1 -mt-1 text-lg font-bold text-start">
                A. Informasi Produk
            </h2>
            <div class="flex flex-col gap-2">
                @foreach ($fields2 as $field)
                    <div class="flex items-center gap-4">
                        <label class="font-medium w-72">{{ $field['label'] }}</label>
                        <input type="text" readonly value="{{ $field['value'] }}"
                            class="flex-1 px-3 py-2 text-sm text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                    </div>
                @endforeach
            </div>
            <div class="flex items-start gap-2">
                <label class="w-48 font-medium">5. Kondisi Produk :</label>
                <div class="flex flex-col gap-1">
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" {{ $kondisi === 'baik' ? 'checked' : '' }} disabled
                            class="w-4 h-4 border border-gray-400 checked:bg-blue-600 checked:border-blue-600">
                        <span>Baik</span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" {{ $kondisi === 'cukup_baik' ? 'checked' : '' }} disabled
                            class="w-4 h-4 border border-gray-400 checked:bg-blue-600 checked:border-blue-600">
                        <span>Cukup Baik</span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" {{ $kondisi === 'perlu_perbaikan' ? 'checked' : '' }} disabled
                            class="w-4 h-4 border border-gray-400 checked:bg-blue-600 checked:border-blue-600">
                        <span>Perlu Perbaikan</span>
                    </label>
                </div>
            </div>
            <div class="">
                <label class="block mb-1 font-semibold">Jelaskan</label>
                <div
                    class="w-full min-h-[75px] px-3 py-2 text-sm leading-relaxed text-left border rounded-md text-black border-black">
                    {{ $deskripsi_kondisi }}
                </div>
            </div>

            <h2 class="col-span-1 text-xl font-bold text-start">
                B. Pengecekan Sebelum Serah Terima
            </h2>
            <div class="mb-1">
                <label class="block font-medium">1. Kondisi Fisik Produk</label>
                <div class="flex flex-col gap-1 pl-4">
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" {{ $kondisi_fisik === 'baik' ? 'checked' : '' }} disabled
                            class="w-4 h-4 border border-gray-400 checked:bg-blue-600 checked:border-blue-600">
                        <span>Tidak ada kerusakan fisik</span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" {{ $kondisi_fisik === 'cukup_baik' ? 'checked' : '' }} disabled
                            class="w-4 h-4 border border-gray-400 checked:bg-blue-600 checked:border-blue-600">
                        <span>Ada sedikit cacat visual</span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" {{ $kondisi_fisik === 'perlu_perbaikan' ? 'checked' : '' }} disabled
                            class="w-4 h-4 border border-gray-400 checked:bg-blue-600 checked:border-blue-600">
                        <span>Ada kerusakan signifikan</span>
                    </label>
                </div>
            </div>
            <div class="">
                <label class="block mb-1 font-semibold">Jelaskan</label>
                <div
                    class="w-full min-h-[75px] px-3 py-2 text-sm leading-relaxed text-left border rounded-md text-black border-black">
                    {{ $deskripsi_kondisi }}
                </div>
            </div>

            <div class="mb-1">
                <label class="block mb-2 font-medium">2. Kelengkapan Komponen</label>
                <div class="flex flex-col gap-1 pl-4">
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" {{ $komponen === 'semua' ? 'checked' : '' }} disabled
                            class="w-4 h-4 border border-gray-400 checked:bg-blue-600 checked:border-blue-600">
                        <span>Semua komponen mekanik terpasang dengan benar</span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" {{ $komponen === 'kurang' ? 'checked' : '' }} disabled
                            class="w-4 h-4 border border-gray-400 checked:bg-blue-600 checked:border-blue-600">
                        <span>Ada komponen yang kurang</span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" {{ $komponen === 'perlu_diganti' ? 'checked' : '' }} disabled
                            class="w-4 h-4 border border-gray-400 checked:bg-blue-600 checked:border-blue-600">
                        <span>Ada komponen yang perlu diganti</span>
                    </label>
                </div>
            </div>

            <div class="pt-4">
                <label class="block mb-1 font-semibold">Sebutkan</label>
                <div
                    class="w-full min-h-[75px] px-3 py-2 text-sm leading-relaxed text-left border rounded-md text-black border-black">
                    {{ $deskripsi_kondisi }}
                </div>
            </div>

            <div class="pt-4 mb-1">
                <label class="block mb-2 font-medium">3. Dokumen Pendukung</label>
                <div class="flex flex-col gap-1 pl-4">
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" {{ $dokumen === 'gambar_teknis' ? 'checked' : '' }} disabled
                            class="w-4 h-4 border border-gray-400 checked:bg-blue-600 checked:border-blue-600">
                        <span>Gambar Teknis</span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" {{ $dokumen === 'sop' ? 'checked' : '' }} disabled
                            class="w-4 h-4 border border-gray-400 checked:bg-blue-600 checked:border-blue-600">
                        <span>SOP atau Instruksi Perakitan</span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" {{ $dokumen === 'laporan' ? 'checked' : '' }} disabled
                            class="w-4 h-4 border border-gray-400 checked:bg-blue-600 checked:border-blue-600">
                        <span>Laporan QC</span>
                    </label>
                </div>
            </div>

            <h2 class="col-span-1 text-xl font-bold text-start">C. Serah Terima</h2>

            @foreach ($fields3 as $field)
                <div class="flex items-center gap-2">
                    <label class="font-medium w-72">{{ $field['label'] }}</label>
                    <input type="text" readonly value="{{ $field['value'] }}"
                        class="flex-1 px-3 py-2 text-sm text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                </div>
            @endforeach

            <div class="mb-1">
                <label class="block mb-2 font-medium">Status Penerimaan</label>
                <div class="flex flex-col gap-1 pl-4">
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" {{ $status_penerimaan === 'diterima' ? 'checked' : '' }} disabled
                            class="w-4 h-4 border border-gray-400 checked:bg-blue-600 checked:border-blue-600">
                        <span>Diterima tanpa catatan</span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" {{ $status_penerimaan === 'catatan' ? 'checked' : '' }} disabled
                            class="w-4 h-4 border border-gray-400 checked:bg-blue-600 checked:border-blue-600">
                        <span>Diterima dengan catatan</span>
                    </label>
                    <div class="">
                        <label class="block mb-1 font-semibold">Jelaskan</label>
                        <div
                            class="w-full min-h-[75px] px-3 py-2 text-sm leading-relaxed text-left border rounded-md text-black border-black">
                            {{ $deskripsi_kondisi }}
                        </div>
                    </div>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" {{ $status_penerimaan === 'ditolak' ? 'checked' : '' }} disabled
                            class="w-4 h-4 border border-gray-400 checked:bg-blue-600 checked:border-blue-600">
                        <span>Ditolak dan dikembalikan</span>
                    </label>
                    <div class="">
                        <label class="block mb-1 font-semibold">Jelaskan</label>
                        <div
                            class="w-full min-h-[75px] px-3 py-2 text-sm leading-relaxed text-left border rounded-md text-black border-black">
                            {{ $deskripsi_kondisi }}
                        </div>
                    </div>
                </div>
            </div>
            <h2 class="col-span-1 text-xl font-bold text-start">D. Tanda Tangan</h2>
        </div>
        <div class="max-w-4xl pt-2 mx-auto">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                @foreach ($roles as $role => $data)
                    <div>
                        <label class="block mb-1 font-semibold">{{ $role }}</label>

                        <div class="flex items-center justify-center w-full h-24 mb-2 bg-white">
                            @if ($data['signature'])
                                <img src="{{ asset('storage/' . $data['signature']) }}" alt="Signature"
                                    class="object-contain h-full" />
                            @else
                                <span class="text-sm text-gray-400">No Signature</span>
                            @endif
                        </div>
                        <input type="text" value="{{ $data['name'] }}" readonly
                            class="w-full p-2 mb-2 text-black " />
                    </div>
                @endforeach
            </div>
            <p>Catatan: Formulir ini harus diisi dengan lengkap sebelum produk diserahkan ke divisi berikutnya. Jika ada
                kendala, harap segera dikomunikasikan kepada pihak terkait. Terima kasih.</p>
        </div>
    </div>

    <div class="mt-6 mb-3 text-center">
        <button onclick="exportPDF('{{ $serahElectrical->id }}')"
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
                filename: "serah-terima-electrical.pdf",
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
            }).from(element).save().then(() => {
                window.location.href = `/produksi/penyerahan-electrical/${id}/download-file`;
            });
        }
    }
</script>
