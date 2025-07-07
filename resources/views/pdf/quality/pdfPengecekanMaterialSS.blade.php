@extends ('pdf.layout.layout')
@section('title', 'Pengecekan Material Stainless Steel PDF')
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
                    Formulir Stainless Steel <br> Production Progress Checklist
                </td>
                <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                    <table class="w-full text-sm dark:bg-gray-900 dark:text-white" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> :
                                FO-QKS-QA-01-04</td>
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

        <div class="grid w-full max-w-4xl grid-cols-1 pt-2 mx-auto text-sm gap-y-4">
            @php
                $fields = [['label' => 'No SPK Produksi :', 'value' => $pengecekanSS->spk->no_spk]];
            @endphp

            @foreach ($fields as $field)
                <div class="flex items-center gap-4">
                    <label class="w-48 font-medium">{{ $field['label'] }}</label>
                    <input type="text" readonly value="{{ $field['value'] }}"
                        class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                </div>
            @endforeach
        </div>

        <h2 class="max-w-4xl mx-auto text-xl font-bold text-start">Chamber Identification</h2>

        <div class="grid w-full max-w-4xl grid-cols-1 pt-4 mx-auto mb-2 text-sm gap-y-4">
            @php
                $fields = [
                    ['label' => 'Type/Model :', 'value' => $pengecekanSS->tipe],
                    ['label' => 'Ref. Document :', 'value' => $pengecekanSS->ref_document],
                ];
            @endphp

            @foreach ($fields as $field)
                <div class="flex items-center gap-4">
                    <label class="w-48 font-medium">{{ $field['label'] }}</label>
                    <input type="text" readonly value="{{ $field['value'] }}"
                        class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                </div>
            @endforeach
        </div>

        @php
            $rawDetails = $pengecekanSS->detail->details ?? [];
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

        <table class="w-full max-w-4xl mx-auto text-sm border border-black">
            <thead class="bg-gray-100">
                <tr>
                    <th class="w-5 px-2 py-2 text-center border border-black" rowspan="2">No</th>
                    <th class="px-2 py-2 text-left border border-black" rowspan="2">Part</th>
                    <th class="px-2 py-2 text-center border border-black" colspan="2">Result</th>
                    <th class="px-2 py-2 text-left border border-black" rowspan="2">Status</th>
                </tr>
                <tr>
                    <th class="px-2 text-center border border-black">Pass</th>
                    <th class="px-2 text-center border border-black">Fail</th>
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

                <div class="w-full max-w-4xl mx-auto pt-4 mb-6">
                    <label for="note" class="block mb-1 text-sm font-medium text-gray-700">Note:</label>
                    <textarea id="note" readonly
                        class="w-full px-3 py-2 overflow-hidden text-sm leading-relaxed border rounded-md resize-none border-black">{{ trim($pengecekanSS->note) }}</textarea>
                </div>

                @php
    $roles = [
        'Checked By' => [
            'name' => $pengecekanSS->pic->inspected_name ?? '-',
            'signature' => $pengecekanSS->pic->inspected_signature ?? null,
            'date' => $pengecekanSS->pic->inspected_date ?? null,
        ],
        'Accepted By' => [
            'name' => $pengecekanSS->pic->accepted_name ?? '-',
            'signature' => $pengecekanSS->pic->accepted_signature ?? null,
            'date' => $pengecekanSS->pic->accepted_date ?? null,
        ],
        'Approved By' => [
            'name' => $pengecekanSS->pic->approved_name ?? '-',
            'signature' => $pengecekanSS->pic->approved_signature ?? null,
            'date' => $pengecekanSS->pic->approved_date ?? null,
        ],
    ];
                @endphp

                <div class="max-w-4xl p-4 mx-auto mb-6">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        @foreach ($roles as $role => $data)
                            <div>
                                <label class="block mb-1 font-semibold">{{ $role }}</label>
                                <input type="text" value="{{ $data['name'] }}" readonly
                                    class="w-full p-2 mb-2 text-black " />

                                <label class="block mb-1 font-semibold">Signature</label>
                                <div class="flex items-center justify-center w-full h-24 mb-2 bg-white ">
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
                filename: "pengecekan-material-stainless-steel.pdf",
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
