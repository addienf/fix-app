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
        </div>
        <div class="max-w-4xl mx-auto my-10 space-y-10 text-sm">
            <!-- Table 1: Inspection -->
            <div class="border rounded-lg overflow-hidden shadow-sm">
                <table class="w-full border-collapse text-left table-fixed">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 border w-1/3">Procedures</th>
                            <th class="px-4 py-2 border w-1/4">Expected Result</th>
                            <th class="px-4 py-2 border w-2/5">Actual Result</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-4 py-2 border align-top">
                                <ul class="list-disc pl-4">
                                    <li>Wipe off the dust, dirt, oil and water on the surface of the material</li>
                                    <li>Make a mark on the upper, middle and bottom side of the material surface to be checked
                                    </li>
                                    <li>Drop the testing liquid on the upper side of material and wait about 2–3 minutes</li>
                                </ul>
                            </td>
                            <td class="px-4 py-2 border align-top text-sm">
                                There was no color change within 3 minutes after the liquid dropped on the surface that
                                indicating material is genuine SS304
                            </td>
                            <td class="px-4 py-2 border align-top">
                                <textarea
                                    class="w-full h-24 border rounded-md px-2 py-1 resize-none focus:outline-none focus:ring focus:ring-gray-300"
                                    placeholder="Enter actual result..."></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 border">Visual Check</td>
                            <td class="px-4 py-2 border text-sm">No Defect and rust found</td>
                            <td class="px-4 py-2 border">
                                <textarea
                                    class="w-full h-20 border rounded-md px-2 py-1 resize-none focus:outline-none focus:ring focus:ring-gray-300"
                                    placeholder="Enter actual result..."></textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Table 2: Summary -->
            <div class="border rounded-lg overflow-hidden shadow-sm w-full">
                <table class="w-full border-collapse text-left text-sm">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 border w-1/2">Summary</th>
                            <th class="px-4 py-2 border w-1/2">Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-4 py-2 border">Total Received</td>
                            <td class="px-4 py-2 border"></td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 border">Total Acceptable</td>
                            <td class="px-4 py-2 border"></td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 border">Total Rejected</td>
                            <td class="px-4 py-2 border"></td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 border">Return to Supplier</td>
                            <td class="px-4 py-2 border"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
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
