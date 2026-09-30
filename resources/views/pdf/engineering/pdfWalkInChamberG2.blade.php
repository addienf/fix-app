@extends('pdf.layout.engineering')
@section('title', 'Regular Maintenance Checklist - Walk In Chamber G2')
@section('pdf-header')
    <table style="width:100%; border-collapse:collapse; margin-bottom:12px;">
        <tr>
            <!-- LOGO -->
            <td rowspan="2" style="width:110px; text-align:center; vertical-align:middle;">
                {{-- <img src="{{ public_path('asset/logo.png') }}" style="height:55px;"> --}}
                @if ($logoBase64)
                    <img src="{{ $logoBase64 }}" style="height:55px;">
                @endif
            </td>

            <!-- PROJECT -->
            <td style="padding:4px 8px; vertical-align:top;">
                <strong>Project : {{ $walkinG2->project }}</strong>
            </td>

            <!-- DOC INFO -->
            <td rowspan="2" style="width:110px; text-align:center; vertical-align:top; font-size:10px; line-height:1.4;">
                FO-QKS-ENG-01-11<br>
                Rev. 02<br>
                01 July 2025
            </td>
        </tr>

        <tr>
            <!-- CLIENT -->
            <td style="padding:4px 8px; vertical-align:top;">
                <strong>Client : {{ $walkinG2->spkService->perusahaan }}</strong>
            </td>
        </tr>
    </table>
@endsection
@section('content')

    @php
        $rawDetails = $walkinG2->detail->checklist ?? [];
        $details = is_string($rawDetails) ? json_decode($rawDetails, true) : $rawDetails;
        $rowNumber = 1;
    @endphp

    <h2 style="text-align:center; font-weight:bold; margin-bottom:12px;">
        REGULAR MAINTENANCE CHECKLIST – WALK-IN CHAMBER G2
    </h2>

    <p style="text-align:center; font-weight:bold; margin-bottom:16px;">
        WTC Name Tag / No : {{ $walkinG2->tag_no }}
    </p>

    <br>

    <table class="text-xs">
        <thead>
            <tr>
                <th rowspan="2" style="width:5%; text-align:center; vertical-align:middle;">
                    NO
                </th>
                <th rowspan="2" style="width:30%; text-align:center; vertical-align:middle;">
                    ITEM TO CHECK
                </th>
                <th rowspan="2" style="width:12%; text-align:center; vertical-align:middle;">
                    BEFORE
                </th>
                <th rowspan="2" style="width:12%; text-align:center; vertical-align:middle;">
                    AFTER
                </th>
                <th colspan="3" style="width:15%; text-align:center;">
                    ACCEPTED
                </th>
                <th rowspan="2" style="width:14%; text-align:center; vertical-align:middle;">
                    REMARK
                </th>
            </tr>

            <tr>
                <th style="text-align:center;">YES</th>
                <th style="text-align:center;">NO</th>
                <th style="text-align:center;">NA</th>
            </tr>
        </thead>

        @foreach ($details as $group)
            <tbody style="page-break-inside: avoid;">

                <!-- MAIN PART -->
                <tr class="main-part">
                    <td class="center">
                        {{ $rowNumber++ }}
                    </td>
                    <td colspan="7">
                        {{ $group['mainPart'] ?? '-' }}
                    </td>
                </tr>

                @foreach ($group['parts'] as $part)
                    @if (($part['type'] ?? 'check') === 'info')
                        <tr>
                            <td></td>
                            <td colspan="7" style="font-style:italic;">
                                {{ $part['part'] }}
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td class="center"></td>
                            <td>{{ $part['part'] }}</td>
                            <td class="center">
                                {!! nl2br(e($part['before'] ?? '-')) !!}
                            </td>
                            <td class="center">
                                {!! nl2br(e($part['after'] ?? '-')) !!}
                            </td>
                            <td class="center">{{ $part['accepted'] === 'yes' ? '✔' : '' }}</td>
                            <td class="center">{{ $part['accepted'] === 'no' ? '✔' : '' }}</td>
                            <td class="center">{{ $part['accepted'] === 'na' ? '✔' : '' }}</td>
                            <td>{{ $part['remark'] ?? '-' }}</td>
                        </tr>
                    @endif
                @endforeach

                @php
                    $extras = collect($group['extra'] ?? [])->filter(
                        fn($e) => !empty($e['part']) ||
                            !empty($e['before']) ||
                            !empty($e['after']) ||
                            !empty($e['accepted']) ||
                            !empty($e['remark']),
                    );
                @endphp

                @if ($extras->count())
                    @foreach ($extras as $extra)
                        <tr style="background-color:#fafafa;">
                            <td class="center">&nbsp;</td>
                            <td>{{ $extra['part'] ?? '-' }}</td>
                            <td class="center">
                                {!! nl2br(e($extra['before'] ?? '-')) !!}
                            </td>
                            <td class="center">
                                {!! nl2br(e($extra['after'] ?? '-')) !!}
                            </td>
                            <td class="center">{{ ($extra['accepted'] ?? '') === 'yes' ? '✔' : '' }}</td>
                            <td class="center">{{ ($extra['accepted'] ?? '') === 'no' ? '✔' : '' }}</td>
                            <td class="center">{{ ($extra['accepted'] ?? '') === 'na' ? '✔' : '' }}</td>
                            <td>{{ $extra['remark'] ?? '-' }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        @endforeach
    </table>

    <br>

    <p class="font-bold">Remark :</p>
    <table>
        <tr>
            <td style="height:60px">
                {{ trim($walkinG2->remarks) }}
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
                <td>{{ $walkinG2->pic?->checked_name ?? '-' }}</td>
                <td>{{ optional(value: $walkinG2->pic)->approved_name }}</td>
            </tr>
            <tr>
                <td class="font-bold text-center align-middle">
                    Signature
                </td>
                <td class="sign-cell">
                    <div class="sign-box">
                        <img src="{{ public_path('storage/' . $walkinG2->pic->checked_signature) }}">
                    </div>
                </td>
                <td class="sign-cell">
                    <div class="sign-box">
                        @if ($walkinG2->pic?->approved_signature)
                            <img src="{{ public_path('storage/' . $walkinG2->pic->approved_signature) }}">
                        @endif
                    </div>
                </td>
            </tr>
            <tr>
                <td class="font-bold">Date</td>
                <td>{{ \Carbon\Carbon::parse($walkinG2->pic->checked_date)->translatedFormat('d F Y') }}</td>
                <td>
                    @if ($walkinG2->pic?->approved_date)
                        {{ \Carbon\Carbon::parse($walkinG2->pic->approved_date)->translatedFormat('d F Y') }}
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
@endsection

<style>
    .main-part {
        font-weight: bold;
        background-color: #f2f2f2;
    }

    .main-part td {
        vertical-align: middle;
    }

    .center {
        text-align: center;
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
