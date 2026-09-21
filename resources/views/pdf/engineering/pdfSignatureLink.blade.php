@extends('pdf.layout.layout')

@section('title', 'Tanda Tangan Berita Acara - ' . $pic->beritaAcara->no_surat)

@section('content')
    <!-- Tailwind CSS CDN (jika belum ada di layout utama) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

    <div class="min-h-screen bg-slate-100 py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto space-y-6">

            <!-- Header Informasi Resmi -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    @if ($logoBase64)
                        <img src="{{ $logoBase64 }}" class="w-12 h-12 object-contain" alt="Logo">
                    @endif
                    <div>
                        <h1 class="text-xl font-bold text-slate-800">Konfirmasi & Tanda Tangan Berita Acara</h1>
                        <p class="text-sm text-slate-500">No. Surat: <span
                                class="font-semibold text-slate-700">{{ $pic->beritaAcara->no_surat }}</span></p>
                    </div>
                </div>
                <div class="text-right hidden sm:block">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-200">
                        Confidential
                    </span>
                </div>
            </div>

            <!-- CONTAINER UTAMA: PREVIEW DOKUMEN BERITA ACARA -->
            <div class="bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden">
                <div class="bg-slate-800 px-6 py-3 text-white text-sm font-medium flex justify-between items-center">
                    <span>Pratinjau Isi Berita Acara</span>
                    <span class="text-xs text-slate-300">Geser ke bawah untuk membaca seluruh dokumen &
                        menandatangani</span>
                </div>

                <!-- Kertas Simulasi Dokumen A4 -->
                <div
                    class="p-8 sm:p-12 bg-white text-slate-800 font-sans text-sm leading-relaxed space-y-6 max-h-[600px] overflow-y-auto border-b border-slate-200">

                    <!-- KOP SURAT / JUDUL -->
                    <div class="text-center border-b pb-4">
                        <h2 class="text-lg font-bold uppercase tracking-wider">BERITA ACARA SERAH TERIMA / SERVICE</h2>
                        <p class="text-xs text-slate-500">Tanggal:
                            {{ \Carbon\Carbon::parse($pic->beritaAcara->tanggal)->translatedFormat('d F Y') }}</p>
                    </div>

                    <!-- ISI DATA DOKUMEN -->
                    <div class="grid grid-cols-2 gap-4 text-xs sm:text-sm bg-slate-50 p-4 rounded-lg">
                        <div>
                            <p><strong class="text-slate-600">Pelanggan:</strong>
                                {{ $pic->beritaAcara->spkService->perusahaan ?? '-' }}</p>
                            <p><strong class="text-slate-600">Penyedia Jasa:</strong>
                                {{ $pic->jasa_name ?? '-' }}</p>
                        </div>
                        <div>
                            <p><strong class="text-slate-600">No SPK:</strong>
                                {{ $pic->beritaAcara->spkService->no_spk_service ?? '-' }}</p>
                            <p><strong class="text-slate-600">PIC:</strong>
                                {{ $pic->beritaAcara->penyediaJasa->nama ?? '-' }} </p>
                        </div>
                    </div>

                    <!-- DESKRIPSI / DETAIL PEKERJAAN -->
                    <div class="space-y-2">
                        <p class="font-semibold text-slate-700">Detail Pekerjaan:</p>
                        <div class="p-4 border border-slate-200 rounded-lg text-xs sm:text-sm bg-white">
                            {!! nl2br(
                                e(
                                    $pic->beritaAcara->detail->desc_pekerjaan ??
                                        'Pekerjaan telah diselesaikan sesuai dengan ketentuan dan spesifikasi yang disepakati.',
                                ),
                            ) !!}
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden mb-6">
                        <div
                            class="bg-slate-800 px-6 py-3 text-white text-sm font-medium flex justify-between items-center">
                            <span>Pratinjau Dokumen Berita Acara (PDF)</span>
                            <!-- Tombol untuk membuka PDF di tab baru jika ingin melihat ukuran penuh -->
                            <a href="{{ route('pdf.beritaAcara', ['record' => $pic->berita_id]) }}" target="_blank"
                                class="text-xs text-blue-300 hover:text-white underline font-medium flex items-center gap-1">
                                🔍 Buka Fullscreen
                            </a>
                        </div>

                        <!-- EMBED PDF ASLI -->
                        <div class="p-4 bg-slate-50">
                            <embed
                                src="{{ route('pdf.beritaAcara', ['record' => $pic->berita_id]) }}#toolbar=0&navpanes=0&scrollbar=0"
                                type="application/pdf"
                                class="w-full h-[600px] rounded-lg border border-slate-300 shadow-sm bg-white">
                        </div>

                        <div
                            class="px-6 py-3 bg-slate-100 border-t border-slate-200 text-xs text-slate-500 flex justify-between items-center">
                            <span>* Silakan gulir (scroll) ke bawah pada kotak dokumen di atas untuk membaca isi
                                lengkapnya.</span>
                            <span class="font-semibold text-slate-700">Status: Menunggu Tanda Tangan Customer</span>
                        </div>
                    </div>

                    <div class="text-xs text-slate-500 italic pt-4">
                        * Dengan menandatangani formulir di bawah ini, pihak pelanggan menyatakan bahwa seluruh pekerjaan di
                        atas telah diperiksa dan disetujui.
                    </div>
                </div>

                <!-- FORMULIR INPUT & TANDA TANGAN (MENYATU DI BAGIAN BAWAH DOKUMEN) -->
                <div class="p-6 sm:p-8 bg-slate-50">
                    <form method="POST"
                        action="{{ route('signature.store', ['type' => $type, 'token' => $record->sign_token ?? 'token']) }}"
                        id="form-signature" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Input Nama -->
                            <div>
                                <label for="nama"
                                    class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-2">Nama
                                    Lengkap Penandatangan</label>
                                <input name="{{ $config['name_field'] }}" type="text" id="nama"
                                    placeholder="Masukkan nama lengkap Anda..." required
                                    class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg text-slate-800 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none transition shadow-sm" />
                            </div>

                            <!-- Info Keamanan -->
                            <div
                                class="flex items-center text-xs text-slate-500 bg-blue-50/50 p-4 rounded-lg border border-blue-100">
                                <div>
                                    <span class="font-semibold text-blue-700">Catatan Keamanan:</span>
                                    <p id="msg">Sistem akan mencatat waktu dan alamat IP perangkat Anda untuk validasi
                                        sah dokumen.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Kotak Tanda Tangan Canvas -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Buat
                                    Tanda Tangan di Kotak Bawah Ini</label>
                                <button type="button" id="clear"
                                    class="text-xs font-medium text-red-600 hover:text-red-800 transition">
                                    🔄 Ulangi Tanda Tangan
                                </button>
                            </div>

                            <div class="signature-area border-2 border-dashed border-slate-300 rounded-xl bg-white relative overflow-hidden shadow-inner h-48 cursor-crosshair"
                                id="pad">
                                <canvas id="canvas" class="w-full h-full touch-none"></canvas>
                                <!-- Tambahkan class 'flex' di awal agar properti flex-nya dikenali saat dimunculkan kembali -->
                                <div
                                    class="placeholder absolute inset-0 flex items-center justify-center text-slate-400 text-sm pointer-events-none select-none">
                                    Tulis tanda tangan Anda di sini menggunakan mouse / layar sentuh
                                </div>
                                <input type="hidden" name="{{ $config['signature_field'] }}" id="signature">
                            </div>
                        </div>

                        <!-- Tombol Submit -->
                        <div>
                            <button type="submit"
                                class="w-full py-3 px-6 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-md transition duration-200 text-sm flex items-center justify-center space-x-2">
                                <span>Simpan & Sahkan Berita Acara</span>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Script Signature Pad -->
    <script>
        const canvas = document.getElementById("canvas");
        const clearBtn = document.getElementById("clear");
        const pad = document.getElementById("pad");
        const form = document.querySelector("form");
        const signatureInput = document.getElementById("signature");
        const msg = document.getElementById("msg");
        const placeholder = pad.querySelector(".placeholder");

        let signaturePad;

        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
        }

        window.addEventListener("resize", resizeCanvas);
        resizeCanvas();

        signaturePad = new SignaturePad(canvas, {
            penColor: "#1e3a8a",
            minWidth: 1.0,
            maxWidth: 2.5,
            velocityFilterWeight: 0.7
        });

        // 1. SAAT MULAI MENULIS: Sembunyikan placeholder menggunakan kelas Tailwind 'hidden'
        signaturePad.addEventListener("beginStroke", function() {
            placeholder.classList.add("hidden");
        });

        signaturePad.addEventListener("endStroke", function() {
            if (!signaturePad.isEmpty()) {
                pad.classList.add("filled");
            }
        });

        // 2. TOMBOL CLEAR (RESET): Munculkan kembali dengan menghapus kelas 'hidden'
        clearBtn.addEventListener("click", function() {
            signaturePad.clear();
            pad.classList.remove("filled");
            placeholder.classList.remove(
                "hidden"); // Kembali memunculkan dan posisinya otomatis di tengah karena bawaan 'flex'
        });

        // 3. VALIDASI SAAT SUBMIT
        form.addEventListener("submit", function(e) {
            if (signaturePad.isEmpty()) {
                msg.textContent = "⚠️ Silakan buat tanda tangan terlebih dahulu sebelum mengirim!";
                msg.style.color = "#dc2626";
                e.preventDefault();
                return;
            }
            signatureInput.value = signaturePad.toDataURL("image/png");
        });
    </script>
@endsection
