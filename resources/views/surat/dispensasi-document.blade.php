@php
    $preview = $preview ?? false;
@endphp
<style>
    @if(! $preview)
        @page {
            size: A4 portrait;
            margin: 43mm 18mm 33mm;
        }

        html, body { margin: 0; padding: 0; }
    @endif

    .surat-document { color: #111; background: #fff; font-family: "DejaVu Serif", "Times New Roman", serif; font-size: 11pt; line-height: 1.45; }
    .surat-document, .surat-document * { box-sizing: border-box; }

    .surat-document.pdf .pdf-header {
        position: fixed;
        top: -43mm;
        left: -18mm;
        right: -18mm;
        width: auto;
        height: 33.7mm;
    }
    .surat-document.pdf .pdf-footer {
        position: fixed;
        bottom: -33mm;
        left: -18mm;
        right: -18mm;
        height: 29.3mm;
    }

    .surat-document.preview {
        position: relative;
        width: 210mm;
        min-height: 297mm;
        padding: 43mm 18mm 33mm;
        transform-origin: top center;
    }
    .surat-document.preview .pdf-header { position: absolute; top: 0; left: 0; width: 100%; height: 33.7mm; }
    .surat-document.preview .pdf-footer { position: absolute; bottom: 0; left: 0; right: 0; height: 29.3mm; }

    .pdf-header { width: 100%; margin: 0; padding: 0; overflow: hidden; }
    .pdf-header img { display: block; width: 100%; height: 100%; max-width: none; margin: 0; padding: 0; object-fit: fill; }
    .surat-kop-fallback { height: 33.7mm; padding: 8mm 18mm 0; border-top: 4mm solid #fff200; border-bottom: 1px solid #111; text-align: center; }
    .surat-kop-fallback strong { display: block; font-size: 14pt; }
    .surat-kop-fallback span { display: block; font-size: 9pt; }

    .pdf-footer { position: relative; color: #666; text-align: center; font-size: 9pt; line-height: 1.15; overflow: hidden; }
    .pdf-footer-content { height: 26.3mm; padding-top: 6mm; }
    .footer-yellow-line { position: absolute; bottom: 0; left: 0; right: 0; width: 100%; height: 3mm; margin: 0; padding: 0; background: #fff200; }

    .surat-date { margin: 0 0 18px; text-align: right; }
    .surat-title { margin-bottom: 20px; text-align: center; font-size: 14pt; font-weight: bold; text-decoration: underline; }
    .surat-paragraph { margin: 0 0 12px; text-align: justify; }
    .surat-details { margin: 0 0 14px 28px; border: 0; }
    .surat-details td { padding: 1px 5px; border: 0; vertical-align: top; }
    .surat-table { width: 100%; margin: 12px 0 16px; border-collapse: collapse; page-break-inside: auto; }
    .surat-table th, .surat-table td { border: 1px solid #111; padding: 5px 8px; }
    .surat-table th { text-align: center; font-weight: bold; }
    .surat-table thead { display: table-header-group; }
    .surat-table tr { page-break-inside: avoid; }
    .surat-no { width: 8%; text-align: center; }
    .surat-name { width: 62%; }
    .surat-class { width: 30%; }
    .signature-section { page-break-inside: avoid; }
    .surat-signatures { width: 100%; margin-top: 20px; border-collapse: collapse; }
    .surat-signatures td { position: relative; width: 50%; padding: 0 12px; border: 0; text-align: center; vertical-align: top; }
    .surat-signature-space { position: relative; height: 78px; }
    .surat-signature { position: absolute; z-index: 2; top: 4px; left: 50%; max-width: 145px; max-height: 70px; transform: translateX(-50%); }
    .surat-stamp { position: absolute; z-index: 1; top: -3px; left: 50%; width: 82px; height: 82px; transform: translateX(-18%); opacity: .82; }
    .surat-signer-name { font-weight: bold; text-decoration: underline; }
</style>

<div class="surat-document {{ $preview ? 'preview' : 'pdf' }}">
    <header class="pdf-header">
        @if($data['images']['kop'])
            <img src="{{ $data['images']['kop'] }}" alt="Kop Surat HMIF">
        @else
            <div class="surat-kop-fallback">
                <strong>HIMPUNAN MAHASISWA TEKNIK INFORMATIKA</strong>
                <strong>UNIVERSITAS KOMPUTER INDONESIA</strong>
                <span>Jalan Dipatiukur Nomor 114, Kampus 4 Lantai 4, Bandung</span>
            </div>
        @endif
    </header>

    <footer class="pdf-footer">
        <div class="pdf-footer-content">
            hmif.unikom.my.id<br>
            Jalan Dipatiukur Nomor 114 Kampus 4 Lantai 4 Telepon (022) 91751073<br>
            Email : hmifunikom5197@gmail.com
        </div>
        <div class="footer-yellow-line"></div>
    </footer>

    <main class="pdf-content">
        <div class="surat-date">{{ $data['kota'] }}, {{ $data['tanggal_surat_formatted'] }}</div>
        <div class="surat-title">{{ $data['judul'] }}</div>
        <p class="surat-paragraph">{{ $data['pembuka'] }}</p>

        <table class="surat-details">
            <tr><td>Hari</td><td>:</td><td>{{ $data['hari_acara'] }}</td></tr>
            <tr><td>Tanggal</td><td>:</td><td>{{ $data['tanggal_acara_formatted'] }}</td></tr>
            <tr><td>Pukul</td><td>:</td><td>{{ $data['jam_mulai'] }} - {{ $data['jam_selesai'] }} WIB</td></tr>
        </table>

        <table class="surat-table">
            <thead><tr><th class="surat-no">No</th><th class="surat-name">Nama</th><th class="surat-class">Kelas</th></tr></thead>
            <tbody>
                @forelse($data['peserta'] as $peserta)
                    <tr><td class="surat-no">{{ $loop->iteration }}</td><td>{{ $peserta['nama'] ?? '' }}</td><td>{{ $peserta['kelas'] ?? '' }}</td></tr>
                @empty
                    <tr><td class="surat-no">1</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                @endforelse
            </tbody>
        </table>

        <div class="signature-section">
            <p class="surat-paragraph">Demikian surat dispensasi ini untuk digunakan sebagaimana mestinya.</p>
            <table class="surat-signatures">
                <tr><td colspan="2" style="padding-bottom: 10px">Hormat Kami,</td></tr>
                <tr>
                    <td>{!! nl2br(e($data['ketua_hmif']['jabatan'])) !!}</td>
                    <td>{!! nl2br(e($data['ketua_pelaksana']['jabatan'])) !!}</td>
                </tr>
                <tr>
                    <td><div class="surat-signature-space">
                        @if($data['ketua_hmif']['tampilkan_ttd'] && $data['images']['ketua_hmif'])<img class="surat-signature" src="{{ $data['images']['ketua_hmif'] }}" alt="Tanda tangan Ketua HMIF">@endif
                        @if($data['tampilkan_stempel'] && $data['images']['stamp_hmif'])<img class="surat-stamp" src="{{ $data['images']['stamp_hmif'] }}" alt="Stempel HMIF">@endif
                    </div></td>
                    <td><div class="surat-signature-space">
                        @if($data['ketua_pelaksana']['tampilkan_ttd'] && $data['images']['ketua_pelaksana'])<img class="surat-signature" src="{{ $data['images']['ketua_pelaksana'] }}" alt="Tanda tangan Ketua Pelaksana">@endif
                    </div></td>
                </tr>
                <tr>
                    <td><span class="surat-signer-name">{{ $data['ketua_hmif']['nama'] }}</span><br>NIM. {{ $data['ketua_hmif']['nim'] }}</td>
                    <td><span class="surat-signer-name">{{ $data['ketua_pelaksana']['nama'] }}</span><br>NIM. {{ $data['ketua_pelaksana']['nim'] }}</td>
                </tr>
            </table>
        </div>
    </main>
</div>
