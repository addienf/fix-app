@extends ('pdf.layout.layout')
@section('title', 'Serah Terima Bahan PDF')
@section('content')
    {{-- ================= HEADER ================= --}}
    <table style="width:100%; border-collapse:collapse; table-layout:fixed;">
        <tr>
            <td rowspan="4" style="width:15%; text-align:center; vertical-align:middle; border:0.5px solid #000;">
                <img src="{{ public_path('asset/logo.png') }}" style="height:55px;">
            </td>

            <td colspan="4" style="text-align:center; font-weight:bold; font-size:11px; border:0.5px solid #000;">
                PT. QLab Kinarya Sentosa
            </td>
        </tr>

        <tr>
            <td rowspan="3" colspan="2"
                style="text-align:center; font-size:15px; font-weight:bold; border:0.5px solid #000; vertical-align:middle;">
                FORMULIR SERAH TERIMA BARANG<br>& SPAREPART
            </td>

            <td style="border:0.5px solid #000; padding-left:8px;">No. Dokumen</td>
            <td style="border:0.5px solid #000; text-align:center;">FO-QKS-WRH-03-03</td>
        </tr>

        <tr>
            <td style="border:0.5px solid #000; padding-left:8px;">Tanggal Rilis</td>
            <td style="border:0.5px solid #000; text-align:center;">
                {{ \Carbon\Carbon::parse($serah_terima->tanggal)->translatedFormat('d F Y') }}
            </td>
        </tr>

        <tr>
            <td style="border:0.5px solid #000; padding-left:8px;">Revisi</td>
            <td style="border:0.5px solid #000; text-align:center;">00</td>
        </tr>
    </table>

    <br>

    {{-- ================= INFORMASI UMUM ================= --}}
    {{-- <table class="no-border">
        <tr>
            <td width="25%">Nomor</td>
            <td width="75%">: {{ $serah_terima->no_surat }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>: {{ \Carbon\Carbon::parse($serah_terima->tanggal)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Dari</td>
            <td>: {{ $serah_terima->dari }}</td>
        </tr>
        <tr>
            <td>Kepada</td>
            <td>: {{ $serah_terima->kepada }}</td>
        </tr>
    </table> --}}
    <table class="no-border" width="100%">
        <tr>
            <td width="15%">Nomor</td>
            <td width="35%">: {{ $serah_terima->no_surat }}</td>
            <td width="15%">Tanggal</td>
            <td width="35%">: {{ \Carbon\Carbon::parse($serah_terima->tanggal)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Dari</td>
            <td>: {{ $serah_terima->dari }}</td>
            <td>Kepada</td>
            <td>: {{ $serah_terima->kepada }}</td>
        </tr>
    </table>

    <p>
        Dengan hormat,<br>
        Berdasarkan Permintaan Barang No
        <b>{{ $serah_terima->peminjamanAlat->spkVendor->perencanaanProduksi->no_surat }}</b>
        dari Departemen
        <b>{{ Str::headline($serah_terima->pic?->submitName?->roles?->first()?->name ?? '') }}</b>,
        berikut material/bahan/barang yang telah diserahkan:
    </p>

    <br>
    {{-- ================= TABEL BARANG ================= --}}
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Nama Bahan</th>
                <th width="25%">Spesifikasi</th>
                <th width="15%">Jumlah</th>
                <th width="25%">Keperluan Barang</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($serah_terima->details as $i => $item)
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

    {{-- ================= SIGNATURE ================= --}}
    <table class="no-border" width="100%" style="margin-top:50px;">
        <tr>
            <td width="50%" class="text-center">
                Diserahkan Oleh,<br><br>

                <div class="signature-box">
                    @if (!empty($serah_terima->pic->submit_signature))
                        <img src="{{ public_path('storage/' . $serah_terima->pic->submit_signature) }}">
                    @endif
                </div>

                <br>
                <b>{{ $serah_terima->pic->submitName->name }}</b>
            </td>

            <td width="50%" class="text-center">
                Diterima Oleh,<br><br>

                <div class="signature-box">
                    @if (!empty($serah_terima->pic->receive_signature))
                        <img src="{{ public_path('storage/' . $serah_terima->pic->receive_signature) }}">
                    @endif
                </div>

                <br>
                <b>{{ $serah_terima->pic->receiveName->name }}</b>
            </td>
        </tr>
    </table>
@endsection

<style>
    @page {
        margin: 25px 30px;
    }

    body {
        font-family: "Times-Roman", serif;
        font-size: 11px;
        color: #000;
        line-height: 1.4;
    }

    /* ================= GLOBAL TABLE ================= */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 10px;
        table-layout: fixed;
    }

    th,
    td {
        border: 0.5px solid #000;
        padding: 4px 6px;
        vertical-align: middle;
    }

    th {
        font-weight: bold;
        text-align: center;
        background-color: #f2f2f2;
        /* soft abu audit */
    }

    /* ================= NO BORDER ================= */
    .no-border td,
    .no-border th {
        border: none !important;
        padding: 3px 2px;
    }

    /* ================= TEXT ================= */
    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }

    .text-left {
        text-align: left;
    }

    /* ================= SECTION TITLE ================= */
    .title {
        font-size: 12px;
        font-weight: bold;
        margin: 12px 0 6px;
    }

    /* ================= PARAGRAPH ================= */
    p {
        margin: 6px 0;
        text-align: justify;
    }

    /* ================= SIGNATURE ================= */
    .signature-box {
        width: 160px;
        height: 70px;
        margin: 0 auto;
        text-align: center;
    }

    .signature-box img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    /* ================= PAGE BREAK ================= */
    .page-break {
        page-break-before: always;
    }
</style>
