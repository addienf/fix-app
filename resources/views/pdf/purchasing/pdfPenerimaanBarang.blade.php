@extends ('pdf.layout.layout')

@section('title', 'Penerimaan Barang PDF')

@section('content')
    @php
        $judul = 'Formulir Penerimaan Barang Oleh Purchasing';
        $no_dokumen = 'FO-QKS-PUR-01-03';
        $tanggal_rilis = '12 Maret 2025';
        $revisi = '00';

        $details = $penerimaan_barang->details ?? collect();
        $minRows = 10;

        $jawabanSesuai = $penerimaan_barang->sesuai == 1;
        $jawabanKondisi = $penerimaan_barang->kondisi == 1;

        $signatures = [
            [
                'label' => 'Dibuat Oleh,',
                'name' => $penerimaan_barang->pic->diterima_name ?? '-',
                'signature' => $penerimaan_barang->pic->diterima_ttd ?? null,
            ],
            [
                'label' => 'Mengetahui,',
                'name' => $penerimaan_barang->pic->diketahui_name ?? '-',
                'signature' => $penerimaan_barang->pic->diketahui_ttd ?? null,
            ],
        ];
    @endphp

    {{-- ================= HEADER ================= --}}
    <table class="header-table">
        <tr>
            <td rowspan="4" class="logo-cell">
                <img src="{{ $logoBase64 }}" style="height:55px;">
            </td>

            <td colspan="4" class="company-title">
                PT. QLab Kinarya Sentosa
            </td>
        </tr>

        <tr>
            <td rowspan="3" colspan="2" class="document-title">
                {{ $judul }}
            </td>

            <td class="header-label">No. Dokumen</td>
            <td class="header-value">{{ $no_dokumen }}</td>
        </tr>

        <tr>
            <td class="header-label">Tanggal Rilis</td>
            <td class="header-value">{{ $tanggal_rilis }}</td>
        </tr>

        <tr>
            <td class="header-label">Revisi</td>
            <td class="header-value">{{ $revisi }}</td>
        </tr>
    </table>

    <div class="content-wrapper">

        {{-- ================= TANGGAL ================= --}}
        <table class="no-border">
            <tr>
                <td class="label-col">Tanggal Penerimaan</td>
                <td class="separator-col">:</td>
                <td>{{ $penerimaan_barang->tanggal_penerimaan ?? '' }}</td>
            </tr>
        </table>

        {{-- ================= SECTION A ================= --}}
        <div class="section-title">A. INFORMASI SUPPLIER</div>

        <table class="no-border section-indent">
            <tr>
                <td class="label-col">Nama Supplier</td>
                <td class="separator-col">:</td>
                <td>{{ $penerimaan_barang->nama_supplier ?? '' }}</td>
            </tr>
            <tr>
                <td>Alamat Supplier</td>
                <td>:</td>
                <td>{{ $penerimaan_barang->alamat_supplier ?? '' }}</td>
            </tr>
            <tr>
                <td>Nomor PO</td>
                <td>:</td>
                <td>{{ $penerimaan_barang->nomor_po ?? '' }}</td>
            </tr>
        </table>

        {{-- ================= SECTION B ================= --}}
        <div class="section-title">B. INFORMASI MATERIAL</div>

        <table class="material-table">
            <thead>
                <tr>
                    <th style="width:5%;">No</th>
                    <th style="width:18%;">Nama Material</th>
                    <th style="width:14%;">Kode Material</th>
                    <th style="width:14%;">Jumlah Diterima</th>
                    <th style="width:12%;">Satuan</th>
                    <th style="width:14%;">Kondisi Material</th>
                    <th style="width:23%;">Status<br>(Diterima/Ditolak)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($details as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">{{ $item->nama_material ?? '-' }}</td>
                        <td class="text-center">{{ $item->kode_material ?? '-' }}</td>
                        <td class="text-center">{{ $item->jumlah ?? '-' }}</td>
                        <td class="text-center">{{ $item->satuan ?? '-' }}</td>
                        <td class="text-center">{{ $item->kondisi ?? '-' }}</td>
                        <td class="text-center">
                            {{ $item->status_barang === '1' ? 'Diterima' : ($item->status_barang === '0' ? 'Ditolak' : '-') }}
                        </td>
                    </tr>
                @endforeach

                @for ($i = $details->count(); $i < $minRows; $i++)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endfor
            </tbody>
        </table>

        {{-- ================= SECTION C ================= --}}
        <div class="section-title">C. PEMERIKSAAN BARANG</div>

        <table class="no-border section-indent">
            <tr>
                <td class="number-col">1)</td>
                <td>
                    Apakah barang sesuai dengan Purchase Order (PO)?
                    (
                    {!! $jawabanSesuai
                        ? 'Ya / <span style="text-decoration: line-through;">Tidak</span>'
                        : '<span style="text-decoration: line-through;">Ya</span> / Tidak' !!}
                    )
                </td>
            </tr>
            <tr>
                <td class="number-col">2)</td>
                <td>
                    Apakah barang dalam kondisi baik?
                    (
                    {!! $jawabanKondisi
                        ? 'Ya / <span style="text-decoration: line-through;">Tidak</span>'
                        : '<span style="text-decoration: line-through;">Ya</span> / Tidak' !!}
                    )
                </td>
            </tr>
            <tr>
                <td class="number-col">3)</td>
                <td>
                    @if (filled($penerimaan_barang->catatan))
                        Catatan Tambahan : {{ $penerimaan_barang->catatan }}
                    @else
                        Catatan Tambahan : ...........................................................
                    @endif
                </td>
            </tr>
        </table>
    </div>

    {{-- ================= SIGNATURE ================= --}}
    <table class="no-border signature-table">
        <tr>
            @foreach ($signatures as $sign)
                <td class="text-center signature-cell" width="{{ 100 / count($signatures) }}%">
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
    @page {
        margin: 25px 30px;
    }

    body {
        font-family: "Times-Roman", serif;
        font-size: 11px;
        line-height: 1.3;
        color: #000;
    }

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

    .header-table {
        table-layout: fixed;
    }

    .logo-cell {
        width: 15%;
        text-align: center;
        vertical-align: middle;
    }

    .company-title {
        text-align: center;
        font-weight: bold;
        font-size: 11px;
    }

    .document-title {
        text-align: center;
        font-size: 16px;
        font-weight: bold;
        vertical-align: middle;
    }

    .header-label {
        padding-left: 8px;
    }

    .header-value {
        text-align: center;
    }

    .content-wrapper {
        margin-top: 12px;
    }

    .section-title {
        font-weight: bold;
        margin-top: 8px;
        margin-bottom: 6px;
    }

    .section-indent {
        margin-left: 15px;
    }

    .label-col {
        width: 120px;
    }

    .separator-col {
        width: 10px;
    }

    .number-col {
        width: 25px;
    }

    .material-table td {
        height: 22px;
    }

    .text-center {
        text-align: center;
    }

    .no-border td {
        border: none !important;
        padding: 3px;
    }

    .signature-table {
        margin-top: 40px;
    }

    .signature-cell {
        border: none !important;
        text-align: center;
        vertical-align: top;
    }

    .signature-box {
        width: 220px;
        height: 90px;
        margin: 10px auto;
        overflow: hidden;
        position: relative;
        text-align: center;
    }

    .signature-box img {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 180%;
        max-height: 90px;
        transform: translate(-50%, -50%);
    }
</style>
