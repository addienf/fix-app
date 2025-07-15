@extends('pdf.layout.layout')
@section('title', 'Regular Maintenance Checklist - Qlab Walk-in Test Chamber G2 PDF')
@section('content')
    <div id="export-area" class="p-2 bg-white text-black">


        <table class="w-full max-w-4xl mx-auto text-sm border border-black" style="border-collapse: collapse;">
            <tr>

                <td rowspan="2" class="w-32 h-24 text-center border border-black align-middle">
                    <img src="{{ asset('asset/logo.png') }}" alt="Logo" class="object-contain h-16 mx-auto" />
                </td>


                <td class="border border-black px-2 py-1 align-top">
                    <strong>Project :</strong>
                </td>


                <td rowspan="2" class="w-48 border border-black align-top text-center text-sm leading-tight pt-2 p-2">
                    FO-QKS-ENG-01-11<br>
                    Rev. 00<br>
                    02 June 2025
                </td>
            </tr>
            <tr>

                <td class="border border-black px-2 py-1 align-top">
                    Client
                </td>
            </tr>
        </table>
        <div class="w-full max-w-4xl pt-4 mx-auto text-sm">
            <h2 class="text-xl font-bold text-center mb-4">
                REGULAR MAINTENANCE CHECK LIST - QLAB WALK-IN TEST CHAMBER G2
            </h2>
            <div class="flex justify-center items-center gap-2 w-full my-6">
                <span class="text-center font-semibold">WTC Name Tag/No: {{ $walkinG2->tag_no }}</span>
                {{-- <input type="text" disabled class="px-2 py-1 text-sm bg-transparent border border-gray-300 rounded"
                    value="" /> --}}
            </div>

            @php
                $rawDetails = $walkinG2->detail->checklist ?? [];
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

            <table class="w-full max-w-4xl pt-4 mx-auto table-auto border border-black text-xs">
                <thead>
                    <tr class="bg-gray-300 text-center font-bold">
                        <th class="border border-black w-8" rowspan="2">NO</th>
                        <th class="border border-black w-48" rowspan="2">ITEM TO CHECK</th>
                        <th class="border border-black w-40" rowspan="2">BEFORE<br>MAINTENANCE</th>
                        <th class="border border-black w-40" rowspan="2">AFTER<br>MAINTENANCE</th>
                        <th class="border border-black w-48" colspan="3">ACCEPTED</th>
                        <th class="border border-black w-36" rowspan="2">REMARK</th>
                    </tr>
                    <tr class="bg-gray-300 text-center font-bold">
                        <th class="border border-black w-16">YES</th>
                        <th class="border border-black w-16">NO</th>
                        <th class="border border-black w-16">NA</th>
                    </tr>
                </thead>
                <tbody>
                    @php $rowNumber = 1; @endphp
                    @foreach ($details as $group)
                        <tr>
                            <td colspan="8" class="px-3 py-2 font-semibold bg-gray-200 border border-black">
                                {{ $group['mainPart'] ?? '-' }}</td>
                        </tr>
                        @foreach ($group['parts'] as $part)
                            <tr>
                                <td class="px-3 py-2 text-center border border-black">{{ $rowNumber++ }}</td>
                                <td class="px-3 py-2 border border-black">{{ $part['part'] ?? '-' }}</td>
                                <td class="px-3 py-2 text-center border border-black">{{ $part['before'] ?? '-' }}</td>
                                <td class="px-3 py-2 text-center border border-black">{{ $part['after'] ?? '-' }}</td>
                                <td class="px-3 py-2 text-center border border-black">
                                    {{ ($part['accepted'] ?? '') === 'yes' ? '✔' : '' }}
                                </td>
                                <td class="px-3 py-2 text-center border border-black">
                                    {{ ($part['accepted'] ?? '') === 'no' ? '✔' : '' }}
                                </td>
                                <td class="px-3 py-2 text-center border border-black">
                                    {{ ($part['accepted'] ?? '') === 'na' ? '✔' : '' }}
                                </td>
                                <td class="px-3 py-2 border border-black">{{ statusLabel($part['remark'] ?? '-') }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>

            <div class="w-full max-w-4xl mx-auto pt-4 mb-3">
                <label for="note" class="block mb-1 text-sm font-medium text-black">Remark:</label>
                <div id="note" readonly
                    class="w-full px-3 py-2 overflow-hidden text-sm leading-relaxed border border-black rounded-md resize-none">
                    {{ trim($walkinG2->remarks) }}</div>
            </div>

            {{-- TTD Section --}}
            <table class="w-full border border-black text-sm">
                <thead>
                    <tr class="bg-gray-200 text-center font-semibold">
                        <th class="border border-black w-32"></th>
                        <th class="border border-black py-2">Checked by</th>
                        <th class="border border-black py-2">Approved by</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-black font-medium px-2 py-2">Name</td>
                        <td class="border border-black px-2 py-2">
                            {{ $walkinG2->pic->checked_name }}
                        </td>
                        <td class="border border-black px-2 py-2">
                            {{ $walkinG2->pic->approved_name }}
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-black font-medium px-2 py-2">Signature</td>
                        <td class="border border-black px-2 py-4">
                            <img src="{{ asset('storage/' . $walkinG2->pic->checked_signature) }}" alt="Signature"
                                class="object-contain h-full" />
                        </td>
                        <td class="border border-black px-2 py-4">
                            <img src="{{ asset('storage/' . $walkinG2->pic->approved_signature) }}" alt="Signature"
                                class="object-contain h-full" />
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-black font-medium px-2 py-2">Date</td>
                        <td class="border border-black px-2 py-2">
                            {{ $walkinG2->pic->checked_date }}
                        </td>
                        <td class="border border-black px-2 py-2">
                            {{ $walkinG2->pic->approved_date }}
                        </td>
                        </td>
                    </tr>
                </tbody>
            </table>
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
                filename: "walkin-chamber-g2-maintenance.pdf",
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
