@extends ('pdf.layout.layout')
@section('title', 'Pengecekan Performa PDF')
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
                    Formulir Performance <br> Qualification
                </td>
                <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                    <table class="w-full text-sm dark:bg-gray-900 dark:text-white" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> :
                                FO-QKS-QA-01-07</td>
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
                $fields = [['label' => 'No SPK Produksi :', 'value' => $performa->spk->no_spk]];
            @endphp

            @foreach ($fields as $field)
                <div class="flex items-center">
                    <label class="w-64 font-medium">{{ $field['label'] }}</label>
                    <input type="text" readonly value="{{ $field['value'] }}"
                        class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                </div>
            @endforeach
        </div>

        <div class="w-full max-w-4xl pt-2 mx-auto space-y-2 text-sm">
            <h2 class="text-xl font-bold">Chamber Identification</h2>
            @php
                $fields = [
                    ['label' => 'Type/Model :', 'value' => $performa->tipe],
                    ['label' => 'Volume  :', 'value' => $performa->volume],
                    ['label' => 'S/N  :', 'value' => $performa->serial_number],
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

        <div class="w-full max-w-4xl pt-4 mx-auto space-y-4 text-sm">
            @php
                $rawDetails = $performa->detail->details ?? [];
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
                        <th class="w-10 px-3 py-2 text-center border border-black" rowspan="2">No</th>
                        <th class="px-3 py-2 text-left border border-black" rowspan="2">Part</th>
                        <th class="px-3 py-2 text-center border border-black" colspan="2">Result</th>
                        <th class="px-3 py-2 text-left border border-black" rowspan="2">Status</th>
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
                            <td colspan="5" class="px-3 py-2 font-semibold bg-gray-200 border border-black">
                                {{ $group['mainPart'] ?? '-' }}
                            </td>
                        </tr>
                        @foreach ($group['parts'] as $part)
                            <tr>
                                <td class="px-3 py-2 text-center border border-black">{{ $rowNumber++ }}</td>
                                <td class="px-3 py-2 border border-black">{{ $part['part'] ?? '-' }}</td>
                                <td class="px-3 py-2 text-center border border-black">
                                    {{ ($part['result'] ?? '0') == '1' ? '✔' : '' }}
                                </td>
                                <td class="px-3 py-2 text-center border border-black">
                                    {{ ($part['result'] ?? '0') == '0' ? '✘' : '' }}
                                </td>
                                <td class="px-3 py-2 border border-black">
                                    {{ statusLabel($part['status'] ?? '-') }}
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>

            <div class="w-full max-w-4xl pt-4 mx-auto mb-6">
                <label for="note" class="block mb-1 text-sm font-medium text-gray-700">Note:</label>
                <div id="note" readonly
                    class="w-full min-h-[75px] px-3 py-2 text-sm leading-relaxed text-left border rounded-md text-black border-black">
                    {{ trim($performa->note) }}
                </div>
            </div>

        </div>

        @php
            $roles = [
                'Checked By' => [
                    'name' => $performa->pic->inspected_name ?? '-',
                    'signature' => $performa->pic->inspected_signature ?? null,
                    'date' => $performa->pic->inspected_date ?? null,
                ],
                'Accepted By' => [
                    'name' => $performa->pic->accepted_name ?? '-',
                    'signature' => $performa->pic->accepted_signature ?? null,
                    'date' => $performa->pic->accepted_date ?? null,
                ],
                'Approved By' => [
                    'name' => $performa->pic->approved_name ?? '-',
                    'signature' => $performa->pic->approved_signature ?? null,
                    'date' => $performa->pic->approved_date ?? null,
                ],
            ];
        @endphp

        <div class="max-w-4xl p-4 mx-auto mb-6">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                @foreach ($roles as $role => $data)
                    <div>
                        <label class="block mb-1 font-semibold">{{ $role }}</label>
                        <input type="text" value="{{ $data['name'] }}" readonly class="w-full p-2 mb-2 text-black" />

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
                        <input type="text" readonly value="{{ \Carbon\Carbon::parse($data['date'])->format('d M Y') }}"
                            class="w-full p-2 text-black" />
                    </div>
                @endforeach
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
                filename: "pengecekan-performa.pdf",
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
