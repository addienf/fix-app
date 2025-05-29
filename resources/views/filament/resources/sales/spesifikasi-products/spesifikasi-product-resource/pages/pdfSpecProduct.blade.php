<x-filament-panels::page>
    <x-filament::section>
        <h2 class="mb-3 text-xl font-bold text-center">Detail Permintaan Spesifikasi Produk</h2>

        <!-- HEADER DOKUMEN -->
        <table class="w-full max-w-4xl mx-auto text-sm border border-black dark:border-gray-600"
            style="border-collapse: collapse;">
            <tr>
                <td rowspan="3" class="w-28 h-28 p-2 border border-black text-center align-middle">
                    <img src="{{ asset('asset/logo.png') }}" alt="Logo" class="object-contain h-30 mx-auto" />
                </td>
                <td colspan="2" class="text-center font-bold border border-black">
                    PT. QLab Kinarya Sentosa
                </td>
            </tr>
            <tr>
                <td class="text-center font-bold border border-black" style="font-size: 20px;">
                    Permintaan Spesifikasi Produk
                </td>
                <td rowspan="2" class="p-0 border border-black align-top">
                    <table class="w-full text-sm" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-2">No. Dokumen</td>
                            <td class="px-3 py-2 font-semibold"> : FO-QKS-PRD-01-01</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2">Tanggal Rilis</td>
                            <td class="px-3 py-2 font-semibold"> : 12 Maret 2025</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2">Revisi</td>
                            <td class="px-3 py-2 font-semibold"> : 0</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- FORM -->
        @php
            $fields = [
                ['label' => 'No', 'value' => $spesifikasi->urs->no_urs],
                ['label' => 'Phone Number', 'value' => $spesifikasi->urs->customer->phone_number],
                ['label' => 'Nama', 'value' => $spesifikasi->urs->customer->name],
                ['label' => 'Company Name', 'value' => $spesifikasi->urs->customer->company_name],
                ['label' => 'Department', 'value' => $spesifikasi->urs->customer->department],
                ['label' => 'Company Address', 'value' => $spesifikasi->urs->customer->company_address],
            ];
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 max-w-4xl mx-auto text-sm pt-6">
            @foreach ($fields as $field)
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-4">
                    <label class="sm:w-40 font-medium">{{ $field['label'] }} :</label>
                    <input type="text" disabled
                        class="flex-1 px-2 py-1 bg-white text-black dark:bg-gray-800 dark:text-white border border-gray-300 dark:border-gray-600 rounded w-full cursor-not-allowed"
                        value="{{ $field['value'] }}" />
                </div>
            @endforeach
        </div>

        <!-- Spesifikasi Teknis -->
        @php
            $chunks = $spesifikasi->details->chunk(2); // Bagi tiap 2 item
        @endphp

        <div class="max-w-4xl mx-auto pt-6 text-sm space-y-4">
            @foreach ($chunks as $chunk)
                <div class="grid gap-4 {{ $chunk->count() == 1 ? 'grid-cols-1' : 'grid-cols-2' }}">
                    @foreach ($chunk as $detail)
                        <div class="p-4 border border-gray-300 dark:border-gray-600 rounded">
                            <div class="pb-4">
                                <label class="block mb-2 font-medium">Nama Item</label>
                                <input type="text" disabled
                                    class="w-full px-2 py-1 bg-white text-black dark:bg-gray-800 dark:text-white border border-gray-300 dark:border-gray-600 rounded cursor-not-allowed"
                                    value="{{ $detail->product->name }}" />
                            </div>
                            <div class="pb-4">
                                <label class="block mb-2 font-medium">Quantity</label>
                                <input type="text" disabled
                                    class="w-full px-2 py-1 bg-white text-black dark:bg-gray-800 dark:text-white border border-gray-300 dark:border-gray-600 rounded cursor-not-allowed"
                                    value="{{ $detail->quantity }}" />
                            </div>
                            @foreach ($detail->specification as $spec)
                                <div class="pb-4">
                                    <label class="block mb-2 font-medium">{{ $spec['name'] }}</label>
                                    <input type="text" disabled
                                        class="w-full px-2 py-1 bg-white text-black dark:bg-gray-800 dark:text-white border border-gray-300 dark:border-gray-600 rounded cursor-not-allowed"
                                        value="{{ in_array($spec['name'], ['Water Feeding System', 'Software'])
                                            ? (isset($spec['value_bool']) && $spec['value_bool']
                                                ? 'Ya'
                                                : 'Tidak')
                                            : $spec['value_str'] ?? '-' }}" />
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>

        <!-- Penanggung Jawab -->
        <div class="max-w-4xl pt-4 mx-auto text-sm">
            <div>
                <label class="font-bold pt-3">Penanggung Jawab</label>
                <div class="flex flex-col text-sm pt-3">
                    <img src="{{ asset('storage/' . $spesifikasi->pic->signature) }}" alt="Product Signature"
                        class="h-20 w-80" />
                    <div class="mt-2 font-medium">
                        {{ $spesifikasi->pic->name }}
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <label class="font-bold">Tanggal: </label>
                <input type="text" readonly disabled
                    class="px-2 py-1 bg-white text-black dark:bg-gray-800 dark:text-white border border-gray-300 dark:border-gray-600 rounded cursor-not-allowed"
                    value="{{ \Carbon\Carbon::parse($spesifikasi->pic->date)->translatedFormat('d F Y') }}" />
            </div>
        </div>
    </x-filament::section>
</x-filament-panels::page>
