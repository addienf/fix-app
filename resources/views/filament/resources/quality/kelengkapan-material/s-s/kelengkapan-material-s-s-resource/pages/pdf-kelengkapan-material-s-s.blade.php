<x-filament-panels::page>
    <x-filament::section>
        <h2 class="mb-3 text-xl font-bold text-center">Detail Kelengkapan Material Stainless Steel</h2>

        <!-- HEADER TABLE -->
        <table
            class="w-full max-w-4xl mx-auto text-sm border border-black dark:border-white dark:bg-gray-900 dark:text-white"
            style="border-collapse: collapse;">
            <tr>
                <td rowspan="3"
                    class="p-2 text-center align-middle border border-black dark:border-white w-28 h-28 dark:bg-gray-900">
                    <img src="{{ asset('asset/logo.png') }}" alt="Logo" class="object-contain mx-auto h-28" />
                </td>
                <td colspan="2" class="font-bold text-center border border-black dark:border-white dark:bg-gray-900">PT.
                    QLab Kinarya Sentosa</td>
            </tr>
            <tr>
                <td class="font-bold text-center border border-black dark:border-white dark:bg-gray-900 text-lg">
                    Kelengkapan Material Stainless Steel</td>
                <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                    <table class="w-full text-sm" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-1 border-b border-black dark:border-white">No. Dokumen</td>
                            <td class="px-3 py-1 font-semibold border-b border-black dark:border-white">:
                                FO-QKS-QA-01-01</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-1 border-b border-black dark:border-white">Tanggal Rilis</td>
                            <td class="px-3 py-1 font-semibold border-b border-black dark:border-white">: 12 Maret 2025
                            </td>
                        </tr>
                        <tr>
                            <td class="px-3 py-1">Revisi</td>
                            <td class="px-3 py-1 font-semibold">: 0</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <div class="w-full max-w-4xl mx-auto pt-4 mb-6 grid grid-cols-1 gap-y-4">
            @php
$fields = [
    ['label' => ' No SPK Produksi  :', 'value' => 'SPK-2025-001'],
];
                @endphp
                @foreach ($fields as $field)
                    <div class="flex flex-col">
                        <label class="font-medium mb-1">{{ $field['label'] }}</label>
                        <input type="text" readonly value="{{ $field['value'] }}"
                            class="w-full px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                    </div>
                @endforeach
            <h2 class="mb-2 text-xl font-bold text-start col-span-1">
                Chamber Identification
            </h2>
        @php
$fields = [
    ['label' => ' Type Model :', 'value' => '05 Juni 2025'],
    ['label' => ' Ref Document :', 'value' => '05 Juni 2025'],
];
        @endphp
        @foreach ($fields as $field)
            <div class="flex flex-col">
                <label class="font-medium mb-1">{{ $field['label'] }}</label>
                <input type="text" readonly value="{{ $field['value'] }}"
                    class="w-full px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
            </div>
        @endforeach 
        @php
            $fields = [
                ['item' => 'Item A', 'spec' => 'Spec A', 'status' => 'OK', 'remark' => 'Checked'],
                ['item' => 'Item B', 'spec' => 'Spec B', 'status' => 'Fail', 'remark' => 'Damaged'],
                ['item' => 'Item C', 'spec' => 'Spec C', 'status' => '', 'remark' => 'Pending'],
                ['item' => '', 'spec' => '', 'status' => '', 'remark' => ''],
            ];
        @endphp
        
        <table class="w-full text-sm border border-black border-collapse mb-6">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border border-black px-3 py-2 text-center w-10">No</th>
                    <th class="border border-black px-3 py-2 text-left">Part</th>
                    <th class="border border-black px-3 py-2 text-left">Order Number</th>
                    <th class="border border-black px-3 py-2 text-center">Result</th>
                    <th class="border border-black px-3 py-2 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($fields as $index => $field)
                    <tr>
                        <td class="border border-black px-3 py-2 text-center">{{ $index + 1 }}</td>
                        <td class="border border-black px-3 py-2">{{ $field['item'] }}</td>
                        <td class="border border-black px-3 py-2">{{ $field['spec'] }}</td>
                        <td class="border border-black px-3 py-2 text-center">{{ $field['status'] }}</td>
                        <td class="border border-black px-3 py-2">{{ $field['remark'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    
        <div class="mt-4">
            <label class="block font-semibold mb-1">Remarks</label>
            <textarea class="w-full border border-black p-2 rounded resize-none cursor-not-allowed"
                rows="4" placeholder="Enter remarks..." readonly></textarea>
        </div>
    
    </div>
           

        <!-- SIGNATURE SECTION -->
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