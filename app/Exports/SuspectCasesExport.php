<?php

namespace App\Exports;

use App\SuspectCase;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SuspectCasesExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize
{
    private $cod_lab;
    private $lab_id;

    public function __construct($cod_lab) {
          $this->cod_lab = $cod_lab;
          if($this->cod_lab == 'hetg'){
              $this->lab_id = 1;
          }
          if($this->cod_lab == 'unap'){
              $this->lab_id = 2;
          }
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        if($this->cod_lab == 'all'){
          return SuspectCase::orderBy('suspect_cases.id', 'desc')
              ->get();
        }
        else{
          return SuspectCase::where('laboratory_id', $this->lab_id)
              ->orderBy('suspect_cases.id', 'desc')
              ->get();
        }


        // return DB::table('suspect_cases')->leftJoin('patients', 'suspect_cases.patient_id', '=', 'patients.id')
        //     ->select('suspect_cases.id', 'suspect_cases.sample_at', 'suspect_cases.origin',
        //              'patients.name', 'patients.fathers_family', 'patients.mothers_family',
        //              'patients.run', 'age', )
        //     ->orderBy('suspect_cases.id', 'desc')
        //     ->get();
    }

    public function headings(): array
    {
        return [
          '#', 'fecha_muestra', 'origen','nombre', 'run', 'edad', 'sexo', 'resultado_ifd',
          'pcr_sars_cov2', 'sem', 'epivigila', 'paho_flu', 'estado', 'observación'
        ];
    }

    /**
    * @var SuspectCasesExport $suspectCase
    */
    public function map($suspectCase): array
    {
        return [
            $suspectCase->id,
            Date::dateTimeToExcel($suspectCase->sample_at),
            $suspectCase->establishment->alias,
            ($suspectCase->patient)?$suspectCase->patient->fullName:'',
            ($suspectCase->patient)?$suspectCase->patient->Identifier:'',
            $suspectCase->age,
            strtoupper($suspectCase->gender[0]),
            $suspectCase->result_ifd,
            $suspectCase->Covid19,
            $suspectCase->epidemiological_week,
            $suspectCase->epivigila,
            $suspectCase->paho_flu,
            $suspectCase->status,
            $suspectCase->observation,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_GENERAL,
            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'C' => NumberFormat::FORMAT_GENERAL,
            'D' => NumberFormat::FORMAT_GENERAL,
            'F' => NumberFormat::FORMAT_GENERAL,
            'G' => NumberFormat::FORMAT_GENERAL,
            'H' => NumberFormat::FORMAT_GENERAL,
            'I' => NumberFormat::FORMAT_GENERAL,
            'J' => NumberFormat::FORMAT_GENERAL,
            'K' => NumberFormat::FORMAT_GENERAL,
            'L' => NumberFormat::FORMAT_GENERAL,
            'M' => NumberFormat::FORMAT_GENERAL,
            'N' => NumberFormat::FORMAT_GENERAL,
        ];
    }
}
