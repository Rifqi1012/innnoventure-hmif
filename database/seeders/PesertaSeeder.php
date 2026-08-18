<?php

namespace Database\Seeders;

use App\Models\CabangLomba;
use App\Models\Instansi;
use App\Models\Tim;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PesertaSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $webDev = CabangLomba::firstOrCreate(['nama' => 'WEB DEVELOPMENT'], ['jenis_penilaian' => 'juri', 'tanggal_mulai' => '2026-08-01', 'tanggal_berakhir' => '2026-08-31']);
            $uiUx = CabangLomba::firstOrCreate(['nama' => 'UI/UX'], ['jenis_penilaian' => 'juri', 'tanggal_mulai' => '2026-08-01', 'tanggal_berakhir' => '2026-08-31']);

            $instansiUmum = Instansi::firstOrCreate(['nama' => 'Umum'], ['logo' => 'default.png']);

            $webdevData = [
                ['nama_tim' => 'Krissagi 67', 'email' => 'krissagi-6767@innoventure.com', 'password' => '2x4OJx9V', 'role' => 'peserta'],
                ['nama_tim' => 'AetherSky', 'email' => 'aethersky96@innoventure.com', 'password' => 'BAVXUEMi', 'role' => 'peserta'],
                ['nama_tim' => 'Bubur Goreng', 'email' => 'bubur-goreng14@innoventure.com', 'password' => 'xn2zpds2', 'role' => 'peserta'],
                ['nama_tim' => 'timber', 'email' => 'timber83@innoventure.com', 'password' => 'a7D9ij5V', 'role' => 'peserta'],
                ['nama_tim' => 'PL ENDEAVOR', 'email' => 'pl-endeavor67@innoventure.com', 'password' => 'H584nnwO', 'role' => 'peserta'],
                ['nama_tim' => 'SMK YADIKA 6', 'email' => 'smk-yadika-633@innoventure.com', 'password' => 'DmpG6WU5', 'role' => 'peserta'],
                ['nama_tim' => 'Muhammad zaidane Mubarok', 'email' => 'muhammad-zaidane-mubarok51@innoventure.com', 'password' => 'i8qWybu6', 'role' => 'peserta'],
                ['nama_tim' => 'No 1 kenzio soldier', 'email' => 'no-1-kenzio-soldier97@innoventure.com', 'password' => '3iWH90bB', 'role' => 'peserta'],
                ['nama_tim' => 'Three BugCoders', 'email' => 'three-bugcoders14@innoventure.com', 'password' => 'brNWgC8G', 'role' => 'peserta'],
                ['nama_tim' => 'NEPER-DevWeb', 'email' => 'neper-devweb81@innoventure.com', 'password' => 'JntqxXwE', 'role' => 'peserta'],
                ['nama_tim' => 'GAMAU EH HASHFI', 'email' => 'gamau-eh-hashfi78@innoventure.com', 'password' => 'MHiMixh0', 'role' => 'peserta'],
                ['nama_tim' => 'GitsubTeams', 'email' => 'gitsubteams65@innoventure.com', 'password' => 't0yuZO3d', 'role' => 'peserta'],
                ['nama_tim' => 'tele-trio', 'email' => 'tele-trio96@innoventure.com', 'password' => '6jnHMTSg', 'role' => 'peserta'],
                ['nama_tim' => 'ngortis', 'email' => 'ngortis56@innoventure.com', 'password' => '1ERQ9B5O', 'role' => 'peserta'],
                ['nama_tim' => 'TPG Apage', 'email' => 'tpg-apage95@innoventure.com', 'password' => 'fJS1ZGAD', 'role' => 'peserta'],
                ['nama_tim' => 'Gelombang Ekonomi', 'email' => 'gelombang-ekonomi43@innoventure.com', 'password' => 'igB9dhvt', 'role' => 'peserta'],
                ['nama_tim' => 'Belah Ketupat', 'email' => 'belah-ketupat27@innoventure.com', 'password' => 'GRPOawHL', 'role' => 'peserta'],
                ['nama_tim' => 'Mutia', 'email' => 'mutia60@innoventure.com', 'password' => 'YLKmx3u3', 'role' => 'peserta'],
                ['nama_tim' => 'PROMPT ENGINEER', 'email' => 'prompt-engineer80@innoventure.com', 'password' => 'aCkSpU7H', 'role' => 'peserta'],
                ['nama_tim' => 'Revolta', 'email' => 'revolta93@innoventure.com', 'password' => 'OZBHtlZU', 'role' => 'peserta'],
                ['nama_tim' => 'SIMANIS', 'email' => 'simanis49@innoventure.com', 'password' => 'mI5SuIOl', 'role' => 'peserta'],
                ['nama_tim' => 'NEWKODE', 'email' => 'newkode75@innoventure.com', 'password' => 'Zkxv41wq', 'role' => 'peserta'],
                ['nama_tim' => 'SMK SUMATRA 40 BANDUNG (Nesozio)', 'email' => 'smk-sumatra-40-bandung-nesozio62@innoventure.com', 'password' => 'ufrTwIVJ', 'role' => 'peserta'],
                ['nama_tim' => 'Ambaweb', 'email' => 'ambaweb41@innoventure.com', 'password' => 'uitX8fLQ', 'role' => 'peserta'],
                ['nama_tim' => 'Clover', 'email' => 'clover54@innoventure.com', 'password' => 'dY43kd0x', 'role' => 'peserta'],
                ['nama_tim' => 'NASADAN', 'email' => 'nasadan96@innoventure.com', 'password' => 'P1JWNxsD', 'role' => 'peserta'],
                ['nama_tim' => 'smapy 1234', 'email' => 'smapy-123411@innoventure.com', 'password' => 'QfJww2M8', 'role' => 'peserta'],
                ['nama_tim' => 'Hanchou Sanchou', 'email' => 'hanchou-sanchou21@innoventure.com', 'password' => 'FssLN1Hv', 'role' => 'peserta'],
                ['nama_tim' => 'IGM', 'email' => 'igm72@innoventure.com', 'password' => '6907lTY7', 'role' => 'peserta'],
                ['nama_tim' => 'paas 100 terus amin', 'email' => 'paas-100-terus-amin91@innoventure.com', 'password' => 'hi42Pzhx', 'role' => 'peserta'],
                ['nama_tim' => 'DevsTerFour', 'email' => 'devsterfour20@innoventure.com', 'password' => 'lcUCV6d9', 'role' => 'peserta'],
                ['nama_tim' => 'CodeVend', 'email' => 'codevend48@innoventure.com', 'password' => 'h0fcl5PN', 'role' => 'peserta'],
                ['nama_tim' => 'Whimsy', 'email' => 'whimsy28@innoventure.com', 'password' => 'yHc0ljUl', 'role' => 'peserta'],
                ['nama_tim' => 'S2 tech', 'email' => 's2-tech20@innoventure.com', 'password' => 'hyQHrGd1', 'role' => 'peserta'],
                ['nama_tim' => 'Ambassador', 'email' => 'ambassador24@innoventure.com', 'password' => 'fL6qCOAd', 'role' => 'peserta'],
                ['nama_tim' => 'Marhas Digital Solutions', 'email' => 'marhas-digital-solutions87@innoventure.com', 'password' => 'bT9QMVzP', 'role' => 'peserta'],
            ];

            foreach ($webdevData as $data) {
                // Mapping role 'peserta' dari CSV menjadi 'peserta_webdev' agar sesuai sistem
                $roleToUse = $data['role'] === 'peserta' ? 'peserta_webdev' : $data['role'];

                User::updateOrCreate(
                    ['email' => $data['email']],
                    [
                        'name' => $data['nama_tim'],
                        'password' => Hash::make($data['password']),
                        'raw_password' => $data['password'],
                        'role' => $roleToUse,
                    ]
                );

                Tim::firstOrCreate(
                    ['nama' => $data['nama_tim'], 'cabang_lomba_id' => $webDev->id],
                    ['instansi_id' => $instansiUmum->id, 'status' => 'Approved']
                );
            }

            $uiuxData = [
                ['nama_tim' => 'RISYA NUR AMELIA', 'email' => 'risya-nur-amelia24@innoventure.com', 'password' => 'UoL775jF', 'role' => 'peserta_uiux'],
                ['nama_tim' => 'Muchammad Fauzii Afriza', 'email' => 'muchammad-fauzii-afriza53@innoventure.com', 'password' => 'zrcfkYhj', 'role' => 'peserta_uiux'],
                ['nama_tim' => 'Malya Maritza', 'email' => 'malya-maritza76@innoventure.com', 'password' => 'uoDYj5TN', 'role' => 'peserta_uiux'],
                ['nama_tim' => 'Christian Rizky Farrell', 'email' => 'christian-rizky-farrell53@innoventure.com', 'password' => 'utk2CzoE', 'role' => 'peserta_uiux'],
                ['nama_tim' => 'aurelfilza553', 'email' => 'aurelfilza55310@innoventure.com', 'password' => 'AhH6wAm4', 'role' => 'peserta_uiux'],
                ['nama_tim' => 'Jevon Tamaro Tamba', 'email' => 'jevon-tamaro-tamba69@innoventure.com', 'password' => 'vw5ErsKb', 'role' => 'peserta_uiux'],
                ['nama_tim' => 'Basarif Salampessy', 'email' => 'basarif-salampessy31@innoventure.com', 'password' => 'N18TBdUS', 'role' => 'peserta_uiux'],
                ['nama_tim' => 'MIKKA AURORA MADINIA', 'email' => 'mikka-aurora-madinia23@innoventure.com', 'password' => 'oNo5i0mL', 'role' => 'peserta_uiux'],
                ['nama_tim' => 'Rahim Ali Fahryan', 'email' => 'rahim-ali-fahryan97@innoventure.com', 'password' => 'OfMy8yoB', 'role' => 'peserta_uiux'],
                ['nama_tim' => 'Gurat Lucky Wicaksono', 'email' => 'gurat-lucky-wicaksono19@innoventure.com', 'password' => 'zsL5F0Cw', 'role' => 'peserta_uiux'],
                ['nama_tim' => 'Ari Nurjaman', 'email' => 'ari-nurjaman87@innoventure.com', 'password' => '1QfpDZK4', 'role' => 'peserta_uiux'],
                ['nama_tim' => 'ashrill', 'email' => 'ashrill19@innoventure.com', 'password' => 'REkgOMBP', 'role' => 'peserta_uiux'],
                ['nama_tim' => 'Luwi Arjuna Sakti Kurnianto', 'email' => 'luwi-arjuna-sakti-kurnianto43@innoventure.com', 'password' => 'SnVmy3it', 'role' => 'peserta_uiux'],
                ['nama_tim' => 'Rafa\'at Shastradynata', 'email' => 'rafaat-shastradynata78@innoventure.com', 'password' => 'lZC1wNOG', 'role' => 'peserta_uiux'],
                ['nama_tim' => 'Kafi Fathilia Rahman', 'email' => 'kafi-fathilia-rahman93@innoventure.com', 'password' => '8aiBkFZl', 'role' => 'peserta_uiux'],
                ['nama_tim' => 'sofiyasminanugraha', 'email' => 'sofiyasminanugraha13@innoventure.com', 'password' => 'YpsXguBA', 'role' => 'peserta_uiux'],
                ['nama_tim' => 'Faris Isham Nugraha', 'email' => 'faris-isham-nugraha52@innoventure.com', 'password' => 'xQifC7yq', 'role' => 'peserta_uiux'],
                ['nama_tim' => 'Dwi Saifullah Aulianto', 'email' => 'dwi-saifullah-aulianto80@innoventure.com', 'password' => 'ma6idY5a', 'role' => 'peserta_uiux'],
                ['nama_tim' => 'Muhammad Ichsan Kamil', 'email' => 'muhammad-ichsan-kamil80@innoventure.com', 'password' => 'wvRQ3pMt', 'role' => 'peserta_uiux'],
                ['nama_tim' => 'flavia.achiko', 'email' => 'flaviaachiko22@innoventure.com', 'password' => 'kak5GjcV', 'role' => 'peserta_uiux'],
                ['nama_tim' => 'ICHA VALENTINA', 'email' => 'icha-valentina50@innoventure.com', 'password' => '4KI0l5U8', 'role' => 'peserta_uiux'],
                ['nama_tim' => 'Adimas Syahyudi', 'email' => 'adimas-syahyudi76@innoventure.com', 'password' => 'jq1SxZJc', 'role' => 'peserta_uiux'],
                ['nama_tim' => 'Rizky Nadir F', 'email' => 'rizky-nadir-f51@innoventure.com', 'password' => 'q4g4sjY8', 'role' => 'peserta_uiux'],
                ['nama_tim' => 'Zalia Putri Adnandya', 'email' => 'zalia-putri-adnandya86@innoventure.com', 'password' => '7fvNUkDY', 'role' => 'peserta_uiux'],
                ['nama_tim' => 'Muhammad Maulidhan Azzam', 'email' => 'muhammad-maulidhan-azzam94@innoventure.com', 'password' => 'wlZwDWCz', 'role' => 'peserta_uiux'],
                ['nama_tim' => 'reyhanramadhan', 'email' => 'reyhanramadhan70@innoventure.com', 'password' => 'Z3xJFou3', 'role' => 'peserta_uiux'],
                ['nama_tim' => 'Rendi Kurniawan', 'email' => 'rendi-kurniawan68@innoventure.com', 'password' => 'HuR2UE4w', 'role' => 'peserta_uiux'],
                ['nama_tim' => 'Davin Fabian', 'email' => 'davin-fabian37@innoventure.com', 'password' => 'yXLN2sjE', 'role' => 'peserta_uiux'],
                ['nama_tim' => 'Ratu Kayyisa Mazaya', 'email' => 'ratu-kayyisa-mazaya33@innoventure.com', 'password' => 'JfDAitDu', 'role' => 'peserta_uiux'],
                ['nama_tim' => 'Keiko Aleisha Turana', 'email' => 'keiko-aleisha-turana91@innoventure.com', 'password' => 'aVIgbbXV', 'role' => 'peserta_uiux'],
                ['nama_tim' => 'Muhammad Rehan Fatih Suhendra', 'email' => 'muhammad-rehan-fatih-suhendra67@innoventure.com', 'password' => 'cCLSgfHD', 'role' => 'peserta_uiux'],
                ['nama_tim' => 'Indi Octaviani', 'email' => 'indi-octaviani24@innoventure.com', 'password' => 'JZqvsEuU', 'role' => 'peserta_uiux'],
                ['nama_tim' => 'NADYA SITI SABILA', 'email' => 'nadya-siti-sabila66@innoventure.com', 'password' => 'hnXy5Pjk', 'role' => 'peserta_uiux'],
                ['nama_tim' => 'Rama Permana Kusuma', 'email' => 'rama-permana-kusuma44@innoventure.com', 'password' => 'sjHK6Ml0', 'role' => 'peserta_uiux'],
                ['nama_tim' => 'Azka Syaida Asmani', 'email' => 'azka-syaida-asmani51@innoventure.com', 'password' => 'RA4Iv2UM', 'role' => 'peserta_uiux'],
                // ['nama_tim' => 'EPUL PELER', 'email' => 'EPULPELER@innoventure.com', 'password' => 'REkgOMBP', 'role' => 'peserta_uiux'],
            ];

            foreach ($uiuxData as $data) {
                User::updateOrCreate(
                    ['email' => $data['email']],
                    [
                        'name' => $data['nama_tim'],
                        'password' => Hash::make($data['password']),
                        'raw_password' => $data['password'],
                        'role' => $data['role'],
                    ]
                );

                Tim::firstOrCreate(
                    ['nama' => $data['nama_tim'], 'cabang_lomba_id' => $uiUx->id],
                    ['instansi_id' => $instansiUmum->id, 'status' => 'Approved']
                );
            }
        });
    }
}
