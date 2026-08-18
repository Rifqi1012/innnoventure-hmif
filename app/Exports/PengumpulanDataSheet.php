<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Illuminate\Database\Eloquent\Collection;

class PengumpulanDataSheet extends DefaultValueBinder implements FromCollection, WithHeadings, WithMapping, WithTitle, WithCustomValueBinder
{
    protected $records;
    protected $cabang;

    public function __construct(Collection $records, string $cabang)
    {
        $this->records = $records;
        $this->cabang = $cabang;
    }

    public function collection()
    {
        return $this->records;
    }

    public function title(): string
    {
        return 'Data Pengumpulan';
    }

    public function headings(): array
    {
        $heads = ['Nama Tim', 'Judul Proyek'];
        if ($this->cabang === 'WEB DEVELOPMENT') {
            return array_merge($heads, ['Link GitHub', 'Link Demo', 'Link Hosting', 'Link PPT', 'Link PDF']);
        } else {
            return array_merge($heads, ['Link Figma', 'Link PPT', 'Link PDF']);
        }
    }

    public function bindValue(Cell $cell, $value)
    {
        if (is_string($value) && str_starts_with($value, '=')) {
            $cell->setValueExplicit($value, DataType::TYPE_FORMULA);
            return true;
        }

        return parent::bindValue($cell, $value);
    }

    public function map($row): array
    {
        $formatUrl = function($url, $label) {
            if (!$url) return '';
            $valid = filter_var($url, FILTER_VALIDATE_URL) ? $url : url(\Illuminate\Support\Facades\Storage::url($url));
            return '=HYPERLINK("' . $valid . '", "' . $label . '")';
        };

        $baseData = [
            $row->tim->nama ?? '',
            $row->judul_proyek ?? '',
        ];

        if ($this->cabang === 'WEB DEVELOPMENT') {
            return array_merge($baseData, [
                $formatUrl($row->link_github, 'Buka GitHub'),
                $formatUrl($row->link_demo, 'Buka Demo'),
                $formatUrl($row->link_hosting, 'Buka Hosting'),
                $formatUrl($row->ppt, 'Buka PPT'),
                $formatUrl($row->pdf, 'Buka PDF'),
            ]);
        } else {
            return array_merge($baseData, [
                $formatUrl($row->link_figma, 'Buka Figma'),
                $formatUrl($row->ppt, 'Buka PPT'),
                $formatUrl($row->pdf, 'Buka PDF'),
            ]);
        }
    }
}
