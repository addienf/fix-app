@extends ('pdf.layout.layout')

@section('title', 'Formulir Pembuatan & Perubahan Informasi Terdokumentasi')

@section('content')

    <div class="page">

        {{-- ================= HEADER ================= --}}
        <table>
            <tr>
                <td rowspan="4" style="width:15%; text-align:center; vertical-align:middle;">

                    @if ($logoBase64)
                        <img src="{{ $logoBase64 }}" style="height:55px;">
                    @endif

                </td>

                <td colspan="4" class="text-center text-bold">

                    PT. QLab Kinarya Sentosa

                </td>
            </tr>

            <tr>

                <td rowspan="3" colspan="2"
                    style="
                text-align:center;
                font-size:16px;
                font-weight:bold;
                vertical-align:middle;
                line-height:1.4;
            ">

                    Formulir Pembuatan & <br>
                    Perubahan Informasi <br>
                    Terdokumentasi

                </td>

                <td style="width:20%;">
                    No. Dokumen
                </td>

                <td class="text-center" style="width:20%;">

                    FO-QKS-MR-01-02

                </td>
            </tr>

            <tr>

                <td>
                    Tanggal Rilis
                </td>

                <td class="text-center">
                    12 Maret 2025
                </td>

            </tr>

            <tr>

                <td>
                    Revisi
                </td>

                <td class="text-center">
                    0
                </td>

            </tr>
        </table>

        <br><br>

        {{-- ================= MAIN TABLE ================= --}}
        <table>

            {{-- ================= PEMOHON ================= --}}
            <tr>
                <td colspan="4" class="section-title section-gray">
                    Pemohon
                </td>
            </tr>

            <tr>

                <td colspan="2" class="h-20">
                    Nama :
                    {{ $perubahan->nama }}
                </td>

                <td colspan="2" class="h-20">
                    Tanggal :
                    {{ $perubahan->tanggal }}
                </td>

            </tr>

            {{-- ================= JENIS DOKUMEN ================= --}}
            <tr>

                <td colspan="2" class="h-50">

                    Jenis Dokumen :
                    <br><br>

                    {{ $perubahan->jenis_dokumen == 'pedoman' ? '☑' : '☐' }}
                    Pedoman Mutu

                    &nbsp;&nbsp;&nbsp;

                    {{ $perubahan->jenis_dokumen == 'prosedur' ? '☑' : '☐' }}
                    Prosedur

                    &nbsp;&nbsp;&nbsp;

                    {{ $perubahan->jenis_dokumen == 'instruksi' ? '☑' : '☐' }}
                    Instruksi Kerja

                    <br><br>

                    {{ $perubahan->jenis_dokumen == 'formulir' ? '☑' : '☐' }}
                    Formulir

                    &nbsp;&nbsp;&nbsp;

                    {{ $perubahan->jenis_dokumen == 'lainnya' ? '☑' : '☐' }}
                    Lainnya :
                    {{ $perubahan->jenis_dokumen_lainnya }}

                </td>

                <td colspan="2" class="h-50">

                    Perubahan Yang Diminta :
                    <br><br>

                    {{ $perubahan->perubahan_diminta == 'baru' ? '☑' : '☐' }}
                    Dokumen Baru

                    &nbsp;&nbsp;&nbsp;

                    {{ $perubahan->perubahan_diminta == 'batal' ? '☑' : '☐' }}
                    Pembatalan

                    <br><br>

                    {{ $perubahan->perubahan_diminta == 'revisi' ? '☑' : '☐' }}
                    Revisi Dokumen

                </td>

            </tr>

            {{-- ================= NOMOR DOKUMEN ================= --}}
            <tr>

                <td colspan="2" class="text-center">
                    Nomor Dokumen
                </td>

                <td class="text-center" style="width:15%;">
                    Nomor Revisi
                </td>

                <td class="text-center">
                    Judul Dokumen
                </td>

            </tr>

            {{-- LAMA --}}
            <tr>

                <td style="width:15%;">
                    No. Lama
                </td>

                <td style="width:20%;">
                    {{ $perubahan->dokPerubahan->nomor_dok_lama }}
                </td>

                <td class="text-center">
                    {{ $perubahan->dokPerubahan->nomor_rev_lama }}
                </td>

                <td>
                    Judul Lama :
                    {{ $perubahan->dokPerubahan->judul_dok_lama }}
                </td>

            </tr>

            {{-- BARU --}}
            <tr>

                <td>
                    No. Baru
                </td>

                <td>
                    {{ $perubahan->dokPerubahan->nomor_dok_baru }}
                </td>

                <td class="text-center">
                    {{ $perubahan->dokPerubahan->nomor_rev_baru }}
                </td>

                <td>
                    Judul Baru :
                    {{ $perubahan->dokPerubahan->judul_dok_baru }}
                </td>

            </tr>

            {{-- ================= DOKUMEN TERKAIT ================= --}}
            <tr>

                <td colspan="4"
                    style="
                    border-top:none;
                    border-bottom:none;
                    padding-top:10px;
                    padding-bottom:8px;
                ">

                    <div style="margin-bottom:12px;">

                        Dokumen Terkait Yang Direvisi :

                    </div>

                    <div style="padding-left:8px;">

                        {{ $perubahan->dokPerubahan->dokumen_terkait }}

                    </div>

                </td>

            </tr>

            {{-- ================= URAIAN ================= --}}
            <tr>

                <td colspan="4"
                    style="
                        height:120px;
                        border-top:none;
                        vertical-align:top;
                        padding-top:10px;
                    ">

                    <div style="margin-bottom:14px;">

                        Uraian Perubahan :

                    </div>

                    <div
                        style="
                        padding-left:8px;
                        line-height:1.5;
                    ">

                        {!! nl2br(e($perubahan->dokPerubahan->uraian_perubahan)) !!}

                    </div>

                </td>

            </tr>

            {{-- ================= PENANGGUNG JAWAB ================= --}}
            <tr>
                <td colspan="4" class="section-title section-gray">

                    Penanggung Jawab Departemen

                </td>
            </tr>

            {{-- DISETUJUI --}}
            <tr>

                <td colspan="4" class="h-20">

                    {{ $perubahan->persetujuanPerubahan->persetujuan_pic == 'disetujui' ? '☑' : '☐' }}
                    Disetujui

                </td>

            </tr>

            {{-- DITOLAK --}}
            <tr>

                <td colspan="4" class="h-20">

                    {{ $perubahan->persetujuanPerubahan->persetujuan_pic == 'ditolak' ? '☑' : '☐' }}
                    Tidak Disetujui

                    &nbsp;&nbsp;&nbsp;&nbsp;

                    Alasan :
                    {{ $perubahan->persetujuanPerubahan->alasan_penolakan_pic }}

                </td>

            </tr>

            {{-- SIGNATURE --}}
            <tr>

                {{-- TTD --}}
                <td colspan="3" style="height:45px; vertical-align:top;">

                    <div style="margin-top:6px;">

                        Tanda Tangan :

                        @if (!empty($perubahan->persetujuanPerubahan->signature_pic))
                            <img class="signature-inline"
                                src="{{ public_path('storage/' . $perubahan->persetujuanPerubahan->signature_pic) }}">
                        @endif

                    </div>

                </td>

                {{-- TANGGAL --}}
                <td style="width:25%; vertical-align:top;">

                    <div style="margin-top:6px;">

                        Tanggal :

                        <br><br>

                        {{ $perubahan->persetujuanPerubahan->signature_pic_date }}

                    </div>

                </td>

            </tr>

            {{-- ================= WAKIL MANAJEMEN ================= --}}
            <tr>
                <td colspan="4" class="section-title section-gray">
                    Wakil Manajemen
                </td>
            </tr>
            <tr>

                <td colspan="4" style="height:95px; vertical-align:top;">

                    {{-- DITOLAK --}}
                    <div>

                        {{ $perubahan->persetujuanPerubahan->persetujuan_manajemen == 'ditolak' ? '☑' : '☐' }}

                        Kurang Informasi. Dikembalikan ke Penanggung Jawab Departemen :

                    </div>

                    <div style="margin-top:10px; margin-left:20px;">

                        Alasan :
                        {{ $perubahan->persetujuanPerubahan->alasan_penolakan_manajemen }}

                    </div>

                    {{-- BOTTOM --}}
                    <table style="width:100%; border:none; margin-top:25px;">

                        <tr>

                            {{-- DITERIMA --}}
                            <td style="border:none; width:50%; vertical-align:top;">

                                {{ $perubahan->persetujuanPerubahan->persetujuan_manajemen == 'disetujui' ? '☑' : '☐' }}
                                Diterima

                            </td>

                            {{-- TTD --}}
                            <td style="border:none; text-align:center; vertical-align:top;">

                                Tanda Tangan:

                                <br>

                                @if (!empty($perubahan->persetujuanPerubahan->signature_manajemen))
                                    <img class="signature-below"
                                        src="{{ public_path('storage/' . $perubahan->persetujuanPerubahan->signature_manajemen) }}">
                                @endif

                            </td>

                        </tr>

                    </table>

                </td>

            </tr>

        </table>

    </div>

@endsection


<style>
    @page {
        margin: 10px;
    }

    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 10px;
        margin: 0;
    }

    .page {
        margin: 8px 14px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        page-break-inside: avoid;
    }

    td,
    th {
        border: 1px solid #000;
        padding: 4px 5px;
        vertical-align: top;
        word-wrap: break-word;
    }

    .text-center {
        text-align: center;
    }

    .text-bold {
        font-weight: bold;
    }

    .middle {
        vertical-align: middle;
    }

    .section-title {
        text-align: center;
        font-weight: bold;
        font-size: 11px;
        padding: 3px;
    }

    .section-gray {
        background-color: #d9d9d9;
    }

    .header-title {
        font-size: 16px;
        font-weight: bold;
        text-align: center;
        vertical-align: middle;
        line-height: 1.3;
    }

    .h-20 {
        height: 20px;
    }

    .h-50 {
        height: 50px;
    }

    .h-80 {
        height: 80px;
    }

    .h-120 {
        height: 120px;
    }

    .signature-inline {
        height: 38px;
        width: auto;
        vertical-align: middle;
        margin-left: 15px;
    }

    .signature-below {
        height: 45px;
        width: auto;
        margin-top: 4px;
    }
</style>
