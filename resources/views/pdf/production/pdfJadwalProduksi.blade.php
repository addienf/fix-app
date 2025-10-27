@extends ('pdf.layout.layout')
@section('title', 'Jadwal Produksi PDF')
@section('content')
    <div id="export-area" class="p-2 text-black bg-white">
        <table
            class="w-full max-w-4xl mx-auto text-sm border border-black dark:border-white dark:bg-gray-900 dark:text-white"
            style="border-collapse: collapse;">
            <tr>
                <td rowspan="3"
                    class="p-2 text-center align-middle border border-black w-28 h-28 dark:border-white dark:bg-gray-900">
                    <img src="{{ asset('asset/logo.png') }}" alt="Logo" class="object-contain mx-auto h-30" />
                </td>
                <td colspan="2" class="font-bold text-center border border-black dark:border-white dark:bg-gray-900">
                    PT. QLab Kinarya Sentosa
                </td>
            </tr>
            <tr>
                <td class="font-bold text-center border border-black dark:border-white dark:bg-gray-900"
                    style="font-size: 20px;">
                    Perencanaan Produksi
                </td>
                <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                    <table class="w-full text-sm dark:bg-gray-900 dark:text-white" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> :
                                FO-QKS-PRO-01-01</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">Tanggal Rilis</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> : 23 September 2025
                            </td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2">Revisi</td>
                            <td class="px-3 py-2 font-semibold"> : 02</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        @php
            $infoUmum = [
                [
                    'label' => 'Tanggal:',
                    'value' => \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d F Y'),
                ],
                ['label' => 'Penanggung Jawab:', 'value' => $jadwal->pic_name],
                ['label' => 'Nomor Surat:', 'value' => $jadwal->no_surat],
                ['label' => 'SPK MKT:', 'value' => $jadwal->spk->no_spk],
            ];
        @endphp

        <div class="max-w-4xl pt-6 mx-auto text-lg font-bold text-start">A. Informasi Umum</div>
        <div class="grid w-full max-w-4xl grid-cols-1 pt-2 mx-auto mb-4 text-sm gap-y-4">
            @foreach ($infoUmum as $field)
                <div class="flex items-center gap-4">
                    <label class="w-40 font-medium">{{ $field['label'] }}</label>
                    <input type="text"
                        class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                        value="{{ $field['value'] }}" readonly />
                </div>
            @endforeach
        </div>

        <!-- B. Identifikasi Produk -->
        <div class="max-w-4xl pt-2 mx-auto mb-4 text-lg font-bold text-start">B. Identifikasi Produk</div>
        <div class="max-w-4xl mx-auto overflow-x-auto">
            <table class="w-full text-sm text-left border border-gray-300 dark:border-gray-600">
                <thead class="text-black bg-gray-100 dark:bg-gray-800 dark:text-white">
                    <tr>
                        <th class="px-4 py-2 border">No</th>
                        <th class="px-4 py-2 border">Nama Alat</th>
                        <th class="px-4 py-2 border">Tipe</th>
                        <th class="px-4 py-2 border">Nomor Serial</th>
                        <th class="px-4 py-2 border">Custom/Standar</th>
                        <th class="px-4 py-2 border">Quantity</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900">
                    @foreach ($jadwal->identifikasiProduks as $index => $produk)
                        <tr>
                            <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 border">{{ $produk['nama_alat'] }}</td>
                            <td class="px-4 py-2 border">{{ $produk['tipe'] }}</td>
                            <td class="px-4 py-2 border">{{ $produk['no_seri'] }}</td>
                            <td class="px-4 py-2 border">{{ $produk['custom_standar'] }}</td>
                            <td class="px-4 py-2 border">{{ $produk['jumlah'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- C. Detail Jadwal Produksi -->
        <div class="max-w-4xl pt-2 mx-auto mb-4 text-lg font-bold text-start">C. Detail Jadwal Produksi</div>
        <div class="max-w-4xl mx-auto overflow-x-auto">
            <table class="w-full text-sm text-left border border-gray-300 dark:border-gray-600">
                <thead class="text-black bg-gray-100 dark:bg-gray-800 dark:text-white">
                    <tr>
                        <th class="px-4 py-2 border">No</th>
                        <th class="px-4 py-2 border">Pekerjaan</th>
                        <th class="px-4 py-2 border">Yang Mengerjakan</th>
                        <th class="px-4 py-2 border">Tanggal Mulai</th>
                        <th class="px-4 py-2 border">Tanggal Selesai</th>
                        <th class="px-4 py-2 border">Penanggung Jawab</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900">
                    @foreach ($jadwal->details as $index => $produk)
                        <tr>
                            <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 border">{{ $produk['pekerjaan'] }}</td>
                            <td class="px-4 py-2 border">{{ $produk['pekerja'] }}</td>
                            <td class="px-4 py-2 border">
                                {{ \Carbon\Carbon::parse($produk['tanggal_mulai'])->translatedFormat('d M Y') }}</td>
                            <td class="px-4 py-2 border">
                                {{ \Carbon\Carbon::parse($produk['tanggal_selesai'])->translatedFormat('d M Y') }}</td>
                            <td>{{ $jadwal->pic_name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- E. Kebutuhan bahan/alat -->
        <div class="max-w-4xl pt-2 mx-auto mb-4 text-lg font-bold text-start">E. Kebutuhan bahan/alat</div>
        <div class="max-w-4xl pb-5 mx-auto overflow-x-auto">
            <table class="w-full text-sm text-left border border-gray-300 dark:border-gray-600">
                <thead class="text-black bg-gray-100 dark:bg-gray-800 dark:text-white">
                    <tr>
                        <th class="px-4 py-2 border">No</th>
                        <th class="px-4 py-2 border">Nama Bahan Baku</th>
                        <th class="px-4 py-2 border">Spesifikasi</th>
                        <th class="px-4 py-2 border">Quantity</th>
                        <th class="px-4 py-2 border">Status (Diterima atau Belum)</th>
                        <th class="px-4 py-2 border">Keperluan</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900">
                    @foreach ($jadwal->sumbers as $index => $produk)
                        <tr>
                            <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 border">{{ $produk['bahan_baku'] }}</td>
                            <td class="px-4 py-2 border">{{ $produk['spesifikasi'] }}</td>
                            <td class="px-4 py-2 border">{{ $produk['jumlah'] }}</td>
                            <td class="px-4 py-2 border">{{ $produk['status'] }}</td>
                            <td class="px-4 py-2 border">{{ $produk['keperluan'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

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

        <div class="page-break">
            <div class="timeline-container">
                <div class="timeline-title">F. Timeline Produksi</div>

                @foreach ($periodeBulan as $periode)
                    @php
                        $bulan = $periode->month;
                        $tahun = $periode->year;
                        $jumlahHari = \Carbon\Carbon::create($tahun, $bulan)->daysInMonth;
                        $namaBulan = \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y');
                    @endphp

                    <div class="timeline-month">{{ $namaBulan }}</div>

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
                                            <td
                                                class="col-day {{ $i >= $arsirMulai && $i <= $arsirSelesai ? 'fill-day' : '' }}">
                                                &nbsp;
                                            </td>
                                        @endfor
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                @endforeach
            </div>
        </div>

        <!-- Signature -->
        <div class="max-w-4xl mx-auto mt-10 text-sm page-break-inside: avoid;">
            <div class="flex items-start justify-between gap-4">
                <!-- Yang Membuat -->
                <div class="flex flex-col items-center">
                    <p class="mb-2 dark:text-white">Dibuat Oleh,</p>
                    <img src="{{ asset('storage/' . $jadwal->pic->create_signature) }}" alt="Signature Pembuat"
                        class="h-20 w-80" />
                    <div class="mt-2 font-medium">
                        {{ $jadwal->pic->createName->name }}
                    </div>
                </div>
                <!-- Yang Menerima -->
                <div class="flex flex-col items-center">
                    <p class="mb-2 dark:text-white">Disetujui Oleh,</p>
                    <img src="{{ asset('storage/' . $jadwal->pic->approve_signature) }}" alt="Signature Penerima"
                        class="h-20 w-80" />
                    <div class="mt-2 font-medium">
                        {{ $jadwal->pic->approveName->name }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-6 mb-3 text-center">
        <button onclick="exportPDF('{{ $jadwal->id }}')"
            class="inline-flex items-center gap-2 py-3 text-sm font-semibold text-black text-white bg-blue-600 border rounded border-animated px-7 border-black-400 hover:bg-purple-600 hover:text-white">
            <!-- Icon download SVG -->
            <svg class="w-5 h-5 transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4">
                </path>
            </svg>
            Download PDF
        </button>
    </div>
@endsection

<style>
    /* Biar setiap div timeline mulai di halaman baru */
    .page-break {
        page-break-before: always;
        page-break-inside: avoid;
    }
</style>

<style>
    .page-break {
        page-break-before: always;
        page-break-inside: avoid;
    }

    .timeline-container {
        max-width: 64rem;
        /* sama seperti max-w-4xl */
        margin: 0 auto 1.5rem auto;
        padding: 0 0.5rem;
        page-break-inside: avoid;
    }

    .timeline-title {
        font-size: 1.125rem;
        font-weight: 700;
        text-align: left;
        margin-bottom: 0.75rem;
    }

    .timeline-month {
        text-align: center;
        font-weight: 600;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }

    .timeline-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 10px;
        text-align: center;
        table-layout: fixed;
        border: 1px solid #9ca3af;
        /* gray-400 */
    }

    .timeline-table th,
    .timeline-table td {
        border: 1px solid #9ca3af;
        padding: 2px;
        height: 14px;
    }

    .timeline-table th {
        background-color: #f3f4f6;
        font-weight: 600;
    }

    .col-no {
        width: 5%;
        min-width: 25px;
    }

    .col-task {
        width: 20%;
        min-width: 160px;
        text-align: left;
        padding-left: 4px;
    }

    .col-day {
        width: calc(75% / 31);
        min-width: 12px;
    }

    .fill-day {
        background-color: #000 !important;
    }

    @media print {
        .timeline-container {
            overflow: visible !important;
        }

        .timeline-table {
            width: 100% !important;
        }
    }
</style>

<script>
    function exportPDF(id) {
        window.scrollTo(0, 0);

        const element = document.getElementById("export-area");

        // Pastikan semua gambar sudah termuat sebelum render
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
                    filename: "jadwal-produksi.pdf",
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
                        orientation: "portrait" // tetap portrait
                    },
                    pagebreak: {
                        mode: ["avoid", "css", "legacy"], // tambahkan legacy biar lebih stabil
                        before: ".page-break" // penting! setiap .page-break = halaman baru
                    }
                })
                .from(element)
                .save()
                .then(() => {
                    window.location.href = `/produksi/jadwal-produksi/${id}/download-file`;
                });
        }
    }
</script>
