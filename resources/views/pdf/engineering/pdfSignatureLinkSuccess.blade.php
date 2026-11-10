@extends('pdf.layout.layout')

@section('title', $title ?? 'Tanda Tangan Berhasil')

@section('content')
    <main class="flex min-h-screen items-start justify-center bg-slate-100 p-3 sm:items-center sm:p-6">
        <div
            class="w-full max-w-[540px] overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl shadow-slate-900/5">

            <header class="flex items-center gap-3 bg-slate-800 px-5 py-4 text-white sm:px-8">
                @if (!empty($logoBase64))
                    <span class="flex shrink-0 items-center rounded-lg bg-white px-2.5 py-1.5">
                        <img src="{{ $logoBase64 }}" alt="QLab" class="h-7 w-auto">
                    </span>
                @endif

                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold leading-tight">
                        {{ $headerTitle ?? 'Konfirmasi & Tanda Tangan Berita Acara' }}
                    </p>
                    <p class="mt-0.5 text-xs text-slate-300">
                        {{ $headerSubtitle ?? 'Status tanda tangan' }}
                    </p>
                </div>

                <span
                    class="hidden shrink-0 rounded-full bg-white/10 px-2.5 py-0.5 text-[11px] font-semibold text-slate-200 sm:inline-flex">
                    {{ $badgeText ?? 'Confidential' }}
                </span>
            </header>

            {{-- Isi: satu fokus visual (ikon sukses) --}}
            <div class="px-6 pb-8 pt-10 text-center sm:px-10">
                <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full border border-emerald-200 bg-emerald-50 ring-8 ring-emerald-50/60"
                    aria-hidden="true">
                    <svg class="h-10 w-10 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9" />
                        <path d="m8.5 12.5 2.5 2.5 4.5-5" />
                    </svg>
                </div>

                <h1 class="mb-3 text-2xl font-bold text-slate-900">
                    {{ $title ?? 'Tanda Tangan Berhasil Disimpan' }}
                </h1>

                <p class="mx-auto max-w-md text-base leading-relaxed text-slate-600">
                    {{ $message ?? 'Terima kasih. Berita acara telah ditandatangani dan disahkan.' }}
                </p>

                @php
                    $nomorDokumen = $docNumber ?? ($pic->beritaAcara->no_surat ?? null);
                    $labelDokumen = $docLabel ?? 'No. Surat:';
                @endphp

                {{-- Opsional: tampil otomatis hanya jika $pic dikirim dari controller --}}
                @if (!empty($nomorDokumen))
                    <p
                        class="mt-4 inline-flex max-w-full items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs text-slate-600">
                        <span>{{ $labelDokumen }}</span>
                        <span class="break-all font-semibold text-slate-800">{{ $nomorDokumen }}</span>
                    </p>
                @endif
                {{-- @isset($pic->beritaAcara->no_surat)
                    <p
                        class="mt-4 inline-flex max-w-full items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs text-slate-600">
                        <span>No. Surat:</span>
                        <span class="break-all font-semibold text-slate-800">{{ $pic->beritaAcara->no_surat }}</span>
                    </p>
                @endisset --}}

                {{-- Informasi lanjutan --}}
                @php
                    $defaultInfoItems = [
                        'Tanda tangan Anda sudah tersimpan dan tercatat bersama waktu serta alamat IP perangkat.',
                        'Tidak ada tindakan lanjutan yang perlu Anda lakukan.',
                        'Jika ada pertanyaan atau koreksi, hubungi teknisi yang mengirimkan link ini.',
                    ];
                    $listInformasi = $infoItems ?? $defaultInfoItems;
                @endphp

                <section class="mt-8 rounded-xl border border-emerald-100 bg-emerald-50 p-5 text-left"
                    aria-labelledby="info-lanjutan">
                    <h2 id="info-lanjutan" class="mb-3 text-sm font-bold text-emerald-700">
                        {{ $infoTitle ?? 'Informasi' }}
                    </h2>
                    <ul class="space-y-3">
                        @foreach ($listInformasi as $item)
                            <li class="flex items-start gap-3 text-sm leading-relaxed text-slate-700">
                                <svg class="mt-0.5 h-5 w-5 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    aria-hidden="true">
                                    <path d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                                <span>{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                </section>

                @if (!empty($actionUrl))
                    <a href="{{ $actionUrl }}"
                        class="mt-6 block w-full rounded-lg bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-blue-200">
                        {{ $actionText ?? 'Pantau Status Permohonan' }}
                    </a>
                    <button type="button" id="closeBtn"
                        class="mt-3 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Tutup Halaman
                    </button>
                @else
                    <button type="button" id="closeBtn"
                        class="mt-6 w-full rounded-lg bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-blue-200">
                        Tutup Halaman
                    </button>
                @endif

                {{-- <button type="button" id="closeBtn"
                    class="mt-6 w-full rounded-lg bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-blue-200">
                    Tutup Halaman
                </button> --}}

                <p id="closeHint" role="status" class="mt-3 hidden text-sm text-slate-500">
                    Halaman tidak bisa ditutup otomatis. Silakan tutup tab ini secara manual.
                </p>
            </div>

            <footer class="border-t border-slate-200 bg-slate-50 px-6 py-4 text-center text-xs text-slate-500">
                &copy; {{ date('Y') }} PT. QLab Kinarya Sentosa
            </footer>
        </div>
    </main>

    {{-- <script>
        document.getElementById('closeBtn').addEventListener('click', function() {
            window.close();

            // Browser memblokir window.close() untuk tab yang tidak dibuka lewat script
            setTimeout(function() {
                document.getElementById('closeHint').classList.remove('hidden');
            }, 300);
        });
    </script> --}}

    <script>
        const closeBtn = document.getElementById('closeBtn');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                window.close();
                setTimeout(function() {
                    document.getElementById('closeHint').classList.remove('hidden');
                }, 300);
            });
        }
    </script>
@endsection
