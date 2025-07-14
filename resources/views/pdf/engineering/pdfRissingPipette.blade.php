@extends('pdf.layout.layout')

@section('content')
    <div id="export-area" class="p-2 bg-white text-black">


        <table class="w-full max-w-4xl mx-auto text-sm border border-black" style="border-collapse: collapse;">
            <tr>

                <td rowspan="2" class="w-32 h-24 text-center border border-black align-middle">
                    <img src="{{ asset('asset/logo.png') }}" alt="Logo" class="object-contain h-16 mx-auto" />
                </td>


                <td class="border border-black px-2 py-1 align-top">
                    <strong>Project :</strong>
                </td>


                <td rowspan="2" class="w-48 border border-black align-top text-center text-sm leading-tight pt-2 p-2">
                    FO-QKS-ENG-01-08<br>
                    Rev. 00<br>
                    02 June 2025
                </td>
            </tr>
            <tr>

                <td class="border border-black px-2 py-1 align-top">
                    Client
                </td>
            </tr>
        </table>
        <div class="w-full max-w-4xl pt-4 mx-auto text-sm">
            <h2 class="text-xl font-bold text-center mb-4">
            REGULAR MAINTENANCE CHECK LIST - QLAB RINSING PIPETTE
            </h2>
            <div class="flex justify-center items-center gap-2 w-full my-6">
                <span class="text-center font-semibold"> Name Tag/No:</span>
                <input type="text" disabled class="px-2 py-1 text-sm bg-transparent border border-gray-300 rounded"
                    value="PB-2025/00123" />
            </div>
            <table class="w-full max-w-4xl pt-4 mx-auto table-auto border border-black text-xs">
                <thead>
                    <tr class="bg-gray-300 text-center font-bold">
                        <th class="border border-black w-8">NO</th>
                        <th class="border border-black w-48">ITEM TO CHECK</th>
                        <th class="border border-black w-40">BEFORE<br>MAINTENANCE</th>
                        <th class="border border-black w-40">AFTER<br>MAINTENANCE</th>
                        <th class="border border-black w-16" colspan="3">ACCEPTED</th>
                        <th class="border border-black w-36">REMARK</th>
                    </tr>
                    <tr class="bg-gray-300 text-center font-bold">
                        <th colspan="4" class="invisible"></th>
                        <th class="border border-black w-10">YES</th>
                        <th class="border border-black w-10">NO</th>
                        <th class="border border-black w-10">NA</th>
                        <th class="invisible"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-center">
                        <td class="border border-black">1</td>
                        <td class="border border-black text-left pl-1">Cooling System</td>
                        <td class="border border-black"></td>
                        <td class="border border-black"></td>
                        <td class="border border-black"></td>
                        <td class="border border-black"></td>
                        <td class="border border-black"></td>
                        <td class="border border-black"></td>
                    </tr>
                </tbody>
            </table>
            <div class="w-full max-w-4xl mx-auto pt-4 mb-3">
                <label for="note" class="block mb-1 text-sm font-medium text-black">Remark:</label>
                <div id="note" readonly
                    class="w-full px-3 py-2 overflow-hidden text-sm leading-relaxed border border-black rounded-md resize-none">
                    test
                </div>
            </div>

            {{-- TTD Section --}}
            <table class="w-full border border-black text-sm">
                <thead>
                    <tr class="bg-gray-200 text-center font-semibold">
                        <th class="border border-black w-32"></th>
                        <th class="border border-black py-2">Checked by</th>
                        <th class="border border-black py-2">Approved by</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-black font-medium px-2 py-2">Name</td>
                        <td class="border border-black px-2 py-2">
                            <input type="text" placeholder="Name..." class="w-full px-2 py-1 border border-gray-300 rounded">
                        </td>
                        <td class="border border-black px-2 py-2">
                            <input type="text" placeholder="Name..." class="w-full px-2 py-1 border border-gray-300 rounded">
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-black font-medium px-2 py-2">Signature</td>
                        <td class="border border-black px-2 py-4">
                            <div class="h-20 border border-gray-300 rounded"></div>
                        </td>
                        <td class="border border-black px-2 py-4">
                            <div class="h-20 border border-gray-300 rounded"></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-black font-medium px-2 py-2">Date</td>
                        <td class="border border-black px-2 py-2">
                            <input type="date" class="w-full px-2 py-1 border border-gray-300 rounded">
                        </td>
                        <td class="border border-black px-2 py-2">
                            <input type="date" class="w-full px-2 py-1 border border-gray-300 rounded">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection