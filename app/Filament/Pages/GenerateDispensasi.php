<?php

namespace App\Filament\Pages;

use App\Services\Surat\DispensasiService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Validation\ValidationException;

class GenerateDispensasi extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Generate Surat';
    protected static ?string $navigationGroup = 'Surat';
    protected static ?string $title = 'Generate Surat Dispensasi';
    protected static string $view = 'filament.pages.generate-dispensasi';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(DispensasiService::defaults());
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Informasi Surat')->schema([
                Select::make('jenis_surat')
                    ->label('Jenis Surat')
                    ->options(DispensasiService::templates())
                    ->required()->live(),
                TextInput::make('kota')->required()->maxLength(100)->live(debounce: 400),
                DatePicker::make('tanggal_surat')->required()->native(false)->displayFormat('d F Y')->live(),
                TextInput::make('judul')->required()->maxLength(150)->live(debounce: 400),
                Textarea::make('pembuka')->required()->rows(5)->live(debounce: 500),
                TextInput::make('hari_acara')->label('Hari Acara')->required()->readOnly()
                    ->helperText('Otomatis mengikuti tanggal acara.'),
                DatePicker::make('tanggal_acara')->required()->native(false)->displayFormat('d F Y')->live()
                    ->afterStateUpdated(fn ($state, callable $set) => $set('hari_acara', DispensasiService::formatDay($state))),
                TimePicker::make('jam_mulai')->seconds(false)->required()->live(),
                TimePicker::make('jam_selesai')->seconds(false)->required()->live()
                    ->after('jam_mulai')
                    ->validationMessages(['after' => 'Jam selesai harus lebih besar dari jam mulai.']),
            ])->columns(2),

            Section::make('Data Peserta')->schema([
                Repeater::make('peserta')->hiddenLabel()->schema([
                    TextInput::make('nama')->required()->maxLength(150)->live(debounce: 400),
                    TextInput::make('kelas')->required()->maxLength(100)->live(debounce: 400),
                ])->columns(2)->minItems(1)->defaultItems(1)->addActionLabel('Tambah Peserta')
                    ->reorderable(false)->live(),
            ]),

            Section::make('Tanda Tangan & Penandatangan')->schema([
                Section::make('Ketua Himpunan Mahasiswa Teknik Informatika')->schema([
                    TextInput::make('ketua_hmif.nama')->label('Nama')->required()->live(debounce: 400),
                    TextInput::make('ketua_hmif.nim')->label('NIM')->required()->live(debounce: 400),
                    Textarea::make('ketua_hmif.jabatan')->label('Jabatan')->rows(2)->required()->live(debounce: 400),
                    FileUpload::make('ketua_hmif.signature')->label('Upload Tanda Tangan')
                        ->disk('public')->directory('surat/signatures')->visibility('public')
                        ->image()->acceptedFileTypes(['image/png', 'image/jpeg', 'image/webp'])
                        ->maxSize(2048)->imagePreviewHeight('120')->openable()->downloadable()->live()
                        ->helperText('PNG transparan direkomendasikan. Maksimal 2 MB.'),
                    Toggle::make('ketua_hmif.tampilkan_ttd')->label('Tampilkan tanda tangan di surat')->live(),
                ])->columnSpan(1),
                Section::make('Ketua Pelaksana')->schema([
                    TextInput::make('ketua_pelaksana.nama')->label('Nama')->required()->live(debounce: 400),
                    TextInput::make('ketua_pelaksana.nim')->label('NIM')->required()->live(debounce: 400),
                    Textarea::make('ketua_pelaksana.jabatan')->label('Jabatan')->rows(2)->required()->live(debounce: 400),
                    FileUpload::make('ketua_pelaksana.signature')->label('Upload Tanda Tangan')
                        ->disk('public')->directory('surat/signatures')->visibility('public')
                        ->image()->acceptedFileTypes(['image/png', 'image/jpeg', 'image/webp'])
                        ->maxSize(2048)->imagePreviewHeight('120')->openable()->downloadable()->live()
                        ->helperText('PNG transparan direkomendasikan. Maksimal 2 MB.'),
                    Toggle::make('ketua_pelaksana.tampilkan_ttd')->label('Tampilkan tanda tangan di surat')->live(),
                ])->columnSpan(1),
                Section::make('Stempel HMIF')->schema([
                    FileUpload::make('stamp_hmif')->label('Upload Stempel HMIF')
                        ->disk('public')->directory('surat/stamps')->visibility('public')
                        ->image()->acceptedFileTypes(['image/png', 'image/jpeg', 'image/webp'])
                        ->maxSize(2048)->imagePreviewHeight('120')->openable()->downloadable()->live()
                        ->helperText('PNG transparan direkomendasikan. Maksimal 2 MB.'),
                    Toggle::make('tampilkan_stempel')->label('Tampilkan stempel')->live(),
                ])->columnSpanFull()->columns(2),
            ])->columns(['default' => 1, 'xl' => 2]),
        ])->statePath('data');
    }

    public function getPreviewDataProperty(): array
    {
        return DispensasiService::prepare($this->data ?? []);
    }

    public function generatePdf(DispensasiService $service)
    {
        $state = $this->form->getState();

        if (($state['jam_selesai'] ?? '') <= ($state['jam_mulai'] ?? '')) {
            throw ValidationException::withMessages([
                'data.jam_selesai' => 'Jam selesai harus lebih besar dari jam mulai.',
            ]);
        }

        return $service->download($state);
    }
}
