<x-filament-panels::page>
    {{ $electrical }}
    <x-filament::section>
    
        {{-- Judul Utama --}}
        <h2 class="mb-3 text-xl font-bold text-center">Detail Pengecekan Material Electrical</h2>
    
        {{-- Tabel Header --}}
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
                    Pengecekan Material Electrical
                </td>
                <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                    <table class="w-full text-sm dark:bg-gray-900" style="border-collapse: collapse;">
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

        <div class="w-full max-w-4xl mx-auto pt-6 mb-6 text-sm grid grid-cols-1 gap-y-4">
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

    <h2 class="text-xl font-bold text-start max-w-4xl mx-auto">Chamber Identification</h2>
    
    {{-- Form Chamber Identification --}}
    <div class="w-full max-w-4xl mx-auto pt-6 mb-6 text-sm grid grid-cols-1 gap-y-4">
        @php
$fields = [
    ['label' => 'Type/Model :', 'value' => 'SPK-2025-001'],
    ['label' => 'Ref. Document :', 'value' => '05 Juni 2025'],
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
    <table class="w-full max-w-4xl mx-auto text-sm border border-black mb-6" style="border-collapse: collapse;">
        <thead>
            <tr>
                <th class="border border-black px-2 py-1 text-center w-10">No</th>
                <th class="border border-black px-2 py-1 text-center">Part</th>
                <th colspan="2" class="border border-black px-2 py-1 text-center">Result</th>
                <th class="border border-black px-2 py-1 text-center">Note</th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th class="border border-black px-2 py-1 text-center">Yes</th>
                <th class="border border-black px-2 py-1 text-center">No</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            {{-- Electrical Layout --}}
            <tr>
                <td class="border border-black px-2 py-1 text-center">1</td>
                <td class="border border-black px-2 py-1">Electrical Layout</td>
                <td class="border border-black px-2 py-1 text-center">
                    <div style="width: 20px; height: 20px; border: 1px solid black; margin: auto;"></div>
                </td>
                <td class="border border-black px-2 py-1 text-center">
                    <div style="width: 20px; height: 20px; border: 1px solid black; margin: auto;"></div>
                </td>
                <td class="border border-black px-2 py-1"></td>
            </tr>
    
            {{-- Display Section Header --}}
            <tr>
                <td colspan="5" class="border border-black px-2 py-1 text-center font-bold">Display</td>
            </tr>
    
            @php
$displayParts = [
    'Installation',
    'Cabling',
    'Function',
    'Home Display',
    'Status and Display',
    'Control Panel display',
    'Settings display',
    'Login function',
];
            @endphp
    
            @foreach ($displayParts as $i => $part)
                <tr>
                    <td class="border border-black px-2 py-1 text-center">{{ $i + 1 }}</td>
                    <td class="border border-black px-2 py-1">{{ $part }}</td>
                    <td class="border border-black px-2 py-1 text-center">
                        <div style="width: 20px; height: 20px; border: 1px solid black; margin: auto;"></div>
                    </td>
                    <td class="border border-black px-2 py-1 text-center">
                        <div style="width: 20px; height: 20px; border: 1px solid black; margin: auto;"></div>
                    </td>
                    <td class="border border-black px-2 py-1"></td>
                </tr>
            @endforeach
    
            {{-- Air Circulating System --}}
            <tr>
                <td colspan="5" class="border border-black px-2 py-1 text-center font-bold">Air Circulating System</td>
            </tr>
    
            @php
$airParts = ['Blower Installation', 'Cabling', 'Function'];
            @endphp
    
            @foreach ($airParts as $i => $part)
                <tr>
                    <td class="border border-black px-2 py-1 text-center">{{ $i + 1 }}</td>
                    <td class="border border-black px-2 py-1">{{ $part }}</td>
                    <td class="border border-black px-2 py-1 text-center">
                        <div style="width: 20px; height: 20px; border: 1px solid black; margin: auto;"></div>
                    </td>
                    <td class="border border-black px-2 py-1 text-center">
                        <div style="width: 20px; height: 20px; border: 1px solid black; margin: auto;"></div>
                    </td>
                    <td class="border border-black px-2 py-1"></td>
                </tr>
            @endforeach
    
            {{-- Cooling System --}}
            <tr>
                <td colspan="5" class="border border-black px-2 py-1 text-center font-bold">Cooling System</td>
            </tr>
    
            <tr>
                <td class="border border-black px-2 py-1 text-center">1</td>
                <td class="border border-black px-2 py-1">Compressor Installation</td>
                <td class="border border-black px-2 py-1 text-center">
                    <div style="width: 20px; height: 20px; border: 1px solid black; margin: auto;"></div>
                </td>
                <td class="border border-black px-2 py-1 text-center">
                    <div style="width: 20px; height: 20px; border: 1px solid black; margin: auto;"></div>
                </td>
                <td class="border border-black px-2 py-1"></td>
            </tr>
        </tbody>
    </table>
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
