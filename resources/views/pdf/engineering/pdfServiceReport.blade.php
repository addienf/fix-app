@extends('pdf.layout.layout')
@section('title', 'Service Report PDF')
@section('content')

    <div id="export-area" class="p-2 text-black bg-white">
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
                    Service Report
                </td>
                <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                    <table class="w-full text-sm dark:bg-gray-900 dark:text-white" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> : -</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">Tanggal Rilis</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> : -</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2">Revisi</td>
                            <td class="px-3 py-2 font-semibold"> : -</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="w-full max-w-4xl pt-4 mx-auto text-sm">
            {{-- Info Umum --}}
            @php
                $infoUmum = [
                    ['label' => 'Form No:', 'value' => $serviceReport->form_no],
                    [
                        'label' => 'Date',
                        'value' => \Carbon\Carbon::parse($serviceReport->tanggal)->translatedFormat('d F Y'),
                    ],
                ];
            @endphp
            <div class="grid w-full max-w-4xl grid-cols-1 pt-2 mx-auto mb-4 text-sm gap-y-4">
                @foreach ($infoUmum as $field)
                    <div class="flex items-center gap-4">
                        <label class="w-40 font-medium">{{ $field['label'] }}</label>
                        <input type="text"
                            class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                            value="{{ $field['value'] }}" readonly />
                    </div>
                @endforeach
            </div>

            @php
                $infoUmum = [
                    ['label' => 'Who Complaint:', 'value' => $serviceReport->name_complaint],
                    ['label' => 'Company Name', 'value' => $serviceReport->company_name],
                    ['label' => 'Address', 'value' => $serviceReport->address],
                    ['label' => 'Phone Number', 'value' => $serviceReport->phone_number],
                ];
            @endphp
            <div class="grid w-full max-w-4xl grid-cols-1 pt-2 mx-auto mb-6 text-sm gap-y-4">
                @foreach ($infoUmum as $field)
                    <div class="flex items-center gap-4">
                        <label class="w-40 font-medium">{{ $field['label'] }}</label>
                        <input type="text"
                            class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                            value="{{ $field['value'] }}" readonly />
                    </div>
                @endforeach
            </div>

            {{-- {{ $serviceReport->produkService->produk_name }} --}}

            {{-- Tabel Produk --}}
            <table class="items-center w-full max-w-4xl mb-5 text-sm border border-black">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-3 py-2 font-semibold text-center border border-black">No</th>
                        <th class="px-3 py-2 font-semibold text-center border border-black">Unit Name</th>
                        <th class="px-3 py-2 font-semibold text-center border border-black">Type/Model</th>
                        <th class="px-3 py-2 font-semibold text-center border border-black">Serial Number</th>
                        <th class="px-3 py-2 font-semibold text-center border border-black">Under Warranty</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($serviceReport->produkServices as $index => $data)
                        <tr>
                            <td class="px-4 py-2 border border-black">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 border border-black">{{ $data->produk_name }}</td>
                            <td class="px-4 py-2 border border-black">{{ $data->type }}</td>
                            <td class="px-4 py-2 border border-black">{{ $data->serial_number }}</td>
                            <td class="px-4 py-2 border border-black">
                                {{ $data->status_warranty == 1 ? 'Yes' : 'No' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Kategori Service --}}
            @php
                $selectedServices = $serviceReport->service_category;
                $selectedActions = $serviceReport->actions;
                $selectedFields = $serviceReport->service_fields;
            @endphp

            <h2 class="mb-5 text-lg font-semibold">Service Category</h2>
            <div class="flex flex-col gap-2 text-sm">
                @foreach (['installation', 'maintenance', 'repair', 'consultation'] as $service)
                    <label class="flex items-center gap-2">
                        <input type="checkbox" class="text-blue-600 form-checkbox" value="{{ $service }}"
                            {{ in_array(strtolower($service), $selectedServices) ? 'checked' : '' }} disabled />
                        <span class="capitalize">{{ $service }}</span>
                    </label>
                @endforeach
            </div>

            {{-- Tabel Remark --}}
            <table class="items-center w-full max-w-4xl mt-5 mb-4 text-sm border border-black">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-3 py-2 font-semibold text-center border border-black">Remark</th>
                        <th class="px-3 py-2 font-semibold text-center border border-black">Action & Taken Item</th>
                        <th class="px-3 py-2 font-semibold text-center border border-black">Service Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="px-4 py-2 border border-black">1</td>
                        <td class="px-4 py-2 border border-black">Kabel Listrik</td>
                        <td class="px-4 py-2 border border-black">100</td>
                    </tr>
                </tbody>
            </table>

            {{-- Action & Service Field --}}
            @php
                $actions = ['cleaning', 'installation', 'repairing', 'maintenance', 'replacing', 'other'];
                $fields = [
                    'controlling',
                    'air_cooling_system',
                    'logging_system',
                    'server_computer',
                    'networking',
                    'water_feeding_system',
                    'cooling_system',
                    'humidifier_system',
                    'communication_system',
                    'air_heating_system',
                    'software',
                    'other',
                ];
            @endphp

            <!-- Actions -->
            <div class="pt-5 mb-8">
                <h2 class="mb-2 text-lg font-semibold">Action</h2>
                @foreach ($actions as $item)
                    <label class="block mb-1">
                        <input type="checkbox" class="mr-2 form-checkbox" name="actions[]" value="{{ $item }}"
                            {{ in_array($item, $selectedActions ?? []) ? 'checked' : '' }} disabled>
                        {{ ucwords(str_replace('_', ' ', $item)) }}
                    </label>
                @endforeach
            </div>

            <!-- Service Fields -->
            <div class="pt-5 mb-8">
                <h2 class="mb-2 text-lg font-semibold">Service Field</h2>
                @foreach ($fields as $field)
                    <label class="block mb-1">
                        <input type="checkbox" class="mr-2 form-checkbox" name="service_fields[]"
                            value="{{ $field }}" {{ in_array($field, $selectedFields ?? []) ? 'checked' : '' }}
                            disabled>
                        {{ ucwords(str_replace('_', ' ', $field)) }}
                    </label>
                @endforeach
            </div>


            {{-- Lampiran Gambar --}}
            <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2">
                @foreach ($serviceReport->details as $detail)
                    @foreach ($detail->upload_file as $gambar)
                        <div class="border border-gray-300 rounded shadow p-2 flex items-center justify-center h-[300px]">
                            <img src="{{ asset('storage/' . $gambar) }}" alt="Lampiran"
                                class="object-contain max-w-full max-h-full" />
                        </div>
                    @endforeach
                @endforeach
            </div>

            {{-- Tanda Tangan --}}
            @php
                $roles = [
                    'Service By' => [
                        'name' => $serviceReport->pic->checkedBy->name,
                        'signature' => $serviceReport->pic->checked_signature,
                        'date' => $serviceReport->pic->checked_date,
                    ],
                    'Approved By' => [
                        'name' => $serviceReport->pic->approvedBy->name,
                        'signature' => $serviceReport->pic->approved_signature,
                        'date' => $serviceReport->pic->approved_date,
                    ],
                ];
            @endphp

            <div class="max-w-4xl mx-auto mb-6">
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="font-semibold text-center bg-gray-100">
                            <th class="border border-black border-[1px] w-1/4"></th>
                            @foreach ($roles as $role => $data)
                                <th class="border border-black border-[1px] py-2">{{ $role }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-black border-[1px] px-2 py-2 font-medium">Name</td>
                            @foreach ($roles as $data)
                                <td class="border border-black border-[1px] px-2 py-2">{{ $data['name'] }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td class="border border-black border-[1px] px-2 py-2 font-medium">Signature</td>
                            @foreach ($roles as $data)
                                <td class="border border-black border-[1px] px-2 py-4">
                                    <div class="flex items-center justify-center h-24">
                                        @if ($data['signature'])
                                            <img src="{{ asset('storage/' . $data['signature']) }}" alt="Signature"
                                                class="object-contain h-full" />
                                        @else
                                            <span class="text-sm text-gray-400">No Signature</span>
                                        @endif
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                        <tr>
                            <td class="border border-black border-[1px] px-2 py-2 font-medium">Date</td>
                            @foreach ($roles as $data)
                                <td class="border border-black border-[1px] px-2 py-2">
                                    {{ $data['date'] ? \Carbon\Carbon::parse($data['date'])->format('d M Y') : '-' }}
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-6 mb-3 text-center">
        <button onclick="exportPDF({{ $serviceReport->id }})"
            class="inline-flex items-center gap-2 py-3 text-sm font-semibold text-black text-white bg-blue-600 border rounded border-animated px-7 border-black-400 hover:bg-purple-600 hover:text-white">
            <!-- Icon download SVG -->
            <svg class="w-5 h-5 transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4">
                </path>
            </svg>
            Download PDF
        </button>
    </div>

@endsection
<script>
    function exportPDF(id) {
        window.scrollTo(0, 0); // pastikan posisi di atas

        const element = document.getElementById("export-area");

        // Pastikan semua gambar sudah termuat sebelum render
        const images = element.getElementsByTagName("img");
        const totalImages = images.length;
        let loadedImages = 0;

        for (let img of images) {
            if (img.complete) {
                loadedImages++;
            } else {
                img.onload = () => {
                    loadedImages++;
                    if (loadedImages === totalImages) renderPDF();
                };
            }
        }

        if (loadedImages === totalImages) {
            renderPDF();
        }

        function renderPDF() {
            html2pdf().set({
                margin: [0.2, 0.2, 0.2, 0.2],
                filename: "service-report.pdf",
                image: {
                    type: "jpeg",
                    quality: 1
                },
                html2canvas: {
                    scale: 3,
                    useCORS: true,
                    letterRendering: true
                },
                jsPDF: {
                    unit: "in",
                    format: "a4",
                    orientation: "portrait"
                },
                pagebreak: {
                    mode: ["avoid", "css"]
                }
            }).from(element).save().then(() => {
                // window.location.href = `/sales/spesifikasi-produk/${id}/download-file`;
                window.location.href = `/engineering/service-report/${id}/download-zip`;
            });
        }
    }
</script>
