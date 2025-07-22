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

    <style>
        tr {
            page-break-inside: avoid !important;
        }
    
        thead {
            display: table-header-group;
        }
    
        tfoot {
            display: table-footer-group;
        }
    
        /* Untuk hindari pemotongan antar section besar */
        .section-block {
            page-break-inside: avoid !important;
        }
    
        /* Jika terlalu panjang, bisa pecah di antar div saja */
        .break-after {
            page-break-after: always;
        }
        .ttd{
            page-break-inside: avoid !important;
        }
    </style>

    

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
