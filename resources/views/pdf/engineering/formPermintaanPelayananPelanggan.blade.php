@extends('pdf.layout.layout')

@section('title', 'Form Permintaan Pelayanan Pelanggan - ' . 'Tes 1001')

@section('content')
    <style>
        .signature-wrapper {
            position: relative;
            width: 100%;
            height: 180px;
            background-color: #fbfbfb;
            border: 2px dashed #cbd5e1;
            border-radius: 0.5rem;
        }

        canvas#signature-pad {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            border-radius: 0.5rem;
        }
    </style>

    <body class="bg-slate-100 text-slate-800 antialiased min-h-screen py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto bg-white shadow-xl rounded-xl border border-slate-200 overflow-hidden">

            <!-- ================= KOP HEADER RESMI ================= -->
            <div class="border-b border-slate-300 p-6 sm:p-8 bg-slate-50/50">
                <div
                    class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center border border-slate-400 rounded-lg p-4 bg-white">
                    <!-- Logo -->
                    <div class="md:col-span-3 text-center md:border-r border-slate-300 pr-4">
                        <img src="{{ asset('asset/logo.png') }}" alt="Logo QLab" class="h-16 mx-auto object-contain"
                            onerror="this.src='https://placehold.co/150x60?text=QLab+Logo'">
                    </div>
                    <!-- Judul Utama -->
                    <div class="md:col-span-5 text-center md:border-r border-slate-300 px-2 py-1">
                        <h3 class="text-xs uppercase tracking-wider font-semibold text-slate-500">PT. QLab Kinarya Sentosa
                        </h3>
                        <h1 class="text-lg sm:text-xl font-bold text-slate-900 mt-1">Formulir Permintaan<br>Pelayanan
                            Pelanggan</h1>
                    </div>
                    <!-- Box Dokumen ISO -->
                    <div class="md:col-span-4 text-xs space-y-1.5 pl-2 text-slate-600">
                        <div class="flex justify-between border-b border-slate-200 pb-1">
                            <span class="font-medium text-slate-500">No. Dokumen</span>
                            <span class="font-mono font-bold text-slate-800">FO-QKS-CC-01-03</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-200 pb-1">
                            <span class="font-medium text-slate-500">Tanggal Rilis</span>
                            <span class="font-medium text-slate-800">13 Oktober 2025</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-slate-500">Revisi</span>
                            <span class="font-medium text-slate-800">021</span>
                        </div>
                    </div>
                </div>
                <!-- Petunjuk Singkat Pengisian -->
                <div class="mt-4 bg-blue-50 border-l-4 border-blue-500 p-3 rounded text-xs text-blue-900">
                    <strong>Informasi untuk Pelanggan:</strong> Silakan lengkapi formulir permohonan layanan ini dengan
                    detail kerusakan unit dan bubuhkan tanda tangan digital Anda di bagian bawah.
                </div>
            </div>
            <!-- ================= FORM BODY ================= -->
            <form id="formPelayanan" action="#" method="POST" class="p-6 sm:p-8 space-y-8">
                @csrf
                <!-- Meta Dokumen (Readonly - Dummy Data) -->
                <div class="bg-slate-50 border border-slate-200 rounded-lg p-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Complaint
                                Form No.</label>
                            <div
                                class="mt-1 font-mono font-bold text-blue-600 bg-white border border-slate-300 px-3 py-2 rounded shadow-sm">
                                SR-20260211-ATJIDL
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Tanggal
                                Permohonan</label>
                            <div
                                class="mt-1 font-medium text-slate-800 bg-white border border-slate-300 px-3 py-2 rounded shadow-sm">
                                22 Oktober 2026
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Perusahaan /
                                Customer</label>
                            <div
                                class="mt-1 font-semibold text-slate-800 bg-white border border-slate-300 px-3 py-2 rounded shadow-sm">
                                PT Tunggal Idaman Abdi
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Alamat
                                Fasilitas</label>
                            <div
                                class="mt-1 text-xs text-slate-700 bg-white border border-slate-300 px-3 py-2 rounded shadow-sm">
                                Jl. Raya Bogor KM.32, RT.3/RW.7, Pekayon, Pasar Rebo, Kota Depok
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ================= A. JENIS PERMINTAAN ================= -->
                <div class="border-t border-slate-200 pt-6">
                    <h2 class="text-base font-bold text-slate-900 flex items-center gap-2 mb-4">
                        <span
                            class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs flex items-center justify-center font-bold">A</span>
                        JENIS PERMINTAAN
                    </h2>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-sm">
                        @php
                            $opsiLayanan = [
                                'Pengiriman',
                                'Service',
                                'Perakitan',
                                'Maintenance',
                                'Kualifikasi',
                                'Kalibrasi',
                            ];
                        @endphp
                        @foreach ($opsiLayanan as $opsi)
                            <label
                                class="flex items-center gap-3 p-3 border border-slate-200 rounded-lg cursor-pointer hover:bg-slate-50 transition">
                                <input type="checkbox" name="jenis_permintaan[]" value="{{ $opsi }}"
                                    class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                                <span class="font-medium text-slate-700">{{ $opsi }}</span>
                            </label>
                        @endforeach
                        <!-- Opsi Lainnya -->
                        <div
                            class="col-span-2 sm:col-span-4 flex items-center gap-3 p-3 border border-slate-200 rounded-lg bg-slate-50">
                            <input type="checkbox" id="checkLainnya" name="jenis_permintaan[]" value="Lainnya"
                                class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                            <label for="checkLainnya" class="font-medium text-slate-700 whitespace-nowrap">Lainnya :</label>
                            <input type="text" name="jenis_permintaan_lainnya"
                                placeholder="Sebutkan jenis permintaan lain jika ada..."
                                class="flex-1 bg-white border border-slate-300 px-3 py-1.5 rounded text-xs focus:outline-none focus:border-blue-500">
                        </div>
                    </div>
                </div>
                <!-- ================= B. IDENTITAS ALAT ================= -->
                <div class="border-t border-slate-200 pt-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                            <span
                                class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs flex items-center justify-center font-bold">B</span>
                            IDENTITAS ALAT & DESKRIPSI KELUHAN
                        </h2>
                        <button type="button" onclick="tambahBarisAlat()"
                            class="text-xs bg-slate-800 hover:bg-slate-700 text-white font-medium py-1.5 px-3 rounded-md transition shadow-sm">
                            + Tambah Alat
                        </button>
                    </div>
                    <div class="overflow-x-auto border border-slate-300 rounded-lg">
                        <table id="tabelAlat" class="w-full text-left text-xs border-collapse">
                            <thead
                                class="bg-slate-100 text-slate-700 border-b border-slate-300 font-semibold uppercase tracking-wider">
                                <tr>
                                    <th class="p-3 w-12 text-center">No</th>
                                    <th class="p-3 w-1/4">Nama Alat</th>
                                    <th class="p-3 w-28">Tipe</th>
                                    <th class="p-3 w-32">Nomor Seri</th>
                                    <th class="p-3">Deskripsi Pekerjaan / Keluhan</th>
                                    <th class="p-3 w-20 text-center">Qty</th>
                                    <th class="p-3 w-12 text-center">Hapus</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 text-slate-800">
                                <!-- Baris Dummy Pertama -->
                                <tr>
                                    <td class="p-3 text-center font-medium bg-slate-50">1</td>
                                    <td class="p-2">
                                        <input type="text" name="alat[0][nama]" value="Climatic Chamber Walk-In"
                                            class="w-full border border-slate-300 rounded px-2.5 py-1.5 text-xs focus:ring-1 focus:ring-blue-500 focus:outline-none">
                                    </td>
                                    <td class="p-2">
                                        <input type="text" name="alat[0][tipe]" value="CC-G2-Standard"
                                            class="w-full border border-slate-300 rounded px-2.5 py-1.5 text-xs focus:ring-1 focus:ring-blue-500 focus:outline-none">
                                    </td>
                                    <td class="p-2">
                                        <input type="text" name="alat[0][no_seri]" value="SN-2024-0899"
                                            class="w-full border border-slate-300 rounded px-2.5 py-1.5 text-xs focus:ring-1 focus:ring-blue-500 focus:outline-none">
                                    </td>
                                    <td class="p-2">
                                        <textarea name="alat[0][deskripsi]" rows="2" placeholder="Jelaskan kendala..."
                                            class="w-full border border-slate-300 rounded px-2.5 py-1.5 text-xs focus:ring-1 focus:ring-blue-500 focus:outline-none">Suhu ruang uji tidak mencapai target -20°C, muncul alarm error E-04 pada panel controller.</textarea>
                                    </td>
                                    <td class="p-2">
                                        <input type="number" name="alat[0][qty]" value="1" min="1"
                                            class="w-full border border-slate-300 rounded px-2 py-1.5 text-xs text-center focus:ring-1 focus:ring-blue-500 focus:outline-none">
                                    </td>
                                    <td class="p-2 text-center">
                                        <span class="text-slate-400 text-xs italic">-</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- ================= C. PELAKSANAAN & TANDA TANGAN ================= -->
                <div class="border-t border-slate-200 pt-6">
                    <h2 class="text-base font-bold text-slate-900 flex items-center gap-2 mb-4">
                        <span
                            class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs flex items-center justify-center font-bold">C</span>
                        DATA PEMOHON & TANDA TANGAN DIGITAL
                    </h2>
                    <div class="bg-slate-50 p-4 border border-slate-200 rounded-lg mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <!-- 1. Nama Lengkap -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">
                                    Nama Lengkap PIC Pemohon <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="pic_nama" value="Bpk. Hendra Saputra" required
                                    placeholder="Contoh: Hendra Saputra"
                                    class="w-full bg-white border border-slate-300 rounded-md px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <!-- 2. Departemen / Divisi (BARU) -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">
                                    Departemen / Divisi <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="pic_departemen" value="Quality Control (QC)" required
                                    placeholder="Contoh: QA / QC / Maintenance"
                                    class="w-full bg-white border border-slate-300 rounded-md px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <!-- 3. No Kontak / WhatsApp -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">
                                    No. Kontak / WhatsApp Aktif <span class="text-rose-500">*</span>
                                </label>
                                <input type="tel" name="pic_kontak" value="082116674124" required
                                    placeholder="Contoh: 0812xxxxxxxx"
                                    class="w-full bg-white border border-slate-300 rounded-md px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                        <!-- Catatan Penjelas (Helper Note) -->
                        <p class="mt-2 text-[11px] text-amber-700 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span>*Mohon cantumkan <strong>Departemen/Divisi</strong> Anda secara spesifik.</span>
                        </p>
                    </div>
                    <!-- Bagian 3 Box Tanda Tangan (Preview Sesuai Form ISO) -->
                    <div
                        class="grid grid-cols-1 sm:grid-cols-3 gap-4 border border-slate-300 rounded-lg p-4 bg-slate-50/50">

                        <!-- 1. Diketahui Oleh (Internal QLab) -->
                        <div
                            class="border border-slate-200 bg-white rounded-lg p-4 text-center flex flex-col justify-between">
                            <div class="text-xs font-bold text-slate-600 uppercase tracking-wide">Diketahui Oleh,</div>
                            <div
                                class="h-28 flex items-center justify-center my-2 text-xs text-slate-400 italic bg-slate-50 border border-dashed border-slate-200 rounded">
                                ( Menunggu Pengesahan MR / HOB )
                            </div>
                            <div>
                                <div class="font-bold text-xs text-slate-700">MR / Head of Business</div>
                                <div class="text-[11px] text-slate-400">PT. QLab Kinarya Sentosa</div>
                            </div>
                        </div>
                        <!-- 2. Diterima Oleh (Internal QLab) -->
                        <div
                            class="border border-slate-200 bg-white rounded-lg p-4 text-center flex flex-col justify-between">
                            <div class="text-xs font-bold text-slate-600 uppercase tracking-wide">Diterima Oleh,</div>
                            <div
                                class="h-28 flex items-center justify-center my-2 text-xs text-slate-400 italic bg-slate-50 border border-dashed border-slate-200 rounded">
                                ( Menunggu Verifikasi Customer Care )
                            </div>
                            <div>
                                <div class="font-bold text-xs text-slate-700">Customer Care Team</div>
                                <div class="text-[11px] text-slate-400">PT. QLab Kinarya Sentosa</div>
                            </div>
                        </div>
                        <!-- 3. Dibuat Oleh (CUSTOMER - DIISI DI SINI) -->
                        <div
                            class="border-2 border-blue-400 bg-blue-50/20 rounded-lg p-4 text-center flex flex-col justify-between shadow-sm">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-xs font-bold text-blue-900 uppercase tracking-wide">Dibuat Oleh
                                    (Customer) *</span>
                                <button type="button" onclick="clearSignature()"
                                    class="text-[11px] text-rose-600 hover:underline font-semibold">
                                    Ulangi TTD
                                </button>
                            </div>

                            <!-- Canvas Signature Pad -->
                            <div class="signature-wrapper my-2">
                                <canvas id="signature-pad"></canvas>
                            </div>
                            <input type="hidden" name="customer_signature" id="signature-input">
                            <div>
                                <div class="font-bold text-xs text-slate-800" id="previewNamaPIC">Bpk. Hendra Saputra
                                </div>
                                <div class="text-[11px] text-slate-500" id="previewDeptPIC">( Quality Control )</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tombol Submit -->
                <div class="border-t border-slate-200 pt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-xs text-slate-500 italic">
                        *Dengan menekan tombol kirim, Anda mengonfirmasi bahwa data permohonan dan tanda tangan di atas
                        adalah sah.
                    </p>
                    <button type="button" onclick="submitForm()"
                        class="w-full sm:w-auto px-8 py-3 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-bold text-sm rounded-lg shadow-lg shadow-blue-500/30 transition transform hover:-translate-y-0.5">
                        Kirim Permohonan Pelayanan
                    </button>
                </div>
            </form>
        </div>
        <!-- SCRIPT SIGNATURE PAD & DYNAMIC ROW -->
        <script>
            let signaturePad;
            document.addEventListener("DOMContentLoaded", function() {
                const canvas = document.getElementById('signature-pad');

                function resizeCanvas() {
                    const ratio = Math.max(window.devicePixelRatio || 1, 1);
                    canvas.width = canvas.offsetWidth * ratio;
                    canvas.height = canvas.offsetHeight * ratio;
                    canvas.getContext("2d").scale(ratio, ratio);
                }
                window.addEventListener("resize", resizeCanvas);
                resizeCanvas();
                signaturePad = new SignaturePad(canvas, {
                    backgroundColor: 'rgba(255, 255, 255, 0)',
                    penColor: 'rgb(20, 50, 160)' // Tinta warna biru formal tanda tangan
                });
                // Sinkronkan input nama PIC dengan teks di bawah tanda tangan
                const inputNama = document.querySelector('input[name="pic_nama"]');
                const labelNama = document.getElementById('previewNamaPIC');
                if (inputNama && labelNama) {
                    inputNama.addEventListener('input', (e) => {
                        labelNama.innerText = e.target.value || '( .................................... )';
                    });
                }
                const inputDept = document.querySelector('input[name="pic_departemen"]');
                const labelDept = document.getElementById('previewDeptPIC');
                if (inputDept && labelDept) {
                    inputDept.addEventListener('input', (e) => {
                        labelDept.innerText = e.target.value ? `( ${e.target.value} )` :
                            '( .................... )';
                    });
                }
            });

            function clearSignature() {
                if (signaturePad) {
                    signaturePad.clear();
                }
            }

            function submitForm() {
                if (!signaturePad || signaturePad.isEmpty()) {
                    alert("Harap bubuhkan tanda tangan digital Anda terlebih dahulu pada kotak 'Dibuat Oleh'!");
                    return;
                }
                const dataUrl = signaturePad.toDataURL('image/png');
                document.getElementById('signature-input').value = dataUrl;
                alert("Berhasil! Tanda tangan tertangkap. (Ini masih mode preview dummy)");
            }
            let barisIndex = 1;

            function tambahBarisAlat() {
                const tbody = document.querySelector("#tabelAlat tbody");
                const index = barisIndex++;
                const tr = document.createElement("tr");
                tr.innerHTML = `
                <td class="p-3 text-center font-medium bg-slate-50">${index + 1}</td>
                <td class="p-2">
                    <input type="text" name="alat[${index}][nama]" placeholder="Nama Alat..." 
                           class="w-full border border-slate-300 rounded px-2.5 py-1.5 text-xs focus:ring-1 focus:ring-blue-500 focus:outline-none">
                </td>
                <td class="p-2">
                    <input type="text" name="alat[${index}][tipe]" placeholder="Tipe..." 
                           class="w-full border border-slate-300 rounded px-2.5 py-1.5 text-xs focus:ring-1 focus:ring-blue-500 focus:outline-none">
                </td>
                <td class="p-2">
                    <input type="text" name="alat[${index}][no_seri]" placeholder="No Seri..." 
                           class="w-full border border-slate-300 rounded px-2.5 py-1.5 text-xs focus:ring-1 focus:ring-blue-500 focus:outline-none">
                </td>
                <td class="p-2">
                    <textarea name="alat[${index}][deskripsi]" rows="2" placeholder="Jelaskan kendala..." 
                              class="w-full border border-slate-300 rounded px-2.5 py-1.5 text-xs focus:ring-1 focus:ring-blue-500 focus:outline-none"></textarea>
                </td>
                <td class="p-2">
                    <input type="number" name="alat[${index}][qty]" value="1" min="1" 
                           class="w-full border border-slate-300 rounded px-2 py-1.5 text-xs text-center focus:ring-1 focus:ring-blue-500 focus:outline-none">
                </td>
                <td class="p-2 text-center">
                    <button type="button" onclick="this.closest('tr').remove()" class="text-rose-600 hover:text-rose-800 font-bold text-sm">✕</button>
                </td>
            `;
                tbody.appendChild(tr);
            }
        </script>
    </body>
@endsection
