<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tiket Seminar Innoventure</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background-color: #f3f4f6; padding: 20px; margin: 0; }
        .card { max-width: 450px; margin: 0 auto; background-color: #ffffff; padding: 32px; border-radius: 16px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); border: 1px solid #f3f4f6; text-align: center; }
        .icon-container { width: 64px; height: 64px; background-color: #dcfce7; color: #16a34a; border-radius: 50%; display: inline-block; text-align: center; line-height: 64px; margin-bottom: 16px; font-size: 32px; font-weight: bold; }
        .title { font-size: 24px; font-weight: bold; color: #111827; margin: 0 0 8px 0; }
        .subtitle { font-size: 16px; color: #6b7280; margin: 0 0 24px 0; }
        .ticket-box { background-color: #f9fafb; padding: 24px; border-radius: 12px; border: 2px dashed #e5e7eb; margin-bottom: 24px; }
        .qr-box { background-color: #ffffff; padding: 16px; border-radius: 12px; display: inline-block; margin-bottom: 16px; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); }
        .label { font-size: 14px; color: #6b7280; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; margin: 0 0 4px 0; }
        .redeem-code { font-size: 30px; font-weight: 900; letter-spacing: 4px; color: #4f46e5; margin: 0 0 16px 0; }
        .raffle-number { font-size: 18px; font-weight: bold; color: #1f2937; margin: 0; }
        .participant-info { text-align: left; background-color: #eff6ff; padding: 16px; border-radius: 8px; border: 1px solid #dbeafe; margin-bottom: 24px; }
        .participant-info p { margin: 4px 0; font-size: 14px; color: #1e40af; }
        .participant-info strong { color: #1e3a8a; }
        .footer { text-align: center; margin-top: 32px; font-size: 12px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon-container">✓</div>
        <h2 class="title">Ticket Secured!</h2>
        <p class="subtitle">This is your digital ticket for the Innoventure Seminar.</p>
        
        <div class="ticket-box">
            <div class="qr-box">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $peserta->kode_absen }}" alt="QR Code" width="200" height="200">
            </div>
            
            <p class="label">Redeem Code</p>
            <p class="redeem-code">{{ $peserta->kode_absen }}</p>
            
            <p class="label">Raffle Number</p>
            <p class="raffle-number">{{ $peserta->no_undian ?? '-' }}</p>
        </div>

        <div class="participant-info">
            <p><strong>Nama:</strong> {{ $peserta->nama }}</p>
            <p><strong>Instansi:</strong> {{ $peserta->instansi }}</p>
            <p><strong>Email:</strong> {{ $peserta->email }}</p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} HIMF Innoventure. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
