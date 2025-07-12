@extends('pdf.layout.layout')

@section('content')

    <div id="export-area" class="p-2 bg-white text-black">
        <table class="w-full max-w-4xl mx-auto text-sm border border-black" style="border-collapse: collapse;">
            <tr>
                <!-- Logo -->
                <td rowspan="2" class="w-32 h-24 text-center border border-black align-middle">
                    <img src="{{ asset('asset/logo.png') }}" alt="Logo" class="object-contain h-16 mx-auto" />
                </td>

                <!-- Project & Client -->
                <td class="border border-black px-2 py-1 align-top">
                    <strong>Project :</strong>
                </td>

                <!-- Info Dokumen -->
                <td rowspan="2" class="w-48 border border-black align-top text-center text-sm leading-tight pt-2 p-2">
                    FO-QKS-ENG-01-12<br>
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
    </div>
    <div class = "w-full max-w-4xl pt-4 mx-auto text-sm"> 
        <h2 class = "text-xl font-bold text-center mb-4">REGULAR MAINTENANCE CHECK LIST - QLAB STABILITY CHAMBER G2 </h2>
        <div class="flex justify-center items-center gap-2 w-full my-6">
            <span class="text-center font-semibold">CTC Name Tag/No:</span>
            <input type="text" disabled class="px-2 py-1 text-sm bg-transparent border border-gray-300 rounded"
                value="PB-2025/00123" />
        </div>

    </div>
@endsection