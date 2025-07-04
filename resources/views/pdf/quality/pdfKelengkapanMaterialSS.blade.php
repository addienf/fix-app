@extends ('pdf.layout.layout')
@section('title', 'Kelengkapan Material Stainless Steel PDF')
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
                    Formulir Cutting & Bending <br> For Production Checklist
                </td>
                <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                    <table class="w-full text-sm dark:bg-gray-900 dark:text-white" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> :
                                FO-QKS-QA-01-03</td>
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
        <div class="grid w-full max-w-4xl grid-cols-1 pt-4 mx-auto gap-y-4">
            @php
                $fields1 = [['label' => 'No SPK Produksi :', 'value' => $kelengkapan->spk->no_spk]];
            @endphp

            @foreach ($fields1 as $field)
                <div class="flex items-center gap-2 mb-2">
                    <label class="w-48 font-medium">{{ $field['label'] }}</label>
                    <input type="text" readonly value="{{ $field['value'] }}"
                        class="w-full px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                </div>
            @endforeach

            <h2 class="col-span-1 text-xl font-bold text-start">
                Chamber Identification
            </h2>

            @php
                $fields2 = [
                    ['label' => 'Type Model :', 'value' => $kelengkapan->tipe],
                    ['label' => 'Ref Document :', 'value' => $kelengkapan->ref_document],
                ];
            @endphp

            @foreach ($fields2 as $field)
                <div class="flex items-center gap-2">
                    <label class="w-48 font-medium">{{ $field['label'] }}</label>
                    <input type="text" readonly value="{{ $field['value'] }}"
                        class="w-full px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                </div>
            @endforeach

            @php
                $rawDetails = $kelengkapan->detail->details ?? [];

                $details = is_string($rawDetails) ? json_decode($rawDetails, true) : $rawDetails;

                $orderNumber = $kelengkapan->spk->no_order ?? '-';

                $fields = collect($details)
                    ->map(function ($item) use ($orderNumber) {
                        return [
                            'item' => $item['part'] ?? '',
                            'spec' => $orderNumber,
                            'result' => ucfirst($item['result'] ?? ''),
                            'remark' => ucfirst($item['select'] ?? ''),
                        ];
                    })
                    ->toArray();

                $remarkLabels = [
                    'ok' => 'OK',
                    'h' => 'Hold',
                    'r' => 'Repaired',
                ];
            @endphp

            <table class="w-full text-sm border border-collapse border-black">
                <thead class="bg-gray-100">
                            <tr>
                                <th class="w-10 px-3 py-2 text-center border border-black" rowspan="2">No</th>
                                <th class="px-3 py-2 text-left border border-black" rowspan="2">Part</th>
                                <th class="px-3 py-2 text-left border border-black" rowspan="2">Order Number</th>
                                <th class="px-3 py-2 text-center border border-black" colspan="2">Result</th>
                                <th class="px-3 py-2 text-left border border-black" rowspan="2">Remark</th>
                            </tr>
                            <tr>
                                <th class="px-3 py-2 text-center border border-black">Pass</th>
                                <th class="px-3 py-2 text-center border border-black">Fail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($fields as $index => $field)
                                <tr>
                                    <td class="px-3 py-2 text-center border border-black">{{ $index + 1 }}</td>
                                    <td class="px-3 py-2 border border-black">{{ $field['item'] }}</td>
                                    <td class="px-3 py-2 border border-black">{{ $field['spec'] }}</td>
                                    <td class="px-3 py-2 text-center border border-black">
                                        {{ $field['result'] === '1' ? '✔' : '' }}
                                    </td>
                                    <td class="px-3 py-2 text-center border border-black">
                                        {{ $field['result'] === '0' ? '✘' : '' }}
                                    </td>
                                    <td class="px-3 py-2 border border-black">
                                        {{ $remarkLabels[strtolower($field['remark'])] ?? $field['remark'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        <label class="block mb-1 font-semibold">Note</label>
                        <textarea class="w-full p-2 border border-black rounded cursor-not-allowed resize-none" readonly>{{ $kelengkapan->note }}</textarea>
                    </div>
                </div>

                @php
    $roles = [
        'Checked By' => [
            'name' => $kelengkapan->pic->inspected_name ?? '-',
            'signature' => $kelengkapan->pic->inspected_signature ?? null,
            'date' => $kelengkapan->pic->inspected_date ?? null,
        ],
        'Accepted By' => [
            'name' => $kelengkapan->pic->accepted_name ?? '-',
            'signature' => $kelengkapan->pic->accepted_signature ?? null,
            'date' => $kelengkapan->pic->accepted_date ?? null,
        ],
        'Approved By' => [
            'name' => $kelengkapan->pic->approved_name ?? '-',
            'signature' => $kelengkapan->pic->approved_signature ?? null,
            'date' => $kelengkapan->pic->approved_date ?? null,
        ],
    ];
                @endphp

                <!-- SIGNATURE SECTION -->
                <div class="max-w-4xl p-4 mx-auto mb-6">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        @foreach ($roles as $role => $data)
                            <div>
                                <label class="block mb-1 font-semibold">{{ $role }}</label>
                                <input type="text" value="{{ $data['name'] }}" readonly
                                    class="w-full p-2 mb-2 text-black " />

                                <label class="block mb-1 font-semibold">Signature</label>
                                <div class="flex items-center justify-center w-full h-24 mb-2 bg-white">
                                    @if ($data['signature'])
                                        <img src="{{ asset('storage/' . $data['signature']) }}" alt="Signature"
                                            class="object-contain h-full" />
                                    @else
                                        <span class="text-sm text-gray-400">No Signature</span>
                                    @endif
                                </div>

                                <label class="block mb-1 font-semibold">Date</label>
                                <input type="text" readonly
                                    value="{{ $data['date'] ? \Carbon\Carbon::parse($data['date'])->format('d M Y') : '-' }}"
                                    class="w-full p-2 text-black " />
                            </div>
                        @endforeach
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
                filename: "kelengkapan-material-stainless-steel.pdf",
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
