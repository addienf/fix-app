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
                                    <td class="px-3 py-2 font-semibold border-b border-black"> : 02 September 2025
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

                <!-- FORM -->
                @php
    $fields = [
        // ['label' => 'No', 'value' => ],
        ['label' => 'Nama', 'value' => $spesifikasi->urs->customer->name],
        ['label' => 'Department', 'value' => $spesifikasi->urs->customer->department],
        ['label' => 'Phone Number', 'value' => $spesifikasi->urs->customer->phone_number],
        ['label' => 'Company Name', 'value' => $spesifikasi->urs->customer->company_name],
        ['label' => 'Company Address', 'value' => $spesifikasi->urs->customer->company_address],
    ];
                @endphp

                <div class="flex flex-col max-w-4xl gap-2 pt-6 mx-auto text-sm">
                    <div><span>No. {{ $spesifikasi->urs->no_urs }}</span></div>
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
    $chunks = $spesifikasi->details->chunk(2);
                @endphp

                <div class="max-w-4xl pt-4 mx-auto space-y-8 text-sm">
                    @foreach ($chunks as $chunk)
                        @foreach ($chunk as $detail)
                                    <div class="p-4 space-y-5 bg-white border border-gray-400 rounded-md shadow-sm">

                                        {{-- NAMA ITEM --}}
                                        <div class="grid items-center grid-cols-4 gap-2">
                                            <label class="col-span-1 font-medium">Nama Item :</label>
                                            <input type="text" disabled
                                                class="col-span-3 px-2 py-1 text-black bg-white border border-gray-300 rounded cursor-not-allowed"
                                                value="{{ $detail->product->name }}" />
                                        </div>

                                        {{-- QUANTITY --}}
                                        <div class="grid items-center grid-cols-4 gap-2">
                                            <label class="col-span-1 font-medium">Quantity :</label>
                                            <input type="text" disabled
                                                class="col-span-3 px-2 py-1 text-black bg-white border border-gray-300 rounded cursor-not-allowed"
                                                value="{{ $detail->quantity }}" />
                                        </div>

                                        {{-- KHUSUS PRODUK QLAB --}}
                                        @if ($detail->product->category?->id === 1)
                                            {{-- Spesifikasi utama Qlab --}}
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
                                            <div class="flex flex-col pt-2">
                                                <label class="mb-1 font-medium">Detail Specification :</label>
                                                <input type="text" disabled
                                                    class="w-full px-2 py-1 text-black bg-white border border-gray-300 rounded cursor-not-allowed"
                                                    value="{{ $spesifikasi->detail_specification ?? '-' }}" />
                                            </div>
                                        @else
                                            {{-- Untuk produk lain (misalnya Mecmesin, Ohaus, dll) --}}
                                            @foreach ($detail->specification_mecmesin ?? [] as $spec)
                                                <div class="grid grid-cols-2 gap-2">
                                                    <div class="flex items-center">
                                                        <label class="font-medium w-32">Sample:</label>
                                                        <div class="p-1 border rounded flex-1">{{ $spec['sample'] ?? '-' }}</div>
                                                    </div>

                                                    <div class="flex items-center">
                                                        <label class="font-medium w-32">Capacity:</label>
                                                        <div class="p-1 border rounded flex-1">{{ $spec['capacity'] ?? '-' }}</div>
                                                    </div>

                                                    <div class="flex items-center">
                                                        <label class="font-medium w-32">Jenis Tes:</label>
                                                        <div class="p-1 border rounded flex-1">{{ ucfirst($spec['jenis_tes'] ?? '-') }}</div>
                                                    </div>

                                                    <div class="flex items-center">
                                                        <label class="font-medium w-32">Test Type:</label>
                                                        <div class="p-1 border rounded flex-1">{{ ucfirst($spec['test_type'] ?? '-') }}</div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif

                                    </div>
                        @endforeach
                    @endforeach
                </div>

                @php
    $status = ucfirst($spesifikasi->status_penerimaan_order);
    $fields = [
        [
            'label' => 'Estimated Delivery Date',
            'value' => \Carbon\Carbon::parse($spesifikasi->estimasi_pengiriman)->translatedFormat('d F Y'),
        ],
        ['label' => 'Receive Order', 'value' => $status],
    ];
                @endphp

                <div class="flex flex-col max-w-4xl gap-2 pt-3 mx-auto text-sm">
                    @foreach ($fields as $field)
                        <div class="flex flex-col">
                            <label class="mb-1 font-medium">{{ $field['label'] }} :</label>
                            <input type="text" disabled
                                class="w-full px-2 py-1 text-black bg-white border border-gray-300 rounded-md"
                                value="{{ $field['value'] }}" />
                        </div>
                    @endforeach

                    @if (strtolower($spesifikasi->status_penerimaan_order) === 'no')
                        <div class="flex flex-col">
                            <label class="mb-1 font-medium">Reason :</label>
                            <textarea readonly rows="3"
                                class="w-full px-3 py-2 text-sm text-black bg-white border border-gray-300 rounded-md resize-none">{{ $spesifikasi->alasan ?? '-' }}</textarea>
                        </div>
                    @endif
                </div>

                @php
    $roles = [
        'Signed by Sales Dept' => [
            'name' => $spesifikasi->pic->signedName->name ?? '-',
            'signature' => $spesifikasi->pic->signed_signature ?? null,
            'date' => $spesifikasi->pic->signed_date ?? null,
        ],
        'Accepted by Production Dept' => [
            'name' => $spesifikasi->pic->acceptedName->name ?? '-',
            'signature' => $spesifikasi->pic->accepted_signature ?? null,
            'date' => $spesifikasi->pic->accepted_date ?? null,
        ],
        'Acknowledge by MR' => [
            'name' => $spesifikasi->pic->acknowledgeName->name ?? '-',
            'signature' => $spesifikasi->pic->acknowledge_signature ?? null,
            'date' => $spesifikasi->pic->acknowledge_date ?? null,
        ],
    ];
                @endphp

                <div class="max-w-4xl pt-4 mx-auto text-sm ttd">
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
