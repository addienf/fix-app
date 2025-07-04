@extends ('pdf.layout.layout')
@section('title', 'Penyerahan Produk Jadi PDF')
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
                        Formulir Penyerahan Produk Jadi
                    </td>
                    <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                        <table class="w-full text-sm dark:bg-gray-900 dark:text-white" style="border-collapse: collapse;">
                            <tr>
                                <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                                <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> :
                                    FO-QKS-PRO-01-02</td>
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
    $fields = [
        [
            'label' => 'Tanggal  :',
            'value' => \Carbon\Carbon::parse($produkJadi->tanggal)->translatedFormat('d M Y'),
        ],
        ['label' => 'Penanggung Jawab :', 'value' => $produkJadi->penanggug_jawab],
        ['label' => 'Penerima :', 'value' => $produkJadi->penerima],
    ];

    $kondisi = $produkJadi->kondisi_produk ?? null;
            @endphp

            <h2 class="w-full max-w-4xl col-span-1 pt-4 mx-auto text-xl font-bold text-start">
                A. Informasi Umum
            </h2>
            <div class="grid w-full max-w-4xl grid-cols-1 pt-4 mx-auto text-sm gap-y-4">
                @foreach ($fields as $field)
                    <div class="flex items-center gap-4">
                        <label class="w-48 font-medium">{{ $field['label'] }}</label>
                        <input type="text" readonly value="{{ $field['value'] }}"
                            class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                    </div>
                @endforeach
            </div>
            
        <h2 class="w-full max-w-4xl col-span-1 pt-4 mx-auto mb-3 text-xl font-bold text-start">
            B. Detail Produk
        </h2>

        <div class="w-full max-w-4xl mx-auto mt-6 overflow-x-auto text-sm">
            <table class="w-full text-sm text-left border border-gray-300 dark:border-gray-600">
                <thead class="text-black bg-gray-100 dark:bg-gray-800 dark:text-white">
                    <tr>
                        <th class="px-2 py-1 border border-gray">No</th>
                        <th class="px-2 py-1 border border-gray">Nama Produk</th>
                        <th class="px-2 py-1 border border-gray">Tipe/Model</th>
                        <th class="px-2 py-1 border border-gray">Volume</th>
                        <th class="px-2 py-1 border border-gray">Jumlah</th>
                        <th class="px-2 py-1 border border-gray">SPK MKT No.</th>
                    </tr>
                </thead>
                <tbody class="text-black bg-white dark:bg-gray-900 dark:text-white">
                    @foreach ($produkJadi->details as $item)
                            <tr>
                                <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">{{ $loop->iteration }}
                                </td>
                                <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                    {{ $item->nama_produk }}</td>
                                <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">{{ $item->tipe }}</td>
                                <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                    {{ $item->volume }}</td>
                                <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                    {{ $item->no_seri }}</td>
                                <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                    {{ $item->jumlah }}</td>
                                <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                    {{ $item->no_spk }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <h2 class="w-full max-w-4xl col-span-1 pt-4 mx-auto mb-3 text-xl font-bold text-start">
                C. Kondisi Produk
            </h2>

            <div class="flex flex-col items-start w-full max-w-4xl mx-auto space-y-2 text-sm">

                <label class="inline-flex items-center space-x-2">
                    <input type="checkbox" {{ $kondisi === 'baik' ? 'checked' : '' }} disabled
                        class="w-4 h-4 border border-gray-400 checked:bg-blue-600 checked:border-blue-600">
                    <span>Baik</span>
                </label>

                <label class="inline-flex items-center space-x-2">
                    <input type="checkbox" {{ $kondisi === 'rusak' ? 'checked' : '' }} disabled
                        class="w-4 h-4 border border-gray-400 checked:bg-blue-600 checked:border-blue-600">
                    <span>Rusak</span>
                </label>

                <label class="inline-flex items-center space-x-2">
                    <input type="checkbox" {{ $kondisi === 'perlu_perbaikan' ? 'checked' : '' }} disabled
                        class="w-4 h-4 border border-gray-400 checked:bg-blue-600 checked:border-blue-600">
                    <span>Perlu Perbaikan</span>
                </label>
            </div>

            <h2 class="w-full max-w-4xl col-span-1 pt-4 mx-auto text-xl font-bold text-start">
                D. Catatan Tambahan
            </h2>

            <div class="max-w-4xl pt-4 mx-auto text-sm">
                <label class="block mb-1 font-semibold">Catatan</label>
                <div
                    class="w-full min-h-[75px] px-3 py-2 text-sm leading-relaxed text-left border rounded-md text-black border-black">
                    {{ trim($produkJadi->catatan_tambahan) }}
                </div>
            </div>

            <div class="max-w-4xl mx-auto mt-10 text-sm">
                <div class="flex items-start justify-between gap-4">
                    <!-- Kiri -->
                    <div class="flex flex-col items-center">
                        <p class="mb-2 dark:text-white">Diserahkan Oleh</p>
                        <img src="{{ asset('storage/' . $produkJadi->pic->submit_signature) }}" alt="Product Signature"
                            class="h-20 w-80" />
                        <p class="mt-4 font-semibold dark:text-white">{{ $produkJadi->pic->submit_name }}</p>
                    </div>
                    <!-- Kanan -->
                    <div class="flex flex-col items-center">
                        <p class="mb-2 dark:text-white">Diterima Oleh</p>
                        <img src="{{ asset('storage/' . $produkJadi->pic->receive_signature) }}" alt="Product Signature"
                            class="h-20 w-80" />
                        <p class="mt-4 font-semibold dark:text-white">{{ $produkJadi->pic->receive_name }}</p>
                    </div>
                </div>
            </div>

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
                filename: "penyerahan-produk-jadi.pdf",
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
