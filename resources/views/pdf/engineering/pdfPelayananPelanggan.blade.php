@extends('pdf.layout.layout')
@section('title', 'Formulir Permintaan Pelayanan Pelanggan')
@section('content')

    <div class="container">

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
                    Formulir Permintaan <br> Pelayanan Pelanggan
                </td>
                <td style="border:0.5px solid #000; padding-left:8px;">No. Dokumen</td>
                <td style="border:0.5px solid #000; text-align:center;">FO-QKS-CC-01-03</td>
            </tr>
            <tr>
                <td style="border:0.5px solid #000; padding-left:8px;">Tanggal Rilis</td>
                <td style="border:0.5px solid #000; text-align:center;">13 Oktober 2025</td>
            </tr>
            <tr>
                <td style="border:0.5px solid #000; padding-left:8px;">Revisi</td>
                <td style="border:0.5px solid #000; text-align:center;">021</td>
            </tr>
        </table>

        <br>

        <div class="font-bold">
            Complaint Form. No : {{ $pelayanan->no_form }}
        </div>

        {{-- ================= INFO UMUM ================= --}}
        <div class="section">
            <table class="form-table">
                <tr>
                    <td class="label">Tanggal</td>
                    <td>: {{ \Carbon\Carbon::parse($pelayanan->tanggal)->translatedFormat('d F Y') }}</td>
                </tr>
                <tr>
                    <td class="label">Alamat</td>
                    <td>: {{ $pelayanan->alamat }}</td>
                </tr>
                <tr>
                    <td class="label">Perusahaan</td>
                    <td>: {{ $pelayanan->perusahaan }}</td>
                </tr>
            </table>
        </div>

        {{-- ================= A. JENIS PERMINTAAN ================= --}}
        <div class="section-title">A. JENIS PERMINTAAN</div>

        @php
            $jenis = $pelayanan->jenis_permintaan ?? [];
        @endphp

        <div class="checkbox-grid">
            <label><input type="checkbox" disabled {{ in_array('pengiriman', $jenis) ? 'checked' : '' }}> Pengiriman</label>
            <label><input type="checkbox" disabled {{ in_array('service', $jenis) ? 'checked' : '' }}> Service</label>

            <label><input type="checkbox" disabled {{ in_array('perakitan', $jenis) ? 'checked' : '' }}> Perakitan</label>
            <label><input type="checkbox" disabled {{ in_array('maintenance', $jenis) ? 'checked' : '' }}>
                Maintenance</label>

            <label><input type="checkbox" disabled {{ in_array('kualifikasi', $jenis) ? 'checked' : '' }}>
                Kualifikasi</label>
            <label><input type="checkbox" disabled {{ in_array('kalibrasi', $jenis) ? 'checked' : '' }}> Kalibrasi</label>

            <label class="checkbox-lainnya">
                <input type="checkbox" disabled {{ in_array('lainnya', $jenis) ? 'checked' : '' }}>
                Lainnya :
                <span class="lainnya-text">{{ $pelayanan->jenis_permintaan_lainnya }}</span>
            </label>
        </div>

        {{-- ================= B. IDENTITAS ALAT ================= --}}
        <div class="section-title">B. IDENTITAS ALAT</div>

        <table class="table-bordered">
            <thead>
                <tr class="font-bold text-center">
                    <td class="col-no">No</td>
                    <td>Nama Alat</td>
                    <td>Tipe</td>
                    <td>Nomor Seri</td>
                    <td>Deskripsi Pekerjaan</td>
                    <td class="col-qty">Qty</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($pelayanan->details as $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $item->nama_alat }}</td>
                        <td>{{ $item->tipe }}</td>
                        <td>{{ $item->nomor_seri }}</td>
                        <td>{{ $item->deskripsi }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- ================= C. PELAKSANAAN ================= --}}
        <div class="section-title">C. PELAKSANAAN</div>

        <div class="section">
            <table class="form-table">
                <tr>
                    <td class="label">Tanggal Pelaksanaan</td>
                    <td>: {{ \Carbon\Carbon::parse($pelayanan->tanggal_pelaksanaan)->translatedFormat('d F Y') }}</td>
                </tr>
                <tr>
                    <td class="label">Tempat Pelaksanaan</td>
                    <td>: {{ $pelayanan->tempat_pelaksanaan }}</td>
                </tr>
                <tr>
                    <td class="label">PIC / No. Kontak / Dept</td>
                    <td>: {{ $pelayanan->no_kontak }}</td>
                </tr>
            </table>
        </div>

        <p class="closing-text">
            Demikian Permintaan Pelayanan ini dibuat agar dapat dipergunakan sebagai mestinya.
            Terima kasih.
        </p>

        {{-- ================= TANDA TANGAN ================= --}}
        @php
            $roles = [
                'Diketahui Oleh' => [
                    'name' => $pelayanan->pic->diketahuiName->name ?? '-',
                    'signature' => $pelayanan->pic->diketahui_signature ?? null,
                ],
                'Diterima Oleh' => [
                    'name' => $pelayanan->pic->diterimaName->name ?? '-',
                    'signature' => $pelayanan->pic->diterima_signature ?? null,
                ],
                'Dibuat Oleh' => [
                    'name' => $pelayanan->pic->dibuat_name ?? '-',
                    'signature' => $pelayanan->pic->dibuat_signature ?? null,
                ],
                // 'Dibuat Oleh' => [
                //     'name' => $pelayanan->pic->dibuatName->name ?? '-',
                //     'signature' => $pelayanan->pic->dibuat_signature ?? null,
                // ],
                // 'Diterima Oleh' => [
                //     'name' => $pelayanan->pic->diterimaName->name ?? '-',
                //     'signature' => $pelayanan->pic->diterima_signature ?? null,
                // ],
                // 'Diketahui Oleh' => [
                //     'name' => $pelayanan->pic->diketahuiName->name ?? '-',
                //     'signature' => $pelayanan->pic->diketahui_signature ?? null,
                // ],
            ];
        @endphp

        <div class="clearfix signature-wrapper">
            @foreach ($roles as $title => $data)
                <div class="signature-item">
                    <div class="signature-title">{{ $title }}</div>
                    <div class="signature-box">
                        @if ($data['signature'])
                            <img src="{{ public_path('storage/' . $data['signature']) }}">
                        @endif
                    </div>
                    <div class="signature-name">{{ $data['name'] }}</div>
                </div>
            @endforeach
        </div>

    </div>
