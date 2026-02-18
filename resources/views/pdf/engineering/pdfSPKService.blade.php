@extends('pdf.layout.layout')
@section('title', 'SPK Service and Maintenance')

@section('content')
    <div class="container">

        {{-- ================= HEADER ================= --}}
        <table class="header-table">
            <tr>
                <td rowspan="4" class="logo-cell">
                    {{-- <img src="{{ public_path('asset/logo.png') }}" style="height:55px;"> --}}
                    @if ($logoBase64)
                        <img src="{{ $logoBase64 }}">
                    @endif
                </td>
                <td colspan="4" class="company-cell">
                    PT. QLab Kinarya Sentosa
                </td>
            </tr>
            <tr>
                <td rowspan="3" colspan="2" class="title-cell">
                    Surat Perintah Kerja<br>Pelayanan Pelanggan
                </td>
                <td class="doc-label">No. Dokumen</td>
                <td class="doc-value">FO-QKS-CC-01-05</td>
            </tr>
            <tr>
                <td class="doc-label">Tanggal Rilis</td>
                <td class="doc-value">13 Oktober 2025</td>
            </tr>
            <tr>
                <td class="doc-label">Revisi</td>
                <td class="doc-value">01</td>
            </tr>
        </table>

        <h3 class="doc-title">FORMULIR SURAT PERINTAH KERJA (SPK)</h3>

        {{-- ================= INFO UMUM ================= --}}
        <table class="form-table">
            <tr>
                <td class="label">Nomor SPK</td>
                <td>: {{ $service->no_spk_service }}</td>
            </tr>
            <tr>
                <td class="label">Perusahaan</td>
                <td>: {{ $service->perusahaan }}</td>
            </tr>
            <tr>
                <td class="label">Alamat</td>
                <td>: {{ $service->alamat }}</td>
            </tr>
        </table>

        {{-- ================= A. DESKRIPSI ================= --}}
        @php $jenis = $service->deskripsi_pekerjaan ?? []; @endphp
        <h4 class="section-title">A. DESKRIPSI PEKERJAAN</h4>

        <div class="checkbox-grid">
            <label><input type="checkbox" {{ in_array('maintenance', $jenis) ? 'checked' : '' }}> Maintenance</label>
            <label><input type="checkbox" {{ in_array('service', $jenis) ? 'checked' : '' }}> Service</label>
            <label><input type="checkbox" {{ in_array('kalibrasi', $jenis) ? 'checked' : '' }}> Kalibrasi</label>

            <label class="checkbox-lainnya">
                <input type="checkbox" {{ in_array('lainnya', $jenis) ? 'checked' : '' }}>
                Lainnya :
                <span class="lainnya-text">{{ $service->deskripsi_pekerjaan_lainnya }}</span>
            </label>
        </div>

        {{-- ================= B. IDENTITAS ALAT ================= --}}
        <h4 class="section-title">B. IDENTITAS ALAT</h4>

        <table class="table-bordered">
            <thead>
                <tr>
                    <th class="col-no">No</th>
                    <th>Nama Alat</th>
                    <th>Tipe</th>
                    <th>Nomor Seri</th>
                    <th>Resolusi</th>
                    <th>Titik Ukur</th>
                    <th class="col-qty">Qty</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($service->details as $i => $item)
                    <tr>
                        <td class="center">{{ $i + 1 }}</td>
                        <td>{{ $item->nama_alat }}</td>
                        <td>{{ $item->tipe }}</td>
                        <td>{{ $item->nomor_seri }}</td>
                        <td>{{ $item->resolusi }}</td>
                        <td>{{ $item->titik_ukur }}</td>
                        <td class="center">{{ $item->quantity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- ================= C. PELAKSANAAN ================= --}}
        <h4 class="section-title">C. PELAKSANAAN</h4>

        <table class="form-table spaced">
            <tr>
                <td class="label">Tanggal Pelaksanaan</td>
                <td>: {{ \Carbon\Carbon::parse($service->tanggal_pelaksanaan)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Tempat Pelaksanaan</td>
                <td>: {{ $service->tempat_pelaksanaan }}</td>
            </tr>
        </table>

        <table class="table-bordered small spaced">
            <tr>
                <th class="col-no">No</th>
                <th>Nama Teknisi</th>
                <th>Jabatan</th>
            </tr>
            @foreach ($service->petugas as $i => $p)
                <tr>
                    <td class="center">{{ $i + 1 }}</td>
                    <td>{{ $p['nama_teknisi'] }}</td>
                    <td>{{ $p['jabatan'] }}</td>
                </tr>
            @endforeach
        </table>

        {{-- ================= TTD ================= --}}
        @php
            $roles = [
                'Dikonfirmasi Oleh' => [
                    'name' => $service->pic->dikonfirmasiNama->name ?? '-',
                    'signature' => $service->pic->dikonfirmasi_signature ?? null,
                ],
                'Dibuat Oleh' => [
                    'name' => $service->pic->dibuatNama->name ?? '-',
                    'signature' => $service->pic->dibuat_signature ?? null,
                ],
            ];
        @endphp

        <div class="clearfix signature-wrapper two-column">
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
        font-size: 10px;
    }

    .container {
        padding: 12px;
    }

    /* HEADER */
    .header-table {
        width: 100%;
        border-collapse: collapse;
    }

    .header-table td {
        border: 0.6px solid #000;
    }

    .logo-cell {
        width: 14%;
        text-align: center;
        vertical-align: middle;
    }

    .logo-cell img {
        height: 55px;
    }

    .company-cell {
        text-align: center;
        font-weight: bold;
    }

    .title-cell {
        text-align: center;
        font-size: 15px;
        font-weight: bold;
        vertical-align: middle;
    }

    .doc-label {
        padding-left: 8px;
    }

    .doc-value {
        text-align: center;
    }

    /* TITLE */
    .doc-title {
        text-align: center;
        font-weight: bold;
        margin: 12px 0;
    }

    /* FORM */
    .form-table td {
        padding: 4px;
    }

    .form-table .label {
        width: 30%;
    }

    /* SECTION */
    .section-title {
        margin-top: 14px;
        margin-bottom: 6px;
        font-weight: bold;
    }

    /* CHECKBOX */
    .checkbox-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        column-gap: 28px;
        row-gap: 8px;
        margin-bottom: 14px;
    }

    .checkbox-grid label {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .checkbox-lainnya {
        grid-column: 1 / -1;
    }

    .lainnya-text {
        border-bottom: 0.5px dotted #000;
        min-width: 320px;
        display: inline-block;
    }

    /* TABLE */
    .table-bordered {
        width: 100%;
        border-collapse: collapse;
    }

    .table-bordered th,
    .table-bordered td {
        border: 0.6px solid #000;
        padding: 4px;
    }

    .col-no {
        width: 32px;
    }

    .col-qty {
        width: 42px;
    }

    .center {
        text-align: center;
    }

    .small th,
    .small td {
        font-size: 9.5px;
    }

    /* SPACING */
    .spaced {
        margin-top: 8px;
        margin-bottom: 10px;
    }

    /* SIGNATURE */
    .signature-wrapper {
        width: 100%;
        margin-top: 30px;
    }

    /* khusus 2 kolom */
    .signature-wrapper.two-column .signature-item {
        width: 50%;
    }

    .signature-item {
        float: left;
        text-align: center;
    }

    .signature-title {
        font-weight: bold;
        margin-bottom: 8px;
    }

    .signature-box {
        height: 90px;
        /* ruang tanda tangan */
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .signature-box img {
        max-height: 100px;
        /* KUNCI: jangan pakai width */
        max-width: 100%;
    }

    .signature-name {
        margin-top: 8px;
        font-weight: bold;
    }

    /* clear float */
    .clearfix::after {
        content: "";
        display: block;
        clear: both;
    }
</style>
