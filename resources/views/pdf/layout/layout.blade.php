<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://raw.githack.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.min.js"></script>
</head>

<body>
    @yield('content')
    {{-- <script>
        function exportPDF() {
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

            var document_title = 'default-title';

            var document_name = "tes-123";

            function renderPDF() {
                html2pdf().set({
                    margin: [0.2, 0.2, 0.2, 0.2],
                    // filename: `${document_title}.pdf`,
                    filename: `${ document_name }.pdf`,

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
    </script> --}}
    <div class="mt-6 text-center mb-3">
        <button onclick="exportPDF()"
            class="inline-flex items-center gap-2 px-7 py-3 text-sm font-semibold border border-black text-black transition-all duration-300 rounded hover:bg-blue-600 hover:text-white hover:border-blue-600">
    
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

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const note = document.getElementById('note');
            if (note) {
                note.style.height = 'auto'; // reset dulu
                note.style.height = note.scrollHeight + 'px'; // sesuaikan isi
            }
        });
    </script>

</body>

</html>
