<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Dispensasi Siswa</title>
    <style>
        @page {
            margin: 2.5cm 2.5cm 2.5cm 3cm; /* Formal margin for A4: Top, Right, Bottom, Left */
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
        }
        
        /* KOP SURAT */
        .kop-surat {
            width: 100%;
            margin-bottom: 2px;
        }
        .kop-surat table {
            width: 100%;
            border-collapse: collapse;
        }
        .kop-surat td {
            vertical-align: middle;
        }
        .kop-logo {
            width: 90px;
            text-align: left;
        }
        /* Placeholder for Logo */
        .kop-logo-placeholder {
            width: 80px;
            height: 80px;
            border: 1px dashed #ccc;
            display: inline-block;
            text-align: center;
            line-height: 80px;
            font-size: 8pt;
            color: #999;
        }
        .kop-text {
            text-align: center;
        }
        .kop-text h2 {
            margin: 0;
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .kop-text h1 {
            margin: 0;
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .kop-text p {
            margin: 5px 0 0 0;
            font-size: 10pt;
        }
        .hr-kop-1 {
            border: 0;
            border-top: 3px solid #000;
            margin: 5px 0 2px 0;
        }
        .hr-kop-2 {
            border: 0;
            border-top: 1px solid #000;
            margin: 0 0 30px 0;
        }

        /* TANGGAL */
        .date-right {
            text-align: right;
            margin-bottom: 25px;
        }

        /* JUDUL SURAT */
        .title {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 25px;
            font-size: 12pt;
        }

        /* ISI SURAT */
        .content {
            text-align: justify;
            margin-bottom: 15px;
        }
        
        .details {
            margin-left: 40px;
            margin-bottom: 20px;
        }
        .details table {
            border: none;
        }
        .details td {
            padding: 2px 5px;
            vertical-align: top;
        }
        
        /* TABEL PESERTA */
        .peserta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .peserta-table th, .peserta-table td {
            border: 1px solid black;
            padding: 6px 10px;
            text-align: left;
        }
        .peserta-table th {
            text-align: center;
            font-weight: bold;
        }
        .col-no {
            width: 5%;
            text-align: center !important;
        }
        .col-nama {
            width: 60%;
        }
        .col-kelas {
            width: 35%;
        }

        /* PENUTUP */
        .footer-text {
            margin-bottom: 40px;
            text-align: justify;
        }

        /* TANDA TANGAN */
        .signatures {
            width: 100%;
            margin-top: 20px;
            border: none;
        }
        .signatures td {
            text-align: center;
            width: 50%;
            vertical-align: bottom;
            border: none;
            padding: 0;
        }
        .ttd-space {
            height: 90px; /* Ruang kosong untuk tanda tangan/stempel */
        }
        .name {
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- 1. KOP SURAT -->
    <div class="kop-surat">
        <table>
            <tr>
                <td class="kop-logo">
                    <!-- Jika ada logo resmi UNIKOM, bisa di-replace di src ini -->
                    <div class="kop-logo-placeholder">Logo<br>UNIKOM</div>
                </td>
                <td class="kop-text">
                    <h2>HIMPUNAN MAHASISWA TEKNIK INFORMATIKA</h2>
                    <h1>UNIVERSITAS KOMPUTER INDONESIA (UNIKOM)</h1>
                    <p>
                        Jalan Dipatiukur Nomor 114 Kampus 4 Lantai 4 Telepon (022) 91751073<br>
                        Email: hmifunikom15197@gmail.com | Website: hmif.unikom.my.id
                    </p>
                </td>
            </tr>
        </table>
    </div>
    <hr class="hr-kop-1">
    <hr class="hr-kop-2">

    <!-- TANGGAL SURAT -->
    <div class="date-right">
        Bandung, {{ $tanggal_surat }}
    </div>

    <!-- 2. JUDUL SURAT -->
    <div class="title">
        SURAT DISPENSASI SISWA
    </div>

    <!-- 3. ISI SURAT -->
    <div class="content">
        Dengan ini menerangkan bahwa nama-nama terlampir mengikuti perlombaan INNOVENTURE CHAPTER II tingkat SMA/SMK se-Jawa Barat yang diadakan oleh Himpunan Mahasiswa Teknik Informatika UNIKOM pada:
    </div>

    <div class="details">
        <table>
            <tr>
                <td width="70">Hari</td>
                <td width="10">:</td>
                <td>{{ $hari }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>:</td>
                <td>{{ $tanggal }}</td>
            </tr>
            <tr>
                <td>Waktu</td>
                <td>:</td>
                <td>{{ $pukul }}</td>
            </tr>
        </table>
    </div>

    <table class="peserta-table">
        <thead>
            <tr>
                <th class="col-no">No.</th>
                <th class="col-nama">Nama</th>
                <th class="col-kelas">Kelas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peserta as $index => $p)
            <tr>
                <td class="col-no">{{ $index + 1 }}</td>
                <td>{{ $p['nama'] }}</td>
                <td>{{ $p['kelas'] }}</td>
            </tr>
            @endforeach
            @if(count($peserta) < 6)
                @for($i = count($peserta) + 1; $i <= 6; $i++)
                <tr>
                    <td class="col-no">{{ $i }}</td>
                    <td></td>
                    <td></td>
                </tr>
                @endfor
            @endif
        </tbody>
    </table>

    <div class="footer-text">
        Demikian surat dispensasi ini untuk digunakan sebagai mana mestinya.
    </div>

    <!-- 4 & 5 & 6. BAGIAN TANDA TANGAN & STEMPEL -->
    <table class="signatures">
        <tr>
            <td colspan="2" style="text-align: center; padding-bottom: 20px;">
                Hormat Kami,
            </td>
        </tr>
        <tr>
            <td>
                Ketua Himpunan Mahasiswa<br>
                Teknik Informatika
            </td>
            <td>
                Ketua Pelaksana
            </td>
        </tr>
        <tr>
            <td>
                <div class="ttd-space">
                    <!-- Tempat untuk gambar tanda tangan / stempel (kiri) -->
                    <!-- <img src="/path/to/ttd_hima.png" style="height: 80px;"> -->
                </div>
            </td>
            <td>
                <div class="ttd-space">
                    <!-- Tempat untuk gambar tanda tangan (kanan) -->
                    <!-- <img src="/path/to/ttd_pelaksana.png" style="height: 80px;"> -->
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <span class="name">Barka Tirta Rama Abdurrahman</span><br>
                NIM. 1.01.23.365
            </td>
            <td>
                <span class="name">Rizki Maulana Hakim</span><br>
                NIM. 1.01.24.317
            </td>
        </tr>
    </table>

</body>
</html>
