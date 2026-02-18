<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="icon" href="{{ asset('asset/logoTab.jpg') }}">
    <link href="{{ public_path('css/tailwind.min.css') }}" rel="stylesheet">

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th,
        td {
            border: 0.5px solid #000;
            padding: 4px 6px;
            vertical-align: top;
            word-wrap: break-word;
        }

        th {
            text-align: center;
            font-weight: bold;
        }

        td.center {
            text-align: center;
        }

        .no-border td {
            border: none;
        }

        .section-title td {
            font-weight: bold;
            background: #f5f5f5;
        }

        .italic {
            font-style: italic;
        }

        thead {
            display: table-header-group;
        }

        tr {
            page-break-inside: avoid;
        }

        .pdf-spacer td {
            border: none;
            height: 6px;
        }
    </style>
</head>

<body>

    <header>
        @yield('pdf-header')
    </header>

    <main class="pdf-wrapper">
        @yield('content')
    </main>

</body>

</html>
