<?php

namespace App\Imports;

use App\Patient;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

use Carbon\Carbon;

HeadingRowFormatter::default('none');

class PatientImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $return = [];
        foreach($row as $key => $column) {
            $return[$key] = $column;
        }

        $patient = Patient::where('run', $return['RUN'])->get();

        if($patient->count() == 0 || $return['Otro Identificador'] != NULL){
            return new Patient([
                'run' => $row['RUN'],
                'dv' => $row['DV'],
                'other_identification' => $row['Otro Identificador'],
                'name' => $row['Nombres'],
                'fathers_family' => $row['Apellido Paterno'],
                'mothers_family' => $row['Apellido Materno'],
                'gender' => $row['Sexo'],
                'birthday' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['Fecha Nacimiento'])->format('Y-m-d'),
                'status' => $row['Estado'],
            ]);
        }
    }

    public function headingRow(): int
    {
        return 1;
    }
}
