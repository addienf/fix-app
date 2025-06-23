@extends ('pdf.layout.layout')

@section('content')
    <div id="export-area" class="p-2 bg-white text-black">
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
                    Pengecekan Material Electrical
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
        <div class="grid w-full max-w-4xl grid-cols-1 pt-4 mx-auto mb-2 text-sm gap-y-4">
            @php
                $fields = [['label' => 'No SPK Produksi :', 'value' => 'SPK-ELEC-2025-015']];
            @endphp

            @foreach ($fields as $field)
                <div class="flex flex-col">
                    <label class="mb-1 font-medium">{{ $field['label'] }}</label>
                    <input type="text" readonly value="{{ $field['value'] }}"
                        class="w-full px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                </div>
            @endforeach
        </div>

        <h2 class="max-w-4xl mx-auto text-xl font-bold text-start ">Chamber Identification</h2>

        <div class="grid w-full max-w-4xl grid-cols-1 pt-2 mx-auto mb-4 text-sm gap-y-4">
            @php
                $fields = [
                    ['label' => 'Type/Model :', 'value' => 'ELECTRA-3000'],
                    ['label' => 'Volume :', 'value' => '1800 L'],
                ];
            @endphp

            @foreach ($fields as $field)
                <div class="flex flex-col">
                    <label class="mb-1 font-medium">{{ $field['label'] }}</label>
                    <input type="text" readonly value="{{ $field['value'] }}"
                        class="w-full px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                </div>
            @endforeach
        </div>

        @php
            $details = [
                [
                    'mainPart' => 'Main Power',
                    'parts' => [
                        ['part' => 'MCB 3P 32A', 'result' => '1', 'status' => 'ok'],
                        ['part' => 'Contactor', 'result' => '0', 'status' => 'r'],
                    ],
                ],
                [
                    'mainPart' => 'Control Panel',
                    'parts' => [
                        ['part' => 'Digital Display', 'result' => '1', 'status' => 'ok'],
                        ['part' => 'Selector Switch', 'result' => '1', 'status' => 'ok'],
                    ],
                ],
            ];

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
                            {{ $group['mainPart'] }}
                        </td>
                    </tr>
                    @foreach ($group['parts'] as $part)
                        <tr>
                            <td class="px-3 py-2 text-center border border-black">{{ $rowNumber++ }}</td>
                            <td class="px-3 py-2 border border-black">{{ $part['part'] }}</td>
                            <td class="px-3 py-2 text-center border border-black">
                                {{ $part['result'] == '1' ? '✔' : '' }}
                            </td>
                            <td class="px-3 py-2 text-center border border-black">
                                {{ $part['result'] == '0' ? '✘' : '' }}
                            </td>
                            <td class="px-3 py-2 border border-black">
                                {{ statusLabel($part['status']) }}
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>

        <div class="w-full max-w-4xl mx-auto ">
            <label for="note" class="block mb-1 text-sm font-medium text-gray-700">Note:</label>
            <div id="note" readonly
                class="w-full px-3 py-2 overflow-hidden text-sm leading-relaxed border resize-none border-black rounded-md">
            Semua panel telah diperiksa. Kontaktor perlu diganti. Fungsi lainnya beroperasi normal.</div>
        </div>

        @php
            $roles = [
                'Checked By' => [
                    'name' => 'Eko Santoso',
                    'signature' => null,
                    'date' => '2025-06-19',
                ],
                'Accepted By' => [
                    'name' => 'Budi Raharjo',
                    'signature' => null,
                    'date' => '2025-06-19',
                ],
                'Approved By' => [
                    'name' => 'Ratna Dewi',
                    'signature' => null,
                    'date' => '2025-06-19',
                ],
            ];
        @endphp

        <div class="max-w-4xl p-4 mx-auto mb-2">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                @foreach ($roles as $role => $data)
                    <div>
                        <label class="block mb-1 font-semibold">{{ $role }}</label>
                        <input type="text" value="{{ $data['name'] }}" readonly
                            class="w-full p-2 mb-2 text-gray-500 bg-gray-100 border border-gray-300 rounded" />

                        <label class="block mb-1">Signature</label>
                        <div class="flex items-center justify-center w-full h-24 mb-2 bg-white border border-black rounded">
                            <span class="text-sm text-gray-400">No Signature</span>
                        </div>

                        <label class="block mb-1">Date</label>
                        <input type="text" readonly value="{{ \Carbon\Carbon::parse($data['date'])->format('d M Y') }}"
                            class="w-full p-2 text-gray-500 bg-gray-100 border border-gray-300 rounded" />
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection