{{--
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script> --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

        <div id="export-area" class="p-2 bg-white text-black">
            <div>
                <!-- HEADER DOKUMEN -->
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
                            Permintaan Spesifikasi Produk
                        </td>
                        <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                            <table class="w-full text-sm dark:bg-gray-900 dark:text-white" style="border-collapse: collapse;">
                                <tr>
                                    <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                                    <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> :
                                        FO-QKS-PRD-01-01</td>
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
            
                <!-- FORM -->
                @php
$fields = [
    ['label' => 'No', 'value' => $spesifikasi->urs->no_urs],
    ['label' => 'Phone Number', 'value' => $spesifikasi->urs->customer->phone_number],
    ['label' => 'Nama', 'value' => $spesifikasi->urs->customer->name],
    ['label' => 'Company Name', 'value' => $spesifikasi->urs->customer->company_name],
    ['label' => 'Department', 'value' => $spesifikasi->urs->customer->department],
    ['label' => 'Company Address', 'value' => $spesifikasi->urs->customer->company_address],
];
                @endphp
            
            <div class="max-w-4xl mx-auto flex flex-col gap-2 pt-4 text-sm">
                @foreach ($fields as $field)
                    <div class="flex flex-col sm:flex-row sm:items-center sm:gap-4">
                        <label class="font-medium sm:w-40">{{ $field['label'] }} :</label>
                        <input type="text" disabled class="w-full px-2 py-1 text-black bg-white border rounded-md border-gray-300"
                            value="{{ $field['value'] }}" />
                    </div>
                @endforeach      
            </div>
                <!-- Spesifikasi Teknis -->
                @php
            $chunks = $spesifikasi->details->chunk(2); // Bagi tiap 2 item
                @endphp
            
                <div class="max-w-4xl pt-6 mx-auto space-y-4 text-sm">
                    @foreach ($chunks as $chunk)
                        <div class="grid gap-4 {{ $chunk->count() == 1 ? 'grid-cols-1' : 'grid-cols-2' }}">
                            @foreach ($chunk as $detail)
                                <div class="p-2 border border-gray-300 rounded dark:border-gray-600">
                                    <div class="pb-4">
                                        <label class="block mb-2 font-medium">Nama Item</label>
                                        <input type="text" disabled
                                            class="w-full px-2 py-1 text-black bg-white border border-gray-300 rounded cursor-not-allowed dark:bg-gray-800 dark:text-white dark:border-gray-600"
                                            value="{{ $detail->product->name }}" />
                                    </div>
                                    <div class="pb-4">
                                        <label class="block mb-2 font-medium">Quantity</label>
                                        <input type="text" disabled
                                            class="w-full px-2 py-1 text-black bg-white border border-gray-300 rounded cursor-not-allowed dark:bg-gray-800 dark:text-white dark:border-gray-600"
                                            value="{{ $detail->quantity }}" />
                                    </div>
                                    @foreach ($detail->specification as $spec)
                                                                <div class="pb-4">
                                                                    <label class="block mb-2 font-medium">{{ $spec['name'] }}</label>
                                                                    <input type="text" disabled
                                                                        class="w-full px-2 py-1 text-black bg-white border border-gray-300 rounded cursor-not-allowed dark:bg-gray-800 dark:text-white dark:border-gray-600"
                                                                        value="{{ in_array($spec['name'], ['Water Feeding System', 'Software'])
                ? (isset($spec['value_bool']) && $spec['value_bool']
                    ? 'Ya'
                    : 'Tidak')
                : $spec['value_str'] ?? '-' }}" />
                                                                </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            
                <!-- Penanggung Jawab -->
                <div class="max-w-4xl pt-2 mx-auto text-sm">
                    <div>
                        <label class="pt-3 font-bold">Penanggung Jawab</label>
                        <div class="flex flex-col pt-3 text-sm">
                            <img src="{{ asset('storage/' . $spesifikasi->pic->signature) }}" alt="Product Signature"
                                class="h-20 w-80" />
                            <div class="mt-2 font-medium">
                                {{ $spesifikasi->pic->name }}
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <label class="font-bold">Tanggal: </label>
                        <input type="text" readonly disabled
                            class="px-2 py-1 text-black bg-white border border-gray-300 rounded cursor-not-allowed dark:bg-gray-800 dark:text-white dark:border-gray-600"
                            value="{{ \Carbon\Carbon::parse($spesifikasi->pic->date)->translatedFormat('d F Y') }}" />
                    </div>
                </div>
            </div>
        </div>
        <!-- Tombol Download -->
        <div class="mt-6 text-center">
            <button onclick="exportPDF()"
                class="inline-flex items-center px-5 py-2 text-sm font-semibold text-white bg-blue-600 rounded hover:bg-blue-700 transition-all">
                ⬇️ Download PDF
            </button>
        </div>
        <script src="https://raw.githack.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.min.js"></script>
        <!-- Script -->
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

                function renderPDF() {
                    html2pdf().set({
                        margin: [0.2, 0.2, 0.2, 0.2],
                        filename: "detail-spesifikasi-produk.pdf",
                        image: { type: "jpeg", quality: 1 },
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
                        pagebreak: { mode: ["avoid", "css"] }
                    }).from(element).save();
                }
            }
        </script>
