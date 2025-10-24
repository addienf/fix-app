@extends('pdf.layout.layout')
@section('title', 'Pengecekan Material Electrical PDF')
@section('content')
    <div id="export-area" class="p-2 text-black bg-white">
        {{-- HEADER TABLE --}}
        <table class="w-full max-w-4xl mx-auto text-sm border border-black" style="border-collapse: collapse;">
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
                    Formulir Electrical Production <br> Progress Checklist
                </td>
                <td rowspan="2" class="p-0 align-top border border-black">
                    <table class="w-full text-sm" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-2 border-b border-black">No. Dokumen</td>
                            <td class="px-3 py-2 font-semibold border-b border-black"> : FO-QKS-QA-01-06</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 border-b border-black">Tanggal Rilis</td>
                            <td class="px-3 py-2 font-semibold border-b border-black"> : 22 Mei 2025</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2">Revisi</td>
                            <td class="px-3 py-2 font-semibold"> : 01</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        {{-- SPK PRODUKSI --}}
        {{-- <div class="grid w-full max-w-4xl grid-cols-1 pt-4 mx-auto mb-2 text-sm gap-y-4">
            @php
                $fields = [['label' => 'No SPK Produksi :', 'value' => $electrical->spk->no_spk]];
            @endphp
            @foreach ($fields as $field)
                <div class="flex items-center">
                    <label class="w-40 font-medium">{{ $field['label'] }}</label>
                    <input type="text" readonly value="{{ $field['value'] }}"
                        class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                </div>
            @endforeach
        </div> --}}

        {{-- CHAMBER IDENTIFICATION --}}
        <h2 class="max-w-4xl pt-4 mx-auto text-xl font-bold text-start">Product Identification</h2>
        <div class="grid w-full max-w-4xl grid-cols-1 pt-2 mx-auto mb-4 text-sm gap-y-4">
            @php
                $fields = [
                    ['label' => 'Type/Model :', 'value' => $electrical->tipe],
                    ['label' => 'Volume :', 'value' => $electrical->volume],
                ];
            @endphp

            @foreach ($fields as $field)
                <div class="flex items-center">
                    <label class="w-40 font-medium">{{ $field['label'] }}</label>
                    <input type="text" readonly value="{{ $field['value'] }}"
                        class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                </div>
            @endforeach
        </div>


        {{-- DETAIL TABLE --}}
        @php
            $rawDetails = $electrical->detail->details ?? [];
            $details = is_string($rawDetails) ? json_decode($rawDetails, true) : $rawDetails;
            function statusLabel($code)
            {
                return match (strtolower($code)) {
                    'ok' => 'OK',
                    'h' => 'Hold',
                    'r' => 'Repaired',
                    default => ucfirst($code ?? '-'),
                };
            }
        @endphp

        <table class="w-full max-w-4xl mx-auto mb-3 text-sm border border-black">
            <thead class="bg-gray-100">
                <tr>
                    <th rowspan="2" class="w-10 px-3 py-2 text-center border border-black">No</th>
                    <th rowspan="2" class="px-3 py-2 text-left border border-black">Part</th>
                    <th colspan="2" class="px-3 py-2 text-center border border-black">Result</th>
                    <th rowspan="2" class="px-3 py-2 text-left border border-black">Status</th>
                </tr>
                <tr>
                    <th class="px-3 py-2 text-center border border-black">Pass</th>
                    <th class="px-3 py-2 text-center border border-black">Fail</th>
                </tr>
            </thead>
            <tbody>
                @php $rowNumber = 1; @endphp
                @foreach ($details as $group)
                    <tr>
                        <td class="px-3 py-2 font-semibold text-center bg-gray-200 border border-black">{{ $rowNumber++ }}
                        </td>
                        <td class="px-3 py-2 font-semibold bg-gray-200 border border-black">
                            {{ $group['mainPart'] ?? '-' }}
                        </td>
                        <td class="px-3 py-2 font-semibold text-center bg-gray-200 border border-black">
                            {{ ($group['mainPart_result'] ?? '0') == '1' ? '✔' : '' }}
                        </td>
                        <td class="px-3 py-2 font-semibold text-center bg-gray-200 border border-black">
                            {{ ($group['mainPart_result'] ?? '0') == '0' ? '✘' : '' }}
                        </td>
                        <td class="px-3 py-2 font-semibold bg-gray-200 border border-black">
                            {{ statusLabel($group['mainPart_status'] ?? '-') }}
                        </td>
                    </tr>
                    @foreach ($group['parts'] as $part)
                        <tr>
                            <td class="px-3 py-2 text-center border border-black">{{ $rowNumber++ }}</td>
                            <td class="px-3 py-2 border border-black">{{ $part['part'] ?? '-' }}</td>
                            <td class="px-3 py-2 text-center border border-black">
                                {{ ($part['result'] ?? '0') == '1' ? '✔' : '' }}</td>
                            <td class="px-3 py-2 text-center border border-black">
                                {{ ($part['result'] ?? '0') == '0' ? '✘' : '' }}</td>
                            <td class="px-3 py-2 border border-black">{{ statusLabel($part['status'] ?? '-') }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>

        {{-- NOTE --}}
        <div class="w-full max-w-4xl mx-auto mb-6">
            <label for="note" class="block mb-1 text-sm font-medium text-black">Note:</label>
            <div id="note" readonly
                class="w-full px-3 py-2 overflow-hidden text-sm leading-relaxed border border-black rounded-md resize-none">
                {{ trim($electrical->note) }}</div>
        </div>

        {{-- SIGNATURES --}}
        @php
            $roles = [
                'Checked By' => [
                    'name' => $electrical->pic->inspectedName->name ?? '-',
                    'signature' => $electrical->pic->inspected_signature ?? null,
                    'date' => $electrical->pic->inspected_date ?? null,
                ],
                'Accepted By' => [
                    'name' => $electrical->pic->acceptedName->name ?? '-',
                    'signature' => $electrical->pic->accepted_signature ?? null,
                    'date' => $electrical->pic->accepted_date ?? null,
                ],
                'Approved By' => [
                    'name' => $electrical->pic->approvedName->name ?? '-',
                    'signature' => $electrical->pic->approved_signature ?? null,
                    'date' => $electrical->pic->approved_date ?? null,
                ],
            ];
        @endphp

        <table class="w-full max-w-4xl mx-auto text-sm border border-black table-fixed">
            <thead>
                <tr class="font-semibold text-center bg-gray-100">
                    <th class="w-1/4 border border-black"></th>
                    @foreach ($roles as $role => $data)
                        <th class="w-1/4 border border-black">{{ $role }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-2 py-2 font-medium border border-black">Name</td>
                    @foreach ($roles as $data)
                        <td class="px-2 py-2 border border-black">
                            <input type="text" readonly value="{{ $data['name'] }}" class="w-full p-1 text-black" />
                        </td>
                    @endforeach
                </tr>
                <tr>
                    <td class="px-2 py-2 font-medium border border-black">Signature</td>
                    @foreach ($roles as $data)
                        <td class="px-2 py-2 border border-black">
                            <div class="flex items-center justify-center h-24 bg-white">
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
                    <td class="px-2 py-2 font-medium border border-black">Date</td>
                    @foreach ($roles as $data)
                        <td class="px-2 py-2 border border-black">
                            <input type="text" readonly
                                value="{{ $data['date'] ? \Carbon\Carbon::parse($data['date'])->format('d M Y') : '-' }}"
                                class="w-full p-1 text-black" />
                        </td>
                    @endforeach
                </tr>
            </tbody>
        </table>


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
                filename: "pengecekan-material-electrical.pdf",
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
