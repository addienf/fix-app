<x-filament-panels::page>
    <x-filament::section>
    
        <h2 class="mb-3 text-xl font-bold text-center">Detail Formulir Incoming Material Non Stainless Steel</h2>
    
        <table
            class="w-full max-w-4xl mx-auto text-sm border border-black dark:border-white dark:bg-gray-900 dark:text-white"
            style="border-collapse: collapse;">
            <tr>
                <td rowspan="3"
                    class="p-2 text-center align-middle border border-black dark:border-white w-28 h-28 dark:bg-gray-900">
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
                    <table class="w-full text-sm dark:bg-gray-900" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> :
                                FO-QKS-PRO-01-01</td>
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
        <div class="w-full max-w-4xl mx-auto pt-4 text-sm space-y-4">
            @php
$fields = [
    ['label' => 'No PO :', 'value' => 'SPK-2025-001'],
    ['label' => 'Supplier  :', 'value' => '05 Juni 2025'],
];
            @endphp
        
            @foreach ($fields as $field)
                <div class="flex items-center">
                    <label class="w-64 font-medium">{{ $field['label'] }}</label>
                    <input type="text" readonly value="{{ $field['value'] }}"
                        class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                </div>
            @endforeach
            <table class="w-full text-sm border border-black border-collapse mb-8">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border border-black px-3 py-2 w-10 text-center">No</th>
                        <th class="border border-black px-3 py-2 text-left">Description</th>
                        <th colspan="2" class="border border-black px-3 py-2 text-center">Condition</th>
                        <th class="border border-black px-3 py-2 text-left">Remark</th>
                    </tr>
                    <tr>
                        <th colspan="2" class="border border-black px-3 py-2"></th>
                        <th class="border border-black px-3 py-2 text-center">Pass</th>
                        <th class="border border-black px-3 py-2 text-center">Fail</th>
                        <th class="border border-black px-3 py-2"></th>
                    </tr>
                </thead>
                <tbody>
                    @php
$descriptions = [
    'The correct items were accepted',
    'Items are not crushed or broken',
    'Specifications are as required',
    'Function check are as required',
    '',
    '',
    '',
];
                    @endphp
                    @foreach ($descriptions as $i => $desc)
                        <tr>
                            <td class="border border-black px-3 py-2 text-center">{{ $i + 1 }}</td>
                            <td class="border border-black px-3 py-2">{{ $desc }}</td>
                            <td class="border border-black px-3 py-2 text-center"></td>
                            <td class="border border-black px-3 py-2 text-center"></td>
                            <td class="border border-black px-3 py-2"></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <table class="w-full text-sm border border-black border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border border-black px-3 py-2 text-left">Summary</th>
                        <th class="border border-black px-3 py-2 text-center">Critical</th>
                        <th class="border border-black px-3 py-2 text-center">Major</th>
                        <th class="border border-black px-3 py-2 text-center">Minor</th>
                    </tr>
                </thead>
                <tbody>
                    @php
$summaryRows = [
    'Total Received Quantity',
    'Return Quantity to Supplier',
    'Total Rejected Quantity',
    'Total Acceptable Quantity',
];
                    @endphp
                    @foreach ($summaryRows as $row)
                        <tr>
                            <td class="border border-black px-3 py-2">{{ $row }}</td>
                            <td class="border border-black px-3 py-2 text-center"></td>
                            <td class="border border-black px-3 py-2 text-center"></td>
                            <td class="border border-black px-3 py-2 text-center"></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                <label class="block font-semibold mb-1">Batch No.</label>
                <textarea class="w-full border border-gray-300 p-2 rounded resize-none bg-gray-100 text-gray-500 cursor-not-allowed"
                    rows="4" placeholder="Enter remarks..." readonly></textarea>
            </div>
            <div class="p-4 mb-6 max-w-4xl mx-auto">
                <div class="grid grid-cols-3 gap-4 text-sm">
                    @foreach (['Checked By', 'Accepted By', 'Approved By'] as $role)
                        <div>
                            <label class="font-semibold block mb-1">{{ $role }}</label>
                            <input type="text" value="{{ $role }} User" readonly
                                class="w-full mb-2 border border-gray-300 p-2 rounded bg-gray-100 text-gray-500 cursor-not-allowed" />

                            <label class="block mb-1">Signature</label>
                            <div class="w-full h-24 mb-2 border border-black rounded bg-white"></div>

                            <label class="block mb-1">Date</label>
                            <input type="date" readonly value="{{ now()->format('Y-m-d') }}"
                                class="w-full border border-gray-300 p-2 rounded bg-gray-100 text-gray-500 cursor-not-allowed" />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>


    </x-filament::section>
</x-filament-panels::page>
