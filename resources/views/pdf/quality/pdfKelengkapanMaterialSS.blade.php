@extends ('pdf.layout.layout')
@section('title', 'Kelengkapan Material Stainless Steel PDF')
@section('content')
    <table>
        <tr>
            <td rowspan="4" class="text-center" style="width:80px;">
                @if ($logoBase64)
                    <img src="{{ $logoBase64 }}" style="height:55px;">
                @endif
            </td>

            <td colspan="4" class="text-center" style="font-weight:bold;">
                PT. QLab Kinarya Sentosa
            </td>
        </tr>

        <tr>
            <td rowspan="3" colspan="2" class="judul">
                {!! nl2br("Formulir Cutting & Bending\nFor Production Checklist") !!}
            </td>

            <td>No. Dokumen</td>
            <td>FO-QKS-QA-01-03</td>
        </tr>

        <tr>
            <td>Tanggal Rilis</td>
            <td>22 Mei 2025</td>
        </tr>

        <tr>
            <td>Revisi</td>
            <td>01</td>
        </tr>
    </table>

    <br>

    <table class="no-border">
        <tr>
            <td style="width:180px;">No SPK Produksi</td>
            <td>: {{ $kelengkapan->spkQC->spkMarketing->no_spk }}</td>
        </tr>
    </table>

    <br>

    <table class="no-border">
        <tr>
            <td style="width:180px;"><b>Chamber Identification</b></td>
        </tr>
    </table>

    <table class="no-border">
        <tr>
            <td style="width:180px;">Type Model</td>
            <td>: {{ $kelengkapan->tipe }}</td>
        </tr>
        <tr>
            <td>Ref Document</td>
            <td>: {{ $kelengkapan->ref_document }}</td>
        </tr>
    </table>

    <br>

    @php
        $rawDetails = $kelengkapan->detail->details ?? [];

        $details = is_string($rawDetails) ? json_decode($rawDetails, true) : $rawDetails;

        $fields = collect($details)
            ->map(function ($item) {
                return [
                    'item' => $item['part'] ?? '',
                    'spec' => '-',
                    'result' => $item['result'] ?? null,
                    'remark' => $item['select'] ?? '',
                ];
            })
            ->toArray();

        $remarkLabels = [
            'ok' => 'OK',
            'h' => 'Hold',
            'r' => 'Repaired',
        ];
    @endphp

    <table class="table-main">
        <thead>
            <tr>
                <th rowspan="2" style="width:5%;">No</th>
                <th rowspan="2">Part</th>
                <th rowspan="2">Order Number</th>
                <th colspan="2">Result</th>
                <th rowspan="2">Remark</th>
            </tr>
            <tr>
                <th style="width:10%;">Pass</th>
                <th style="width:10%;">Fail</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($fields as $i => $field)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $field['item'] }}</td>
                    <td class="text-center">{{ $field['spec'] }}</td>
                    <td class="text-center">
                        {{ $field['result'] == '1' ? '✔' : '' }}
                    </td>

                    <td class="text-center">
                        {{ $field['result'] == '0' ? '✖' : '' }}
                    </td>

                    <td class="text-center">
                        {{ $remarkLabels[strtolower($field['remark'])] ?? ucfirst($field['remark']) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="no-border">
        <tr>
            <td style="font-size:10px; font-weight: bold;">
                Remarks : OK = Ok &nbsp;&nbsp;&nbsp; H = Hold &nbsp;&nbsp;&nbsp; R = Repaired
            </td>
        </tr>
    </table>

    <br>

    <div style="margin-top: 2px; margin-bottom: 16px;">
        <label style="display: block; margin-bottom: 4px; font-weight: bold;">Note</label>

        <div style="width: 100%; padding: 8px; border: 1px solid black; border-radius: 4px;">
            {{ $kelengkapan->note }}
        </div>
    </div>

    <br><br>

    @php
        $roles = [
            'Inspected By' => [
                'name' => $kelengkapan->pic->inspectedName->name ?? '-',
                'signature' => $kelengkapan->pic->inspected_signature ?? null,
                'date' => $kelengkapan->pic->inspected_date ?? null,
            ],
            'Accepted By' => [
                'name' => $kelengkapan->pic->acceptedName->name ?? '-',
                'signature' => $kelengkapan->pic->accepted_signature ?? null,
                'date' => $kelengkapan->pic->accepted_date ?? null,
            ],
            'Approved By' => [
                'name' => $kelengkapan->pic->approvedName->name ?? '-',
                'signature' => $kelengkapan->pic->approved_signature ?? null,
                'date' => $kelengkapan->pic->approved_date ?? null,
            ],
        ];
    @endphp

    <table class="" style="text-align:center;">
        <tr>
            <td>Inspected By</td>
            <td>Accepted By</td>
            <td>Approved By</td>
        </tr>

        <tr>
            @foreach ($roles as $data)
                <td style="height:70px;">
                    <div class="signature-box">
                        @if ($data['signature'])
                            <img src="{{ public_path('storage/' . $data['signature']) }}">
                        @endif
                    </div>
                </td>
            @endforeach
        </tr>

        <tr>
            @foreach ($roles as $data)
                <td><b>{{ $data['name'] }}</b></td>
            @endforeach
        </tr>

        <tr>
            @foreach ($roles as $data)
                <td>
                    {{ $data['date'] ? \Carbon\Carbon::parse($data['date'])->format('d F Y') : '-' }}
                </td>
            @endforeach
        </tr>
    </table>

@endsection

<style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 11px;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    th,
    td {
        border: 1px solid #000;
        padding: 4px;
    }

    .no-border td {
        border: none !important;
    }

    .text-center {
        text-align: center;
    }

    .judul {
        font-size: 14px;
        font-weight: bold;
        text-align: center;
    }

    .table-main td {
        height: 25px;
    }

    .checkbox {
        width: 12px;
        height: 12px;
        border: 1px solid black;
        margin: auto;
        text-align: center;
        line-height: 12px;
        font-size: 10px;
    }

    .signature-box {
        height: 70px;
        position: relative;
    }

    .signature-box img {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 120%;
        max-height: 70px;
    }
</style>
