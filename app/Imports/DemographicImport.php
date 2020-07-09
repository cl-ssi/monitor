<?php

namespace App\Imports;

use App\Demographic;
use App\Patient;
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
        $return = [];
        foreach($row as $key => $column) {
            $return[$key] = $column;
        }

        $patient = Patient::where('run', $return['RUN'])->get()->first();

        $demographic = Demographic::where('patient_id', $patient->id)->get();

        if($demographic->count() == 0){
            return new Demographic([
                'street type' => $row['Via Residencia'],
                'address' => $row['Direccion'],
                'number' => $row['Numero'],
                'department' => $row['Depto'],
                'city' => $row['Ciudad o Pueblo'],
                'suburb' => $row['Poblacion o Suburbio'],
                'commune_id' => $row['Comuna'],
                'region_id' => $row['Region'],
                'nationality' => $row['Nacionalidad'],
                'telephone' => $row['Telefono'],
                'email' => $row['Email'],
                'patient_id' => $patient->id,
            ]);
        }
    }

    public function headingRow(): int
    {
        return 1;
    }
}
