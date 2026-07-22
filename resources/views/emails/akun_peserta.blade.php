<!DOCTYPE html>
<html>
<head>
    <title>Akun Peserta Innoventure</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Halo, {{ $user->name }}!</h2>
    <p>Selamat bergabung di INNOVENTURE. Akun Anda telah berhasil dibuat oleh Admin.</p>
    
    <div style="background: #f4f4f4; padding: 15px; border-radius: 8px; margin: 20px 0;">
        <h3 style="margin-top: 0;">Detail Login Anda:</h3>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Password:</strong> {{ $password }}</p>
        <p><strong>Role:</strong> {{ $user->role }}</p>
    </div>

    <p style="color: #e53e3e;"><strong>Penting:</strong> Simpan password ini dengan baik karena sistem kami menggunakan password unik yang di-generate otomatis.</p>
    
    <h3>Timeline Kegiatan</h3>
    <ul>
        <li><strong>Pendaftaran:</strong> 1 - 31 Agustus</li>
        <li><strong>Pengumpulan Karya:</strong> 1 - 15 September</li>
        <li><strong>Penilaian:</strong> 16 - 25 September</li>
        <li><strong>Pengumuman:</strong> 30 September</li>
    </ul>

    <p>Silakan login ke <a href="{{ config('app.url') }}/login">Dashboard Innoventure</a> untuk memulai.</p>
    <br>
    <p>Salam hangat,<br>Tim Innoventure</p>
</body>
</html>
