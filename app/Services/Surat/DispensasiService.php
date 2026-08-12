<?php

namespace App\Services\Surat;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class DispensasiService
{
    public static function templates(): array
    {
        return ['dispensasi_siswa' => 'Surat Dispensasi Siswa'];
    }

    public static function defaults(): array
    {
        return [
            'jenis_surat' => 'dispensasi_siswa',
            'kota' => 'Bandung',
            'tanggal_surat' => '2026-08-10',
            'judul' => 'SURAT DISPENSASI SISWA',
            'pembuka' => 'Dengan ini menerangkan bahwa nama-nama terlampir mengikuti perlombaan INNOVENTURE CHAPTER II tingkat SMA/SMK se-Jawa Barat yang diadakan oleh Himpunan Mahasiswa Teknik Informatika UNIKOM pada:',
            'hari_acara' => 'Senin',
            'tanggal_acara' => '2026-08-10',
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:30',
            'peserta' => [['nama' => 'Khoirul Ummam Purnama', 'kelas' => 'XI RPL 1']],
            'ketua_hmif' => [
                'nama' => 'Barka Tirta Rama Abdurrahman', 'nim' => '1.01.23.365',
                'jabatan' => "Ketua Himpunan Mahasiswa\nTeknik Informatika", 'signature' => null, 'tampilkan_ttd' => true,
            ],
            'ketua_pelaksana' => [
                'nama' => 'Rizki Maulana Hakim', 'nim' => '1.01.24.317',
                'jabatan' => 'Ketua Pelaksana', 'signature' => null, 'tampilkan_ttd' => true,
            ],
            'stamp_hmif' => null,
            'tampilkan_stempel' => true,
        ];
    }

    public static function prepare(array $state, bool $forPdf = false): array
    {
        $defaults = self::defaults();
        $data = array_replace($defaults, $state);

        // Nested signer data may be partially updated, but repeater items must
        // replace the defaults completely because Filament keys them by UUID.
        $data['ketua_hmif'] = array_replace(
            $defaults['ketua_hmif'],
            is_array($state['ketua_hmif'] ?? null) ? $state['ketua_hmif'] : [],
        );
        $data['ketua_pelaksana'] = array_replace(
            $defaults['ketua_pelaksana'],
            is_array($state['ketua_pelaksana'] ?? null) ? $state['ketua_pelaksana'] : [],
        );
        $data['hari_acara'] = self::formatDay($data['tanggal_acara']);
        $data['tanggal_surat_formatted'] = self::formatDate($data['tanggal_surat']);
        $data['tanggal_acara_formatted'] = self::formatDate($data['tanggal_acara']);
        $data['jam_mulai'] = substr((string) $data['jam_mulai'], 0, 5);
        $data['jam_selesai'] = substr((string) $data['jam_selesai'], 0, 5);
        $data['assets'] = collect(['kop-surat.png', 'ttd-ketua-hmif.png', 'ttd-ketua-pelaksana.png', 'stempel-hmif.png'])
            ->mapWithKeys(fn ($file) => [$file => file_exists(public_path("surat/{$file}")) ? public_path("surat/{$file}") : null])
            ->all();
        $data['images'] = [
            'kop' => self::resolveImage(null, 'kop-surat.png', $forPdf),
            'ketua_hmif' => self::resolveImage($data['ketua_hmif']['signature'], 'ttd-ketua-hmif.png', $forPdf),
            'ketua_pelaksana' => self::resolveImage($data['ketua_pelaksana']['signature'], 'ttd-ketua-pelaksana.png', $forPdf),
            // A separate stamp is optional. Do not force a default stamp when
            // the uploaded HMIF signature already contains one.
            'stamp_hmif' => self::resolveUploadedImage($data['stamp_hmif'], $forPdf),
        ];

        return $data;
    }

    public static function formatDate(?string $date): string
    {
        if (! $date) return '';
        $months = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $value = Carbon::parse($date);
        return $value->day.' '.$months[$value->month].' '.$value->year;
    }

    public static function formatDay(?string $date): string
    {
        if (! $date) return '';
        $days = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
        return $days[Carbon::parse($date)->format('l')];
    }

    private static function resolveImage(mixed $uploadedPath, string $defaultFile, bool $forPdf): ?string
    {
        $uploadedPath = self::firstFile($uploadedPath);
        $path = null;
        $url = null;

        if (! $forPdf && $uploadedPath instanceof TemporaryUploadedFile) {
            return $uploadedPath->temporaryUrl();
        }

        if (is_string($uploadedPath) && Storage::disk('public')->exists($uploadedPath)) {
            $path = Storage::disk('public')->path($uploadedPath);
            $url = Storage::disk('public')->url($uploadedPath);
        } elseif (file_exists(public_path("surat/{$defaultFile}"))) {
            $path = public_path("surat/{$defaultFile}");
            $url = asset("surat/{$defaultFile}");
        }

        if (! $path) return null;
        if (! $forPdf) return $url;

        $mime = mime_content_type($path) ?: 'image/png';
        return 'data:'.$mime.';base64,'.base64_encode(file_get_contents($path));
    }

    private static function resolveUploadedImage(mixed $uploadedPath, bool $forPdf): ?string
    {
        $uploadedPath = self::firstFile($uploadedPath);

        if (! $forPdf && $uploadedPath instanceof TemporaryUploadedFile) {
            return $uploadedPath->temporaryUrl();
        }

        if (! is_string($uploadedPath) || ! Storage::disk('public')->exists($uploadedPath)) {
            return null;
        }

        $path = Storage::disk('public')->path($uploadedPath);

        if (! $forPdf) {
            return Storage::disk('public')->url($uploadedPath);
        }

        $mime = mime_content_type($path) ?: 'image/png';

        return 'data:'.$mime.';base64,'.base64_encode(file_get_contents($path));
    }

    private static function firstFile(mixed $file): mixed
    {
        if (! is_array($file)) {
            return $file;
        }

        return collect($file)->first(fn ($item) => is_string($item) || $item instanceof TemporaryUploadedFile);
    }

    public function download(array $state)
    {
        $data = self::prepare($state, true);
        $firstName = data_get(collect($data['peserta'])->first(), 'nama', 'peserta');
        $filename = 'Surat_Dispensasi_'.Str::slug($firstName, '_').'.pdf';

        return response()->streamDownload(function () use ($data) {
            echo Pdf::loadView('pdf.dispensasi', compact('data'))->setPaper('a4', 'portrait')->output();
        }, $filename, ['Content-Type' => 'application/pdf']);
    }
}
