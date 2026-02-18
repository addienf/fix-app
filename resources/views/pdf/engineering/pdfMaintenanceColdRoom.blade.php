@extends('pdf.layout.engineering')
@section('title', 'Regular Maintenance Checklist - Qlab Cold Room PDF')
@section('pdf-header')
    <table style="width:100%; border-collapse:collapse; margin-bottom:12px;">
        <tr>
            <td rowspan="2" style="width:110px; text-align:center; vertical-align:middle;">
                {{-- <img src="{{ public_path('asset/logo.png') }}" style="height:55px;"> --}}
                @if ($logoBase64)
                    <img src="{{ $logoBase64 }}" style="height:55px;">
                @endif
            </td>

            <td style="padding:4px 8px; vertical-align:top;">
                <strong>Project :</strong>
            </td>

            <td rowspan="2" style="width:110px; text-align:center; vertical-align:top; font-size:10px; line-height:1.4;">
                FO-QKS-ENG-01-07<br>
                Rev. 00<br>
                12 Maret 2025
            </td>
        </tr>

        <tr>
            <td style="padding:4px 8px; vertical-align:top;">
                Client
            </td>
        </tr>
    </table>
@endsection
@section('content')

    @php
        $rawDetails = $cold->detail->checklist ?? [];
        $details = is_string($rawDetails) ? json_decode($rawDetails, true) : $rawDetails;
        $rowNumber = 1;
    @endphp

    <h2 style="text-align:center; font-weight:bold; margin-bottom:12px;">
        MAINTENANCE CHECKLIST
    </h2>

    <p style="text-align:center; font-weight:bold; margin-bottom:16px;">
        Name / Tag No : {{ $cold->tag_no }}
    </p>

    <br>

    <table class="checklist-table">
        <thead>
            <tr>
                <th rowspan="2" class="col-no">NO</th>
                <th rowspan="2" class="col-item">ITEM TO CHECK</th>
                <th colspan="3" class="col-accepted">ACCEPTED</th>
                <th rowspan="2" class="col-remark">REMARK</th>
            </tr>
            <tr>
                <th class="col-acc">YES</th>
                <th class="col-acc">NO</th>
                <th class="col-acc">NA</th>
            </tr>
        </thead>

        @foreach ($details as $group)
            <tbody style="page-break-inside: avoid;">
                <!-- MAIN PART -->
                <tr class="main-part">
                    <td class="center">{{ $rowNumber++ }}</td>
                    <td>{{ $group['mainPart'] }}</td>
                    <td class="center">{{ ($group['accepted'] ?? '') === 'yes' ? '✔' : '' }}</td>
                    <td class="center">{{ ($group['accepted'] ?? '') === 'no' ? '✔' : '' }}</td>
                    <td class="center">{{ ($group['accepted'] ?? '') === 'na' ? '✔' : '' }}</td>
                    <td>{{ $group['remark'] ?? '-' }}</td>
                </tr>

                @foreach ($group['parts'] as $part)
                    <tr>
                        <td></td>
                        <td>{{ $part['part'] }}</td>
                        <td class="center">{{ ($part['accepted'] ?? '') === 'yes' ? '✔' : '' }}</td>
                        <td class="center">{{ ($part['accepted'] ?? '') === 'no' ? '✔' : '' }}</td>
                        <td class="center">{{ ($part['accepted'] ?? '') === 'na' ? '✔' : '' }}</td>
                        <td>{{ $part['remark'] ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        @endforeach
    </table>

    <br>

    <p class="font-bold">Remark :</p>
    <table>
        <tr>
            <td style="height:60px">
                {{ trim($cold->remarks) }}
            </td>
        </tr>
    </table>

    <br>

    <table>
        <thead>
            <tr>
                <th></th>
                <th>Checked By</th>
                <th>Approved By</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="font-bold">Name</td>
                <td>{{ $cold->pic?->checkedBy?->name ?? '-' }}</td>
                <td>{{ optional(value: $cold->pic)->approved_name }}</td>
            </tr>
            <tr>
                <td class="font-bold text-center align-middle">
                    Signature
                </td>
                <td class="sign-cell">
                    <div class="sign-box">
                        <img src="{{ public_path('storage/' . $cold->pic->checked_signature) }}">
                    </div>
                </td>
                <td class="sign-cell">
                    <div class="sign-box">
                        @if ($cold->pic?->approved_signature)
                            <img src="{{ public_path('storage/' . $cold->pic->approved_signature) }}">
                        @endif
                    </div>
                </td>
            </tr>
            <tr>
                <td class="font-bold">Date</td>
                <td>{{ \Carbon\Carbon::parse($cold->pic->checked_date)->translatedFormat('d F Y') }}
                </td>
                <td>
                    @if ($cold->pic?->approved_date)
                        {{ \Carbon\Carbon::parse($cold->pic->approved_date)->translatedFormat('d F Y') }}
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
@endsection

<style>
    .checklist-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        /* WAJIB biar width kepake */
        font-size: 11px;
    }

    .checklist-table th,
    .checklist-table td {
        border: 1px solid #666;
        padding: 4px 6px;
        vertical-align: middle;
    }

    /* ===== LEBAR KOLOM ===== */
    .col-no {
        width: 5%;
    }

    .col-item {
        width: 45%;
    }

    .col-acc {
        width: 6%;
    }

    .col-accepted {
        width: 18%;
        /* total YES+NO+NA */
    }

    .col-remark {
        width: 14%;
    }

    .center {
        text-align: center;
    }

    .main-part {
        background-color: #ffffff;
        /* putih sesuai request */
        font-weight: bold;
    }

    .main-part td {
        padding-top: 6px;
        padding-bottom: 6px;
    }

    .sign-cell {
        height: 90px;
        text-align: center;
        vertical-align: middle;
    }

    .sign-box {
        height: 60px;
        overflow: hidden;
        margin: auto;
    }

    .sign-box img {
        height: 60px;
        width: auto;
    }
</style>
