@extends ('pdf.layout.layout')
@section('title', 'Permintaan Bahan Warehouse PDF')
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
                    Formulir Permintaan Bahan
                </td>
                <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                    <table class="w-full text-sm dark:bg-gray-900 dark:text-white" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> :
                                FO-QKS-WRH-03-01</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">Tanggal Rilis</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> : 16 Juli 2025
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
                ['label' => 'Nomor :', 'value' => $permintaan_bahan->no_surat],
                [
                    'label' => 'Tanggal :',
                    'value' => \Carbon\Carbon::parse($permintaan_bahan->tanggal)->translatedFormat('d F Y'),
                ],
                ['label' => 'Dari : ', 'value' => $permintaan_bahan->dari],
                ['label' => 'Kepada :', 'value' => $permintaan_bahan->kepada],
            ];
        @endphp

        <div class="grid max-w-4xl grid-cols-1 pt-6 mx-auto mb-6 text-sm md:grid-cols-2 gap-x-6 gap-y-4">
            @foreach ($infoUmum as $field)
                <div class="flex flex-col items-start gap-2 md:flex-row md:gap-4 md:items-center">
                    <label class="font-medium md:w-48">{{ $field['label'] }}</label>
                    <input type="text"
                        class="w-full px-2 py-1 text-black bg-white border border-gray-300 rounded dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                        value="{{ $field['value'] }}" />
                </div>
            @endforeach
        </div>

        <!-- PARAGRAF PERMINTAAN -->
        <div class="max-w-4xl mx-auto mb-6 text-sm">
            <p class="mb-2">Dengan hormat,</p>
            <p class="flex flex-wrap items-center gap-1">
                <span>Berdasarkan Permintaan Barang No</span>
                <input disabled class="w-64 px-2 py-1 text-sm align-middle bg-transparent border-none h-7"
                    value="{{ $permintaan_bahan->permintaanBahanPro->no_surat ?? 'Untuk Stock' }}" />
                <span>Dari Departemen</span>
                <input disabled class="w-32 px-2 py-1 text-sm align-middle bg-transparent border-none h-7"
                    value="{{ Str::headline($permintaan_bahan->pic->dibuatName->roles->first()?->name ?? '-') }}" />
                <span>mohon bantuan untuk memenuhi kebutuhan bahan/sparepart dengan rincian sebagai berikut:</span>
            </p>
        </div>

        <!-- TABEL PRODUK -->
        <div class="max-w-4xl mx-auto overflow-x-auto">
            <table class="w-full text-sm text-left border border-gray-300 dark:border-gray-600">
                <thead class="text-black bg-gray-100 dark:bg-gray-800 dark:text-white">
                    <tr>
                        <th class="px-4 py-2 border">No</th>
                        <th class="px-4 py-2 border">Nama Bahan</th>
                        <th class="px-4 py-2 border">Spesifikasi</th>
                        <th class="px-4 py-2 border">Jumlah</th>
                        <th class="px-4 py-2 border">Keperluan Barang</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900">
                    @foreach ($permintaan_bahan->details as $index => $produk)
                        <tr>
                            <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 border">{{ $produk['bahan_baku'] }}</td>
                            <td class="px-4 py-2 border">{{ $produk['spesifikasi'] }}</td>
                            <td class="px-4 py-2 border">{{ $produk['jumlah'] }}</td>
                            <td class="px-4 py-2 border">{{ $produk['keperluan_barang'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @php
            $roles = [
                'Dibuat Oleh,' => [
                    'name' => $permintaan_bahan->pic->dibuatName->name ?? '-',
                    'signature' => $permintaan_bahan->pic->dibuat_signature ?? null,
                    'date' => $permintaan_bahan->pic->dibuat_date ?? null,
                ],
                'Mengetahui' => [
                    'name' => $permintaan_bahan->pic->mengetahuiName->name ?? '-',
                    'signature' => $permintaan_bahan->pic->mengetahui_signature ?? null,
                    'date' => $permintaan_bahan->pic->mengetahui_date ?? null,
                ],
                'Diserahkan Ke,' => [
                    'name' => $permintaan_bahan->pic->diserahkanName->name ?? '-',
                    'signature' => $permintaan_bahan->pic->diserahkan_signature ?? null,
                    'date' => $permintaan_bahan->pic->diserahkan_date ?? null,
                ],
            ];
        @endphp

        <div class="max-w-4xl pt-10 mx-auto text-sm ttd">
            <table class="w-full text-sm border-collapse">
                <thead>
                    <tr class="font-semibold text-center bg-gray-100">
                        @foreach ($roles as $role => $data)
                            <th class="border border-gray-300 border-[1px] py-2">{{ $role }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>

                    <tr>
                        @foreach ($roles as $data)
                            <td class="border border-gray-300 border-[1px] px-2 py-4">
                                <div class="flex items-center justify-center h-24">
                                    @if ($data['signature'])
                                        <img src="{{ asset('storage/' . $data['signature']) }}"
                                            class="object-contain h-full" />
                                    @else
                                        <span class="text-sm text-gray-400">No Signature</span>
                                    @endif
                                </div>
                            </td>
                        @endforeach
                    </tr>

                    <tr>
                        @foreach ($roles as $data)
                            <td class="border border-gray-300 border-[1px] px-2 py-2 text-center font-medium">
                                {{ $data['name'] }}
                            </td>
                        @endforeach
                    </tr>

                    <tr>
                        @foreach ($roles as $data)
                            <td class="border border-gray-300 border-[1px] px-2 py-2 text-center">
                                {{ $data['date'] ? \Carbon\Carbon::parse($data['date'])->format('d M Y') : '-' }}
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
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
                filename: "permintaan-bahan-warehouse.pdf",
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
