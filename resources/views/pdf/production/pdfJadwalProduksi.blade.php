@extends ('pdf.layout.layout')
@section('title', 'Jadwal Produksi PDF')
@section('content')
    {{-- HEADER --}}
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
                style="text-align:center; font-size:16px; font-weight:bold; border:0.5px solid #000; vertical-align:middle;">
                PERENCANAAN PRODUKSI
            </td>

            <td style="border:0.5px solid #000; padding-left:8px;">
                No. Dokumen
            </td>
            <td style="border:0.5px solid #000; text-align:center;">
                FO-QKS-PRO-01-01
            </td>
        </tr>

        <tr>
            <td style="border:0.5px solid #000; padding-left:8px;">
                Tanggal Rilis
            </td>
            <td style="border:0.5px solid #000; text-align:center;">
                {{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d F Y') }}
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

    {{-- A. INFORMASI UMUM --}}
    <div class="title">A. Informasi Umum</div>
    <table class="no-border">
        <tr>
            <td width="30%">Tanggal</td>
            <td>: {{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Penanggung Jawab</td>
            <td>: {{ $jadwal->pic_name }}</td>
        </tr>
        <tr>
            <td>Nomor Surat</td>
            <td>: {{ $jadwal->no_surat }}</td>
        </tr>
        <tr>
            <td>SPK MKT</td>
            <td>: {{ $jadwal->spk->no_spk }}</td>
        </tr>
    </table>
    {{-- <table class="no-border" width="100%">
        <tr>
            <td width="15%">Nomor</td>
            <td width="35%">: {{ $jadwal->no_surat }}</td>
            <td width="15%">Tanggal</td>
            <td width="35%">: {{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Dari</td>
            <td>: {{ $jadwal->dari }}</td>
            <td>Kepada</td>
            <td>: {{ $jadwal->kepada }}</td>
        </tr>
    </table> --}}

    {{-- B. IDENTIFIKASI PRODUK --}}
    <div class="title">B. Identifikasi Produk</div>
    <table>
        <tr>
            <th>No</th>
            <th>Nama Alat</th>
            <th>Tipe</th>
            <th>Nomor Serial</th>
            <th>Custom / Standar</th>
            <th>QTY</th>
        </tr>
        @foreach ($jadwal->identifikasiProduks as $i => $p)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $p->nama_alat }}</td>
                <td>{{ $p->tipe }}</td>
                <td>{{ $p->no_seri }}</td>
                <td class="text-center">{{ $p->custom_standar }}</td>
                <td class="text-center">{{ $p->jumlah }}</td>
            </tr>
        @endforeach
    </table>

    {{-- C. DETAIL JADWAL --}}
    <div class="title">C. Detail Jadwal Produksi</div>
    <table>
        <tr>
            <th>No</th>
            <th>Pekerjaan</th>
            <th>Yang Mengerjakan</th>
            <th>Tgl Mulai</th>
            <th>Tgl Selesai</th>
            <th>Penanggung Jawab</th>
        </tr>
        @foreach ($jadwal->details as $i => $d)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $d->pekerjaan }}</td>
                <td>{{ $d->pekerja }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($d->tanggal_mulai)->format('d-m-Y') }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($d->tanggal_selesai)->format('d-m-Y') }}</td>
                <td>{{ $d->penanggung_jawab ?? '-' }}</td>
            </tr>
        @endforeach
    </table>

    @php
        $sumbersGrouped = $jadwal->sumbers->groupBy('kategori');
    @endphp
    {{-- E. KEBUTUHAN BAHAN (SPLIT KATEGORI) --}}
    <div class="title">D. Kebutuhan Bahan / Alat</div>
    @foreach (['Electrical', 'UPS', 'Mechanical'] as $kategori)
        @if (!empty($sumbersGrouped[$kategori]))
            <div class="subtitle">Kebutuhan Pengerjaan {{ $kategori }}</div>
            <table>
                <tr>
                    <th>No</th>
                    <th>Nama Bahan Baku</th>
                    <th>Spesifikasi</th>
                    <th>QTY</th>
                    <th>Status</th>
                    <th>Keperluan</th>
                </tr>
                @foreach ($sumbersGrouped[$kategori] as $i => $s)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td>{{ $s->bahan_baku }}</td>
                        <td>{{ $s->spesifikasi }}</td>
                        <td class="text-center">{{ $s->jumlah }}</td>
                        <td class="text-center">{{ $s->status }}</td>
                        <td>{{ $s->keperluan }}</td>
                    </tr>
                @endforeach
            </table>
        @endif
    @endforeach

    @php
        $tanggalMulai = \Carbon\Carbon::parse($jadwal->timelines->min('tanggal_mulai'))->startOfMonth();
        $tanggalSelesai = \Carbon\Carbon::parse($jadwal->timelines->max('tanggal_selesai'))->endOfMonth();

        $periodeBulan = collect();
        $current = $tanggalMulai->copy();
        while ($current <= $tanggalSelesai) {
            $periodeBulan->push($current->copy());
            $current->addMonthNoOverflow();
        }
    @endphp

    <div class="timeline-title">F. Timeline Produksi</div>
    @foreach ($periodeBulan as $periode)
        @php
            $bulan = $periode->month;
            $tahun = $periode->year;
            $jumlahHari = \Carbon\Carbon::create($tahun, $bulan)->daysInMonth;
            $namaBulan = \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y');
        @endphp

        <div class="timeline-month">{{ strtoupper($namaBulan) }}</div>

        <table class="timeline-table">
            <thead>
                <tr>
                    <th class="col-no">No</th>
                    <th class="col-task">Task</th>
                    @for ($i = 1; $i <= $jumlahHari; $i++)
                        <th class="col-day">{{ $i }}</th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @foreach ($jadwal->timelines as $index => $timeline)
                    @php
                        $mulai = \Carbon\Carbon::parse($timeline->tanggal_mulai);
                        $selesai = \Carbon\Carbon::parse($timeline->tanggal_selesai);

                        $awalBulan = \Carbon\Carbon::create($tahun, $bulan, 1);
                        $akhirBulan = \Carbon\Carbon::create($tahun, $bulan, $jumlahHari);

                        $arsirMulai = $mulai->lessThan($awalBulan) ? 1 : $mulai->day;
                        $arsirSelesai = $selesai->greaterThan($akhirBulan) ? $jumlahHari : $selesai->day;
                    @endphp

                    @if ($selesai->greaterThanOrEqualTo($awalBulan) && $mulai->lessThanOrEqualTo($akhirBulan))
                        <tr>
                            <td class="col-no">{{ $index + 1 }}</td>
                            <td class="col-task">{{ $timeline->task }}</td>

                            @for ($i = 1; $i <= $jumlahHari; $i++)
                                <td class="col-day {{ $i >= $arsirMulai && $i <= $arsirSelesai ? 'fill-day' : '' }}">
                                    &nbsp;
                                </td>
                            @endfor
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @endforeach

    {{-- SIGNATURE --}}
    <table class="no-border">
        <tr>
            <td class="text-center" width="50%">
                Dibuat Oleh,<br><br>

                <div class="signature-box">
                    @if (!empty($jadwal->pic->create_signature))
                        <img src="{{ public_path('storage/' . $jadwal->pic->create_signature) }}">
                    @endif
                </div>

                <br>
                <b>{{ $jadwal->pic->createName->name }}</b>
            </td>

            <td class="text-center" width="50%">
                Disetujui Oleh,<br><br>

                <div class="signature-box">
                    @if (!empty($jadwal->pic->approve_signature))
                        <img src="{{ public_path('storage/' . $jadwal->pic->approve_signature) }}">
                    @endif
                </div>

                <br>
                <b>{{ $jadwal->pic->approveName->name }}</b>
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

    /* ================= TABLE GLOBAL ================= */
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
        text-align: center;
        font-weight: bold;
    }

    .no-border td {
        border: none !important;
        padding: 3px;
    }

    .text-center {
        text-align: center;
    }

    /* ================= TITLE ================= */
    .title {
        font-size: 12.5px;
        font-weight: bold;
        margin: 10px 0 4px;
    }

    .subtitle {
        font-size: 11.5px;
        font-weight: bold;
        margin: 8px 0 4px;
    }

    /* ================= PAGE BREAK ================= */
    .page-break {
        page-break-before: always;
    }

    /* ================= TIMELINE ================= */
    .timeline-title {
        font-size: 12.5px;
        font-weight: bold;
        margin-bottom: 6px;
    }

    .timeline-month {
        font-weight: bold;
        margin: 6px 0 4px;
    }

    .timeline-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        font-size: 9px;
    }

    .timeline-table th,
    .timeline-table td {
        border: 1px solid #000;
        padding: 2px;
        text-align: center;
    }

    .timeline-table .col-no {
        width: 25px;
    }

    .timeline-table .col-task {
        width: 140px;
        text-align: left;
        padding-left: 4px;
    }

    .timeline-table .col-day {
        width: 16px;
    }

    /* arsiran produksi */
    .fill-day {
        background-color: #000;
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
</style>
