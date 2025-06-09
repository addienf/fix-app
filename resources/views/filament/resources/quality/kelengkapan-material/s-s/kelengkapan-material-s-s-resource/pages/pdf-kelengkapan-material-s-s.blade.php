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
                    Standarisasi Gambar Kerja</td>
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

        <!-- DETAIL FIELD -->
        <div class="w-full max-w-4xl mx-auto pt-6 mb-6 text-sm ">
            <div class="flex flex-col">
                <label class="font-medium mb-1">No. PO :</label>
                <input type="text" readonly value="SPK-2025-001"
                    class="w-full px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
            </div>
            <div class="flex flex-col">
                <label class="font-medium mb-1 pt-4">Supplier :</label>
                <input type="text" readonly value="05 Juni 2025"
                    class="w-full px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
            </div>
        </div>

        <!-- PROCEDURE TABLE -->
        <div class="p-4 mb-6 max-w-4xl mx-auto">
            <table class="w-full table-fixed border border-gray-300 text-sm">
                <thead class="bg-blue-300 text-black">
                    <tr>
                        <th class="p-2 border w-1/3">Procedures</th>
                        <th class="p-2 border w-1/3">Expected Result</th>
                        <th class="p-2 border w-1/3">Actual Result</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="p-2 border align-top">
                            <ul class="list-disc ml-4">
                                <li>Wipe off the dust, dirt, oil and water on the surface of the material</li>
                                <li>Make zone on the upper, middle and bottom side of the material surface to be checked
                                </li>
                                <li>Drop the testing liquid on the upper side of material and wait about 2–3 minutes
                                </li>
                            </ul>
                        </td>
                        <td class="p-2 border align-top">There was no color change within 3 minutes after the liquid
                            dropped on the surface that indicating materials is genuine SUS304</td>
                        <td class="p-2 border align-top">
                            <textarea readonly class="w-full border border-gray-300 p-2 rounded resize-none" rows="5"
                                placeholder="Enter actual result..."></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td class="p-2 border">Visual Check</td>
                        <td class="p-2 border">No Defect and rust found</td>
                        <td class="p-2 border">
                            <textarea readonly class="w-full border border-gray-300 p-2 rounded resize-none" rows="2"
                                placeholder="Enter actual result..."></textarea>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- SUMMARY TABLE -->
        <div class="p-4 mb-6 max-w-4xl mx-auto">
            <table class="w-full table-fixed border border-gray-300 text-sm">
                <thead class="bg-blue-300 text-black">
                    <tr>
                        <th class="p-2 border w-2/3">Summary</th>
                        <th class="p-2 border w-1/3">Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="p-2 border">Total Received</td>
                        <td class="p-2 border">
                            <input type="number" readonly
                                class="w-full border border-gray-300 p-2 rounded bg-gray-100 text-gray-500 cursor-not-allowed"
                                placeholder="Enter quantity" />
                        </td>
                    </tr>
                    <tr>
                        <td class="p-2 border">Total Acceptable</td>
                        <td class="p-2 border">
                            <input type="number" readonly
                                class="w-full border border-gray-300 p-2 rounded bg-gray-100 text-gray-500 cursor-not-allowed"
                                placeholder="Enter quantity" />
                        </td>
                    </tr>
                    <tr>
                        <td class="p-2 border">Total Rejected</td>
                        <td class="p-2 border">
                            <input type="number" readonly
                                class="w-full border border-gray-300 p-2 rounded bg-gray-100 text-gray-500 cursor-not-allowed"
                                placeholder="Enter quantity" />
                        </td>
                    </tr>
                    <tr>
                        <td class="p-2 border">Return to Supplier</td>
                        <td class="p-2 border">
                            <input type="number" readonly
                                class="w-full border border-gray-300 p-2 rounded bg-gray-100 text-gray-500 cursor-not-allowed"
                                placeholder="Enter quantity" />
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Remarks -->
            <div class="mt-4">
                <label class="block font-semibold mb-1">Remarks</label>
                <textarea class="w-full border border-gray-300 p-2 rounded resize-none bg-gray-100 text-gray-500 cursor-not-allowed"
                    rows="4" placeholder="Enter remarks..." readonly></textarea>      
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
                        <input type="file" disabled
                            class="w-full mb-2 border border-gray-300 p-2 rounded bg-gray-100 text-gray-500 cursor-not-allowed" />

                        <label class="block mb-1">Date</label>
                        <input type="date" readonly value="{{ now()->format('Y-m-d') }}"
                            class="w-full border border-gray-300 p-2 rounded bg-gray-100 text-gray-500 cursor-not-allowed" />
                    </div>
                @endforeach
            </div>
        </div>

    </x-filament::section>
</x-filament-panels::page>