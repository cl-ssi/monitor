<?php

namespace App\Exports;

use App\SuspectCase;
use Maatwebsite\Excel\Concerns\{FromCollection, WithHeadings, WithMapping,
  WithColumnFormatting, ShouldAutoSize};
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Carbon\Carbon;

class SeremiSuspectCasesExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize
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
        return SuspectCase::where('laboratory_id', $this->cod_lab)
        ->orderBy('id', 'desc')
        ->get();
    }

    public function headings(): array
    {
        return [
          'ID', 'Nombre completo', 'RUN o Ident.', 'Procedencia', 'Edad', 'Embarazo (EG)',
          'Tipo de muestra', 'Semana epidemiológica', 'Fecha de toma de muestra',
          'Fecha de envío de muestra', 'Institución que procesa la muestra',
          'Fecha resultado', 'Resultado',
        ];
    }

    /**
    * @var SuspectCasesExport $suspectCase
    */
    public function map($suspectCase): array
    {
        return [
            $suspectCase->id,
            $suspectCase->patient->fullName,
            $suspectCase->patient->Identifier,
            strtoupper($suspectCase->origin),
            $suspectCase->age,
            $suspectCase->gestation,
            $suspectCase->sample_type,
            $suspectCase->epidemiological_week,
            Date::dateTimeToExcel($suspectCase->sample_at),
            $suspectCase->SentExternalAt,
            $suspectCase->ProcesingLab,
            $suspectCase->pscr_sars_cov_2_at,
            $suspectCase->Covid19,
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
            'G' => NumberFormat::FORMAT_GENERAL,
            'H' => NumberFormat::FORMAT_GENERAL,
            'I' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'J' => NumberFormat::FORMAT_TEXT,
            'K' => NumberFormat::FORMAT_GENERAL,
            'L' => NumberFormat::FORMAT_GENERAL,
            'M' => NumberFormat::FORMAT_GENERAL,
        ];
    }
}
