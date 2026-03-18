@extends('pdf.layout.layout')

@section('title', 'Tanda Tangan Berhasil')

@section('content')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: system-ui, -apple-system, sans-serif;
            background: #f8f9fa;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            color: #333;
        }

        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 40px 32px;
            width: 100%;
            max-width: 480px;
            text-align: center;
        }

        .logo {
            width: 100px;
            height: 100px;
            /* background: #0066cc; */
            color: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            font-weight: bold;
            margin: 0 auto 32px;
        }

        .success-icon {
            width: 90px;
            height: 90px;
            margin: 0 auto 28px;
            background: #ecfdf5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: #10b981;
            box-shadow: 0 2px 10px rgba(16, 185, 129, 0.15);
        }

        h1 {
            font-size: 28px;
            color: #111827;
            margin-bottom: 12px;
        }

        .subtitle {
            font-size: 16px;
            color: #4b5563;
            line-height: 1.5;
            margin-bottom: 32px;
        }

        .thanks {
            font-size: 18px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 40px;
        }

        .action-btn {
            display: inline-block;
            padding: 14px 32px;
            background: #3b82f6;
            color: white;
            text-decoration: none;
            font-weight: 500;
            border-radius: 8px;
            transition: background 0.2s;
        }

        .action-btn:hover {
            background: #2563eb;
        }

        .footer-note {
            margin-top: 40px;
            font-size: 13px;
            color: #6b7280;
        }
    </style>

    <div class="container">
        <div class="logo">
            @if ($logoBase64)
                <img src="{{ $logoBase64 }}">
            @endif
        </div>

        <div class="success-icon">✓</div>

        <h1>Tanda Tangan Berhasil!</h1>

        <div class="subtitle">
            Dokumen Anda telah ditandatangani secara elektronik dengan aman.
        </div>

        <div class="thanks">
            Terima kasih telah menggunakan layanan kami.
        </div>

        {{-- <a href="index.html" class="action-btn">
            Kembali ke Beranda
        </a> --}}

        <div class="footer-note">
            Halaman akan ditutup dalam <span id="countdown">3</span> detik<br>
            Jika tidak tertutup otomatis, silakan tutup tab ini.
        </div>
    </div>

    <script>
        let timeLeft = 3;
        const countdownEl = document.getElementById("countdown");

        const timer = setInterval(() => {

            timeLeft--;

            countdownEl.textContent = timeLeft;

            if (timeLeft <= 0) {

                clearInterval(timer);

                // window.close();

                // window.location.href = "about:blank";

            }

        }, 1000);
    </script>
@endsection
