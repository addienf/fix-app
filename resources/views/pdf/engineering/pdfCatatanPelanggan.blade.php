@extends('pdf.layout.layout')
@section('title', 'Catatan Keluhan Pelanggan Maintenance PDF')
@section('content')
    <div id="export-area" class="p-2 text-black bg-white">
        <!-- Header Table -->
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
                    Formulir Catatan Keluhan Pelanggan
                </td>
                <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                    <table class="w-full text-sm dark:bg-gray-900 dark:text-white" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen : </td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white">
                                FO-QKS-CC-01-10
                            </td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">Tanggal Rilis : </td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white">
                                12 Maret 2025
                            </td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2">Revisi : </td>
                            <td class="px-3 py-2 font-semibold"> 0</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="w-full max-w-4xl pt-4 mx-auto text-sm">
            @php
                use Carbon\Carbon;
                Carbon::setLocale('id');
                $datePart = Carbon::parse($complaint->tanggal)->format('Y-m-d');
                $timePart = Carbon::parse($complaint->created_at)->format('H:i:s');
                $combined = Carbon::parse($datePart . ' ' . $timePart);

                $datePartPIC = Carbon::parse($complaint->pic->reported_date)->format('Y-m-d');
                $timePartPIC = Carbon::parse($complaint->pic->created_at)->format('H:i:s');
                $combinedPIC = Carbon::parse($datePart . ' ' . $timePart);

                $formattedDateTime = $combined->translatedFormat('l, d - m - Y H:i');
                $formattedDateTime3 = $combinedPIC->translatedFormat('l, d - m - Y H:i');
                $formattedDateTime2 = $combined->translatedFormat('d/m/Y H:i');
            @endphp

            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold">Incoming Complaint Report</h2>
                <span class="text-sm font-bold">
                    {{ $formattedDateTime }}
                </span>
            </div>



            @php
                $fields = [
                    [
                        'label' => '',
                        'value' => '<strong>Reported by</strong><br>' . '<em>Name: ' . $complaint->dari . '</em>',
                    ],
                    [
                        'label' => '',
                        'value' =>
                            '<strong>To</strong><br>' .
                            '<em>Engineering Department' .
                            '</em>' .
                            '<br>' .
                            $complaint->kepada,
                    ],
                ];
            @endphp

            <div class="grid gap-4 mb-6">
                @foreach ($fields as $field)
                    <div class="text-sm leading-relaxed text-black">
                        {!! $field['value'] !!}
                    </div>
                @endforeach
            </div>
        </div>

        <div class="w-full max-w-4xl pt-4 mx-auto text-sm">
            @php
                $fields = [
                    ['label' => 'Form No :', 'value' => $complaint->form_no],
                    ['label' => 'Who Complaint :', 'value' => $complaint->name_complain],
                    ['label' => 'Company Name :', 'value' => $complaint->company_name],
                    ['label' => 'Department :', 'value' => $complaint->department],
                    ['label' => 'Phone Number :', 'value' => $complaint->phone_number],
                    ['label' => 'Complaint Received By :', 'value' => $complaint->receive_by],
                ];
            @endphp

            <div class="grid gap-3 mb-6">
                @foreach ($fields as $field)
                    <div class="flex flex-wrap items-center gap-2">
                        <label class="w-40 font-medium text-gray-700">{{ $field['label'] }}</label>
                        <input type="text" readonly value="{{ $field['value'] }}"
                            class="flex-1 min-w-[200px] px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                    </div>
                @endforeach
            </div>
        </div>

        <table class="w-full max-w-4xl pt-4 mx-auto text-sm border border-black table-fixed">
            <thead class="text-black bg-gray-300">
                <tr>
                    <th class="p-2 border border-black">No</th>
                    <th class="p-2 border border-black">Unit Name</th>
                    <th class="p-2 border border-black">Type/model</th>
                    <th class="p-2 border border-black">Under Warranty</th>
                    <th class="p-2 border border-black">Field Category</th>
                    <th class="p-2 border border-black">Description</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($complaint->details as $index => $data)
                    <tr>
                        <td class="px-4 py-2 border border-black">{{ $index + 1 }}</td>
                        <td class="px-4 py-2 border border-black">{{ $data->unit_name }}</td>
                        <td class="px-4 py-2 border border-black">{{ $data->tipe_model }}</td>
                        <td class="px-4 py-2 border border-black">
                            {{ $data->status_warranty == 1 ? 'Yes' : 'No' }}
                        </td>
                        <td class="px-4 py-2 border border-black">{{ $data->field_category }}</td>
                        <td class="px-4 py-2 border border-black">{{ $data->deskripsi }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="w-full max-w-4xl pt-4 mx-auto text-sm">
            <h2 class="mb-4 text-xl text-starts">Report Details :</h2>

            @php
                $fields = [
                    ['label' => 'Reported Date :', 'value' => $formattedDateTime3],
                    ['label' => 'Recorded By :', 'value' => $complaint->pic->reportedBy->name],
                    ['label' => 'Recorded Date :', 'value' => $formattedDateTime2],
                ];
            @endphp

            <div class="grid gap-2 mb-6">
                @foreach ($fields as $field)
                    <div class="flex items-center">
                        <label class="w-32 font-medium">{{ $field['label'] }}</label>
                        <input type="text" readonly value="{{ $field['value'] }}"
                            class="flex-1 px-3 py-2 text-black bg-white border border-gray-300 rounded-md cursor-not-allowed" />
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="mt-6 mb-3 text-center">
        <button onclick="exportPDF()"
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
                filename: "complaint-form.pdf",
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
            }).from(element).save();
        }
    }
</script>
