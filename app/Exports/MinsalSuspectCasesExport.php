<?php

namespace App\Exports;

use App\SuspectCase;
use App\Patient;
use Maatwebsite\Excel\Concerns\{FromCollection, WithHeadings, WithMapping,
  WithColumnFormatting, ShouldAutoSize};
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Carbon\Carbon;

class MinsalSuspectCasesExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize
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
        $today = date("Y-m-d");

        return SuspectCase::where('laboratory_id', $this->cod_lab)
            ->whereDate('pscr_sars_cov_2_at', '=', $today)
            ->orderBy('id', 'desc')
            ->get();
        // return SuspectCase::find(1301);

    }

    public function headings(): array
    {
        return [
          'Run', 'Nombre', 'Sexo', 'Edad', 'Tipo muestra', 'Resultado', 'Fecha de toma de muestra',
          'Fecha de recepción de la muestra', 'Fecha de resultado', 'Hospital o establecimiento de origen',
          'Región de establecimiento de origen','Laboratorio de referencia',
          'Región de laboratorio donde se procesa la muestra',
          'Teléfono de contacto de paciente', 'Correo de contacto de paciente',
          'Dirección de contacto de paciente',
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

            if($suspectCase->patient->demographic->telephone) {
                $email = $suspectCase->patient->demographic->email;
            }
            else {
                $email  = '';
            }

            if($suspectCase->patient->demographic->address ||
                  $suspectCase->patient->demographic->number ||
                  $suspectCase->patient->demographic->commune) {
                $address = $suspectCase->patient->demographic->address;
                $number = $suspectCase->patient->demographic->number;
                $commune = $suspectCase->patient->demographic->commune;
            }
            else {
                $address  = '';
                $number = '';
                $commune = '';
            }
        }
        else {
          $telephone  = '';
          $email  = '';
          $address  = '';
          $number = '';
          $commune = '';
        }

        return [
            $suspectCase->patient->run,
            strtoupper($suspectCase->patient->fullName),
            strtoupper($suspectCase->patient->GenderEsp),
            $suspectCase->age,
            strtoupper($suspectCase->sample_type),
            strtoupper($suspectCase->Covid19),
            Date::dateTimeToExcel($suspectCase->sample_at),
            Date::dateTimeToExcel($suspectCase->created_at),
            Date::dateTimeToExcel($suspectCase->pscr_sars_cov_2_at),
            strtoupper($suspectCase->origin),
            'TARAPACÁ',
            $this->nombre_lab,
            'TARAPACÁ',
            $telephone,
            $email,
            strtoupper($address.' '.$number.' '.$commune),
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_GENERAL,
            'B' => NumberFormat::FORMAT_GENERAL,
            'C' => NumberFormat::FORMAT_GENERAL,
            'D' => NumberFormat::FORMAT_GENERAL,
            'E' => NumberFormat::FORMAT_GENERAL,
            'F' => NumberFormat::FORMAT_GENERAL,
            'G' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'H' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'I' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'J' => NumberFormat::FORMAT_GENERAL,
            'K' => NumberFormat::FORMAT_GENERAL,
            'L' => NumberFormat::FORMAT_GENERAL,
            'M' => NumberFormat::FORMAT_GENERAL,
            'N' => NumberFormat::FORMAT_GENERAL,
            'O' => NumberFormat::FORMAT_GENERAL,
            'P' => NumberFormat::FORMAT_GENERAL,
        ];
    }
}
