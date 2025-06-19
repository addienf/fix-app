<x-filament-panels::page>
    <x-filament::section>
        <h2 class="mb-3 text-xl font-bold text-center">Detail Incoming Material </h2>
        <!-- HEADER DOKUMEN MIRIP EXCEL -->
        <table
            class="w-full max-w-4xl mx-auto text-sm border border-black dark:border-white dark:bg-gray-900 dark:text-white"
            style="border-collapse: collapse;">
            <tr>
                <td rowspan="3"
                    class="p-2 text-center align-middle border border-black dark:border-white w-28 h-28 dark:bg-gray-900">
                    <img src="{{ asset('asset/logo.png') }}" alt="Logo" class="object-contain mx-auto h-30" />
                </td>
                <td colspan="2" class="font-bold text-center border border-black dark:border-white dark:bg-gray-900">
                    PT. QLab Kinarya Sentosa
                </td>
            </tr>
            <tr>
                <td class="font-bold text-center border border-black dark:border-white dark:bg-gray-900"
                    style="font-size: 20px;">
                    Formulir Incoming Material
                </td>
                <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                    <table class="w-full text-sm dark:bg-gray-900" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white">
                                : FO-QKS-WRH-01-01</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">Tanggal Rilis</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white">
                                : 12 Maret 2025</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2">Revisi</td>
                            <td class="px-3 py-2 font-semibold">: 0</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="w-full max-w-4xl pt-4 mx-auto space-y-2 text-sm">
            @php
                $fields = [
                    [
                        'label' => 'Nomor :',
                        'value' => $incomingMaterial->permintaanPembelian->permintaanBahanWBB->no_surat,
                    ],
                    [
                        'label' => 'Tanggal Penerimaan :',
                        'value' => \Carbon\Carbon::parse($incomingMaterial->tanggal)->translatedFormat('d M Y'),
                    ],
                ];
            @endphp

            @foreach ($fields as $field)
                <div class="flex items-center">
                    <label class="w-64 font-medium">{{ $field['label'] }}</label>
                    <input type="text" readonly value="{{ $field['value'] }}"
                        class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                </div>
            @endforeach
        </div>

        <h2 class="w-full max-w-4xl col-span-1 pt-4 mx-auto mb-4 text-xl font-bold text-start">
            A. Informasi Material
        </h2>

        <div class="max-w-4xl mx-auto mt-6 overflow-x-auto text-sm">
            <table class="min-w-full text-sm text-left border border-gray">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-3 py-2 border border-gray">No</th>
                        <th class="px-3 py-2 border border-gray">Nama Material</th>
                        <th class="px-3 py-2 border border-gray">Batch No.</th>
                        <th class="px-3 py-2 border border-gray">Jumlah Diterima</th>
                        <th class="px-3 py-2 border border-gray">Satuan</th>
                        <th class="px-3 py-2 border border-gray">Kondisi Material</th>
                        <th class="px-3 py-2 border border-gray">Status</th>
                    </tr>
                </thead>
                <tbody class="text-black bg-white dark:bg-gray-900 dark:text-white">
                    @foreach ($incomingMaterial->details as $index => $item)
                        <tr>
                            <td class="px-3 py-2 text-center border border-gray">{{ $index + 1 }}</td>
                            <td class="px-3 py-2 border border-gray">{{ $item->nama_material }}</td>
                            <td class="px-3 py-2 border border-gray">{{ $item->batch_no }}</td>
                            <td class="px-3 py-2 text-center border border-gray">{{ $item->jumlah }}</td>
                            <td class="px-3 py-2 text-center border border-gray">{{ $item->satuan }}</td>
                            <td class="px-3 py-2 border border-gray">{{ $item->kondisi_material }}</td>
                            <td class="px-3 py-2 text-center border border-gray">
                                {{ $item->status_qc == '1' ? 'Ya' : 'Tidak' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @php
            $laporanQc = $incomingMaterial->dokumen_pendukung ?? null;
            $kondisiMaterial = $incomingMaterial->kondisi_material ?? null;
            $penerimaan = $incomingMaterial->status_penerimaan ?? null;
        @endphp

        <div class="max-w-4xl mx-auto space-y-4 text-sm">

            <div>

                <h2 class="w-full max-w-4xl col-span-1 pt-4 mx-auto mb-4 text-xl font-bold text-start">
                    B. Pemeriksaan Material
                </h2>

                <p class="ml-4">1. Apakah material dalam kondisi baik? (Ya/Tidak)</p>

                <div class="mt-1 ml-8 space-x-4">

                    <label class="inline-flex items-center">
                        <input type="checkbox"
                            class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600"
                            {{ $kondisiMaterial == 1 ? 'checked' : '' }} disabled />
                        <span class="ml-2">Ya</span>
                    </label>

                    <label class="inline-flex items-center">
                        <input type="checkbox"
                            class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600"
                            {{ $kondisiMaterial == 0 ? 'checked' : '' }} disabled />
                        <span class="ml-2">Tidak</span>
                    </label>

                </div>

            </div>

            <!-- C. Status Penerimaan -->
            <div>
                <h2 class="w-full max-w-4xl col-span-1 pt-4 mx-auto mb-4 text-xl font-bold text-start">
                    C. Status Penerimaan </h2>
                <div class="ml-4 space-y-1">
                    <label class="inline-flex items-center">
                        <input type="checkbox"
                            class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600"
                            {{ $penerimaan == 1 ? 'checked' : '' }} disabled />
                        <span class="ml-2">Diterima</span>
                    </label>
                    <br />
                    <label class="inline-flex items-center">
                        <input type="checkbox"
                            class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600"
                            {{ $penerimaan == 0 ? 'checked' : '' }} disabled />
                        <span class="ml-2">Ditolak dan dikembalikan</span>
                    </label>
                </div>
            </div>

            <div>

                <h2 class="w-full max-w-4xl col-span-1 pt-4 mx-auto mb-4 text-xl font-bold text-start">
                    D. Dokumen Pendukung
                </h2>

                <div class="ml-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox"
                            class="w-4 h-4 border border-gray-400 appearance-none checked:bg-blue-600 checked:border-blue-600"
                            {{ $laporanQc == 1 ? 'checked' : '' }} disabled />
                        <span class="ml-2">Laporan QC (Quality Control)</span>
                    </label>
                </div>

            </div>

        </div>

        <div class="max-w-4xl mx-auto mt-10 text-sm">
            <div class="flex items-start justify-between gap-4">
                <!-- Kiri -->
                <div class="flex flex-col items-center">
                    <p class="mb-2 dark:text-white">Diserahkan Oleh</p>
                    <img src="{{ asset('storage/' . $incomingMaterial->pic->submited_signature) }}"
                        alt="Product Signature" class="h-20 w-80" />
                    <p class="mt-4 font-semibold dark:text-white">{{ $incomingMaterial->pic->submited_name }}</p>
                </div>
                <!-- Kanan -->
                <div class="flex flex-col items-center">
                    <p class="mb-2 dark:text-white">Diterima Oleh</p>
                    <img src="{{ asset('storage/' . $incomingMaterial->pic->received_signature) }}"
                        alt="Product Signature" class="h-20 w-80" />
                    <p class="mt-4 font-semibold dark:text-white">{{ $incomingMaterial->pic->received_name }}</p>
                </div>
            </div>
        </div>



    </x-filament::section>
</x-filament-panels::page>