@endsection

<style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 11px;
    }

    .container {
        padding: 10px;
    }

    .font-bold {
        font-weight: bold;
    }

    .section {
        margin-bottom: 14px;
        page-break-inside: avoid;
    }

    .section-title {
        font-weight: bold;
        margin: 18px 0 8px;
    }

    .form-table tr td {
        padding: 3px 0;
    }

    .form-table .label {
        width: 30%;
    }

    .checkbox-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        column-gap: 28px;
        /* lebih lebar */
        row-gap: 8px;
        margin-top: 6px;
        margin-bottom: 14px;
    }

    .checkbox-grid label {
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .checkbox-lainnya {
        grid-column: 1 / -1;
    }

    .lainnya-text {
        border-bottom: 0.5px dotted #000;
        min-width: 320px;
        /* lebih panjang = lebih lega */
        display: inline-block;
    }

    .table-bordered {
        width: 100%;
        border-collapse: collapse;
    }

    .table-bordered td {
        border: 1px solid #000;
        padding: 6px 6px;
    }

    .table-bordered thead td {
        padding: 7px 6px;
        font-weight: bold;
    }

    .col-no {
        width: 40px;
    }

    .col-qty {
        width: 50px;
    }

    .text-center {
        text-align: center;
    }

    .signature-wrapper {
        width: 100%;
        margin-top: 30px;
    }

    .signature-item {
        float: left;
        width: 33.33%;
        text-align: center;
    }

    .signature-title {
        font-weight: bold;
        margin-bottom: 6px;
    }

    .signature-box {
        height: 85px;
    }

    .signature-box img {
        max-height: 75px;
    }

    .signature-name {
        margin-top: 6px;
        font-weight: bold;
    }

    /* clearfix */
    .clearfix::after {
        content: "";
        display: block;
        clear: both;
    }
</style>
