@extends('pdf.layout.layout')
@section('title', 'Incoming Material PDF')

@section('content')
    <style>
        @page {
            size: A4 landscape;
            margin: 8mm 12mm 8mm 12mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            color: #111;
            line-height: 1.35;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }

        .page-border {
            border: 1.5px solid #000;
            padding: 10px 14px;
            width: 100%;
        }

        /* ================= KOP TABLE ================= */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .kop-table td {
            border: 1px solid #000;
            padding: 0;
            vertical-align: middle;
        }

        .kop-logo {
            width: 18%;
            text-align: center;
            padding: 6px 4px !important;
        }

        .kop-logo img {
            max-height: 52px;
            max-width: 130px;
        }

        .kop-company {
            text-align: center;
            font-weight: bold;
            font-size: 13pt;
            letter-spacing: 0.5px;
            padding: 5px !important;
            height: 22px;
        }

        .kop-title {
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            padding: 6px !important;
        }

        /* ================= META SECTION ================= */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .info-table td {
            padding: 2.5px 0;
            vertical-align: top;
            font-size: 10pt;
        }

        .label-col {
            font-weight: bold;
            color: #222;
        }

        /* ================= ITEM TABLE ================= */
        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }

        .item-table th,
        .item-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            font-size: 10pt;
        }

        .item-table th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-align: center;
            color: #000;
        }

        .empty-row td {
            height: 23px;
        }

        /* ================= NOTE ================= */
        .note-text {
            font-size: 9pt;
            font-style: italic;
            margin-top: 4px;
            margin-bottom: 14px;
            color: #333;
        }

        /* ================= SIGNATURE SECTION ================= */
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        .signature-table td {
            text-align: center;
            vertical-align: top;
            width: 50%;
        }

        .sig-title {
            font-weight: bold;
            font-size: 11pt;
            margin-bottom: 4px;
            color: #111;
        }

        .sig-container {
            width: 200px;
            height: 55px;
            margin: 0 auto;
            text-align: center;
        }

        .sig-container img {
            width: 180px;
            height: 48px;
        }

        .sig-name {
            font-weight: bold;
            font-size: 11pt;
            text-decoration: underline;
            margin-top: 4px;
            color: #000;
        }

        .sig-role {
            font-size: 10pt;
            margin-top: 2px;
            color: #333;
        }
    </style>

    <div class="page-border">
        {{-- KOP HEADER --}}
        <table class="kop-table">
            <tr>
                <td rowspan="4" class="kop-logo">
                    @if ($logoBase64)
                        <img src="{{ $logoBase64 }}" alt="Logo QLab">
                    @else
                        <strong>QLab</strong>
                    @endif
                </td>
                <td colspan="3" class="kop-company">
                    PT. QLab Kinarya Sentosa
                </td>
            </tr>
            <tr>
                <td rowspan="3" class="kop-title">
                    Surat Perintah Kerja
                </td>
                <td style="width: 100px; padding: 3px 6px;">No. Dokumen</td>
                <td style="width: 140px; padding: 3px 6px;">: FO-QKS-MKT-01-03</td>
            </tr>
            <tr>
                <td style="padding: 3px 6px;">Tanggal Rilis</td>
                <td style="padding: 3px 6px;">: 12 Maret 2025</td>
            </tr>
            <tr>
                <td style="padding: 3px 6px;">Revisi</td>
                <td style="padding: 3px 6px;">: 0</td>
            </tr>
        </table>

        {{-- INFORMASI UMUM (METADATA) --}}
        <table class="info-table">
            <tr>
                <td style="width: 10%;" class="label-col">Tanggal</td>
                <td style="width: 38%;">: {{ \Carbon\Carbon::parse($spk_mkt->tanggal)->translatedFormat('d F Y') }}</td>
                <td style="width: 10%;" class="label-col">No. SPK</td>
                <td style="width: 42%;">: {{ $spk_mkt->no_spk }}</td>
            </tr>
            <tr>
                <td class="label-col">Customer</td>
                <td>: {{ $spk_mkt->spesifikasiProduct?->urs?->company?->name ?? '-' }}</td>
                <td class="label-col">Dari</td>
                <td>: {{ $spk_mkt->dari }}</td>
            </tr>
            <tr>
                <td class="label-col">No. Order</td>
                <td>: {{ $spk_mkt->no_order }}</td>
                <td class="label-col">Kepada</td>
                <td>: {{ $spk_mkt->kepada }}</td>
            </tr>
        </table>

        {{-- TABEL PRODUK --}}
        <table class="item-table">
            <thead>
                <tr>
                    <th style="width: 6%;">No.</th>
                    <th style="width: 38%;">Nama Produk yang Dipesan</th>
                    <th style="width: 16%;">Jumlah Pesanan</th>
                    <th style="width: 20%;">URS No.</th>
                    <th style="width: 20%;">Rencana Pengiriman</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $details = $spk_mkt->spesifikasiProduct?->details ?? collect();
                    $totalRows = 0;
                @endphp

                @forelse ($details as $item)
                    @php $totalRows++; @endphp
                    <tr>
                        <td style="text-align: center;">{{ $loop->iteration }}</td>
                        <td>{{ $item->product?->name ?? '-' }}</td>
                        <td style="text-align: center;">{{ $item->quantity }}</td>
                        <td style="text-align: center;">{{ $spk_mkt->spesifikasiProduct?->urs?->no_urs ?? '-' }}</td>
                        <td style="text-align: center;">
                            {{ $spk_mkt->spesifikasiProduct?->estimasi_pengiriman ? \Carbon\Carbon::parse($spk_mkt->spesifikasiProduct->estimasi_pengiriman)->translatedFormat('d F Y') : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; font-style: italic; color: #555;">Tidak ada data
                            produk</td>
                    </tr>
                @endforelse

                @for ($i = $totalRows; $i < 4; $i++)
                    <tr class="empty-row">
                        <td style="text-align: center;">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                @endfor
            </tbody>
        </table>

        {{-- CATATAN KAKI --}}
        <div class="note-text">
            *Salinan URS Wajib diberikan kepada Departemen Produksi
        </div>

        {{-- TANDA TANGAN --}}
        <table class="signature-table">
            <tr>
                <td>
                    <div class="sig-title">Yang Membuat,</div>
                    <div class="sig-container">
                        @if ($createSigBase64)
                            <img src="{{ $createSigBase64 }}" alt="Signature Pembuat">
                        @else
                            <div style="height: 48px;"></div>
                        @endif
                    </div>
                    <div class="sig-name">
                        {{ $spk_mkt->pic?->createName?->name ?? '( .................................... )' }}</div>
                    <div class="sig-role">( Marketing )</div>
                </td>
                <td>
                    <div class="sig-title">Yang Menerima,</div>
                    <div class="sig-container">
                        @if ($receiveSigBase64)
                            <img src="{{ $receiveSigBase64 }}" alt="Signature Penerima">
                        @else
                            <div style="height: 48px;"></div>
                        @endif
                    </div>
                    <div class="sig-name">
                        {{ $spk_mkt->pic?->receiveName?->name ?? '( .................................... )' }}</div>
                    <div class="sig-role">( Produksi )</div>
                </td>
            </tr>
        </table>
    </div>
@endsection
