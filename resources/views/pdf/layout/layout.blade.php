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
    <div class="mt-6 mb-3 text-center">
        <button onclick="exportPDF()"
            class="border-animated inline-flex items-center gap-2 px-7 py-3 text-sm font-semibold text-black border border-black-400 rounded bg-blue-600 text-white hover:bg-purple-600 hover:text-white">
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
    
    <style>
        .border-animated {
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
    
        .border-animated::before,
        .border-animated::after {
            content: '';
            position: absolute;
            height: 2px;
            width: 100%;
            background: linear-gradient(90deg, transparent, #fff, transparent);
            animation: slide-horizontal 2s linear infinite;
        }
    
        .border-animated::before {
            top: 0;
            left: -100%;
        }
    
        .border-animated::after {
            bottom: 0;
            left: -100%;
            animation-delay: 1s;
        }
    
        @keyframes slide-horizontal {
            0% {
                left: -100%;
            }
    
            50% {
                left: 100%;
            }
    
            100% {
                left: -100%;
            }
        }
    </style>
    

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const note = document.getElementById('note');
            if (note) {
                note.style.height = 'auto';
                note.style.height = note.scrollHeight + 'px';
            }
        });
    </script>

</body>

</html>
