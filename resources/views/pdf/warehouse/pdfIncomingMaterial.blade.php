@extends ('pdf.layout.layout')
@section('title', 'Incoming Material PDF')
@section('content')
    {{-- ================= HEADER (BAKU) ================= --}}
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
                FORMULIR INCOMING MATERIAL
            </td>

            <td style="border:0.5px solid #000; padding-left:8px;">
                No. Dokumen
            </td>
            <td style="border:0.5px solid #000; text-align:center;">
                FO-QKS-WRH-01-01
            </td>
        </tr>

        <tr>
            <td style="border:0.5px solid #000; padding-left:8px;">
                Tanggal Rilis
            </td>
            <td style="border:0.5px solid #000; text-align:center;">
                {{ \Carbon\Carbon::parse($incomingMaterial->tanggal)->translatedFormat('d F Y') }}
            </td>
        </tr>

        <tr>
            <td style="border:0.5px solid #000; padding-left:8px;">
                Revisi
            </td>
            <td style="border:0.5px solid #000; text-align:center;">
                00
            </td>
        </tr>
    </table>

    {{-- ================= A. INFORMASI UMUM ================= --}}
    <div class="title">A. Informasi Umum</div>
    <table class="no-border">
        <tr>
            <td width="30%">Nomor</td>
            <td>
                :
                @php
                    $noSurat = $incomingMaterial->permintaanPembelian?->permintaanBahanWBB?->no_surat;
                    $createdAt = $incomingMaterial->permintaanPembelian?->created_at?->format('YmdHis');
                @endphp

                {{ $noSurat ?: "Untuk Stock Pembelian - {$createdAt}" }}
            </td>
        </tr>
        <tr>
            <td>Tanggal Penerimaan</td>
            <td>: {{ \Carbon\Carbon::parse($incomingMaterial->tanggal)->translatedFormat('d F Y') }}</td>
        </tr>
    </table>

    {{-- ================= B. INFORMASI MATERIAL ================= --}}
    <div class="title">B. Informasi Material</div>
    <table>
        <tr>
            <th class="col-no">No</th>
            <th>Nama Material</th>
            <th>Batch No</th>
            <th>Jumlah</th>
            <th>Satuan</th>
            <th>Kondisi</th>
            <th>Status QC</th>
        </tr>

        @foreach ($incomingMaterial->details as $i => $item)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $item->nama_material }}</td>
                <td>{{ $item->batch_no }}</td>
                <td class="text-center">{{ $item->jumlah ?? '-' }}</td>
                <td class="text-center">{{ $item->satuan ?? '-' }}</td>
                <td>{{ $item->kondisi_material ?? '-' }}</td>
                <td class="text-center">{{ $item->status_qc ? 'Ya' : 'Tidak' }}</td>
            </tr>
        @endforeach
    </table>

    {{-- ================= C. PEMERIKSAAN & STATUS ================= --}}
    <div class="title">C. Pemeriksaan & Status</div>

    <table class="no-border">
        <tr>
            <!-- KIRI -->
            <td width="50%" valign="top">
                <b>Kondisi Material</b>
                <table class="no-border" style="margin-top:6px;">
                    <tr>
                        <td width="20">
                            <input type="checkbox" {{ $incomingMaterial->kondisi_material == 1 ? 'checked' : '' }}>
                        </td>
                        <td>Baik</td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" {{ $incomingMaterial->kondisi_material == 0 ? 'checked' : '' }}>
                        </td>
                        <td>Tidak</td>
                    </tr>
                </table>
            </td>

            <!-- KANAN -->
            <td width="50%" valign="top">
                <b>Status Penerimaan</b>
                <table class="no-border" style="margin-top:6px;">
                    <tr>
                        <td width="20">
                            <input type="checkbox" {{ $incomingMaterial->status_penerimaan == 1 ? 'checked' : '' }}>
                        </td>
                        <td>Diterima</td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" {{ $incomingMaterial->status_penerimaan == 0 ? 'checked' : '' }}>
                        </td>
                        <td>Ditolak / Dikembalikan</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- ================= D. DOKUMEN PENDUKUNG ================= --}}
    <div class="title">D. Dokumen Pendukung</div>

    <table class="no-border">
        <tr>
            <td width="20">
                <input type="checkbox" {{ $incomingMaterial->dokumen_pendukung == 1 ? 'checked' : '' }}>
            </td>
            <td>
                Laporan QC (Quality Control)
            </td>
        </tr>
    </table>

    <br><br>

    {{-- ================= SIGNATURE (BAKU – 2 ORANG) ================= --}}
    <table class="no-border">
        <tr>
            <td class="text-center" width="50%">
                Diserahkan Oleh,<br><br>

                <div class="signature-box">
                    @if (!empty($incomingMaterial->pic->submited_signature))
                        <img src="{{ public_path('storage/' . $incomingMaterial->pic->submited_signature) }}">
                    @endif
                </div>

                <br>
                <b>{{ $incomingMaterial->pic->submitedName->name ?? '-' }}</b>
            </td>

            <td class="text-center" width="50%">
                Diterima Oleh,<br><br>

                <div class="signature-box">
                    @if (!empty($incomingMaterial->pic->received_signature))
                        <img src="{{ public_path('storage/' . $incomingMaterial->pic->received_signature) }}">
                    @endif
                </div>

                <br>
                <b>{{ $incomingMaterial->pic->receivedName->name ?? '-' }}</b>
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
        background-color: #f2f2f2;
    }

    .no-border td {
        border: none !important;
        padding: 3px;
    }

    .text-center {
        text-align: center;
    }

    .title {
        font-size: 12.5px;
        font-weight: bold;
        margin: 10px 0 4px;
    }

    .col-no {
        width: 40px;
    }

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

{{-- <script>
    function exportPDF(id) {
        window.scrollTo(0, 0);

        const element = document.getElementById("export-area");
        const images = element.getElementsByTagName("img");
        const totalImages = images.length;
        let loadedImages = 0;

        for (let img of images) {
            if (img.complete) {
                loadedImages++;
            } else {
                img.onload = () => {
                    loadedImages++;
                    if (loadedImages === totalImages) renderPDF();
                };
            }
        }

        if (loadedImages === totalImages) {
            renderPDF();
        }

        function renderPDF() {
            html2pdf().set({
                    margin: [0.2, 0.2, 0.2, 0.2],
                    filename: "incoming-material.pdf",
                    image: {
                        type: "jpeg",
                        quality: 1
                    },
                    html2canvas: {
                        scale: 3,
                        useCORS: true,
                        letterRendering: true
                    },
                    jsPDF: {
                        unit: "in",
                        format: "a4",
                        orientation: "portrait"
                    },
                    pagebreak: {
                        mode: ["avoid", "css"]
                    }
                })
                // .from(element).save().then(() => {
                //     window.location.href = `/warehouse/incoming-material/${id}/download-file`;
                // });
                .from(element).save().then(() => {
                    const url = `/warehouse/incoming-material/${id}/download-file`;

                    fetch(url, {
                            method: 'GET'
                        })
                        .then(response => {
                            if (!response.ok) {
                                console.warn("File tidak ditemukan");
                                return;
                            }
                            window.location.href = url;
                        })
                        .catch(error => {
                            console.error("Error saat mengecek file:", error);
                        });
                });
        }
    }
</script> --}}
