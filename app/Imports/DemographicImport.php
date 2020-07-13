<?php

namespace App\Imports;

use App\Demographic;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

use Carbon\Carbon;

HeadingRowFormatter::default('none');

class DemographicImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

    }

    public function headingRow(): int
    {
        return 1;
    }

}
