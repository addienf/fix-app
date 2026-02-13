@extends ('pdf.layout.layout')
@section('title', 'Permintaan Bahan Warehouse PDF')
@section('content')
    @php
        $groupedByStock = $permintaan_bahan->permintaanDetails->groupBy('status_stock');

        $judul_form = 'FORMULIR PERMINTAAN BAHAN';
        $no_dokumen = 'FO-QKS-WRH-03-01';
        $tanggal_rilis = \Carbon\Carbon::parse($permintaan_bahan->tanggal)->translatedFormat('d F Y');
        $revisi = '01';

        $signatures = [
            [
                'label' => 'Dibuat Oleh,',
                'name' => $permintaan_bahan->pic->dibuatName->name ?? '-',
                'signature' => $permintaan_bahan->pic->dibuat_signature ?? null,
            ],
            [
                'label' => 'Mengetahui,',
                'name' => $permintaan_bahan->pic->mengetahuiName->name ?? '-',
                'signature' => $permintaan_bahan->pic->mengetahui_signature ?? null,
            ],
            [
                'label' => 'Diserahkan Ke,',
                'name' => $permintaan_bahan->pic->diserahkanName->name ?? '-',
                'signature' => $permintaan_bahan->pic->diserahkan_signature ?? null,
            ],
        ];
    @endphp

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
                style="text-align:center; font-size:16px; font-weight:bold;
                   border:0.5px solid #000; vertical-align:middle;">
                {{ $judul_form }}
            </td>

            <td style="border:0.5px solid #000; padding-left:8px;">
                No. Dokumen
            </td>
            <td style="border:0.5px solid #000; text-align:center;">
                {{ $no_dokumen }}
            </td>
        </tr>

        <tr>
            <td style="border:0.5px solid #000; padding-left:8px;">
                Tanggal Rilis
            </td>
            <td style="border:0.5px solid #000; text-align:center;">
                {{ $tanggal_rilis }}
            </td>
        </tr>

        <tr>
            <td style="border:0.5px solid #000; padding-left:8px;">
                Revisi
            </td>
            <td style="border:0.5px solid #000; text-align:center;">
                {{ $revisi }}
            </td>
        </tr>
    </table>

    {{-- <table class="no-border" style="margin-top:12px;">
        <tr>
            <td width="25%">Nomor</td>
            <td>: {{ $permintaan_bahan->no_surat }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>: {{ \Carbon\Carbon::parse($permintaan_bahan->tanggal)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Dari</td>
            <td>: {{ $permintaan_bahan->dari }}</td>
        </tr>
        <tr>
            <td>Kepada</td>
            <td>: {{ $permintaan_bahan->kepada }}</td>
        </tr>
    </table> --}}

    <table class="no-border" width="100%">
        <tr>
            <td width="15%">Nomor</td>
            <td width="35%">: {{ $permintaan_bahan->no_surat }}</td>
            <td width="15%">Tanggal</td>
            <td width="35%">: {{ \Carbon\Carbon::parse($permintaan_bahan->tanggal)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Dari</td>
            <td>: {{ $permintaan_bahan->dari }}</td>
            <td>Kepada</td>
            <td>: {{ $permintaan_bahan->kepada }}</td>
        </tr>
    </table>

    <p style="margin-top:12px;">
        Berdasarkan Permintaan Barang No
        <b>{{ $permintaan_bahan->permintaanBahanPro->no_surat ?? 'Untuk Stock' }}</b>
        dari Departemen
        <b>{{ Str::headline($permintaan_bahan->pic->dibuatName->roles->first()?->name ?? '-') }}</b>,
        mohon bantuan untuk memenuhi kebutuhan bahan/sparepart dengan rincian sebagai berikut:
    </p>

    <br>

    @foreach ($groupedByStock as $status => $items)
        <div class="section-title">
            Status Stock : {{ strtoupper($status) }}
        </div>

        <table class="table-bordered">
            <thead>
                <tr>
                    <th class="col-no">No</th>
                    <th>Nama Bahan</th>
                    <th>Spesifikasi</th>
                    <th class="col-qty">Jumlah</th>
                    <th>Keperluan Barang</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $i => $produk)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td>{{ $produk->bahan_baku }}</td>
                        <td>{{ $produk->spesifikasi }}</td>
                        <td class="text-center">{{ $produk->jumlah }}</td>
                        <td>{{ $produk->keperluan_barang }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    <table class="no-border" style="margin-top:40px;">
        <tr>
            @foreach ($signatures as $sign)
                <td class="text-center" width="{{ 100 / count($signatures) }}%">
                    {{ $sign['label'] }}<br><br>

                    <div class="signature-box">
                        @if (!empty($sign['signature']))
                            <img src="{{ public_path('storage/' . $sign['signature']) }}">
                        @endif
                    </div>

                    <br>
                    <b>{{ $sign['name'] }}</b>
                </td>
            @endforeach
        </tr>
    </table>
@endsection

<style>
    /* ================= PAGE ================= */
    @page {
        margin: 25px 30px;
    }

    /* ================= BODY ================= */
    body {
        font-family: "Times-Roman", serif;
        font-size: 11px;
        color: #000;
        line-height: 1.4;
    }

    /* ================= TABLE GLOBAL ================= */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 10px;
    }

    th,
    td {
        border: 1px solid #000;
        padding: 5px 6px;
        vertical-align: middle;
    }

    th {
        text-align: center;
        font-weight: bold;
    }

    /* ================= HEADER SOFT GRAY ================= */
    thead th {
        background-color: #f2f2f2;
    }

    /* ================= NO BORDER ================= */
    .no-border td {
        border: none !important;
        padding: 3px;
    }

    /* ================= ALIGN ================= */
    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }

    /* ================= INFO FORM ================= */
    .info-table td {
        border: none;
        padding: 2px 4px;
    }

    /* ================= SECTION ================= */
    .section {
        margin-bottom: 14px;
    }

    /* ================= TABLE COLUMN ================= */
    .col-no {
        width: 35px;
    }

    .col-qty {
        width: 70px;
    }

    .col-stock {
        width: 80px;
    }

    /* ================= SIGNATURE ================= */
    .signature-table {
        margin-top: 30px;
    }

    .signature-table td {
        border: none;
        text-align: center;
        vertical-align: top;
        padding-top: 10px;
    }

    /* box tanda tangan */
    .signature-box {
        width: 160px;
        height: 70px;
        margin: 10px auto;
        text-align: center;
    }

    .signature-box img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    /* ================= SMALL TEXT ================= */
    .text-small {
        font-size: 10px;
    }
</style>
