 <x-filament-panels::page>
        <x-filament::section>
        
            <h2 class="mb-3 text-xl font-bold text-center">Detail Formulir Performance Qualification</h2>
        
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
                        Formulir Performance <br> Qualification
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
    ['label' => 'No SPK Produksi :', 'value' => 'SPK-2025-001'],
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
                <div class="w-full max-w-4xl mx-auto pt-4 text-sm space-y-4">
                    <h2 class="text-xl font-bold">Chamber Identification</h2>
                    @php
$fields = [
    ['label' => 'Type/Model :', 'value' => 'SPK-2025-001'],
    ['label' => 'Volume  :', 'value' => '05 Juni 2025'],
    ['label' => 'S/N  :', 'value' => '05 Juni 2025'],
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
                @php
$fields = [
    'Cabinet Qualification' => [
        'Body Chamber',
        'Glass Door',
        'Stainless Steel Door',
        'Spec Plate',
    ],
    'Electrical Qualification' => [
        'Part Installation',
        'Cable Installation',
        'Interior Lamp Installation',
    ],
    'Performance Qualification' => [
        'Recovery Time',
        'Logging System',
        'Calibration',
        'Mapping',
        'Fixed Alarm',
        'Power Failure Test',
    ],
];
    @endphp
    
    <div class="w-full max-w-4xl mx-auto pt-4 text-sm space-y-4">
        <table class="min-w-full text-sm border border-black">
            <thead class="bg-gray-100">
                <tr>
                    <th rowspan="2" class="border border-black px-2 py-1 align-middle">No</th>
                    <th rowspan="2" class="border border-black px-2 py-1 align-middle">Part</th>
                    <th colspan="2" class="border border-black px-2 py-1">Result</th>
                    <th rowspan="2" class="border border-black px-2 py-1 align-middle">Status</th>
                </tr>
                <tr>
                    <th class="border border-black px-2 py-1">Yes</th>
                    <th class="border border-black px-2 py-1">No</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($fields as $section => $parts)
                    <!-- Section Header -->
                    <tr>
                        <td colspan="5" class="border border-black font-semibold text-left px-2 py-1 bg-gray-50">
                            {{ $section }}
                        </td>
                    </tr>
                    @foreach ($parts as $part)
                        <tr>
                            <td class="border border-black text-center">{{ $no++ }}</td>
                            <td class="border border-black text-left px-2">{{ $part }}</td>
                            <td class="border border-black text-center"><input type="checkbox" class="scale-125" /></td>
                            <td class="border border-black text-center"><input type="checkbox" class="scale-125" /></td>
                            <td class="border border-black"></td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            <label class="block font-semibold mb-1">Remarks</label>
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
    