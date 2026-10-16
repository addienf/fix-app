@extends('pdf.layout.layout')

@section('title', 'Link Expired')

@section('content')
    <main class="flex min-h-screen items-start justify-center bg-slate-100 p-3 sm:items-center sm:p-6">
        <div
            class="w-full max-w-[540px] overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl shadow-slate-900/5">

            {{-- Header: logo + judul + badge. Logo di chip putih supaya tetap terbaca di latar navy --}}
            <header class="flex items-center gap-3 bg-slate-800 px-5 py-4 text-white sm:px-8">
                @if (!empty($logoBase64))
                    <span class="flex shrink-0 items-center rounded-lg bg-white px-2.5 py-1.5">
                        <img src="{{ $logoBase64 }}" alt="QLab" class="h-7 w-auto">
                    </span>
                @endif

                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold leading-tight">Konfirmasi &amp; Tanda Tangan Berita Acara</p>
                    <p class="mt-0.5 text-xs text-slate-300">Status link</p>
                </div>

                <span
                    class="hidden shrink-0 rounded-full bg-white/10 px-2.5 py-0.5 text-[11px] font-semibold text-slate-200 sm:inline-flex">
                    Confidential
                </span>
            </header>

            {{-- Isi: hanya ada satu fokus visual (ikon status), tidak berebut dengan logo --}}
            <div class="px-6 pb-8 pt-10 text-center sm:px-10">
                <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full border border-amber-200 bg-amber-50 ring-8 ring-amber-50/60"
                    aria-hidden="true">
                    <svg class="h-10 w-10 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9" />
                        <path d="M12 7v5l3 2" />
                    </svg>
                </div>

                <h1 class="mb-3 text-2xl font-bold text-slate-900">Link Sudah Tidak Berlaku</h1>

                <p class="mx-auto max-w-md text-base leading-relaxed text-slate-600">
                    Link tanda tangan ini sudah kedaluwarsa atau tidak valid.
                    Demi keamanan dokumen, link hanya berlaku dalam waktu terbatas.
                </p>

                <p class="mt-4 text-sm text-slate-500">Tenang, ini bukan kesalahan Anda.</p>

                {{-- Langkah selanjutnya: gaya sama dengan "Catatan Keamanan" --}}
                <section class="mt-8 rounded-xl border border-blue-100 bg-blue-50 p-5 text-left"
                    aria-labelledby="next-steps">
                    <h2 id="next-steps" class="mb-3 text-sm font-bold text-blue-700">Langkah selanjutnya</h2>

                    <ol class="space-y-3">
                        <li class="flex items-start gap-3 text-sm leading-relaxed text-slate-700">
                            <span
                                class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-blue-600 text-[11px] font-bold text-white">1</span>
                            <span>Hubungi teknisi yang mengirimkan link ini.</span>
                        </li>
                        <li class="flex items-start gap-3 text-sm leading-relaxed text-slate-700">
                            <span
                                class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-blue-600 text-[11px] font-bold text-white">2</span>
                            <span>Minta dikirimkan link tanda tangan yang baru.</span>
                        </li>
                        <li class="flex items-start gap-3 text-sm leading-relaxed text-slate-700">
                            <span
                                class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-blue-600 text-[11px] font-bold text-white">3</span>
                            <span>Buka link baru tersebut untuk menandatangani berita acara.</span>
                        </li>
                    </ol>
                </section>

                <button type="button" id="closeBtn"
                    class="mt-6 w-full rounded-lg bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-blue-200">
                    Tutup Halaman
                </button>

                <p id="closeHint" role="status" class="mt-3 hidden text-sm text-slate-500">
                    Halaman tidak bisa ditutup otomatis. Silakan tutup tab ini secara manual.
                </p>
            </div>

            <footer class="border-t border-slate-200 bg-slate-50 px-6 py-4 text-center text-xs text-slate-500">
                &copy; {{ date('Y') }} PT. QLab Kinarya Sentosa
            </footer>
        </div>
    </main>

    <script>
        document.getElementById('closeBtn').addEventListener('click', function() {
            window.close();

            // Browser memblokir window.close() untuk tab yang tidak dibuka lewat script
            setTimeout(function() {
                document.getElementById('closeHint').classList.remove('hidden');
            }, 300);
        });
    </script>
@endsection
