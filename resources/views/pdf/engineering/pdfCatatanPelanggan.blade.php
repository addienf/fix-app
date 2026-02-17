@extends('pdf.layout.layout')
@section('title', 'Catatan Keluhan Pelanggan')
@section('content')

    @php
        use Carbon\Carbon;

        Carbon::setLocale('id');

        // ===== TANGGAL UTAMA =====
        $combined = Carbon::parse(
            Carbon::parse($complaint->tanggal)->format('Y-m-d') .
                ' ' .
                Carbon::parse($complaint->created_at)->format('H:i:s'),
        );

        // ===== TANGGAL PIC =====
        $combinedPIC = Carbon::parse(
            Carbon::parse($complaint->pic->reported_date)->format('Y-m-d') .
                ' ' .
                Carbon::parse($complaint->pic->created_at)->format('H:i:s'),
        );

        // ===== FORMAT =====
        $formattedDateTime = $combined->translatedFormat('l, d - m - Y H:i');
        $formattedDateTime2 = $combined->translatedFormat('d/m/Y H:i');
        $formattedDateTime3 = $combinedPIC->translatedFormat('l, d - m - Y H:i');

        // ===== FIELD CATEGORY =====
        $fieldCategories = [
            'controlling' => 'Controlling',
            'air_cooling_system' => 'Air Cooling System',
            'logging_system' => 'Logging System',
            'server_computer' => 'Server Computer',
            'networking' => 'Networking',
            'water_feeding_system' => 'Water Feeding System',
            'cooling_system' => 'Cooling System',
            'humidifier_system' => 'Humidifier System',
            'communication_system' => 'Communication System',
            'air_heating_system' => 'Air Heating System',
            'software' => 'Software',
            'other' => 'Other',
        ];
    @endphp

    <table style="width:100%; border-collapse:collapse; table-layout:fixed;">
        <tr>
            <td rowspan="4" style="width:15%; text-align:center; vertical-align:middle; border:0.5px solid #000;">
                {{-- <img src="{{ public_path('asset/logo.png') }}" style="height:55px;"> --}}
                @if ($logoBase64)
                    <img src="{{ $logoBase64 }}" style="height:55px;">
                @endif
            </td>

            <td colspan="4" style="text-align:center; font-weight:bold; font-size:13px; border:0.5px solid #000;">
                PT. QLab Kinarya Sentosa
            </td>
        </tr>

        <tr>
            <td rowspan="3" colspan="2"
                style="text-align:center; font-size:16px; font-weight:bold; border:0.5px solid #000; vertical-align:middle;">
                Formulir Catatan Keluhan Pelanggan
            </td>

            <td style="border:0.5px solid #000; padding-left:8px;">
                No. Dokumen
            </td>

            <td style="border:0.5px solid #000; text-align:center;">
                FO-QKS-CC-01-10
            </td>
        </tr>

        <tr>
            <td style="border:0.5px solid #000; padding-left:8px;">
                Tanggal Rilis
            </td>

            <td style="border:0.5px solid #000; text-align:center;">
                12 Maret 2025
            </td>
        </tr>

        <tr>
            <td style="border:0.5px solid #000; padding-left:8px;">
                Revisi
            </td>

            <td style="border:0.5px solid #000; text-align:center;">
                0
            </td>
        </tr>
    </table>

    <br>

    <div style="width:100%; margin-bottom:8px;">
        <div style="float:left; font-weight:bold;">
            Incoming Complaint Report
        </div>

        <div style="float:right; font-weight:bold;">
            {{ $formattedDateTime }}
        </div>

        <div style="clear:both;"></div>
    </div>

    <br>

    <div style="margin-bottom:8px;">
        <strong>Reported by</strong><br>
        Name: {{ $complaint->dari }}
    </div>

    <div style="margin-top:6px;">
        <strong>To</strong><br>
        Engineering Department<br>
        {{ $complaint->kepada }}
    </div>

    <br>

    <div style="margin-bottom:12px;">

        <div>
            <span style="display:inline-block; width:140px; font-weight:bold;">Form No</span>
            : {{ $complaint->form_no }}
        </div>

        <div>
            <span style="display:inline-block; width:140px; font-weight:bold;">Who Complaint</span>
            : {{ $complaint->name_complain }}
        </div>

        <div>
            <span style="display:inline-block; width:140px; font-weight:bold;">Company Name</span>
            : {{ optional($complaint->companies->first())->name }}
        </div>

        <div>
            <span style="display:inline-block; width:140px; font-weight:bold;">Department</span>
            : {{ $complaint->department }}
        </div>

        <div>
            <span style="display:inline-block; width:140px; font-weight:bold;">Phone Number</span>
            : {{ $complaint->phone_number }}
        </div>

        <div>
            <span style="display:inline-block; width:140px; font-weight:bold;">Received By</span>
            : {{ $complaint->receive_by }}
        </div>

    </div>

    <br>

    {{-- ================= DETAIL TABLE ================= --}}
    <table>
        <thead>
            <tr class="font-bold text-center">
                <td>No</td>
                <td>Unit Name</td>
                <td>Type / Model</td>
                <td>Warranty</td>
                <td>Field Category</td>
                <td>Description</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($complaint->details as $i => $data)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $data->unit_name }}</td>
                    <td>{{ $data->tipe_model }}</td>
                    <td class="text-center">{{ $data->status_warranty ? 'Yes' : 'No' }}</td>
                    <td>{{ $fieldCategories[$data->field_category] ?? '-' }}</td>
                    <td>{{ $data->deskripsi }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br>

    {{-- ================= REPORT DETAILS ================= --}}
    <div style="margin-top:12px;">

        <div>
            <strong>Reported Date</strong> : {{ $formattedDateTime3 }}
        </div>

        <div>
            <strong>Recorded By</strong> : {{ $complaint->pic->reportedBy->name }}
        </div>

        <div>
            <strong>Recorded Date</strong> : {{ $formattedDateTime2 }}
        </div>

    </div>

@endsection
<style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 14px;
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

    td,
    th {
        border: 0.5px solid #000;
        padding: 4px;
        vertical-align: top;
    }

    .text-center {
        text-align: center;
    }

    .font-bold {
        font-weight: bold;
    }

    .mb-4 {
        margin-bottom: 12px;
    }
</style>
