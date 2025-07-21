@extends ('pdf.layout.layout')
@section('title', 'Formulir Attachment Defect anda Repair Statuses')
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
                    Attachment Defect and <br> Repaired Status
                </td>
                <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                    <table class="w-full text-sm dark:bg-gray-900 dark:text-white" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> :
                                FO-QKS-QA-01-05</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">Tanggal Rilis</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> : 17 Juni 2025
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
        <div class="grid w-full max-w-4xl grid-cols-1 pt-4 mx-auto mb-3 text-sm gap-y-4">

            @php
                $fields = [['label' => 'No SPK Produksi :', 'value' => $defect->spk->no_spk]];
            @endphp

            @foreach ($fields as $field)
                <div class="flex flex-row items-center gap-4">
                    <label class="w-48 font-medium">{{ $field['label'] }}</label>
                    <input type="text" readonly value="{{ $field['value'] }}"
                        class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                </div>
            @endforeach
        </div>
        <div class="grid w-full max-w-4xl grid-cols-1 mx-auto mb-6 gap-y-4">
            <h2 class="col-span-1 mb-2 text-xl font-bold text-start">
                Chamber Identification
            </h2>
            @php
                $fields = [
                    ['label' => 'Type/Model :', 'value' => $defect->tipe],
                    ['label' => 'Volume :', 'value' => $defect->volume],
                    ['label' => 'S/N :', 'value' => $defect->serial_number],
                ];
            @endphp
            @foreach ($fields as $field)
                <div class="flex flex-row items-center gap-4">
                    <label class="w-48 font-medium">{{ $field['label'] }}</label>
                    <input type="text" readonly value="{{ $field['value'] }}"
                        class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                </div>
            @endforeach
        </div>

        @php
            // Ambil semua spesifikasi_ditolak dari relasi details
            $details = $defect->details
                ->pluck('spesifikasi_ditolak') // ambil semua
                ->flatten(1) // gabungkan array
                ->map(function ($item) {
                    return is_string($item) ? json_decode($item, true) : $item;
                })
                ->toArray();

            // Closure untuk label status
            $statusLabel = fn($code) => match (strtolower($code)) {
                'ok' => 'OK',
                'h' => 'Hold',
                'r' => 'Repaired',
                default => ucfirst($code ?? '-'),
            };
        @endphp

        <h2 class="mt-4 mb-2 text-lg font-semibold text-center">
            Spesifikasi Ditolak
        </h2>

        <table class="w-full max-w-4xl mx-auto mb-3 text-sm border border-black">
            <thead class="bg-gray-100">
                <tr>
                    <th rowspan="2" class="w-10 px-3 py-2 text-center border border-black">No</th>
                    <th rowspan="2" class="px-3 py-2 text-left border border-black">Incompatible Part</th>
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
                        <td class="px-3 py-2 text-center border border-black">{{ $rowNumber++ }}</td>
                        <td class="px-3 py-2 font-semibold bg-gray-200 border border-black">
                            {{ $group['mainPart'] ?? '-' }}
                        </td>
                        <td class="px-3 py-2 text-center border border-black">
                            {{ ($group['mainPart_result'] ?? '0') == '1' ? '✔' : '' }}
                        </td>
                        <td class="px-3 py-2 text-center border border-black">
                            {{ ($group['mainPart_result'] ?? '0') == '0' ? '✘' : '' }}
                        </td>
                        <td class="px-3 py-2 border border-black">
                            {{ $statusLabel($group['mainPart_status'] ?? '-') }}
                        </td>
                    </tr>
                    @foreach ($group['parts'] ?? [] as $part)
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
                                {{ $statusLabel($part['status'] ?? '-') }}
                            </td>
                        </tr>
                    @endforeach
                @endforeach

                @if (count($details) === 0)
                    <tr>
                        <td colspan="5" class="px-3 py-2 text-center text-gray-500 border border-black">
                            Tidak ada data spesifikasi ditolak.
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        @php
            $details = $defect->details
                ->pluck('spesifikasi_revisi')
                ->flatten(1)
                ->map(function ($item) {
                    return is_string($item) ? json_decode($item, true) : $item;
                })
                ->toArray();

            $statusLabel = fn($code) => match (strtolower($code)) {
                'ok' => 'OK',
                'h' => 'Hold',
                'r' => 'Repaired',
                default => ucfirst($code ?? '-'),
            };
        @endphp

        <h2 class="mt-4 mb-2 text-lg font-semibold text-center">
            Spesifikasi Revisi
        </h2>

        <table class="w-full max-w-4xl mx-auto mb-3 text-sm border border-black">
            <thead class="bg-gray-100">
                <tr>
                    <th rowspan="2" class="w-10 px-3 py-2 text-center border border-black">No</th>
                    <th rowspan="2" class="px-3 py-2 text-left border border-black">Incompatible Part</th>
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
                        <td class="px-3 py-2 text-center border border-black">{{ $rowNumber++ }}</td>
                        <td class="px-3 py-2 font-semibold bg-gray-200 border border-black">
                            {{ $group['mainPart'] ?? '-' }}
                        </td>
                        <td class="px-3 py-2 text-center border border-black">
                            {{ ($group['mainPart_result'] ?? '0') == '1' ? '✔' : '' }}
                        </td>
                        <td class="px-3 py-2 text-center border border-black">
                            {{ ($group['mainPart_result'] ?? '0') == '0' ? '✘' : '' }}
                        </td>
                        <td class="px-3 py-2 border border-black">
                            {{ $statusLabel($group['mainPart_status'] ?? '-') }}
                        </td>
                    </tr>
                    @foreach ($group['parts'] ?? [] as $part)
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
                                {{ $statusLabel($part['status'] ?? '-') }}
                            </td>
                        </tr>
                    @endforeach
                @endforeach

                @if (count($details) === 0)
                    <tr>
                        <td colspan="5" class="px-3 py-2 text-center text-gray-500 border border-black">
                            Tidak ada data spesifikasi ditolak.
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="w-full max-w-4xl mx-auto mb-4">
            <label for="note" class="block mb-1 text-sm font-medium text-gray-700">Note:</label>
            <div id="note" readonly
                class="w-full min-h-[75px] px-3 py-2 text-sm leading-relaxed text-left border rounded-md text-black border-black">
                {{ trim($defect->note) }}
            </div>
        </div>

        @php
            $roles = [
                'Inspected By' => [
                    'name' => $defect->pic->inspectedName->name ?? '-',
                    'signature' => $defect->pic->inspected_signature ?? null,
                    'date' => $defect->pic->inspected_date ?? null,
                ],
                'Accepted By' => [
                    'name' => $defect->pic->acceptedName->name ?? '-',
                    'signature' => $defect->pic->accepted_signature ?? null,
                    'date' => $defect->pic->accepted_date ?? null,
                ],
                'Approved By' => [
                    'name' => $defect->pic->approvedName->name ?? '-',
                    'signature' => $defect->pic->approved_signature ?? null,
                    'date' => $defect->pic->approved_date ?? null,
                ],
            ];
        @endphp

        <div class="max-w-4xl mx-auto mb-6 text-sm">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="font-semibold text-center bg-gray-100">
                        <th class="w-32 border border-black"></th>
                        @foreach ($roles as $role => $data)
                            <th class="border border-black">{{ $role }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="px-2 py-2 font-medium border border-black">Name</td>
                        @foreach ($roles as $data)
                            <td class="px-2 py-2 border border-black">{{ $data['name'] }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="px-2 py-2 font-medium border border-black">Signature</td>
                        @foreach ($roles as $data)
                            <td class="px-2 py-3 border border-black">
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
                        <td class="px-2 py-2 font-medium border border-black">Date</td>
                        @foreach ($roles as $data)
                            <td class="px-2 py-2 border border-black">
                                {{ $data['date'] ? \Carbon\Carbon::parse($data['date'])->format('d M Y') : '-' }}
                            </td>
                        @endforeach
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
                filename: "defect-status.pdf",
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
