@extends ('pdf.layout.layout')

@section('content')
        <div id="export-area" class="p-2 bg-white text-black">
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
                        Formulir Serah Terima <br> Produksi Mekanik ke <br> Produksi Elektrikal
                    </td>
                    <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                        <table class="w-full text-sm dark:bg-gray-900 dark:text-white" style="border-collapse: collapse;">
                            <tr>
                                <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                                <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> :
                                    FO-QKS-PRD-01-01</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 border-b border-black dark:border-white">Tanggal Rilis</td>
                                <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> : 12 Maret 2025
                                </td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2">Revisi</td>
                                <td class="px-3 py-2 font-semibold"> : 0</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            @php
    $fields2 = [
        ['label' => '1. Nama Produk :', 'value' => 'Inverter Motor'],
        ['label' => '2. Type/Model :', 'value' => 'INV-MTR-2025'],
        ['label' => '3. Nomor SPK MKT :', 'value' => 'BATCH-3342'],
        ['label' => '4. Tanggal Produksi Mekanik Selesai:', 'value' => '15 Juni 2025'],
        ['label' => '5. Jumlah Unit :', 'value' => '10'],
    ];
    $kondisi = 'cukup_baik';
    $deskripsi_kondisi = 'Terdapat goresan kecil pada casing unit bagian samping.';
    $kondisi_fisik = 'perlu_perbaikan';
    $detail_kondisi_fisik = 'Cover depan penyok dan perlu diganti.';
    $komponen = 'kurang';
    $detail_kelengkapan_komponen = 'Baut pengikat belum lengkap.';
    $dokumen = 'sop';
    $fields3 = [
        ['label' => 'Tanggal Serah Terima :', 'value' => '18 Juni 2025'],
        ['label' => 'Diterima oleh (Nama & Jabatan) :', 'value' => 'Andika Saputra - Supervisor Elektrikal'],
        ['label' => 'Catatan Tambahan :', 'value' => 'Perlu QC lanjutan sebelum proses wiring.'],
    ];
    $status_penerimaan = 'catatan';
    $penjelasan_status = 'Beberapa konektor tidak sesuai spesifikasi.';
    $alasan_status = 'Produk tidak sesuai desain awal.';
    $roles = [
        'Diserahkan Oleh' => [
            'name' => 'Rudi Setiawan',
            'signature' => 'dummy-signature-submit.png',
            'date' => '18 Juni 2025',
        ],
        'Diterima Oleh' => [
            'name' => 'Andika Saputra',
            'signature' => 'dummy-signature-receive.png',
            'date' => '18 Juni 2025',
        ],
        'Diketahui Oleh' => [
            'name' => 'Ibu Dina',
            'signature' => null,
            'date' => '18 Juni 2025',
        ],
    ];
            @endphp

            <div class="grid w-full max-w-4xl grid-cols-1 pt-2 mx-auto gap-y-2">
                <h2 class="col-span-1 text-xl font-bold text-start">
                    Dari Divisi Mekanik ke Divisi Elektrikal
                </h2>
                <h2 class="col-span-1 -mt-1 text-lg font-bold text-start">
                    A. Informasi Produk                               
                </h2>
                <div class="flex flex-col gap-2">
                    @foreach ($fields2 as $field)
                        <div class="flex items-center gap-4">
                            <label class="font-medium w-72">{{ $field['label'] }}</label>
                            <input type="text" readonly value="{{ $field['value'] }}"
                                class="flex-1 px-3 py-2 text-sm text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                        </div>
                    @endforeach                      
                </div>
                <div class="flex items-start gap-2">
                    <label class="w-48 font-medium">5. Kondisi Produk :</label>
                    <div class="flex flex-col gap-1">
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" {{ $kondisi === 'baik' ? 'checked' : '' }} disabled
                                class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600">
                            <span>Baik</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" {{ $kondisi === 'cukup_baik' ? 'checked' : '' }} disabled
                                class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600">
                            <span>Cukup Baik</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" {{ $kondisi === 'perlu_perbaikan' ? 'checked' : '' }} disabled
                                class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600">
                            <span>Perlu Perbaikan</span>
                        </label>
                    </div>
                </div>
                <div class="">
                    <label class="block mb-1 font-semibold">Jelaskan</label>
                    <div
                        class="w-full min-h-[75px] px-3 py-2 text-sm leading-relaxed text-left border rounded-md text-black border-black">
                        {{ $deskripsi_kondisi }}
                    </div>
                </div>

                <h2 class="col-span-1 text-xl font-bold text-start">
                    B. Pengecekan Sebelum Serah Terima
                </h2>
                <div class="mb-1">
                    <label class="block font-medium">1. Kondisi Fisik Produk</label>
                    <div class="flex flex-col gap-1 pl-4">
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" {{ $kondisi_fisik === 'baik' ? 'checked' : '' }} disabled
                                class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600">
                            <span>Tidak ada kerusakan fisik</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" {{ $kondisi_fisik === 'cukup_baik' ? 'checked' : '' }} disabled
                                class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600">
                            <span>Ada sedikit cacat visual</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" {{ $kondisi_fisik === 'perlu_perbaikan' ? 'checked' : '' }} disabled
                                class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600">
                            <span>Ada kerusakan signifikan</span>
                        </label>
                    </div>
                </div>
                <div class="">
                    <label class="block mb-1 font-semibold">Jelaskan</label>
                    <div
                        class="w-full min-h-[75px] px-3 py-2 text-sm leading-relaxed text-left border rounded-md text-black border-black">
                        {{ $deskripsi_kondisi }}
                    </div>
                </div>

                <div class="mb-1">
                    <label class="block mb-2 font-medium">2. Kelengkapan Komponen</label>
                    <div class="flex flex-col gap-1 pl-4">
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" {{ $komponen === 'semua' ? 'checked' : '' }} disabled
                                class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600">
                            <span>Semua komponen mekanik terpasang dengan benar</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" {{ $komponen === 'kurang' ? 'checked' : '' }} disabled
                                class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600">
                            <span>Ada komponen yang kurang</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" {{ $komponen === 'perlu_diganti' ? 'checked' : '' }} disabled
                                class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600">
                            <span>Ada komponen yang perlu diganti</span>
                        </label>
                    </div>
                </div>

                <div class="pt-4">
                    <label class="block mb-1 font-semibold">Sebutkan</label>
                    <div
                        class="w-full min-h-[75px] px-3 py-2 text-sm leading-relaxed text-left border rounded-md text-black border-black">
                        {{ $deskripsi_kondisi }}
                    </div>
                </div>

                <div class="mb-1">
                    <label class="block mb-2 font-medium">3. Dokumen Pendukung</label>
                    <div class="flex flex-col gap-1 pl-4">
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" {{ $dokumen === 'gambar_teknis' ? 'checked' : '' }} disabled
                                class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600">
                            <span>Gambar Teknis</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" {{ $dokumen === 'sop' ? 'checked' : '' }} disabled
                                class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600">
                            <span>SOP atau Instruksi Perakitan</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" {{ $dokumen === 'laporan' ? 'checked' : '' }} disabled
                                class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600">
                            <span>Laporan QC</span>
                        </label>
                    </div>
                </div>

                <h2 class="col-span-1 text-xl font-bold text-start">C. Serah Terima</h2>

                @foreach ($fields3 as $field)
                    <div class="flex items-center gap-2">
                        <label class="font-medium w-72">{{ $field['label'] }}</label>
                        <input type="text" readonly value="{{ $field['value'] }}"
                            class="flex-1 px-3 py-2 text-sm text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                    </div>
                @endforeach

                <div class="mb-1">
                    <label class="block mb-2 font-medium">Status Penerimaan</label>
                    <div class="flex flex-col gap-1 pl-4">
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" {{ $status_penerimaan === 'diterima' ? 'checked' : '' }} disabled
                                class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600">
                            <span>Diterima tanpa catatan</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" {{ $status_penerimaan === 'catatan' ? 'checked' : '' }} disabled
                                class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600">
                            <span>Diterima dengan catatan</span>
                        </label>
                        <div class="">
                            <label class="block mb-1 font-semibold">Jelaskan</label>
                            <div
                                class="w-full min-h-[75px] px-3 py-2 text-sm leading-relaxed text-left border rounded-md text-black border-black">
                                {{ $deskripsi_kondisi }}
                            </div>  
                        </div>
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" {{ $status_penerimaan === 'ditolak' ? 'checked' : '' }} disabled
                                class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600">
                            <span>Ditolak dan dikembalikan</span>
                        </label>
                        <div class="">
                            <label class="block mb-1 font-semibold">Jelaskan</label>
                            <div
                                class="w-full min-h-[75px] px-3 py-2 text-sm leading-relaxed text-left border rounded-md text-black border-black">
                                {{ $deskripsi_kondisi }}
                            </div>      
                        </div>
                    </div>
                </div>
                <h2 class="col-span-1 text-xl font-bold text-start">D. Tanda Tangan</h2>
            </div>
            <div class="max-w-4xl mx-auto pt-2">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    @foreach ($roles as $role => $data)
                        <div>
                            <label class="block mb-1 font-semibold">{{ $role }}</label>

                            <div class="flex items-center justify-center w-full h-24 mb-2 bg-white border rounded border-gray">
                                @if ($data['signature'])
                                    <img src="{{ asset('storage/' . $data['signature']) }}" alt="Signature" class="object-contain h-full" />
                                @else
                                    <span class="text-sm text-gray-400">No Signature</span>
                                @endif
                            </div>
                            <input type="text" value="{{ $data['name'] }}" readonly
                                class="w-full p-2 mb-2 text-gray-500 bg-gray-100 border border-gray-300 rounded" />
                        </div>
                    @endforeach
                </div>
                <p>Catatan: Formulir ini harus diisi dengan lengkap sebelum produk diserahkan ke divisi berikutnya. Jika ada
                    kendala, harap segera dikomunikasikan kepada pihak terkait. Terima kasih.</p>
            </div>
        </div>
@endsection