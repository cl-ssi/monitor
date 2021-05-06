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

class UnapSuspectCasesExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize
{
    private $cod_lab;
    private $nombre_lab;

    public function __construct($cod_lab, $nombre_lab) {
          $this->cod_lab = $cod_lab;
          $this->nombre_lab = $nombre_lab;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return SuspectCase::select('id', 'sample_at', 'origin', 'patient_id', 'patient_id',
            'age', 'gender', 'result_ifd', 'pcr_sars_cov_2', 'epidemiological_week',
            'epivigila', 'paho_flu', 'status', 'observation')
            ->with('patient')
            ->where('laboratory_id', 2)
            ->orderBy('id', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
          '#', 'fecha_muestra', 'origen','nombre', 'run', 'edad', 'sexo', 'resultado_ifd',
          'pcr_sars_cov2', 'sem', 'epivigila', 'paho_flu', 'observación'
        ];
    }

    /**
    * @var SuspectCasesExport $suspectCase
    */
    public function map($suspectCase): array
    {
        if($suspectCase->patient->demographic) {
          if($suspectCase->patient->demographic->telephone) {
            $telephone = $suspectCase->patient->demographic->telephone;
          }
          else {
            $telephone  = '';
          }
        }

        return [
            $suspectCase->id,
            Date::dateTimeToExcel($suspectCase->sample_at),
            $suspectCase->origin,
            $suspectCase->patient->fullName,
            $suspectCase->patient->Identifier,
            $suspectCase->age,
            strtoupper($suspectCase->gender[0]),
            $suspectCase->result_ifd,
            $suspectCase->Covid19,
            $suspectCase->epidemiological_week,
            $suspectCase->epivigila,
            $suspectCase->paho_flu,
            $suspectCase->observation,
            $telephone,
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
        ];
    }

}
