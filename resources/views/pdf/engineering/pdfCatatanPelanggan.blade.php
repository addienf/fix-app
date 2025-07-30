@extends('pdf.layout.layout')
@section('title', 'Catatan Keluhan Pelanggan Maintenance PDF')
@section('content')
    <div id="export-area" class="p-2 text-black bg-white">
        <!-- Header Table -->
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
                    Formulir Catatan Keluhan Pelanggan
                </td>
                <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                    <table class="w-full text-sm dark:bg-gray-900 dark:text-white" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white">
                                : FO-QKS-CC-01-10
                            </td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">Tanggal Rilis</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white">
                                : 12 Maret 2025
                            </td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2">Revisi</td>
                            <td class="px-3 py-2 font-semibold">: 0</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="w-full max-w-4xl pt-4 mx-auto text-sm">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Incoming Complaint Report</h2>
                <span class="text-sm font-bold">
                    Wednesday, 14 - 07 -2021 08:43:05 AM
                </span>
            </div>

            @php
                $fields = [
                    ['label' => 'Reported By :', 'value' => 'PT. QLab Kinarya Sentosa'],
                    ['label' => 'Name :', 'value' => 'Jl. Raya No. 123, Jakarta'],
                    ['label' => ' To :', 'value' => '(021) 12345678'],
                    ['label' => 'Email :', 'value' => '(021) 12345678'],
                    ['label' => 'Form No :', 'value' => '(021) 12345678'],
                ];
            @endphp

            <div class="grid gap-2 mb-6">
                @foreach ($fields as $field)
                    <div class="flex flex-wrap items-center gap-2">
                        <label class="w-40 font-medium">{{ $field['label'] }}</label>
                        <input type="text" readonly value="{{ $field['value'] }}"
                            class="flex-1 min-w-[200px] px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                    </div>
                @endforeach
            </div>
        </div>

        <div class="w-full max-w-4xl pt-4 mx-auto text-sm">
            @php
                $fields = [
                    ['label' => 'Who Complaint :', 'value' => 'PT. QLab Kinarya Sentosa'],
                    ['label' => 'Company Name :', 'value' => 'Jl. Raya No. 123, Jakarta'],
                    ['label' => 'Department :', 'value' => 'Quality Assurance'],
                    ['label' => 'Phone Number :', 'value' => '(021) 12345678'],
                    ['label' => 'Complaint Received By :', 'value' => 'Budi Santoso'],
                ];
            @endphp

            <div class="grid gap-3 mb-6">
                @foreach ($fields as $field)
                    <div class="flex flex-wrap items-center gap-2">
                        <label class="w-40 font-medium text-gray-700">{{ $field['label'] }}</label>
                        <input type="text" readonly value="{{ $field['value'] }}"
                            class="flex-1 min-w-[200px] px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                    </div>
                @endforeach
            </div>
        </div>

        <table class="w-full max-w-4xl pt-4 mx-auto text-sm border border-black table-fixed">
            <thead class="bg-gray-300 text-black">
                <tr>
                    <th class="p-2 border border-black">Unit Name</th>
                    <th class="p-2 border border-black">Type/model</th>
                    <th class="p-2 border border-black">Under Warranty</th>
                    <th class="p-2 border border-black">Field Category</th>
                    <th class="p-2 border border-black">Description</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="p-2 border border-black">Climatic Test Chamber</td>
                    <td class="p-2 border border-black">
                        QL 1000.3<br />
                        (K32G4978PW01)<br />
                        6/6
                    </td>
                    <td class="p-2 border border-black">Yes</td>
                    <td class="p-2 border border-black">Humidifier System</td>
                    <td class="p-2 border border-black">
                        Terjadi feeding fault di sebabkan LS tidak mau mati (tgl : 05/07/2021)
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="w-full max-w-4xl pt-4 mx-auto text-sm">
            <h2 class="mb-4 text-xl text-starts">Report Details :</h2>

            @php
                $fields = [
                    ['label' => 'Reported Date :', 'value' => 'PT. QLab Kinarya Sentosa'],
                    ['label' => 'Recorded By :', 'value' => 'Jl. Raya No. 123, Jakarta'],
                    ['label' => 'Recorded Date :', 'value' => '(021) 12345678'],
                ];
            @endphp

            <div class="grid gap-2 mb-6">
                @foreach ($fields as $field)
                    <div class="flex items-center">
                        <label class="w-32 font-medium">{{ $field['label'] }}</label>
                        <input type="text" readonly value="{{ $field['value'] }}"
                            class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection