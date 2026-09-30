@extends ('pdf.layout.layout')
@section('title', 'SPK Quality PDF')
@section('content')
    <table>
        <tr>
            <td rowspan="4" class="text-center" style="width:80px;">
                @if ($logoBase64)
                    <img src="{{ $logoBase64 }}" style="height:55px;">
                @endif
            </td>

            <td colspan="4" class="header-title">
                PT. QLab Kinarya Sentosa
            </td>
        </tr>

        <tr>
            <td rowspan="3" colspan="2" class="judul">
                Surat Perintah Kerja QC
            </td>

            <td class="label">No. Dokumen</td>
            <td>FO-QKS-PRO-01-05</td>
        </tr>

        <tr>
            <td>Tanggal Rilis</td>
            <td>12 Maret 2025</td>
        </tr>

        <tr>
            <td>Revisi</td>
            <td>00</td>
        </tr>
    </table>

    <br>

    {{-- ================= INFO ================= --}}
    <table class="no-border" style="width:100%; border-collapse:none !important; font-size:11px;">
        <tr>
            <td style="width:15%; padding:3px 6px;">No. SPK</td>
            <td style="width:2%; text-align:center;">:</td>
            <td style="width:33%; padding:3px 6px;">{{ $spk_qc->no_spk }}</td>

            <td style="width:15%; padding:3px 6px;">No. SPK MKT</td>
            <td style="width:2%; text-align:center;">:</td>
            <td style="width:33%; padding:3px 6px;">{{ $spk_qc->spkmarketing->no_spk }}</td>
        </tr>

        <tr>
            <td style="padding:3px 6px;">Dari</td>
            <td style="text-align:center;">:</td>
            <td style="padding:3px 6px;">{{ $spk_qc->dari }}</td>

            <td style="padding:3px 6px;">Kepada</td>
            <td style="text-align:center;">:</td>
            <td style="padding:3px 6px;">{{ $spk_qc->kepada }}</td>
        </tr>
    </table>

    <br>

    {{-- ================= TABLE ================= --}}
    <table class="table-main">
        <thead>
            <tr>
                <th style="width:5%;">No.</th>
                <th style="width:35%;">Nama Produk yang Dipesan</th>
                <th style="width:15%;">Jumlah Pesanan</th>
                <th style="width:15%;">URS No.</th>
                <th style="width:30%;">Rencana Pengiriman</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($spk_qc->details as $i => $item)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $item->nama_produk }}</td>
                    <td class="text-center">{{ $item->jumlah }}</td>
                    <td>{{ $item->no_urs }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</td>
                </tr>
            @endforeach

            {{-- ROW KOSONG --}}
            @for ($i = count($spk_qc->details); $i < 6; $i++)
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endfor
        </tbody>
    </table>

    {{-- ================= SIGNATURE ================= --}}
    <table class="no-border signature">
        <tr>
            <td>
                Yang Membuat,<br><br>

                <div class="signature-box">
                    @if (!empty($spk_qc->pic->create_signature))
                        <img src="{{ public_path('storage/' . $spk_qc->pic->create_signature) }}">
                    @endif
                </div>

                <b>{{ $spk_qc->pic->createName->name ?? '-' }}</b><br>
                ( Produksi )
            </td>

            <td>
                Yang Menerima,<br><br>

                <div class="signature-box">
                    @if (!empty($spk_qc->pic->receive_signature))
                        <img src="{{ public_path('storage/' . $spk_qc->pic->receive_signature) }}">
                    @endif
                </div>

                <b>{{ $spk_qc->pic->receiveName->name ?? '-' }}</b><br>
                ( QC )
            </td>
        </tr>
    </table>

@endsection

<style>
    body {
        font-family: "Times-Roman", serif;
        font-size: 11px;
        line-height: 1.2;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    td,
    th {
        border: 0.5px solid #000;
        padding: 3px 5px;
    }

    .no-border td {
        border: none !important;
    }

    .header-title {
        font-weight: bold;
        text-align: center;
    }

    .judul {
        font-size: 14px;
        font-weight: bold;
        text-align: center;
    }

    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }

    .info td {
        padding: 4px 6px;
    }

    .table-main th {
        font-weight: bold;
        text-align: center;
    }

    .table-main td {
        height: 20px;
    }

    .signature {
        margin-top: 30px;
        text-align: center;
    }

    .signature-box {
        width: 180px;
        height: 70px;
        margin: 10px auto;
        position: relative;
        overflow: hidden;
    }

    .signature-box img {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 160%;
        max-height: 70px;
    }

    .label {
        width: 120px;
    }
</style>
