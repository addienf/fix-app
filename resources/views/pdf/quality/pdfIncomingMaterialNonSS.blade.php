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
                    ['label' => 'No PO :', 'value' => 'PO-2025-002'],
                    ['label' => 'Supplier  :', 'value' => 'PT. Contoh Supplier'],
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

                $fixedData = collect([
                    ['part' => 'The correct items were accepted', 'result' => '1', 'remark' => 'OK'],
                    ['part' => 'Items are not crushed or broken', 'result' => '1', 'remark' => 'Good'],
                    ['part' => 'Specifications are as required', 'result' => '0', 'remark' => 'Mismatch'],
                    ['part' => 'Function check are as required', 'result' => '1', 'remark' => 'Passed'],
                ]);

                $additionalData = [
                    ['part' => 'Packaging is sealed', 'result' => '1', 'remark' => 'Excellent'],
                ];
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

                    @foreach ($additionalData as $j => $extra)
                        <tr>
                            <td class="px-3 py-2 text-center border border-black">{{ count($fixedDescriptions) + $j + 1 }}</td>
                            <td class="px-3 py-2 border border-black">{{ $extra['part'] }}</td>
                            <td class="px-3 py-2 text-center border border-black">{{ $extra['result'] == '1' ? '✔' : '' }}</td>
                            <td class="px-3 py-2 text-center border border-black">{{ $extra['result'] == '0' ? '✘' : '' }}</td>
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
                <tbody>
                    <tr>
                        <td class="px-3 py-2 border border-black">Total Received Quantity</td>
                        <td class="px-3 py-2 text-center border border-black">3</td>
                        <td class="px-3 py-2 text-center border border-black">5</td>
                        <td class="px-3 py-2 text-center border border-black">1</td>
                    </tr>
                    <tr>
                        <td class="px-3 py-2 border border-black">Return Quantity to Supplier</td>
                        <td class="px-3 py-2 text-center border border-black">1</td>
                        <td class="px-3 py-2 text-center border border-black">0</td>
                        <td class="px-3 py-2 text-center border border-black">0</td>
                    </tr>
                    <tr>
                        <td class="px-3 py-2 border border-black">Total Rejected Quantity</td>
                        <td class="px-3 py-2 text-center border border-black">2</td>
                        <td class="px-3 py-2 text-center border border-black">1</td>
                        <td class="px-3 py-2 text-center border border-black">0</td>
                    </tr>
                    <tr>
                        <td class="px-3 py-2 border border-black">Total Acceptable Quantity</td>
                        <td colspan="3" class="px-3 py-2 text-center border border-black">12</td>
                    </tr>
                </tbody>
            </table>

            <div class="w-full max-w-4xl mx-auto ">
                <label for="note" class="block mb-1 text-sm font-medium text-gray-700">Batch No</label>
                <div id="note" readonly
                    class="w-full min-h-[75px] px-3 py-2 text-sm leading-relaxed text-left border rounded-md text-black border-black">
                    BATCH-998877
                </div>
            </div>

            @php
                $roles = [
                    'Checked By' => [
                        'name' => 'John Doe',
                        'signature' => null,
                        'date' => now()->toDateString(),
                    ],
                    'Accepted By' => [
                        'name' => 'Jane Smith',
                        'signature' => null,
                        'date' => now()->toDateString(),
                    ],
                    'Approved By' => [
                        'name' => 'Robert Tan',
                        'signature' => null,
                        'date' => now()->toDateString(),
                    ],
                ];
            @endphp

            <div class="max-w-4xl p-4 mx-auto">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    @foreach ($roles as $role => $data)
                        <div>
                            <label class="block mb-1 font-semibold">{{ $role }}</label>
                            <input type="text" value="{{ $data['name'] }}" readonly
                                class="w-full p-2 mb-2 text-gray-500 bg-gray-100 border border-gray-300 rounded" />

                            <label class="block mb-1">Signature</label>
                            <div class="flex items-center justify-center w-full h-24 mb-2 bg-white border border-gray rounded">
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
    </div>
@endsection