@extends ('pdf.layout.layout')
@section('title', 'Permintaan Pembelian PDF')
@section('content')
    {{-- ================= HEADER (TEMPLATE BAKU) ================= --}}
    @php
        $judul = 'PERMINTAAN PEMBELIAN';
        $no_dokumen = 'FO-QKS-PUR-01-01';
        $tanggal_rilis = \Carbon\Carbon::parse($permintaan_pembelian->tanggal)->translatedFormat('d F Y');
        $revisi = '00';
    @endphp

    <table style="width:100%; border-collapse:collapse; table-layout:fixed;">
        <tr>
            <td rowspan="4" style="width:15%; text-align:center; vertical-align:middle; border:0.5px solid #000;">
                {{-- <img src="{{ public_path('asset/logo.png') }}" style="height:55px;"> --}}
                <img src="{{ $logoBase64 }}" style="height:55px;">
            </td>

            <td colspan="4" style="text-align:center; font-weight:bold; font-size:11px; border:0.5px solid #000;">
                PT. QLab Kinarya Sentosa
            </td>
        </tr>

        <tr>
            <td rowspan="3" colspan="2"
                style="text-align:center; font-size:16px; font-weight:bold; border:0.5px solid #000; vertical-align:middle;">
                {{ $judul }}
            </td>

            <td style="border:0.5px solid #000; padding-left:8px;">No. Dokumen</td>
            <td style="border:0.5px solid #000; text-align:center;">{{ $no_dokumen }}</td>
        </tr>

        <tr>
            <td style="border:0.5px solid #000; padding-left:8px;">Tanggal Rilis</td>
            <td style="border:0.5px solid #000; text-align:center;">{{ $tanggal_rilis }}</td>
        </tr>

        <tr>
            <td style="border:0.5px solid #000; padding-left:8px;">Revisi</td>
            <td style="border:0.5px solid #000; text-align:center;">{{ $revisi }}</td>
        </tr>
    </table>

    {{-- ================= PARAGRAF ================= --}}
    <p style="margin-top:14px;">
        Dengan hormat,
    </p>

    <p>
        Berdasarkan Permintaan Barang No
        <b>
            {{ $permintaan_pembelian->permintaanBahanWBB?->no_surat ??
                'Untuk Stock - ' . $permintaan_pembelian->created_at->timezone('Asia/Jakarta')->format('YmdHis') }}
        </b>,
        mohon bantuan untuk memenuhi kebutuhan bahan / sparepart dengan rincian sebagai berikut:
    </p>

    {{-- ================= TABEL BARANG ================= --}}
    <table>
        <thead style="background:#f2f2f2;">
            <tr>
                <th width="40">No</th>
                <th width="120">Kode Barang</th>
                <th>Nama Barang</th>
                <th width="70">Qty</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($permintaan_pembelian->details as $i => $item)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $item->kode_barang }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td class="text-center">{{ $item->jumlah }}</td>
                    <td>{{ $item->keterangan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ================= SIGNATURE (TEMPLATE FLEKSIBEL) ================= --}}
    @php
        $signatures = [
            [
                'label' => 'Dibuat Oleh,',
                'name' => $permintaan_pembelian->pic->createName->name ?? '-',
                'signature' => $permintaan_pembelian->pic->create_signature ?? null,
            ],
            [
                'label' => 'Mengetahui,',
                'name' => $permintaan_pembelian->pic->knowingName->name ?? '-',
                'signature' => $permintaan_pembelian->pic->knowing_signature ?? null,
            ],
        ];
    @endphp

    <br><br>

    <table class="no-border">
        <tr>
            @foreach ($signatures as $item)
                <td class="text-center" width="{{ 100 / count($signatures) }}%">
                    {{ $item['label'] }}<br><br>

                    <div class="signature-box">
                        @if ($item['signature'])
                            <img src="{{ public_path('storage/' . $item['signature']) }}">
                        @endif
                    </div>

                    <br>
                    <b>{{ $item['name'] }}</b>
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

    /* ================= GLOBAL TABLE ================= */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 10px;
    }

    th,
    td {
        border: 1px solid #000;
        padding: 4px 6px;
        vertical-align: middle;
    }

    th {
        font-weight: bold;
        text-align: center;
    }

    /* ================= SOFT HEADER ================= */
    thead th {
        background-color: #f2f2f2;
    }

    /* ================= NO BORDER ================= */
    .no-border td {
        border: none !important;
        padding: 3px;
    }

    /* ================= TEXT ================= */
    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }

    .text-small {
        font-size: 10px;
    }

    /* ================= SECTION ================= */
    .section {
        margin-bottom: 14px;
    }

    /* ================= COLUMN WIDTH ================= */
    .col-no {
        width: 40px;
    }

    .col-qty {
        width: 70px;
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
