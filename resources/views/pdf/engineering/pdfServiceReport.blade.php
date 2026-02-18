@extends('pdf.layout.layout')
@section('title', 'Service Report')
@section('content')
    <table style="width:100%; border-collapse:collapse; table-layout:fixed;">
        <tr>
            <td rowspan="4" style="width:15%; text-align:center; vertical-align:middle; border:0.5px solid #000;">
                {{-- <img src="{{ public_path('asset/logo.png') }}" style="height:55px;"> --}}
                @if ($logoBase64)
                    <img src="{{ $logoBase64 }}" style="height:55px;">
                @endif
            </td>

            <td colspan="4" style="text-align:center; font-weight:bold; border:0.5px solid #000;">
                PT. QLab Kinarya Sentosa
            </td>
        </tr>

        <tr>
            <td rowspan="3" colspan="3"
                style="text-align:center; font-size:16px; font-weight:bold; border:0.5px solid #000; vertical-align:middle;">
                SERVICE REPORT
            </td>

            <td style="border:0.5px solid #000;">
                No. Dokumen : -
            </td>
        </tr>

        <tr>
            <td style="border:0.5px solid #000;">
                Tanggal Rilis : -
            </td>
        </tr>

        <tr>
            <td style="border:0.5px solid #000;">
                Revisi : -
            </td>
        </tr>
    </table>

    <div style="height:10px;"></div>

    <div class="">
        <table class="">
            <tr>
                <td class="label">Form No</td>
                <td> : {{ $serviceReport->form_no }}</td>
            </tr>
            <tr>
                <td class="label">Date</td>
                <td> : {{ \Carbon\Carbon::parse($serviceReport->tanggal)->format('d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Who Complaint</td>
                <td> : {{ $serviceReport->name_complaint }}</td>
            </tr>
            <tr>
                <td class="label">Company Name</td>
                <td> : {{ $serviceReport->company_name }}</td>
            </tr>
            <tr>
                <td class="label">Address</td>
                <td> : {{ $serviceReport->address }}</td>
            </tr>
            <tr>
                <td class="label">Phone Number</td>
                <td> : {{ $serviceReport->phone_number }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Product List</div>
        <table class="table">
            <thead>
                <tr>
                    <th style="width:5%">No</th>
                    <th>Unit Name</th>
                    <th>Type / Model</th>
                    <th>Serial Number</th>
                    <th style="width:15%">Warranty</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($serviceReport->produkServices as $i => $item)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td>{{ $item->produk_name }}</td>
                        <td>{{ $item->type }}</td>
                        <td>{{ $item->serial_number }}</td>
                        <td class="text-center">{{ $item->status_warranty ? 'Yes' : 'No' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Service Category</div>
        @foreach (['installation', 'maintenance', 'repair', 'consultation'] as $cat)
            <div class="checkbox">
                [{{ in_array($cat, $serviceReport->service_category) ? '✔' : ' ' }}]
                {{ ucfirst($cat) }}
            </div>
        @endforeach
    </div>

    <div class="section">
        <div class="section-title">Remark</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Remark</th>
                    <th>Action & Taken Item</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($serviceReport->details as $detail)
                    <tr>
                        <td>{{ $detail->remark }}</td>
                        <td>{{ $detail->service_status }}</td>
                        <td>{{ $detail->taken_item }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <table style="width:100%; border-collapse:collapse;">
            <tr>
                <td style="width:50%; vertical-align:top; padding-right:10px;">
                    <div class="section-title">Action</div>
                    @foreach (['cleaning', 'installation', 'repairing', 'maintenance', 'replacing', 'other'] as $action)
                        <div>
                            [{{ in_array($action, $serviceReport->actions ?? []) ? '✔' : ' ' }}]
                            {{ ucwords($action) }}
                        </div>
                    @endforeach
                </td>

                <td style="width:50%; vertical-align:top; padding-left:10px;">
                    <div class="section-title">Service Field</div>
                    @foreach ($serviceReport->service_fields ?? [] as $field)
                        <div>
                            [✔] {{ ucwords(str_replace('_', ' ', $field)) }}
                        </div>
                    @endforeach
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Attachment</div>
        <table class="table">
            <tr>
                @foreach ($serviceReport->details as $detail)
                    @foreach ($detail->upload_file as $img)
                        <td class="image-box">
                            <img src="{{ public_path('storage/' . $img) }}">
                        </td>
                    @endforeach
                @endforeach
            </tr>
        </table>
    </div>

    <div class="section">
        <table class="table">
            <thead>
                <tr>
                    <th></th>
                    <th>Service By</th>
                    <th>Approved By</th>
                </tr>
            </thead>
            {{-- <tbody>
                <tr>
                    <td>Name</td>
                    <td>{{ $serviceReport->pic->checkedBy->name }}</td>
                    <td>{{ $serviceReport->pic->approvedBy->name }}</td>
                </tr>
                <tr>
                    <td>Signature</td>
                    <td class="signature-box">
                        @if ($serviceReport->pic->checked_signature)
                            <img src="{{ public_path('storage/' . $serviceReport->pic->checked_signature) }}">
                        @endif
                    </td>
                    <td class="signature-box">
                        @if ($serviceReport->pic->approved_signature)
                            <img src="{{ public_path('storage/' . $serviceReport->pic->approved_signature) }}">
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Date</td>
                    <td>{{ $serviceReport->pic->checked_date }}</td>
                    <td>{{ $serviceReport->pic->approved_date }}</td>
                </tr>
            </tbody> --}}
            <tbody>
                <tr>
                    <td>Name</td>
                    <td>{{ $serviceReport->pic->checkedBy->name ?? '-' }}</td>
                    <td>
                        {{ optional($serviceReport->pic)->approved_name }}
                    </td>
                </tr>

                <tr>
                    <td>Signature</td>

                    {{-- CHECKED (pakai TTD image) --}}
                    <td class="signature-box">
                        @if ($serviceReport->pic->checked_signature)
                            <img src="{{ public_path('storage/' . $serviceReport->pic->checked_signature) }}"
                                style="height:60px;">
                        @endif
                    </td>

                    {{-- APPROVED (kosong untuk tanda tangan basah) --}}
                    <td class="signature-box">
                        @if ($serviceReport->pic?->approved_signature)
                            <img src="{{ public_path('storage/' . $serviceReport->pic->approved_signature) }}">
                        @endif
                    </td>
                </tr>

                <tr>
                    <td>Date</td>
                    <td>{{ $serviceReport->pic->checked_date ?? '-' }}</td>
                    {{-- <td>{{ $serviceReport->pic->approved_date ?? '-' }}</td> --}}
                    <td>
                        @if ($serviceReport->pic?->approved_date)
                            {{ \Carbon\Carbon::parse($serviceReport->pic->approved_date)->translatedFormat('d F Y') }}
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
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
        page-break-inside: avoid;
    }

    .section-title {
        font-weight: bold;
        margin-bottom: 6px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .table th,
    .table td {
        border: 0.5px solid #000;
        padding: 4px;
        word-wrap: break-word;
    }

    .table th {
        background: #f2f2f2;
        text-align: center;
    }

    .label {
        width: 30%;
        font-weight: bold;
    }

    .text-center {
        text-align: center;
    }

    .image-box {
        border: 0.5px solid #000;
        height: 150px;
        text-align: center;
    }

    .image-box img {
        max-width: 100%;
        max-height: 140px;
    }

    .signature-box {
        height: 90px;
        text-align: center;
    }

    .signature-box img {
        max-height: 80px;
    }
</style>
