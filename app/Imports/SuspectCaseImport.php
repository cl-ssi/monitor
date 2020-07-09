<?php

namespace App\Imports;

use App\SuspectCase;
use App\Patient;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class SuspectCaseImport implements ToModel, WithHeadingRow
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
          return new SuspectCase([
              'street type' => $row['Laboratorio'],
              'address' => $row['Tipo Muestra'],
              'number' => $row['Fecha Muestra'],
              'department' => $row['Fecha Recepcion'],
              'department' => $row['Fecha Resultado'],
              'city' => $row['Resultado'],
              'suburb' => $row['Establecimiento Muestra'],
              'commune_id' => $row['Detalle Origen'],
              'region_id' => $row['Run Medico'],
              'nationality' => $row['Sintomas'],
              'telephone' => $row['Fecha Inicio Sintomas'],
              'email' => $row['Gestante'],
              'email' => $row['Semanas Gestacion'],
              'email' => $row['Indice'],
              'email' => $row['Funcionario Salud'],
              'email' => $row['Observacion'],
              'email' => $row['Epivigila'],
              // 'email' => $row['Usuario Sistema'],
              'patient_id' => $patient->id,
          ]);
      }
        // return new SuspectCase([
        //     //
        // ]);
    }
}
