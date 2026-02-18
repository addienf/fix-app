@extends('pdf.layout.layout')
@section('title', 'Permintaan Sparepart dan Alat Kerja')
@section('content')

    {{-- ================= HEADER ================= --}}
    <table style="width:100%; border-collapse:collapse; table-layout:fixed;">
        <tr>
            <td rowspan="4" style="width:15%; text-align:center; vertical-align:middle; border:0.5px solid #000;">
                {{-- <img src="{{ public_path('asset/logo.png') }}" style="height:55px;"> --}}
                @if ($logoBase64)
                    <img src="{{ $logoBase64 }}" style="height:55px;">
                @endif
            </td>

            <td colspan="4" style="text-align:center; font-weight:bold; font-size:11px; border:0.5px solid #000;">
                PT. QLab Kinarya Sentosa
            </td>
        </tr>

        <tr>
            <td rowspan="3" colspan="2"
                style="text-align:center; font-size:16px; font-weight:bold; border:0.5px solid #000; vertical-align:middle;">
                Formulir Permintaan <br> Sparepart dan Alat Kerja
            </td>

            <td style="border:0.5px solid #000; padding-left:8px;">
                No. Dokumen
            </td>

            <td style="border:0.5px solid #000; text-align:center;">
                FO-QKS-ENG-01-10
            </td>
        </tr>

        <tr>
            <td style="border:0.5px solid #000; padding-left:8px;">
                Tanggal Rilis
            </td>

            <td style="border:0.5px solid #000; text-align:center;">
                17 Juni 2025
            </td>
        </tr>

        <tr>
            <td style="border:0.5px solid #000; padding-left:8px;">
                Revisi
            </td>

            <td style="border:0.5px solid #000; text-align:center;">
                01
            </td>
        </tr>
    </table>

    <div style="height:10px;"></div>

    {{-- ================= IDENTITAS ================= --}}
    <div class="section">
        <table style="width:100%;">
            <tr>
                <td style="width:20%;">No Surat</td>
                <td>: {{ $sparepart->no_surat }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>: {{ \Carbon\Carbon::parse($sparepart->tanggal)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td>Dari</td>
                <td>: {{ $sparepart->dari }}</td>
            </tr>
            <tr>
                <td>Kepada</td>
                <td>: {{ $sparepart->kepada }}</td>
            </tr>
        </table>
    </div>

    {{-- ================= PEMBUKA ================= --}}
    <p>
        Dengan hormat,<br><br>
        Berdasarkan SPK No {{ $sparepart->spkService->no_spk_service }},
        mohon bantuan untuk memenuhi kebutuhan sparepart dan alat kerja
        dengan rincian sebagai berikut:
    </p>

    {{-- ================= TABEL BARANG ================= --}}
    <table class="table">
        <thead>
            <tr>
                <th style="width:5%;">No</th>
                <th style="width:25%;">Nama Barang</th>
                <th style="width:25%;">Spesifikasi</th>
                <th style="width:10%;">Jumlah</th>
                <th style="width:35%;">Keperluan Barang</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sparepart->details as $i => $item)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $item['bahan_baku'] }}</td>
                    <td>{{ $item['spesifikasi'] }}</td>
                    <td class="text-center">{{ $item['jumlah'] }}</td>
                    <td>{{ $item['keperluan_barang'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ================= PENUTUP ================= --}}
    <p style="margin-top:10px;">
        Demikian permintaan ini disampaikan, atas perhatian dan kerjasamanya
        diucapkan terima kasih.
    </p>

    {{-- ================= TANDA TANGAN ================= --}}
    <table style="width:100%; margin-top:30px;">
        <tr>
            <td style="width:33%; text-align:center;">
                Dibuat Oleh,<br><br>
                @if ($sparepart->pic->dibuat_signature)
                    <img src="{{ public_path('storage/' . $sparepart->pic->dibuat_signature) }}" style="height:60px;"><br>
                @endif
                ( {{ $sparepart->pic->dibuatName->name }} )
            </td>

            <td style="width:33%; text-align:center;">
                Diketahui Oleh,<br><br>
                @if ($sparepart->pic->diketahui_signature)
                    <img src="{{ public_path('storage/' . $sparepart->pic->diketahui_signature) }}"
                        style="height:60px;"><br>
                @endif
                ( {{ $sparepart->pic->diketahuiName->name }} )
            </td>

            <td style="width:33%; text-align:center;">
                Diserahkan Kepada,<br><br>
                @if ($sparepart->pic->diserahkan_signature)
                    <img src="{{ public_path('storage/' . $sparepart->pic->diserahkan_signature) }}"
                        style="height:60px;"><br>
                @endif
                ( {{ $sparepart->pic->diserahkanName->name }} )
            </td>
        </tr>
    </table>

@endsection

<style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 10px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    td,
    th {
        vertical-align: top;
    }

    .header-table {
        border: 1px solid #000;
    }

    .header-logo {
        width: 15%;
        text-align: center;
        vertical-align: middle;
        border-right: 1px solid #000;
    }

    .header-logo img {
        height: 55px;
    }

    .header-company {
        width: 55%;
        text-align: center;
        font-weight: bold;
        border-bottom: 1px solid #000;
    }

    .header-title {
        text-align: center;
        font-size: 16px;
        font-weight: bold;
        padding: 10px 0;
        border-bottom: 1px solid #000;
    }

    .header-spacer {
        height: 18px;
    }

    .header-doc {
        width: 30%;
        padding: 0;
        border-left: 1px solid #000;
    }

    .doc-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 10px;
    }

    .doc-table td {
        padding: 4px;
        border-bottom: 0.5px solid #000;
    }

    .doc-table tr:last-child td {
        border-bottom: none;
    }

    .section {
        margin-bottom: 12px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th,
    .table td {
        border: 0.5px solid #000;
        padding: 4px;
    }

    .table th {
        text-align: center;
    }

    .text-center {
        text-align: center;
    }
</style>
