<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Database\Eloquent\Collection;

class PengumpulanDataSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
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
        if ($this->cabang === 'WEB DEVELOPMENT') {
            return ['Link GitHub', 'Link Demo', 'Link Hosting', 'Link PPT', 'Link PDF'];
        } else {
            return ['Link Figma', 'Link PPT', 'Link PDF'];
        }
    }

    public function map($row): array
    {
        $formatUrl = function($url, $label) {
            if (!$url) return '';
            $valid = filter_var($url, FILTER_VALIDATE_URL) ? $url : url(\Illuminate\Support\Facades\Storage::url($url));
            return '=HYPERLINK("' . $valid . '", "' . $label . '")';
        };

        if ($this->cabang === 'WEB DEVELOPMENT') {
            return [
                $formatUrl($row->link_github, 'Buka GitHub'),
                $formatUrl($row->link_demo, 'Buka Demo'),
                $formatUrl($row->link_hosting, 'Buka Hosting'),
                $formatUrl($row->ppt, 'Buka PPT'),
                $formatUrl($row->pdf, 'Buka PDF'),
            ];
        } else {
            return [
                $formatUrl($row->link_figma, 'Buka Figma'),
                $formatUrl($row->ppt, 'Buka PPT'),
                $formatUrl($row->pdf, 'Buka PDF'),
            ];
        }
    }
}
