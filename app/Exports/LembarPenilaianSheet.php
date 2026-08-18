<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Database\Eloquent\Collection;
use App\Models\AspekPenilaian;

class LembarPenilaianSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $records;
    protected $cabang;
    protected $aspeks;

    public function __construct(Collection $records, string $cabang)
    {
        $this->records = $records;
        $this->cabang = $cabang;
        // Fetch aspects specific to this branch
        $this->aspeks = AspekPenilaian::whereHas('cabangLomba', fn($q) => $q->where('nama', $this->cabang))->get();
    }

    public function collection()
    {
        return $this->records;
    }

    public function title(): string
    {
        return 'Lembar Penilaian';
    }

    public function headings(): array
    {
        $heads = ['Nama Tim', 'Judul Proyek'];
        foreach ($this->aspeks as $aspek) {
            $heads[] = "{$aspek->nama} ({$aspek->bobot_penilaian}%)";
        }
        $heads[] = 'Total Nilai';
        $heads[] = 'Komentar Juri';
        return $heads;
    }

    public function map($row): array
    {
        $data = [
            $row->tim->nama ?? '',
            $row->judul_proyek ?? '',
        ];
        
        // Add empty columns for each scoring aspect
        foreach ($this->aspeks as $aspek) {
            $data[] = '';
        }
        
        // Add empty columns for Total Nilai and Komentar Juri
        $data[] = '';
        $data[] = '';
        
        return $data;
    }
}
