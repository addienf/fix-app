@extends('pdf.layout.layout')

@section('title', 'Tanda Tangan Customer')

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
            padding: 32px;
            width: 100%;
            max-width: 480px;
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
            margin: 0 auto 24px;
        }

        h1 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 8px;
            color: #1a1a1a;
        }

        .subtitle {
            text-align: center;
            color: #666;
            font-size: 14px;
            margin-bottom: 32px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #444;
            font-size: 14px;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.2s;
        }

        input[type="text"]:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .signature-area {
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            background: #fafafa;
            height: 180px;
            position: relative;
            overflow: hidden;
            cursor: crosshair;
            margin-bottom: 12px;
        }

        .signature-area canvas {
            width: 100%;
            height: 100%;
            touch-action: none;
        }

        .placeholder {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #9ca3af;
            font-size: 15px;
            pointer-events: none;
            user-select: none;
        }

        .signature-area.filled .placeholder {
            display: none;
        }

        .controls {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
            justify-content: center;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-submit {
            width: 100%;
        }

        .btn-clear {
            background: #e5e7eb;
            color: #374151;
        }

        .btn-clear:hover {
            background: #d1d5db;
        }

        .btn-submit {
            background: #3b82f6;
            color: white;
            font-weight: 500;
        }

        .btn-submit:hover {
            background: #2563eb;
        }

        .btn-submit:disabled {
            background: #9ca3af;
            cursor: not-allowed;
        }

        .message {
            text-align: center;
            margin-top: 16px;
            font-size: 14px;
            color: #666;
        }
    </style>



    <div class="container">

        <div class="logo">
            @if ($logoBase64)
                <img src="{{ $logoBase64 }}">
            @endif
        </div>

        <h1>Tanda Tangan Digital</h1>
        <div class="subtitle">Masukkan nama dan tanda tangan Anda</div>

        <form method="POST" action="{{ route('signature.store', ['type' => $type, 'token' => $record->sign_token]) }}"
            id="form-signature">

            @csrf

            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input name="{{ $config['name_field'] }}" type="text" id="nama" placeholder="Masukan Nama Anda"
                    required />
            </div>

            <div class="form-group">
                <label>Tanda Tangan</label>

                <div class="signature-area" id="pad">
                    <canvas id="canvas"></canvas>

                    <input type="hidden" name="{{ $config['signature_field'] }}" id="signature">
                </div>

                <div class="controls">
                    <button type="button" class="btn btn-clear" id="clear">
                        Hapus
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-submit" id="submit">
                Kirim Tanda Tangan
            </button>

            <div class="message" id="msg">
                Data akses Anda akan dicatat untuk keamanan.
            </div>
        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

    <script>
        const canvas = document.getElementById("canvas");
        const clearBtn = document.getElementById("clear");
        const pad = document.getElementById("pad");
        const form = document.querySelector("form");
        const signatureInput = document.getElementById("signature");
        const msg = document.getElementById("msg");

        let signaturePad;

        function resizeCanvas() {

            const ratio = Math.max(window.devicePixelRatio || 1, 1);

            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;

            canvas.getContext("2d").scale(ratio, ratio);

        }

        resizeCanvas();
        window.addEventListener("resize", resizeCanvas);


        signaturePad = new SignaturePad(canvas, {
            penColor: "#0118D8",
            minWidth: 0.8,
            maxWidth: 2.2,
            velocityFilterWeight: 0.7
        });


        signaturePad.addEventListener("endStroke", function() {

            if (!signaturePad.isEmpty()) {
                pad.classList.add("filled");
            }

        });


        clearBtn.addEventListener("click", function() {

            signaturePad.clear();
            pad.classList.remove("filled");

        });


        form.addEventListener("submit", function(e) {

            if (signaturePad.isEmpty()) {

                msg.textContent = "Silakan tanda tangan terlebih dahulu";
                msg.style.color = "#dc2626";

                e.preventDefault();
                return;
            }

            signatureInput.value = signaturePad.toDataURL("image/png");

        });
    </script>

@endsection
