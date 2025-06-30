@extends ('pdf.layout.layout')
@section('title', 'SPK Quality PDF')
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
                    Surat Perintah Kerja QC
                </td>
                <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                    <table class="w-full text-sm dark:bg-gray-900 dark:text-white" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> :
                                FO-QKS-PRO-01-05</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">Tanggal Rilis</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> : 12 Maret 2025
                            </td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2">Revisi</td>
                            <td class="px-3 py-2 font-semibold"> : 00</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        @php
            $fields = [
                ['label' => 'No SPK  :', 'value' => $spk_qc->no_spk],
                ['label' => 'No SPK MKT :', 'value' => $spk_qc->spk->no_spk],
                ['label' => 'Dari :', 'value' => $spk_qc->dari],
                ['label' => 'Kepada :', 'value' => $spk_qc->kepada],
            ];
        @endphp

        <div class="grid w-full max-w-4xl grid-cols-2 pt-4 mx-auto text-sm gap-x-6 gap-y-4">
            @foreach ($fields as $field)
                <div class="flex items-center">
                    <label class="w-32 font-medium">{{ $field['label'] }}</label>
                    <input type="text" readonly value="{{ $field['value'] }}"
                        class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                </div>
            @endforeach
        </div>
        <div class="w-full max-w-4xl mx-auto mt-6 overflow-x-auto text-sm">
            <table class="w-full text-sm text-left border border-gray-300 dark:border-gray-600">
                <thead class="text-black bg-gray-100 dark:bg-gray-800 dark:text-white">
                    <tr>
                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">Nomor</th>
                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">Nama Produk</th>
                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">Jumlah Pesanan</th>
                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">No URS</th>
                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">Rencana Pengiriman</th>
                    </tr>
                </thead>
                <tbody class="text-black bg-white dark:bg-gray-900 dark:text-white">
                    @foreach ($spk_qc->spk->spesifikasiProduct->details as $item)
                        <tr>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">{{ $loop->iteration }}
                            </td>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                {{ $item->product->name }}</td>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">{{ $item->quantity }}</td>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                {{ $spk_qc->spk->spesifikasiProduct->urs->no_urs }}</td>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                {{ \Carbon\Carbon::parse($spk_qc->spk->tanggal)->translatedFormat('d M Y') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="max-w-4xl mx-auto mt-10 text-sm">
            <div class="flex items-start justify-between gap-4">
                <!-- Kiri -->
                <div class="flex flex-col items-center">
                    <p class="mb-2 dark:text-white">Yang Membuat</p>
                    <img src="{{ asset('storage/' . $spk_qc->pic->create_signature) }}" alt="Product Signature"
                        class="h-20 w-80" />
                    <p class="mt-1 font-semibold dark:text-white">{{ $spk_qc->dari }}</p>
                    <p class="mt-1 font-semibold dark:text-white">Produksi</p>
                </div>
                <!-- Kanan -->
                <div class="flex flex-col items-center">
                    <p class="mb-2 dark:text-white">Yang Menerima</p>
                    <img src="{{ asset('storage/' . $spk_qc->pic->receive_signature) }}" alt="Product Signature"
                        class="h-20 w-80" />
                    <p class="mt-1 font-semibold dark:text-white">{{ $spk_qc->kepada }}</p>
                    <p class="mt-1 font-semibold dark:text-white">QC</p>
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
                filename: "spk-quality.pdf",
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
