@extends ('pdf.layout.layout')
@section('title', 'Formulir Product Release')
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
                    Formulir Product Release
                </td>
                <td rowspan="2" class="p-0 align-top border border-black dark:border-white dark:bg-gray-900">
                    <table class="w-full text-sm dark:bg-gray-900 dark:text-white" style="border-collapse: collapse;">
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">No. Dokumen</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> :
                                FO-QKS-QA-01-10</td>
                        </tr>
                        <tr>
                            <td class="px-3 py-2 border-b border-black dark:border-white">Tanggal Rilis</td>
                            <td class="px-3 py-2 font-semibold border-b border-black dark:border-white"> : 08 Oktober 2025
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

        <div class="w-full max-w-4xl mx-auto mt-6 border border-black p-6 leading-relaxed text-[15px]">

            <p>Release Order No : {{ $release->no_order_release }}</p>

            <p class="mt-6">
                The Product : {{ $release->product }}
            </p>

            <p class="mt-6">
                Batch No : {{ $release->batch }}
            </p>

            <p class="mt-6 font-bold">
                Is released for distribution.
            </p>
        </div>

        <div class="w-full max-w-4xl mx-auto mt-6">
            <p class="text-sm font-medium mb-1">Remark :</p>

            <div class="w-full min-h-[120px] border border-black p-3 text-sm">
                {{ trim($release->remarks) }}
            </div>
        </div>

        @php
            $roles = [
                'Dibuat Oleh,' => [
                    'name' => $release->pic->dibuatName->name ?? '-',
                    'signature' => $release->pic->dibuat_signature ?? null,
                    'date' => $release->pic->dibuat_date ?? null,
                ],
                'Dikonfirmasi Oleh,' => [
                    'name' => $release->pic->dikonfirmasiName->name ?? '-',
                    'signature' => $release->pic->dikonfirmasi_signature ?? null,
                    'date' => $release->pic->dikonfirmasi_date ?? null,
                ],
                'Diterima Oleh,' => [
                    'name' => $release->pic->diterimaName->name ?? '-',
                    'signature' => $release->pic->diterima_signature ?? null,
                    'date' => $release->pic->diterima_date ?? null,
                ],
                'Diketahui Oleh,' => [
                    'name' => $release->pic->diketahuiName->name ?? '-',
                    'signature' => $release->pic->diketahui_signature ?? null,
                    'date' => $release->pic->diketahui_date ?? null,
                ],
            ];
        @endphp

        <div class="max-w-4xl mx-auto mt-6 text-sm">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="font-semibold text-center bg-gray-100">
                        @foreach ($roles as $role => $data)
                            <th class="border border-black">{{ $role }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach ($roles as $data)
                            <td class="px-2 py-3 border border-black">
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
                        @foreach ($roles as $data)
                            <td class="px-2 py-2 border border-black">
                                {{ $data['date'] ? \Carbon\Carbon::parse($data['date'])->format('d F Y') : '-' }}
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
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
    function exportPDF() {
        window.scrollTo(0, 0);

        const element = document.getElementById("export-area");
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
                filename: "defect-status.pdf",
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
                // }).from(element).save();
            }).from(element).save().then(() => {
                window.location.href = `/quality/defect-status/${id}/download-file`;
            });
        }
    }
</script>
