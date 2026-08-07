<?php

namespace App\Services;

use App\Models\DaftarSeminar;
use Illuminate\Support\Str;

class SeminarService
{
    public function register(array $data)
    {
        $data['no_undian'] = strtoupper(Str::random(8));
        $data['kode_absen'] = strtoupper(Str::random(10));

        if (isset($data['bukti_follow_ig']) && $data['bukti_follow_ig']->isValid()) {
            $data['bukti_follow_ig'] = $data['bukti_follow_ig']->store('seminar/bukti_follow', 'public');
        }

        $peserta = DaftarSeminar::create($data);

        // Send email to participant
        try {
            \Illuminate\Support\Facades\Mail::to($peserta->email)->send(new \App\Mail\SeminarRegistrationMail($peserta));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal mengirim email pendaftaran seminar: ' . $e->getMessage());
        }

        return $peserta;
    }

    public function attend(string $kodeAbsen)
    {
        $peserta = DaftarSeminar::where('kode_absen', $kodeAbsen)->first();

        if (!$peserta) {
            return null;
        }

        $peserta->is_attended = true;
        $peserta->save();

        // Send attendance email
        try {
            \Illuminate\Support\Facades\Mail::to($peserta->email)->send(new \App\Mail\SeminarAttendanceMail($peserta));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal mengirim email kehadiran seminar: ' . $e->getMessage());
        }

        return $peserta;
    }
}
