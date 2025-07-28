@extends ('pdf.layout.layout')
@section('title', 'Spesifikasi Produk PDF')
@section('content')
    <div id="export-area" class="p-2 text-black bg-white">
        <div>
            <!-- HEADER DOKUMEN -->
            <table class="w-full max-w-4xl mx-auto text-sm border border-black " style="border-collapse: collapse;">
                <tr>
                    <td rowspan="3" class="p-2 text-center align-middle border border-black w-28 h-28">
                        <img src="{{ asset('asset/logo.png') }}" alt="Logo" class="object-contain mx-auto h-30" />
                    </td>
                    <td colspan="2" class="font-bold text-center border border-black">
                        PT. QLab Kinarya Sentosa
                    </td>
                </tr>
                <tr>
                    <td class="font-bold text-center border border-black" style="font-size: 20px;">
                        Permintaan Spesifikasi Produk
                    </td>
                    <td rowspan="2" class="p-0 align-top border border-black">
                        <table class="w-full text-sm " style="border-collapse: collapse;">
                            <tr>
                                <td class="px-3 py-2 border-b border-black">No. Dokumen</td>
                                <td class="px-3 py-2 font-semibold border-b border-black"> :
                                    FO-QKS-MKT-01-01</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 border-b border-black">Tanggal Rilis</td>
                                <td class="px-3 py-2 font-semibold border-b border-black"> : 12 Maret 2025
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

            <div class="flex flex-col max-w-4xl gap-4 pt-6 mx-auto text-sm">
                @foreach ($fields as $field)
                    <div class="flex flex-col sm:flex-row sm:items-center sm:gap-4">
                        <label class="font-medium sm:w-40">{{ $field['label'] }} :</label>
                        <input type="text" disabled
                            class="w-full px-2 py-1 text-black bg-white border border-gray-300 rounded-md"
                            value="{{ $field['value'] }}" />
                    </div>
                @endforeach
            </div>

            @php
                $chunks = $spesifikasi->details->chunk(2); // Bagi tiap 2 item
            @endphp

            <div class="max-w-4xl pt-8 mx-auto space-y-8 text-sm">
                {{-- @foreach ($chunks as $chunk)
                    @foreach ($chunk as $detail)
                        <div class="p-4 space-y-5 bg-white border border-gray-400 rounded-md shadow-sm">

                            <div class="grid items-center grid-cols-4 gap-2">
                                <label class="col-span-1 font-medium">Nama Item :</label>
                                <input type="text" disabled
                                    class="col-span-3 px-2 py-1 text-black bg-white border border-gray-300 rounded cursor-not-allowed dark:bg-gray-800 dark:text-white dark:border-gray-600"
                                    value="{{ $detail->product->name }}" />
                            </div>

                            <div class="grid items-center grid-cols-4 gap-2">
                                <label class="col-span-1 font-medium">Quantity :</label>
                                <input type="text" disabled
                                    class="col-span-3 px-2 py-1 text-black bg-white border border-gray-300 rounded cursor-not-allowed dark:bg-gray-800 dark:text-white dark:border-gray-600"
                                    value="{{ $detail->quantity }}" />
                            </div>

                            @foreach ($detail->specification as $spec)
                                <div class="grid items-center grid-cols-4 gap-2">
                                    <label class="col-span-1 font-medium">{{ $spec['name'] }} :</label>
                                    <input type="text" disabled
                                        class="col-span-3 px-2 py-1 text-black bg-white border border-gray-300 rounded cursor-not-allowed dark:bg-gray-800 dark:text-white dark:border-gray-600"
                                        value="{{ match ($spec['name']) {
                                            'Tipe Chamber' => $spec['value_bool'] === 'knockdown' ? 'Knockdown' : 'Regular',

                                            'Water Feeding System' => in_array($spec['value_bool'], ['1', 'yes']) ? 'Ya' : 'Tidak',

                                            'Software' => $spec['value_bool'] === 'with' ? 'With Software' : 'Without Software',

                                            default => $spec['value_str'] ?? ($spec['value_bool'] ?? '-'),
                                        } }}" />
                                </div>
                            @endforeach

                        </div>
                    @endforeach
                @endforeach --}}

                @foreach ($chunks as $chunk)
                    @foreach ($chunk as $detail)
                        <div class="p-4 space-y-5 bg-white border border-gray-400 rounded-md shadow-sm">

                            {{-- Produk --}}
                            <div class="grid items-center grid-cols-4 gap-2">
                                <label class="col-span-1 font-medium">Nama Item :</label>
                                <input type="text" disabled
                                    class="col-span-3 px-2 py-1 text-black bg-white border border-gray-300 rounded cursor-not-allowed"
                                    value="{{ $detail->product->name }}" />
                            </div>

                            {{-- Quantity --}}
                            <div class="grid items-center grid-cols-4 gap-2">
                                <label class="col-span-1 font-medium">Quantity :</label>
                                <input type="text" disabled
                                    class="col-span-3 px-2 py-1 text-black bg-white border border-gray-300 rounded cursor-not-allowed"
                                    value="{{ $detail->quantity }}" />
                            </div>

                            {{-- Cek kategori produk: QLab atau bukan --}}
                            @if ($detail->product->category?->id === 1)
                                {{-- Spesifikasi QLab --}}
                                @foreach ($detail->specification ?? [] as $spec)
                                    <div class="grid items-center grid-cols-4 gap-2">
                                        <label class="col-span-1 font-medium">{{ $spec['name'] }} :</label>
                                        <input type="text" disabled
                                            class="col-span-3 px-2 py-1 text-black bg-white border border-gray-300 rounded cursor-not-allowed"
                                            value="{{ match ($spec['name']) {
                                                'Tipe Chamber' => $spec['value_bool'] === 'knockdown' ? 'Knockdown' : 'Regular',
                                                'Water Feeding System' => in_array($spec['value_bool'], ['1', 'yes']) ? 'Ya' : 'Tidak',
                                                'Software' => $spec['value_bool'] === 'with' ? 'With Software' : 'Without Software',
                                                default => $spec['value_str'] ?? ($spec['value_bool'] ?? '-'),
                                            } }}" />
                                    </div>
                                @endforeach
                            @else
                                {{-- Spesifikasi Mecmesin / Non-QLab --}}
                                @foreach ($detail->specification_mecmesin ?? [] as $spec)
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="font-medium">Sample:</label>
                                            <div class="p-1 mt-1 border rounded">{{ $spec['sample'] ?? '-' }}</div>
                                        </div>
                                        <div>
                                            <label class="font-medium">Capacity:</label>
                                            <div class="p-1 mt-1 border rounded">{{ $spec['capacity'] ?? '-' }}</div>
                                        </div>
                                        <div>
                                            <label class="font-medium">Jenis Tes:</label>
                                            <div class="p-1 mt-1 border rounded">{{ ucfirst($spec['jenis_tes'] ?? '-') }}
                                            </div>
                                        </div>
                                        <div>
                                            <label class="font-medium">Test Type:</label>
                                            <div class="p-1 mt-1 border rounded">{{ ucfirst($spec['test_type'] ?? '-') }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                        </div>
                    @endforeach
                @endforeach
            </div>

            <!-- Penanggung Jawab -->
            <div class="max-w-4xl pt-10 mx-auto text-sm ttd">
                <table class="w-full text-sm border border-gray-300 rounded-md table-fixed">
                    <tr class="h-20">
                        <td class="w-1/3 p-2 font-semibold align-top border border-gray-300 rounded-md">Signed</td>
                        <td class="w-2/3 p-2 border border-gray-300 rounded-md">
                            <img src="{{ asset('storage/' . $spesifikasi->pic->signature) }}" alt="Signature"
                                class="h-10">
                        </td>
                    </tr>
                    <tr class="h-10">
                        <td class="p-2 font-semibold border border-gray-300 rounded-md">Name</td>
                        <td class="p-2 border border-gray-300 rounded-md">{{ $spesifikasi->pic->userName->name }}</td>
                    </tr>
                    <tr class="h-10">
                        <td class="p-2 font-semibold border border-gray-300 rounded-md">Date</td>
                        <td class="p-2 border border-gray-300 rounded-md">
                            {{ \Carbon\Carbon::parse($spesifikasi->pic->date)->translatedFormat('d F Y') }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-6 mb-3 text-center">
        <button onclick="exportPDF('{{ $spesifikasi->id }}')"
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
                filename: "spesifikasi-produk.pdf",
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
                // }).from(element).save();
            }).from(element).save().then(() => {
                window.location.href = `/sales/spesifikasi-produk/${id}/download-file`;
            });
        }
    }
</script>
