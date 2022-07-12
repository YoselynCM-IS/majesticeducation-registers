<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Carbon\Carbon;

class DayExport implements WithMultipleSheets
{
    
    use Exportable;

    protected $day;
    protected $status;
    
    public function __construct($day, $status)
    {
        $this->day = $day;
        $this->status = $status;
    }

    public function sheets(): array
    {
        $sheets = [];
        
        foreach ($this->status as $state) {
            $sheets[] = new DayStatusExport($state, $this->day);
        }

        return $sheets;
    }
}
