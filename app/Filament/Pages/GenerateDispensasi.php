<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class GenerateDispensasi extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Surat Dispensasi';
    protected static ?string $title = 'Generate Surat Dispensasi';

    protected static string $view = 'filament.pages.generate-dispensasi';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'hari' => 'Senin',
            'tanggal' => '10 Agustus 2026',
            'pukul' => '08:00 - 10:30 WIB',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('hari')
                    ->label('Hari')
                    ->required(),
                TextInput::make('tanggal')
                    ->label('Tanggal')
                    ->required(),
                TextInput::make('pukul')
                    ->label('Waktu (Pukul)')
                    ->required(),
                Textarea::make('data_peserta')
                    ->label('Data Peserta (Nama - Kelas)')
                    ->placeholder("Khoirul Ummam - XI RPL 1\nBudi Santoso - XI TKJ 2")
                    ->helperText('Tulis tiap peserta di baris baru. Format: Nama - Kelas')
                    ->rows(10)
                    ->required()
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function generatePdf()
    {
        $data = $this->form->getState();
        
        $pesertaLines = explode("\n", str_replace("\r", "", $data['data_peserta']));
        $pesertaList = [];
        
        foreach ($pesertaLines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            // Try to split by '-' or ' - '
            $parts = explode('-', $line);
            if (count($parts) >= 2) {
                // Take the last part as kelas, rest as nama
                $kelas = trim(array_pop($parts));
                $nama = trim(implode('-', $parts));
                $pesertaList[] = ['nama' => $nama, 'kelas' => $kelas];
            } else {
                $pesertaList[] = ['nama' => $line, 'kelas' => '-'];
            }
        }

        $pdfData = [
            'hari' => $data['hari'],
            'tanggal' => $data['tanggal'],
            'pukul' => $data['pukul'],
            'peserta' => $pesertaList,
            'tanggal_surat' => now()->translatedFormat('d F Y'),
        ];

        $firstName = count($pesertaList) > 0 ? Str::slug($pesertaList[0]['nama']) : 'Peserta';
        $filename = 'Surat_Dispensasi_' . $firstName . '.pdf';

        $pdf = Pdf::loadView('pdf.dispensasi', $pdfData);
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $filename);
    }
}
