<x-filament-panels::page>
    <x-filament::section>

        <h2 class="mb-3 text-xl font-bold text-center">Detail Serah Terima Electrial</h2>

        <!-- Header Table -->
        <table class="w-full max-w-4xl mx-auto mb-3 text-sm border border-black" style="border-collapse: collapse;">
            <tr>
                <td rowspan="3" class="p-2 text-center align-middle border border-black w-28 h-28">
                    <img src="{{ asset('asset/logo.png') }}" alt="Logo" class="object-contain mx-auto h-30" />
                </td>
                <td colspan="2" class="font-bold text-center border border-black">PT. QLab Kinarya Sentosa</td>
            </tr>
            <tr>
                <td class="font-bold text-center border border-black" style="font-size: 20px;">
                    Formulir Serah Terima <br> Produksi Mekanik ke <br> Produksi Elektrikal
                </td>
                <td rowspan="2" class="p-0 align-top border border-black">
                    <table class="w-full text-sm" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-2 border-b border-black">No. Dokumen</td>
                            <td class="px-3 py-2 font-semibold border-b border-black"> : FO-QKS-PRO-01-01</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 border-b border-black">Tanggal Rilis</td>
                            <td class="px-3 py-2 font-semibold border-b border-black"> : 12 Maret 2025</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2">Revisi</td>
                            <td class="px-3 py-2 font-semibold"> : 0</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="grid w-full max-w-4xl grid-cols-1 pt-4 mx-auto mb-4 gap-y-4">

            <h2 class="col-span-1 mb-2 text-xl font-bold text-start">
                Dari Divisi Mekanik ke Divisi Elektrikal
            </h2>

            @php
                $fields2 = [
                    ['label' => '1. Nama Produk :', 'value' => $serahElectrical->nama_produk],
                    ['label' => '2. Kode Produk :', 'value' => $serahElectrical->kode_produk],
                    ['label' => '3. Nomor Batch/Seri :', 'value' => $serahElectrical->no_seri],
                    [
                        'label' => '4. Tanggal Produksi Mekanik Selesai:',
                        'value' => \Carbon\Carbon::parse($serahElectrical->tanggal_selesai)->format('d M Y'),
                    ],
                    ['label' => '5. Jumlah Unit :', 'value' => $serahElectrical->jumlah],
                ];
            @endphp

            @foreach ($fields2 as $field)
                <div class="flex items-center gap-4">
                    <label class="font-medium w-72">{{ $field['label'] }}</label>
                    <input type="text" readonly value="{{ $field['value'] }}"
                        class="flex-1 px-3 py-2 text-sm text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                </div>
            @endforeach

            <div class="flex items-start gap-2">

                @php
                    $kondisi = $serahElectrical->kondisi ?? null;
                @endphp

                <label class="w-48 font-medium">5. Kondisi Produk :</label>

                <div class="flex flex-col gap-1">

                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="kondisi" value="baik"
                            class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600"
                            {{ $kondisi === 'baik' ? 'checked' : '' }} disabled>
                        <span>Baik</span>
                    </label>

                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="kondisi" value="baik"
                            class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600"
                            {{ $kondisi === 'cukup_baik' ? 'checked' : '' }} disabled>
                        <span>Cukup Baik</span>
                    </label>

                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="kondisi" value="baik"
                            class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600"
                            {{ $kondisi === 'perlu_perbaikan' ? 'checked' : '' }} disabled>
                        <span>Perlu Perbaikan</span>
                    </label>

                </div>

            </div>

            <div class="pt-1">
                <label class="block mb-1 font-semibold">Jelaskan</label>
                <textarea id="note" readonly
                    class="w-full px-3 py-2 overflow-hidden text-sm leading-relaxed text-gray-800 bg-gray-100 border cursor-not-allowed resize-none border-black-600">
                {{ trim($serahElectrical->deskripsi_kondisi) }}
                </textarea>
            </div>

            <h2 class="col-span-1 mb-2 text-xl font-bold text-start">
                B. Pengecekan Sebelum Serah Terima
            </h2>

            <div class="mb-1">

                @php
                    $kondisi_fisik = $serahElectrical->sebelumSerahTerima->kondisi_fisik ?? null;
                @endphp

                <label class="block mb-2 font-medium">1. Kondisi Fisik Produk</label>

                <div class="flex flex-col gap-1 pl-4">

                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="kondisi" value="baik"
                            class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600"
                            {{ $kondisi_fisik === 'baik' ? 'checked' : '' }} disabled>
                        <span>Tidak ada kerusakan fisik</span>
                    </label>

                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="kondisi" value="baik"
                            class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600"
                            {{ $kondisi_fisik === 'cukup_baik' ? 'checked' : '' }} disabled>
                        <span>Ada sedikit cacat visual (tidak mempengaruhi fungsi)</span>
                    </label>

                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="kondisi" value="baik"
                            class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600"
                            {{ $kondisi_fisik === 'perlu_perbaikan' ? 'checked' : '' }} disabled>
                        <span>Ada kerusakan signifikan</span>
                    </label>

                </div>

            </div>

            @if ($serahElectrical->sebelumSerahTerima->kondisi_fisik === 'perlu_perbaikan')
                <div class="pt-1">
                    <label class="block mb-1 font-semibold">Jelaskan</label>
                    <textarea id="note" readonly
                        class="w-full px-3 py-2 overflow-hidden text-sm leading-relaxed text-gray-800 bg-gray-100 border cursor-not-allowed resize-none border-black-600">
                    {{ trim($serahElectrical->sebelumSerahTerima->detail_kondisi_fisik) }}
                    </textarea>
                </div>
            @endif

            <div class="mb-1">

                @php
                    $komponen = $serahElectrical->sebelumSerahTerima->kelengkapan_komponen ?? null;
                @endphp

                <label class="block mb-2 font-medium">2. Kelengkapan Komponen</label>

                <div class="flex flex-col gap-1 pl-4">

                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="kondisi" value="baik"
                            class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600"
                            {{ $komponen === 'semua' ? 'checked' : '' }} disabled>
                        <span>Semua komponen mekanik terpasang dengan benar</span>
                    </label>

                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="kondisi" value="baik"
                            class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600"
                            {{ $komponen === 'kurang' ? 'checked' : '' }} disabled>
                        <span>Ada komponen yang kurang</span>
                    </label>

                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="kondisi" value="baik"
                            class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600"
                            {{ $komponen === 'perlu_diganti' ? 'checked' : '' }} disabled>
                        <span>Ada komponen yang perlu diperbaiki atau diganti</span>
                    </label>

                </div>

            </div>

            @if ($serahElectrical->sebelumSerahTerima->kelengkapan_komponen === 'kurang')
                <div class="pt-1">
                    <label class="block mb-1 font-semibold">Sebutkan</label>
                    <textarea id="note" readonly
                        class="w-full px-3 py-2 overflow-hidden text-sm leading-relaxed text-gray-800 bg-gray-100 border cursor-not-allowed resize-none border-black-600">
                    {{ trim($serahElectrical->sebelumSerahTerima->detail_kelengkapan_komponen) }}
                    </textarea>
                </div>
            @endif

            <div class="mb-1">

                @php
                    $dokumen = $serahElectrical->sebelumSerahTerima->dokumen_pendukung ?? null;
                @endphp

                <label class="block mb-2 font-medium">3. Dokumen Pendukung</label>

                <div class="flex flex-col gap-1 pl-4">

                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="kondisi" value="baik"
                            class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600"
                            {{ $dokumen === 'gambar_teknis' ? 'checked' : '' }} disabled>
                        <span>Gambar Teknis </span>
                    </label>

                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="kondisi" value="baik"
                            class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600"
                            {{ $dokumen === 'sop' ? 'checked' : '' }} disabled>
                        <span>SOP atau Instruksi Perakitan</span>
                    </label>

                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="kondisi" value="baik"
                            class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600"
                            {{ $dokumen === 'laporan' ? 'checked' : '' }} disabled>
                        <span>Laporan QC (Quality Control)</span>
                    </label>

                </div>

            </div>

            <h2 class="col-span-1 mb-2 text-xl font-bold text-start">
                B. Pengecekan Sebelum Serah Terima
            </h2>

            @php
                $fields2 = [
                    [
                        'label' => 'Tanggal Serah Terima :',
                        'value' => \Carbon\Carbon::parse($serahElectrical->penerimaElectrical->tanggal)->format(
                            'd M Y',
                        ),
                    ],
                    [
                        'label' => 'Diterima oleh (Nama & Jabatan) :',
                        'value' => $serahElectrical->penerimaElectrical->diterima_oleh,
                    ],
                    [
                        'label' => 'Catatan Tambahan :',
                        'value' => $serahElectrical->penerimaElectrical->catatan_tambahan,
                    ],
                ];
            @endphp

            @foreach ($fields2 as $field)
                <div class="flex items-center gap-4">
                    <label class="font-medium w-72">{{ $field['label'] }}</label>
                    <input type="text" readonly value="{{ $field['value'] }}"
                        class="flex-1 px-3 py-2 text-sm text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                </div>
            @endforeach

            <div class="mb-1">

                @php
                    $penerimaan = $serahElectrical->penerimaElectrical->status_penerimaan ?? null;
                @endphp

                <label class="block mb-2 font-medium">Status Penerimaan</label>

                <div class="flex flex-col gap-1 pl-4">
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="kondisi" value="baik"
                            class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600"
                            {{ $penerimaan === 'diterima' ? 'checked' : '' }} disabled>
                        <span>Diterima tanpa catatan </span>
                    </label>

                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="kondisi" value="baik"
                            class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600"
                            {{ $penerimaan === 'catatan' ? 'checked' : '' }} disabled>
                        <span>Diterima dengan catatan</span>
                    </label>

                    @if ($serahElectrical->penerimaElectrical->status_penerimaan === 'catatan')
                        <div class="pt-1">
                            <label class="block mb-1 font-semibold">Jelaskan</label>
                            <textarea id="note" readonly
                                class="w-full px-3 py-2 overflow-hidden text-sm leading-relaxed text-gray-800 bg-gray-100 border cursor-not-allowed resize-none border-black-600">
                            {{ trim($serahElectrical->penerimaElectrical->penjelasan_status) }}
                            </textarea>
                        </div>
                    @endif

                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="kondisi" value="baik"
                            class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600"
                            {{ $penerimaan === 'ditolak' ? 'checked' : '' }} disabled>
                        <span>Ditolak dan dikembalikan ke divisi mekanik</span>
                    </label>

                    @if ($serahElectrical->penerimaElectrical->status_penerimaan === 'ditolak')
                        <div class="pt-1">
                            <label class="block mb-1 font-semibold">Alasan</label>
                            <textarea id="note" readonly
                                class="w-full px-3 py-2 overflow-hidden text-sm leading-relaxed text-gray-800 bg-gray-100 border cursor-not-allowed resize-none border-black-600">
                            {{ trim($serahElectrical->penerimaElectrical->alasan_status) }}
                            </textarea>
                        </div>
                    @endif

                </div>

            </div>

            <h2 class="col-span-1 mb-2 text-xl font-bold text-start">
                D. Tandan Tangan
            </h2>

            @php
                $roles = [
                    'Submit By' => [
                        'name' => $serahElectrical->pic->submit_name ?? '-',
                        'signature' => $serahElectrical->pic->submit_signature ?? null,
                        'date' => $serahElectrical->pic->submit_date ?? null,
                    ],
                    'Receive By' => [
                        'name' => $serahElectrical->pic->receive_name ?? '-',
                        'signature' => $serahElectrical->pic->receive_signature ?? null,
                        'date' => $serahElectrical->pic->receive_date ?? null,
                    ],
                    'Knowing By' => [
                        'name' => $serahElectrical->pic->knowing_name ?? '-',
                        'signature' => $serahElectrical->pic->knowing_signature ?? null,
                        'date' => $serahElectrical->pic->knowing_date ?? null,
                    ],
                ];
            @endphp

            <!-- SIGNATURE SECTION -->
            <div class="max-w-4xl p-4 mx-auto mb-6">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    @foreach ($roles as $role => $data)
                        <div>
                            <label class="block mb-1 font-semibold">{{ $role }}</label>
                            <input type="text" value="{{ $data['name'] }}" readonly
                                class="w-full p-2 mb-2 text-gray-500 bg-gray-100 border border-gray-300 rounded" />

                            <label class="block mb-1">Signature</label>
                            <div
                                class="flex items-center justify-center w-full h-24 mb-2 bg-white border rounded border-gray">
                                @if ($data['signature'])
                                    <img src="{{ asset('storage/' . $data['signature']) }}" alt="Signature"
                                        class="object-contain h-full" />
                                @else
                                    <span class="text-sm text-gray-400">No Signature</span>
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>

            <p> Catatan: Formulir ini harus diisi dengan lengkap sebelum produk diserahkan ke divisi berikutnya. Jika
                ada kendala, harap segera
                dikomunikasikan kepada pihak terkait.
                Terima kasih.
            </p>
        </div>

    </x-filament::section>
</x-filament-panels::page>
