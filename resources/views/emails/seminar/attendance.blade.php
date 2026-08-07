<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Konfirmasi Kehadiran Seminar</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background-color: #f3f4f6; padding: 20px; margin: 0; }
        .card { max-width: 500px; margin: 0 auto; background-color: #ffffff; padding: 32px; border-radius: 16px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); border: 1px solid #f3f4f6; text-align: center; }
        .icon-container { width: 64px; height: 64px; background-color: #dbeafe; color: #2563eb; border-radius: 50%; display: inline-block; text-align: center; line-height: 64px; margin-bottom: 16px; font-size: 32px; font-weight: bold; }
        .title { font-size: 24px; font-weight: bold; color: #111827; margin: 0 0 8px 0; }
        .subtitle { font-size: 16px; color: #6b7280; margin: 0 0 24px 0; }
        .info-box { text-align: left; background-color: #f8fafc; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 24px; }
        .info-box p { margin: 8px 0; font-size: 15px; color: #334155; }
        .info-box strong { color: #0f172a; }
        .footer { text-align: center; margin-top: 32px; font-size: 12px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon-container">👋</div>
        <h2 class="title">Terima Kasih Telah Hadir!</h2>
        <p class="subtitle">Kehadiran Anda di Seminar Innoventure telah tercatat.</p>
        
        <div class="info-box">
            <p>Halo <strong>{{ $peserta->nama }}</strong>,</p>
            <p>Email ini adalah konfirmasi otomatis bahwa Anda telah berhasil melakukan registrasi ulang (absen) di lokasi acara Seminar Innoventure.</p>
            <br>
            <p><strong>Waktu Kehadiran:</strong> {{ $peserta->updated_at->format('d M Y, H:i') }}</p>
            <p><strong>No. Undian / Doorprice:</strong> {{ $peserta->no_undian ?? '-' }}</p>
            <br>
            <p>Selamat mengikuti rangkaian acara! Jangan lewatkan kesempatan untuk mendapatkan doorprize menarik di akhir acara.</p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} HIMF Innoventure. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
