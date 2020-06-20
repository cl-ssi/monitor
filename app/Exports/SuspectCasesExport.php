<?php

namespace App\Exports;

use App\SuspectCase;
use App\Establishment;
use Illuminate\Support\Facades\Auth;
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

    public function __construct($cod_lab) {
          $this->cod_lab = $cod_lab;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        if($this->cod_lab == 'own'){
          return SuspectCase::where(function($q){
              $q->whereIn('establishment_id', Auth::user()->establishments->pluck('id'))
                  ->orWhere('user_id', Auth::user()->id);
          })->get();
        }
        elseif ($this->cod_lab == 'all'){
            return SuspectCase::orderBy('suspect_cases.id', 'desc')
                ->get();
        }
        else{
          return SuspectCase::where('laboratory_id', $this->cod_lab)
              ->orderBy('suspect_cases.id', 'desc')
              ->get();
        }
    }

    public function headings(): array
    {
        return [
          '#', 'fecha_muestra', 'origen','nombre','run', 'edad', 'sexo', 'resultado_ifd',
          'pcr_sars_cov2', 'sem', 'epivigila', 'paho_flu', 'estado', 'observación', 'teléfono',
          'dirección', 'comuna'
        ];
    }

    /**
    * @var SuspectCasesExport $suspectCase
    */
    public function map($suspectCase): array
    {
        // dd($suspectCase);
        return [
            $suspectCase->id,
            Date::dateTimeToExcel($suspectCase->sample_at),
            ($suspectCase->establishment)?$suspectCase->establishment->alias.' - '.$suspectCase->origin: '',
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
            ($suspectCase->patient && $suspectCase->patient->demographic)?$suspectCase->patient->demographic->telephone:'',
            ($suspectCase->patient && $suspectCase->patient->demographic)?$suspectCase->patient->demographic->fullAddress:'',
            ($suspectCase->patient && $suspectCase->patient->demographic && $suspectCase->patient->demographic->commune)?$suspectCase->patient->demographic->commune->name:'',
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
            'O' => NumberFormat::FORMAT_GENERAL,
            'P' => NumberFormat::FORMAT_GENERAL,
            'Q' => NumberFormat::FORMAT_GENERAL,
        ];
    }
}
