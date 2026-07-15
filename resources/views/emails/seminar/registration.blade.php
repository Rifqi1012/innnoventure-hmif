<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tiket Seminar Innoventure 2026</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f3f4f6; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 40px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        h1 { color: #1e3a8a; text-align: center; }
        .details { margin-top: 30px; background-color: #f8fafc; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0; }
        .details p { margin: 10px 0; font-size: 16px; color: #334155; }
        .details strong { color: #0f172a; }
        .qr-container { text-align: center; margin-top: 40px; }
        .redeem-code { display: inline-block; font-size: 24px; font-weight: bold; color: #ffffff; background-color: #2563eb; padding: 10px 20px; border-radius: 8px; letter-spacing: 2px; margin-bottom: 20px; }
        .footer { text-align: center; margin-top: 40px; font-size: 12px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Tiket Seminar Innoventure</h1>
        <p>Halo <strong>{{ $peserta->nama }}</strong>,</p>
        <p>Terima kasih telah mendaftar di acara Seminar Innoventure 2026. Berikut adalah tiket elektronik Anda.</p>
        
        <div class="details">
            <p><strong>Nama:</strong> {{ $peserta->nama }}</p>
            <p><strong>Instansi:</strong> {{ $peserta->instansi }}</p>
            <p><strong>No. HP:</strong> {{ $peserta->no_hp }}</p>
            <p><strong>Tanggal Pendaftaran:</strong> {{ $peserta->created_at->format('d M Y H:i') }}</p>
        </div>

        <div class="qr-container">
            <p style="margin-bottom: 15px; color: #64748b;">Tunjukkan QR Code ini kepada panitia saat registrasi ulang (Absen):</p>
            <div class="redeem-code">
                {{ $peserta->kode_absen }}
            </div>
            <br>
            <!-- Menggunakan api eksternal agar mudah dirender di email client tanpa attachment khusus -->
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ $peserta->kode_absen }}" alt="QR Code" width="250" height="250">
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} HIMF Innoventure. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
