@extends('pdf.layout.layout')

@section('title', 'Berita Acara Penyelesaian Service dan Maintenance')

@section('content')

    <table style="width:100%; border-collapse:collapse; table-layout:fixed;">
        <tr>
            <td rowspan="4" style="width:15%; text-align:center; vertical-align:middle; border:0.5px solid #000;">
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
                Berita Acara Penyelesaian <br> Service dan Maintenance
            </td>

            <td style="border:0.5px solid #000; padding-left:8px;">
                No. Dokumen
            </td>

            <td style="border:0.5px solid #000; text-align:center;">
                FO-QKS-ENG-01-09
            </td>
        </tr>

        <tr>
            <td style="border:0.5px solid #000; padding-left:8px;">
                Tanggal Rilis
            </td>

            <td style="border:0.5px solid #000; text-align:center;">
                29 September 2025
            </td>
        </tr>

        <tr>
            <td style="border:0.5px solid #000; padding-left:8px;">
                Revisi
            </td>

            <td style="border:0.5px solid #000; text-align:center;">
                02
            </td>
        </tr>
    </table>

    <div style="height:10px;"></div>

    <div class="main-title">
        BERITA ACARA PENYELESAIAN SERVICE &amp; MAINTENANCE
    </div>

    <div style="height:10px;"></div>

    <div class="section">
        <table class="form-table">
            <tr>
                <td class="label">Nomor Surat</td>
                <td>: {{ $berita->no_surat }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal</td>
                <td>: {{ \Carbon\Carbon::parse($berita->tanggal)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Status Barang </td>
                <td>
                    :
                    [{{ $berita->status_po === 'yes' ? '✔' : ' ' }}] Received
                    &nbsp;
                    [{{ $berita->status_po === 'wait' ? '✔' : ' ' }}] Not Received
                    &nbsp;
                </td>
            </tr>
            <tr>
                <td class="label">Nomor PO</td>
                <td>: {{ $berita->nomor_po }}</td>
            </tr>
        </table>
    </div>

    @php
        \Carbon\Carbon::setLocale('id');
        $tanggal = \Carbon\Carbon::parse($berita->tanggal);
    @endphp

    <p class="paragraph">
        Pada hari {{ strtolower($tanggal->translatedFormat('l')) }}
        tanggal {{ $tanggal->translatedFormat('d/m/Y') }}
        kami yang bertanda tangan di bawah ini:
    </p>

    <div class="section">
        <div class="party-title">(Pihak 1) Penyedia Jasa</div>
        <table class="info-table">
            <tr>
                <td class="label">Nama</td>
                <td>: {{ $berita->penyediaJasa->nama }}</td>
            </tr>
            <tr>
                <td class="label">Perusahaan</td>
                <td>: {{ $berita->penyediaJasa->perusahaan }}</td>
            </tr>
            <tr>
                <td class="label">Alamat</td>
                <td>: {{ $berita->penyediaJasa->alamat }}</td>
            </tr>
            <tr>
                <td class="label">Jabatan</td>
                <td>: {{ $berita->penyediaJasa->jabatan }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="party-title">(Pihak 2) Pelanggan</div>
        <table class="info-table">
            <tr>
                <td class="label">Nama</td>
                <td>: {{ $berita->pelanggan->nama }}</td>
            </tr>
            <tr>
                <td class="label">Perusahaan</td>
                <td>: {{ $berita->pelanggan->perusahaan }}</td>
            </tr>
            <tr>
                <td class="label">Alamat</td>
                <td>: {{ $berita->pelanggan->alamat }}</td>
            </tr>
            <tr>
                <td class="label">Jabatan</td>
                <td>: {{ $berita->pelanggan->jabatan }}</td>
            </tr>
        </table>
    </div>

    @php
        $jenis = strtolower($berita->detail->jenis_pekerjaan);

        if ($jenis === 'service') {
            $text = 'Service/<span style="text-decoration: line-through;">Maintenance</span>';
        } elseif ($jenis === 'maintenance') {
            $text = '<span style="text-decoration: line-through;">Service</span>/Maintenance';
        } elseif ($jenis === 'lainnya') {
            $text = $berita->detail->jenis_pekerjaan_lainnya;
        } else {
            $text = 'Service/Maintenance';
        }
    @endphp

    <p class="paragraph">
        Dengan ini menyatakan bahwa pekerjaan {!! $text !!}
        telah diselesaikan dengan rincian sebagai berikut
        <i>(*coret yang tidak perlu)</i>:
    </p>

    <ul class="list">
        <li>Produk / Model : {{ $berita->detail->produk }}</li>
        <li>Serial Number : {{ $berita->detail->serial_number }}</li>
        <li>Deskripsi Pekerjaan / Barang : {{ $berita->detail->desc_pekerjaan }}</li>
        <li>
            Status Barang :
            [{{ $berita->detail->status_barang === 'yes' ? '✔' : ' ' }}] Installed
            &nbsp;
            [{{ $berita->detail->status_barang === 'wait' ? '✔' : ' ' }}] Delivered
            &nbsp;
            [{{ $berita->detail->status_barang === 'na' ? '✔' : ' ' }}] N/A
        </li>
    </ul>

    <p class="paragraph">
        Setelah dilakukan pemeriksaan, kedua belah pihak sepakat bahwa pekerjaan telah
        selesai sesuai ketentuan yang disepakati dan dalam kondisi baik.
        Demikian berita acara ini dibuat untuk dipergunakan sebagaimana mestinya.
    </p>

    <div class="section">
        <table class="signature-table">
            <tr>
                <td>
                    ( Pihak 1 Penyedia Jasa )<br><br>
                    @if (!empty($berita->pic?->jasa_ttd))
                        <div style="height:20px;"></div>
                        <img src="{{ public_path('storage/' . $berita->pic->jasa_ttd) }}">
                    @else
                        <div style="height:80px;"></div>
                    @endif
                    <div class="signature-name">
                        ( {{ $berita->pic?->jasa_name ?: '....................' }} )
                    </div>
                </td>
                <td>
                    ( Pihak 2 Pelanggan )

                    @if (!empty($berita->pic?->pelanggan_ttd))
                        <div style="height:20px;"></div>
                        <img src="{{ public_path('storage/' . $berita->pic->pelanggan_ttd) }}">
                    @else
                        <div style="height:80px;"></div>
                    @endif

                    <div class="signature-name">
                        ( {{ $berita->pic?->pelanggan_name ?: '....................' }} )
                    </div>
                </td>
            </tr>
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

    .main-title {
        text-align: center;
        font-size: 13px;
        margin-top: 14px;
        margin-bottom: 8px;
        font-weight: bold;
    }

    /* ===== SECTION ===== */
    .section {
        margin-bottom: 14px;
        page-break-inside: avoid;
    }

    .party-title {
        font-weight: bold;
        margin-bottom: 6px;
    }

    /* ===== TABLE FORM ===== */
    .form-table .label,
    .info-table .label {
        width: 28%;
    }

    /* ===== TEXT ===== */
    .paragraph {
        margin: 8px 0;
        line-height: 1.5;
    }

    .list {
        margin-left: 18px;
    }

    /* ===== SIGNATURE ===== */
    .signature-table td {
        width: 50%;
        text-align: center;
        padding-top: 25px;
    }

    .signature-table img {
        height: 70px;
        display: block;
        margin: 0 auto;
    }

    .signature-name {
        margin-top: 8px;
        width: 200px;
        text-align: center;
        margin: 0 auto;
    }
</style>
