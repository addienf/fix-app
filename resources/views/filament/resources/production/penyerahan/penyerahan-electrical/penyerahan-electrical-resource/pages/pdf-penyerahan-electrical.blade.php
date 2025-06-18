<x-filament-panels::page>
    <x-filament::section>
        {{ $serahElectrical }}
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
                    ['label' => '1. Nama Produk :', 'value' => 'ABC-123'],
                    ['label' => '2. Kode Produk :', 'value' => 'DOC-456789'],
                    ['label' => '3. Nomor Batch/Seri :', 'value' => 'DOC-456789'],
                    [
                        'label' => '4. Tanggal Produksi Mekanik Selesai:',
                        'value' => \Carbon\Carbon::now()->format('d M Y'),
                    ],
                    ['label' => '5. Jumlah Unit :', 'value' => '10'],
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
                <label class="w-48 font-medium">5. Kondisi Produk :</label>
                <div class="flex flex-col gap-1">
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" disabled checked class="text-blue-600 form-checkbox" />
                        <span>Baik</span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" disabled class="text-blue-600 form-checkbox" />
                        <span>Cukup Baik</span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" disabled class="text-blue-600 form-checkbox" />
                        <span>Perlu Perbaikan</span>
                    </label>
                </div>
            </div>
            <div class="pt-1">
                <label class="block mb-1 font-semibold">Jelaskan</label>
                <textarea class="w-full p-2 border border-black rounded cursor-not-allowed resize-none" readonly></textarea>
            </div>
            <h2 class="col-span-1 mb-2 text-xl font-bold text-start">
                B. Pengecekan Sebelum Serah Terima
            </h2>
            <div class="mb-1">
                <label class="block mb-2 font-medium">1. Kondisi Fisik Produk</label>

                <div class="flex flex-col gap-1 pl-4">
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" disabled checked class="text-blue-600 form-checkbox" />
                        <span>Tidak ada kerusakan fisik</span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" disabled class="text-blue-600 form-checkbox" />
                        <span>Ada sedikit cacat visual (tidak mempengaruhi fungsi)</span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" disabled class="text-blue-600 form-checkbox" />
                        <span>Ada kerusakan signifikan</span>
                    </label>
                </div>
            </div>
            <div class="pt-1">
                <label class="block mb-1 font-semibold">Jelaskan</label>
                <textarea class="w-full p-2 border border-black rounded cursor-not-allowed resize-none" readonly></textarea>
            </div>
            <div class="mb-1">
                <label class="block mb-2 font-medium">2. Kelengkapan Dokumen</label>

                <div class="flex flex-col gap-1 pl-4">
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" disabled checked class="text-blue-600 form-checkbox" />
                        <span>Semua komponen mekanik terpasang dengan benar</span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" disabled class="text-blue-600 form-checkbox" />
                        <span>Ada komponen yang kurang</span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" disabled class="text-blue-600 form-checkbox" />
                        <span>Ada komponen yang perlu diperbaiki atau diganti</span>
                    </label>
                </div>
            </div>
            <div class="pt-1">
                <label class="block mb-1 font-semibold">Sebutkan</label>
                <textarea class="w-full p-2 border border-black rounded cursor-not-allowed resize-none" readonly></textarea>
            </div>
            <div class="mb-1">
                <label class="block mb-2 font-medium">3. Dokumen Pendukung</label>

                <div class="flex flex-col gap-1 pl-4">
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" disabled checked class="text-blue-600 form-checkbox" />
                        <span>Gambar Teknis </span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" disabled class="text-blue-600 form-checkbox" />
                        <span>SOP atau Instruksi Perakitan</span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" disabled class="text-blue-600 form-checkbox" />
                        <span>Laporan QC (Quality Control)</span>
                    </label>
                </div>
            </div>
            <h2 class="col-span-1 mb-2 text-xl font-bold text-start">
                B. Pengecekan Sebelum Serah Terima
            </h2>
            @php
                $fields2 = [
                    ['label' => 'Tanggal Serah Terima :', 'value' => 'ABC-123'],
                    ['label' => 'Diterima oleh (Nama & Jabatan) :', 'value' => 'DOC-456789'],
                    ['label' => 'Catatan Tambahan :', 'value' => 'DOC-456789'],
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
                <label class="block mb-2 font-medium">Status Penerimaan</label>

                <div class="flex flex-col gap-1 pl-4">
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" />
                        <span>Diterima tanpa catatan </span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" />
                        <span>Diterima dengan catatan</span>
                    </label>
                    <div
                        class="w-full px-3 py-2 text-sm text-gray-500 bg-white border border-gray-300 rounded-md cursor-not-allowed">
                        Jelaskan:
                    </div>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" />
                        <span>Ditolak dan dikembalikan ke divisi mekanik</span>
                    </label>
                    <div
                        class="w-full px-3 py-2 text-sm text-gray-500 bg-white border border-gray-300 rounded-md cursor-not-allowed">
                        Alasan:
                    </div>
                </div>
            </div>
            <h2 class="col-span-1 mb-2 text-xl font-bold text-start">
                D. Tandan Tangan
            </h2>
            @php
                $roles = [
                    'Diserahkan Oleh' => [],
                    'Diterima Oleh' => [],
                    'Diketehui Oleh' => [],
                ];
            @endphp
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                @foreach ($roles as $role => $data)
                    <div>
                        <!-- Role title -->
                        <label class="block mb-1 font-semibold">{{ $role }}</label>
                        <input type="text" value="" readonly
                            class="w-full p-2 mb-2 text-gray-500 bg-gray-100 border border-gray-300 rounded" />

                        <!-- Signature -->
                        <label class="block mb-1">Signature</label>
                        <div
                            class="flex items-center justify-center w-full h-24 mb-2 bg-white border rounded border-gray">
                            <span class="text-sm text-gray-400">No Signature</span>
                        </div>

                        <!-- Date -->
                        <label class="block mb-1">Date</label>
                        <input type="text" value="" readonly
                            class="w-full p-2 text-gray-500 bg-gray-100 border border-gray-300 rounded" />
                    </div>
                @endforeach
            </div>
            <p> Catatan: Formulir ini harus diisi dengan lengkap sebelum produk diserahkan ke divisi berikutnya. Jika
                ada kendala, harap segera
                dikomunikasikan kepada pihak terkait.
                Terima kasih.
            </p>
        </div>

    </x-filament::section>
</x-filament-panels::page>
