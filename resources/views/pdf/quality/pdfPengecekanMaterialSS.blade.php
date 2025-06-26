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
                    Pengecekan Material<br>Stainless Steel
                </td>
                <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                    <table class="w-full text-sm dark:bg-gray-900 dark:text-white" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> :
                                FO-QKS-QA-01-05</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">Tanggal Rilis</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> : 17 Juni 2025
                            </td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2">Revisi</td>
                            <td class="px-3 py-2 font-semibold"> : 01</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <div class="grid w-full max-w-4xl grid-cols-1 pt-6 mx-auto mb-6 text-sm gap-y-4">
            <div class="flex items-center gap-4">
                <label class="w-48 font-medium">No SPK Produksi :</label>
                <input type="text" readonly value="SPK-2024-001"
                    class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
            </div>
        </div>

        <h2 class="max-w-4xl mx-auto mb-1 text-xl font-bold text-start">Chamber Identification</h2>

        <div class="grid w-full max-w-4xl grid-cols-1 pt-2 mx-auto mb-6 text-sm gap-y-4">
            <div class="flex items-center gap-4">
                <label class="w-48 font-medium">Type/Model :</label>
                <input type="text" readonly value="CHMB-200XL"
                    class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
            </div>
            <div class="flex items-center gap-4">
                <label class="w-48 font-medium">Ref. Document :</label>
                <input type="text" readonly value="REF-0321/DOC"
                    class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
            </div>
        </div>

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
                <tr>
                    <td colspan="5" class="px-3 py-2 font-semibold bg-gray-200 border border-black">Power System</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 text-center border border-black">1</td>
                    <td class="px-3 py-2 border border-black">Main Breaker</td>
                    <td class="px-3 py-2 text-center border border-black">✔</td>
                    <td class="px-3 py-2 text-center border border-black"></td>
                    <td class="px-3 py-2 border border-black">OK</td>
                </tr>
                <tr>
                    <td class="px-3 py-2 text-center border border-black">2</td>
                    <td class="px-3 py-2 border border-black">Control Panel</td>
                    <td class="px-3 py-2 text-center border border-black"></td>
                    <td class="px-3 py-2 text-center border border-black">✘</td>
                    <td class="px-3 py-2 border border-black">Hold</td>
                </tr>
            </tbody>
        </table>

        <div class="w-full max-w-4xl mx-auto mb-6">
            <label for="note" class="block mb-1 text-sm font-medium text-gray-700">Note:</label>
            <div id="note" readonly
                class="w-full px-3 py-2 overflow-hidden text-sm leading-relaxed border rounded-md resize-none border-black">Unit mengalami masalah pada bagian kontrol, perlu perbaikan lanjutan.</div>
        </div>

        <div class="max-w-4xl p-4 mx-auto mb-6">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div>
                    <label class="block mb-1 font-semibold">Checked By</label>
                    <input type="text" value="Andi Saputra" readonly
                        class="w-full p-2 mb-2 text-gray-500 bg-gray-100 border border-gray-300 rounded" />
                    <label class="block mb-1">Signature</label>
                    <div class="flex items-center justify-center w-full h-24 mb-2 bg-white border border-black rounded">
                        <span class="text-sm text-gray-400">No Signature</span>
                    </div>
                    <label class="block mb-1">Date</label>
                    <input type="text" readonly value="01 Jan 2025"
                        class="w-full p-2 text-gray-500 bg-gray-100 border border-gray-300 rounded" />
                </div>
                <div>
                    <label class="block mb-1 font-semibold">Accepted By</label>
                    <input type="text" value="Rina Hartati" readonly
                        class="w-full p-2 mb-2 text-gray-500 bg-gray-100 border border-gray-300 rounded" />
                    <label class="block mb-1">Signature</label>
                    <div class="flex items-center justify-center w-full h-24 mb-2 bg-white border border-black rounded">
                        <span class="text-sm text-gray-400">No Signature</span>
                    </div>
                    <label class="block mb-1">Date</label>
                    <input type="text" readonly value="02 Jan 2025"
                        class="w-full p-2 text-gray-500 bg-gray-100 border border-gray-300 rounded" />
                </div>
                <div>
                    <label class="block mb-1 font-semibold">Approved By</label>
                    <input type="text" value="Budi Prasetyo" readonly
                        class="w-full p-2 mb-2 text-gray-500 bg-gray-100 border border-gray-300 rounded" />
                    <label class="block mb-1">Signature</label>
                    <div class="flex items-center justify-center w-full h-24 mb-2 bg-white border border-black rounded">
                        <span class="text-sm text-gray-400">No Signature</span>
                    </div>
                    <label class="block mb-1">Date</label>
                    <input type="text" readonly value="03 Jan 2025"
                        class="w-full p-2 text-gray-500 bg-gray-100 border border-gray-300 rounded" />
                </div>
            </div>
        </div>
    </div>
@endsection