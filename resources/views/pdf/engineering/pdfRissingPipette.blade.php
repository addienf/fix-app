@extends('pdf.layout.layout')
@section('title', 'Regular Maintenance Checklist - Qlab Rinsing Pipette PDF')
@section('content')
    <div id="export-area" class="p-2 text-black bg-white">


        <table class="w-full max-w-4xl mx-auto text-sm border border-black" style="border-collapse: collapse;">
            <tr>

                <td rowspan="2" class="w-32 h-24 text-center align-middle border border-black">
                    <img src="{{ asset('asset/logo.png') }}" alt="Logo" class="object-contain h-16 mx-auto" />
                </td>


                <td class="px-2 py-1 align-top border border-black">
                    <strong>Project :</strong>
                </td>


                <td rowspan="2" class="w-48 p-2 pt-2 text-sm leading-tight text-center align-top border border-black">
                    FO-QKS-ENG-01-08<br>
                    Rev. 00<br>
                    02 June 2025
                </td>
            </tr>
            <tr>

                <td class="px-2 py-1 align-top border border-black">
                    Client
                </td>
            </tr>
        </table>
        <div class="w-full max-w-4xl pt-4 mx-auto text-sm">
            <h2 class="mb-4 text-xl font-bold text-center">
                REGULAR MAINTENANCE CHECK LIST - QLAB RINSING PIPETTE
            </h2>
            <div class="flex items-center justify-center w-full gap-2 my-6">
                <span class="font-semibold text-center">Name Tag/No: {{ $rissing->tag_no }}</span>
                {{-- <input type="text" disabled class="px-2 py-1 text-sm bg-transparent border border-gray-300 rounded"
                    value="PB-2025/00123" /> --}}
            </div>

            <table class="w-full max-w-4xl pt-4 mx-auto text-xs border border-black table-auto">
                <thead>
                    <tr class="font-bold text-center">
                        <th class="w-8 border border-black">NO</th>
                        <th class="w-48 border border-black">ITEM TO CHECK</th>
                        <th class="w-40 border border-black">BEFORE<br>MAINTENANCE</th>
                        <th class="w-40 border border-black">AFTER<br>MAINTENANCE</th>
                        <th class="w-16 border border-black" colspan="3">ACCEPTED</th>
                        <th class="border border-black w-36">REMARK</th>
                    </tr>
                    <tr class="font-bold text-center">
                        <th colspan="4" class="invisible"></th>
                        <th class="w-10 border border-black">YES</th>
                        <th class="w-10 border border-black">NO</th>
                        <th class="w-10 border border-black">NA</th>
                        <th class="invisible"></th>
                    </tr>
                </thead>
                <tbody>
                    {{-- @php $rowNumber = 1; @endphp --}}
                    @foreach ($rissing->detail->checklist ?? [] as $index => $part)
                        <tr>
                            <td class="px-3 py-2 text-center border border-black">{{ $index + 1 }}</td>
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
                            <td class="px-3 py-2 border border-black">{{ $part['remark'] ?? '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="w-full max-w-4xl pt-4 mx-auto mb-3">
                <label for="note" class="block mb-1 text-sm font-medium text-black">Remark:</label>
                <div id="note" readonly
                    class="w-full px-3 py-2 overflow-hidden text-sm leading-relaxed border border-black rounded-md resize-none">
                    {{ trim($rissing->remarks) }}</div>
            </div>

            {{-- TTD Section --}}
            <table class="w-full text-sm border border-black">
                <thead>
                    <tr class="font-semibold text-center bg-gray-200">
                        <th class="w-32 border border-black"></th>
                        <th class="py-2 border border-black">Checked by</th>
                        <th class="py-2 border border-black">Approved by</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="px-2 py-2 font-medium border border-black">Name</td>
                        <td class="px-2 py-2 border border-black">
                            {{ $rissing->pic->checked_name }}
                        </td>
                        <td class="px-2 py-2 border border-black">
                            {{ $rissing->pic->approved_name }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-2 py-2 font-medium border border-black">Signature</td>
                        <td class="px-2 py-4 border border-black">
                            <img src="{{ asset('storage/' . $rissing->pic->checked_signature) }}" alt="Signature"
                                class="object-contain h-full" />
                        </td>
                        <td class="px-2 py-4 border border-black">
                            <img src="{{ asset('storage/' . $rissing->pic->approved_signature) }}" alt="Signature"
                                class="object-contain h-full" />
                        </td>
                    </tr>
                    <tr>
                        <td class="px-2 py-2 font-medium border border-black">Date</td>
                        <td class="px-2 py-2 border border-black">
                            {{ $rissing->pic->checked_date }}
                        </td>
                        <td class="px-2 py-2 border border-black">
                            {{ $rissing->pic->approved_date }}
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
                filename: "rissing-pipette-maintenance.pdf",
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
