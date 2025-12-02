<x-filament-panels::page>
<x-filament::section>
    {{ $defect }}
    <h2 class="mb-3 text-xl font-bold text-center">Detail Attachment Defect and Repaired Status</h2>
    <table class="w-full max-w-4xl mx-auto text-sm border border-black dark:border-white dark:bg-gray-900 dark:text-white"
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
                Attachment Defect and <br> Repaired Status
            </td>
            <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                <table class="w-full text-sm dark:bg-gray-900" style="border-collapse: collapse;">
                    <tr>
                        <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                        <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> : FO-QKS-QA-01-01</td>
                    </tr>
                    <tr>
                        <td class="px-3 py-2 border-b border-black dark:border-white">Tanggal Rilis</td>
                        <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> : 12 Maret 2025</td>
                    </tr>
                    <tr>
                        <td class="px-3 py-2">Revisi</td>
                        <td class="px-3 py-2 font-semibold"> : 0</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <div class="w-full max-w-4xl mx-auto pt-4 mb-3 text-sm grid grid-cols-1 gap-y-4">
        @php
$fields = [
    ['label' => 'No SPK Produksi :', 'value' => 'SPK-2025-001'],
];
        @endphp
    
        @foreach ($fields as $field)
            <div class="flex flex-col">
                <label class="font-medium mb-1">{{ $field['label'] }}</label>
                <input type="text" readonly value="{{ $field['value'] }}"
                    class="w-full px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
            </div>
        @endforeach
    </div>
    <div class="w-full max-w-4xl mx-auto mb-6 grid grid-cols-1 gap-y-4">
        <h2 class="mb-2 text-xl font-bold text-start col-span-1">
            Chamber Identification
        </h2>
        @php
$fields = [
    ['label' => ' Type/Model :', 'value' => 'SPK-2025-001'],
    ['label' => ' Volume :', 'value' => '05 Juni 2025'],
    ['label' => ' S/N :', 'value' => '05 Juni 2025'],
];
        @endphp
        @foreach ($fields as $field)
            <div class="flex flex-col">
                <label class="font-medium mb-1">{{ $field['label'] }}</label>
                <input type="text" readonly value="{{ $field['value'] }}"
                    class="w-full px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
            </div>
        @endforeach
    </div>
    <table class="w-full max-w-4xl mx-auto pt-4 text-sm border border-black mb-3"
        style="border-collapse: collapse; table-layout: fixed;">
        <thead>
            <tr>
                <th class="border border-black px-2 py-1 text-center" style="width: 40px;" rowspan="2">No</th>
                <th class="border border-black px-2 py-1 text-center w-1/3" rowspan="2">Incompatible Part</th>
                <th colspan="2" class="border border-black px-2 py-1 text-center w-1/4">Result</th>
                <th class="border border-black px-2 py-1 text-center w-1/6" rowspan="2">Status</th>
            </tr>
            <tr>
                <th class="border border-black px-2 py-1 text-center">Yes</th>
                <th class="border border-black px-2 py-1 text-center">No</th>
            </tr>
        </thead>
        @php
$rows = [
    '',
    '',
    '',
    '',
    '',
    '',
    '',
];
        @endphp
        <tbody>
            @foreach ($rows as $index => $part)
                <tr>
                    <td class="border border-black px-2 py-1 text-center" style="width: 40px;">
                        {{ $index + 1 }}
                    </td>
                    <td class="border border-black px-2 py-1">{{ $part }}</td>
                    <td class="border border-black px-2 py-1 text-center">
                        <input type="checkbox" class="w-4 h-4" />
                    </td>
                    <td class="border border-black px-2 py-1 text-center">
                        <input type="checkbox" class="w-4 h-4" />
                    </td>
                    <td class="border border-black px-2 py-1 text-center"></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="w-full max-w-4xl mx-auto mb-6">
        <label for="note" class="block text-sm font-medium text-gray-700 mb-1">Note:</label>
        <textarea id="note" name="note" rows="4"
            class="w-full border border-black px-3 py-2 text-sm resize-none"></textarea>
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
</x-filament::section>
</x-filament-panels::page>
