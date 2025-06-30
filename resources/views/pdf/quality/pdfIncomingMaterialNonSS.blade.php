@extends ('pdf.layout.layout')
@section('title', 'Incoming Material Non Stainless Steel PDF')
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
                    Formulir Incoming Material <br> Non Stainless Steel
                </td>
                <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                    <table class="w-full text-sm dark:bg-gray-900 dark:text-white" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> :
                                FO-QKS-QA-01-01</td>
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
        <div class="w-full max-w-4xl pt-4 mx-auto space-y-4 text-sm">

            @php
                $fields = [
                    ['label' => 'No PO :', 'value' => $incomingNonSS->no_po],
                    ['label' => 'Supplier  :', 'value' => $incomingNonSS->supplier],
                ];
            @endphp

            @foreach ($fields as $field)
                <div class="flex items-center">
                    <label class="w-64 font-medium">{{ $field['label'] }}</label>
                    <input type="text" readonly value="{{ $field['value'] }}"
                        class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                </div>
            @endforeach

            @php
                $fixedDescriptions = [
                    'The correct items were accepted',
                    'Items are not crushed or broken',
                    'Specifications are as required',
                    'Function check are as required',
                ];

                $fixedData = collect($incomingNonSS->detail->details ?? []);
                $additionalData = $incomingNonSS->detail->details_tambahan ?? [];
            @endphp

            <table class="w-full mb-8 text-sm border border-collapse border-black">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="w-10 px-3 py-2 text-center border border-black">No</th>
                        <th class="px-3 py-2 text-left border border-black">Description</th>
                        <th colspan="2" class="px-3 py-2 text-center border border-black">Condition</th>
                        <th class="px-3 py-2 text-left border border-black">Remark</th>
                    </tr>
                    <tr>
                        <th colspan="2" class="px-3 py-2 border border-black"></th>
                        <th class="px-3 py-2 text-center border border-black">Pass</th>
                        <th class="px-3 py-2 text-center border border-black">Fail</th>
                        <th class="px-3 py-2 border border-black"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($fixedDescriptions as $i => $desc)
                        @php
                            $row = $fixedData->firstWhere('part', $desc);
                            $result = $row['result'] ?? null;
                            $remark = $row['remark'] ?? '';
                        @endphp
                        <tr>
                            <td class="px-3 py-2 text-center border border-black">{{ $i + 1 }}</td>
                            <td class="px-3 py-2 border border-black">{{ $desc }}</td>
                            <td class="px-3 py-2 text-center border border-black">{{ $result === '1' ? '✔' : '' }}</td>
                            <td class="px-3 py-2 text-center border border-black">{{ $result === '0' ? '✘' : '' }}</td>
                            <td class="px-3 py-2 border border-black">{{ $remark }}</td>
                        </tr>
                    @endforeach

                    {{-- Data Tambahan --}}
                    @foreach ($additionalData as $j => $extra)
                        <tr>
                            <td class="px-3 py-2 text-center border border-black">
                                {{ count($fixedDescriptions) + $j + 1 }}</td>
                            <td class="px-3 py-2 border border-black">{{ $extra['part'] }}</td>
                            <td class="px-3 py-2 text-center border border-black">
                                {{ $extra['result'] == '1' ? '✔' : '' }}</td>
                            <td class="px-3 py-2 text-center border border-black">
                                {{ $extra['result'] == '0' ? '✘' : '' }}</td>
                            <td class="px-3 py-2 border border-black">{{ $extra['remark'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <table class="w-full text-sm border border-collapse border-black">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-3 py-2 text-left border border-black">Summary</th>
                        <th class="px-3 py-2 text-center border border-black">Critical</th>
                        <th class="px-3 py-2 text-center border border-black">Major</th>
                        <th class="px-3 py-2 text-center border border-black">Minor</th>
                    </tr>
                </thead>
                @foreach ($incomingNonSS->summary['summary'] as $item)
                    <tbody>
                        <tr>
                            <td class="px-3 py-2 border border-black">Total Received Quantity</td>
                            <td class="px-3 py-2 text-center border border-black">{{ $item['critical_1'] ?? '' }}</td>
                            <td class="px-3 py-2 text-center border border-black">{{ $item['major_1'] ?? '' }}</td>
                            <td class="px-3 py-2 text-center border border-black">{{ $item['minor_1'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 border border-black">Return Quantity to Supplier</td>
                            <td class="px-3 py-2 text-center border border-black">{{ $item['critical_2'] ?? '' }}</td>
                            <td class="px-3 py-2 text-center border border-black">{{ $item['major_2'] ?? '' }}</td>
                            <td class="px-3 py-2 text-center border border-black">{{ $item['minor_2'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 border border-black">Total Rejected Quantity</td>
                            <td class="px-3 py-2 text-center border border-black">{{ $item['critical_3'] ?? '' }}</td>
                            <td class="px-3 py-2 text-center border border-black">{{ $item['major_3'] ?? '' }}</td>
                            <td class="px-3 py-2 text-center border border-black">{{ $item['minor_3'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 border border-black">Total Acceptable Quantity</td>
                            <td colspan="3" class="px-3 py-2 text-center border border-black">
                                {{ $item['total_acceptable_quantity'] ?? '' }}
                            </td>
                        </tr>
                    </tbody>
                @endforeach
            </table>

            <div class="w-full max-w-4xl mx-auto mb-6">
                <label for="note" class="block mb-1 text-sm font-medium text-gray-700">Batch No</label>
                <div id="note" readonly
                    class="w-full min-h-[75px] px-3 py-2 text-sm leading-relaxed text-left border rounded-md text-black border-black">
                    {{ trim($incomingNonSS->batch_no) }}
                </div>
            </div>

            @php
                $roles = [
                    'Checked By' => [
                        'name' => $incomingNonSS->pic->checked_name ?? '-',
                        'signature' => $incomingNonSS->pic->checked_signature ?? null,
                        'date' => $incomingNonSS->pic->checked_date ?? null,
                    ],
                    'Accepted By' => [
                        'name' => $incomingNonSS->pic->accepted_name ?? '-',
                        'signature' => $incomingNonSS->pic->accepted_signature ?? null,
                        'date' => $incomingNonSS->pic->accepted_date ?? null,
                    ],
                    'Approved By' => [
                        'name' => $incomingNonSS->pic->approved_name ?? '-',
                        'signature' => $incomingNonSS->pic->approved_signature ?? null,
                        'date' => $incomingNonSS->pic->approved_date ?? null,
                    ],
                ];
            @endphp

            <div class="max-w-4xl p-4 mx-auto mb-6">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    @foreach ($roles as $role => $data)
                        <div>
                            <label class="block mb-1 font-semibold">{{ $role }}</label>
                            <input type="text" value="{{ $data['name'] }}" readonly
                                class="w-full p-2 mb-2 text-gray-500 bg-gray-100 border border-gray-300 rounded" />

                            <label class="block mb-1">Signature</label>
                            <div
                                class="flex items-center justify-center w-full h-24 mb-2 bg-white border rounded border-gray">
                                @if ($data['signature'])
                                    <img src="{{ asset('storage/' . $data['signature']) }}" alt="Signature"
                                        class="object-contain h-full" />
                                @else
                                    <span class="text-sm text-gray-400">No Signature</span>
                                @endif
                            </div>

                            <label class="block mb-1">Date</label>
                            <input type="text" readonly
                                value="{{ $data['date'] ? \Carbon\Carbon::parse($data['date'])->format('d M Y') : '-' }}"
                                class="w-full p-2 text-gray-500 bg-gray-100 border border-gray-300 rounded" />
                        </div>
                    @endforeach
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
                filename: "incoming-material-non-stainless-steel.pdf",
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
