<x-filament-panels::page>
    <x-filament::section>
        {{-- Judul Utama --}}
        <h2 class="mb-3 text-xl font-bold text-center">Detail Pengecekan Material Stainless Steel</h2>

        {{-- Tabel Header --}}
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
                    Pengecekan Material<br>Stainless Steel
                </td>
                <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                    <table class="w-full text-sm dark:bg-gray-900" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> :
                                FO-QKS-QA-01-01</td>
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

        {{-- Bagian Informasi SPK --}}
        <div class="grid w-full max-w-4xl grid-cols-1 pt-6 mx-auto mb-6 text-sm gap-y-4">
            @php
                $fields = [['label' => 'No SPK Produksi :', 'value' => $pengecekanSS->spk->no_spk]];
            @endphp

            @foreach ($fields as $field)
                <div class="flex flex-col">
                    <label class="mb-1 font-medium">{{ $field['label'] }}</label>
                    <input type="text" readonly value="{{ $field['value'] }}"
                        class="w-full px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                </div>
            @endforeach
        </div>

        {{-- Judul Section Chamber --}}
        <h2 class="max-w-4xl mx-auto text-xl font-bold text-start">Chamber Identification</h2>

        {{-- Form Chamber Identification --}}
        <div class="grid w-full max-w-4xl grid-cols-1 pt-6 mx-auto mb-6 text-sm gap-y-4">
            @php
                $fields = [
                    ['label' => 'Type/Model :', 'value' => $pengecekanSS->tipe],
                    ['label' => 'Ref. Document :', 'value' => $pengecekanSS->ref_document],
                ];
            @endphp

            @foreach ($fields as $field)
                <div class="flex flex-col">
                    <label class="mb-1 font-medium">{{ $field['label'] }}</label>
                    <input type="text" readonly value="{{ $field['value'] }}"
                        class="w-full px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                </div>
            @endforeach
        </div>

        @php
            $rawDetails = $pengecekanSS->detail->details ?? [];
            $details = is_string($rawDetails) ? json_decode($rawDetails, true) : $rawDetails;

            function statusLabel($code)
            {
                return match (strtolower($code)) {
                    'ok' => 'OK',
                    'h' => 'Hold',
                    'r' => 'Repaired',
                    default => ucfirst($code ?? '-'),
                };
            }
        @endphp

        {{-- <table class="w-full mb-6 text-sm border border-collapse border-black"> --}}
        <table class="w-full max-w-4xl mx-auto mb-3 text-sm border border-black">
            <thead class="bg-gray-100">
                <tr>
                    <th class="w-10 px-3 py-2 text-center border border-black">No</th>
                    <th class="px-3 py-2 text-left border border-black">Part</th>
                    <th class="px-3 py-2 text-center border border-black">Result</th>
                    <th class="px-3 py-2 text-left border border-black">Status</th>
                </tr>
            </thead>

            <tbody>
                @php $rowNumber = 1; @endphp
                @foreach ($details as $group)
                    <tr>
                        <td colspan="4" class="px-3 py-2 font-semibold bg-gray-200 border border-black">
                            {{ $group['mainPart'] ?? '-' }}
                        </td>
                    </tr>
                    @foreach ($group['parts'] as $part)
                        <tr>
                            <td class="px-3 py-2 text-center border border-black">{{ $rowNumber++ }}</td>
                            <td class="px-3 py-2 border border-black">{{ $part['part'] ?? '-' }}</td>
                            <td class="px-3 py-2 text-center border border-black">
                                {{ ($part['result'] ?? '0') == '1' ? 'Yes' : 'No' }}
                            </td>
                            <td class="px-3 py-2 border border-black">
                                {{ statusLabel($part['status'] ?? '-') }}
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>

        <div class="w-full max-w-4xl mx-auto mb-6">
            <label for="note" class="block mb-1 text-sm font-medium text-gray-700">Note:</label>
            <textarea id="note" readonly
                class="w-full px-3 py-2 overflow-hidden text-sm leading-relaxed text-gray-800 bg-gray-100 border resize-none border-black-600">{{ trim($pengecekanSS->note) }}</textarea>
        </div>

        @php
            $roles = [
                'Checked By' => [
                    'name' => $pengecekanSS->pic->inspected_name ?? '-',
                    'signature' => $pengecekanSS->pic->inspected_signature ?? null,
                    'date' => $pengecekanSS->pic->inspected_date ?? null,
                ],
                'Accepted By' => [
                    'name' => $pengecekanSS->pic->accepted_name ?? '-',
                    'signature' => $pengecekanSS->pic->accepted_signature ?? null,
                    'date' => $pengecekanSS->pic->accepted_date ?? null,
                ],
                'Approved By' => [
                    'name' => $pengecekanSS->pic->approved_name ?? '-',
                    'signature' => $pengecekanSS->pic->approved_signature ?? null,
                    'date' => $pengecekanSS->pic->approved_date ?? null,
                ],
            ];
        @endphp

        <div class="max-w-4xl p-4 mx-auto mb-6">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                @foreach ($roles as $role => $data)
                    <div>
                        <label class="block mb-1 font-semibold">{{ $role }}</label>
                        <input type="text" value="{{ $data['name'] }}" readonly
                            class="w-full p-2 mb-2 text-gray-500 bg-gray-100 border border-gray-300 rounded" />

                        <label class="block mb-1">Signature</label>
                        <div
                            class="flex items-center justify-center w-full h-24 mb-2 bg-white border border-black rounded">
                            @if ($data['signature'])
                                <img src="{{ asset('storage/' . $data['signature']) }}" alt="Signature"
                                    class="object-contain h-full" />
                            @else
                                <span class="text-sm text-gray-400">No Signature</span>
                            @endif
                        </div>

                        <label class="block mb-1">Date</label>
                        <input type="text" readonly
                            value="{{ $data['date'] ? \Carbon\Carbon::parse($data['date'])->format('d/m/Y') : '-' }}"
                            class="w-full p-2 text-gray-500 bg-gray-100 border border-gray-300 rounded" />
                    </div>
                @endforeach
            </div>
        </div>

        <script>
            window.addEventListener('DOMContentLoaded', () => {
                const note = document.getElementById('note');
                if (note) {
                    note.style.height = 'auto'; // reset dulu
                    note.style.height = note.scrollHeight + 'px'; // sesuaikan isi
                }
            });
        </script>
    </x-filament::section>
</x-filament-panels::page>
