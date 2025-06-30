@extends ('pdf.layout.layout')
@section('title', 'Pelabelan QC Passed PDF')
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
                    Formulir Laporan <br> Produk Jadi Masuk <br> dan Keluar
                </td>
                <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                    <table class="w-full text-sm dark:bg-gray-900 dark:text-white" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> :
                                FO-QKS-WBB-04-01</td>
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
        <div class="grid w-full max-w-4xl grid-cols-1 pt-2 mx-auto mb-6 text-sm gap-y-4">

            <h2 class="col-span-1 text-xl font-bold text-start">
                A. Informasi Umum
            </h2>

            <div class="w-full max-w-4xl mx-auto space-y-2 text-sm">
                @php
                    $fields = [
                        ['label' => 'Tanggal :', 'value' => $pelabelan->spk->no_spk],
                        ['label' => 'Penanggung Jawab :', 'value' => $pelabelan->penanggung_jawab],
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

            <h2 class="col-span-1 text-xl font-bold text-start">
                B. Detail Laporan Produk
            </h2>
        </div>
        <div class="max-w-4xl mx-auto overflow-x-auto">
            <div class="max-w-4xl mx-auto overflow-x-auto">

                <table class="w-full text-sm text-left border border-gray-300 dark:border-gray-600">
                    <thead class="text-black bg-gray-100 dark:bg-gray-800 dark:text-white">
                        <tr>
                            <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">Nomor</th>
                            <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">Nama Produk</th>
                            <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">Model/Type</th>
                            <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">S/N</th>
                            <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">Jenis Transaksi</th>
                            <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">Jumlah</th>
                            <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="text-black bg-white dark:bg-gray-900 dark:text-white">
                        @foreach ($pelabelan->details as $item)
                            <tr>

                                <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                    {{ $item->nama_produk }}
                                </td>

                                <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                    {{ $item->tipe }}
                                </td>

                                <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                    {{ $item->serial_number }}
                                </td>

                                <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                    {{ $item->jenis_transaksi }}
                                </td>

                                <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                    {{ $item->jumlah }}
                                </td>

                                <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                    {{ $item->keterangan }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <h2 class="col-span-1 pt-4 mb-4 text-xl font-bold text-start">
                C. Syarat dan Ketentuan
            </h2>

            <div class="w-full max-w-4xl pt-4 mx-auto space-y-2 text-sm">
                @php
                    $fields = [
                        ['label' => 'Total Produk Masuk :', 'value' => $pelabelan->total_masuk . ' Buah'],
                        ['label' => 'Total Produk Keluar :', 'value' => $pelabelan->total_keluar . ' Buah'],
                        ['label' => 'Sisa Stock :', 'value' => $pelabelan->sisa_stock . ' Buah'],
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
        </div>
        <div class="max-w-4xl mx-auto mt-10 text-sm">


            <div class="flex items-start justify-between gap-4">

                <!-- Kiri -->
                <div class="flex flex-col items-center">
                    <p class="mb-2 dark:text-white">Dibuat Oleh</p>
                    <img src="{{ asset('storage/' . $pelabelan->pic->created_signature) }}" alt="Product Signature"
                        class="h-20 w-80" />
                    <p class="mt-1 font-semibold dark:text-white">{{ $pelabelan->pic->created_name }}</p>
                </div>

                <!-- Kanan -->
                <div class="flex flex-col items-center">
                    <p class="mb-2 dark:text-white">Disetujui Oleh</p>
                    <img src="{{ asset('storage/' . $pelabelan->pic->approved_signature) }}" alt="Product Signature"
                        class="h-20 w-80" />
                    <p class="mt-1 font-semibold dark:text-white">{{ $pelabelan->pic->approved_name }}</p>
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
                filename: "pelabelan-qc-passed.pdf",
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
