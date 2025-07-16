@extends ('pdf.layout.layout')
@section('title', 'Incoming Material Stainless Steel PDF')
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
                            Formulir Incoming Stainless Steel <br> Materials Quality Check
                        </td>
                        <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                            <table class="w-full text-sm dark:bg-gray-900 dark:text-white" style="border-collapse: collapse;">
                                <tr>
                                    <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                                    <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> : FO-QKS-QA-01-02
                                    </td>
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

                <div class="w-full max-w-4xl pt-4 mx-auto space-y-5 text-sm">
                    @php
    $fields = [
        ['label' => 'No PO :', 'value' => $incomingSS->no_po],
        ['label' => 'Supplier  :', 'value' => $incomingSS->supplier],
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

                @php
    $detail = json_decode($incomingSS->detail, true);
    $checklist = $detail['checklists'][0] ?? ['actual_result_1' => '', 'actual_result_2' => ''];
    $detailsTambahan = $detail['details_tambahan'] ?? [];
                @endphp

                <div class="max-w-4xl mx-auto mt-5 space-y-10 text-sm">
                    <div class="max-w-4xl pt-4 mx-auto space-y-5 text-sm">
                        <div class="overflow-hidden border rounded-lg shadow-sm">
                            <table class="w-full text-left border-collapse table-fixed">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="w-1/3 px-4 py-2 border">Procedures</th>
                                        <th class="w-1/4 px-4 py-2 border">Expected Result</th>
                                        <th class="w-2/5 px-4 py-2 border">Actual Result</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="px-4 py-2 align-top border">
                                            <ul class="pl-4 list-disc">
                                                <li>Wipe off the dust, dirt, oil and water on the surface of the material</li>
                                                <li>Make a mark on the upper, middle and bottom side of the material surface to be
                                                    checked</li>
                                                <li>Drop the testing liquid on the upper side of material and wait about 2–3 minutes
                                                </li>
                                            </ul>
                                        </td>
                                        <td class="px-4 py-2 text-sm align-top border">
                                            There was no color change within 3 minutes after the liquid dropped on the surface that
                                            indicating material is genuine SS304
                                        </td>
                                        <td class="px-4 py-2 align-top border">
                                            <textarea
                                                class="w-full h-24 px-2 py-1 border rounded-md resize-none focus:outline-none focus:ring focus:ring-gray-300">{{ $checklist['actual_result_1'] ?? '-' }}</textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-2 border">Visual Check</td>
                                        <td class="px-4 py-2 text-sm border">No Defect and rust found</td>
                                        <td class="px-4 py-2 border">
                                            <textarea
                                                class="w-full h-20 px-2 py-1 border rounded-md resize-none focus:outline-none focus:ring focus:ring-gray-300">{{ $checklist['actual_result_2'] ?? '-' }}</textarea>
                                        </td>
                                    </tr>
                                    @foreach ($detailsTambahan as $item)
                                        <tr>
                                            <td class="px-4 py-2 align-top border">{{ $item['procedures'] ?? '-' }}</td>
                                            <td class="px-4 py-2 align-top border">{{ $item['expected_result'] ?? '-' }}</td>
                                            <td class="px-4 py-2 align-top border">
                                                <textarea
                                                    class="w-full h-20 px-2 py-1 border rounded-md resize-none focus:outline-none focus:ring focus:ring-gray-300">{{ $item['actual_result_1'] ?? '-' }}</textarea>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="w-full overflow-hidden border rounded-lg shadow-sm">
                        <table class="w-full text-sm text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="w-1/2 px-4 py-2 border">Summary</th>
                                    <th class="w-1/2 px-4 py-2 border">Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="px-4 py-2 border">Total Received</td>
                                    <td class="px-4 py-2 border">{{ $incomingSS->summary['summary']['total_received'] ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 border">Total Acceptable</td>
                                    <td class="px-4 py-2 border">{{ $incomingSS->summary['summary']['total_acceptable'] ?? '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 border">Total Rejected</td>
                                    <td class="px-4 py-2 border">{{ $incomingSS->summary['summary']['total_rejected'] ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 border">Return to Supplier</td>
                                    <td class="px-4 py-2 border">{{ $incomingSS->summary['summary']['return_to_supplier'] ?? '-' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="w-full max-w-4xl mx-auto pt-10 mb-8">
                    <label for="note" class="block mb-1 text-sm font-medium text-gray-700">Remarks</label>
                    <div id="note" readonly
                        class="w-full px-3 py-2 overflow-hidden text-sm leading-relaxed border rounded-md resize-none border-black break-words">
                        {{ trim($incomingSS->remark) }}
                    </div>
                </div>

                @php
    $roles = [
        'Checked By' => [
            'name' => $incomingSS->pic->checked_name ?? '-',
            'signature' => $incomingSS->pic->checked_signature ?? null,
            'date' => $incomingSS->pic->checked_date ?? null,
        ],
        'Accepted By' => [
            'name' => $incomingSS->pic->accepted_name ?? '-',
            'signature' => $incomingSS->pic->accepted_signature ?? null,
            'date' => $incomingSS->pic->accepted_date ?? null,
        ],
        'Approved By' => [
            'name' => $incomingSS->pic->approved_name ?? '-',
            'signature' => $incomingSS->pic->approved_signature ?? null,
            'date' => $incomingSS->pic->approved_date ?? null,
        ],
    ];
                @endphp
                <div class="max-w-4xl mx-auto p-4 pt-20 mb-6 text-sm">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-center font-semibold">
                                <th class="border border-black w-32"></th>
                                @foreach ($roles as $role => $data)
                                    <th class="border border-black">{{ $role }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-black px-2 py-2 font-medium">Name</td>
                                @foreach ($roles as $data)
                                    <td class="border border-black px-2 py-2">{{ $data['name'] }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="border border-black px-2 py-2 font-medium">Signature</td>
                                @foreach ($roles as $data)
                                    <td class="border border-black px-2 py-3">
                                        <div class="flex items-center justify-center h-24">
                                            @if ($data['signature'])
                                                <img src="{{ asset('storage/' . $data['signature']) }}" alt="Signature"
                                                    class="object-contain h-full" />
                                            @else
                                                <span class="text-sm text-gray-400">No Signature</span>
                                            @endif
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="border border-black px-2 py-2 font-medium">Date</td>
                                @foreach ($roles as $data)
                                    <td class="border border-black px-2 py-2">
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
                filename: "incoming-material-stainless-steel.pdf",
                image: { type: "jpeg", quality: 1 },
                html2canvas: { scale: 3, useCORS: true, letterRendering: true },
                jsPDF: { unit: "in", format: "a4", orientation: "portrait" },
                pagebreak: { mode: ["avoid", "css"] }
            }).from(element).save();
        }
    }
</script>