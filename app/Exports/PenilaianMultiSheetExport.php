<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Database\Eloquent\Collection;

class PenilaianMultiSheetExport implements WithMultipleSheets
{
    use Exportable;

    protected $records;
    protected $cabang;

    public function __construct(Collection $records, string $cabang)
    {
        $this->records = $records;
        $this->cabang = $cabang;
    }

    public function sheets(): array
    {
        return [
            new PengumpulanDataSheet($this->records, $this->cabang),
            new LembarPenilaianSheet($this->records, $this->cabang),
        ];
    }
}
