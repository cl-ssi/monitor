<?php

namespace App\Exports;

use App\SuspectCase;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UnapSuspectCasesExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return SuspectCase::select('id', 'sample_at', 'origin', 'patient_id', 'patient_id',
            'age', 'gender', 'result_ifd', 'pscr_sars_cov_2', 'epidemiological_week',
            'epivigila', 'paho_flu', 'status', 'observation')
            ->with('patient')
            ->where('laboratory_id', 2)
            ->orderBy('id', 'desc')
            ->get();

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
            Carbon::parse($suspectCase->sample_at)->format('d-m-Y'),
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
            $suspectCase->status,
            $suspectCase->observation,
        ];
    }
}
