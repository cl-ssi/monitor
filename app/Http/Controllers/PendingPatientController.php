<?php

namespace App\Http\Controllers;

use App\Commune;
use App\PendingPatient;
use App\Region;
use App\Specialty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PendingPatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $selectedStatus = $request->get('status');
        if (!$selectedStatus) {
            $selectedStatus = 'not_contacted';
        }

        $pendingPatients = $this->getPendingPatients($selectedStatus);
        return view('pending_patient.index', compact('pendingPatients', 'selectedStatus'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $regions = Region::orderBy('id', 'ASC')->get();
        $communes = Commune::orderBy('id', 'ASC')->get();
        $specialties = Specialty::all();
        return view('pending_patient.create', compact('regions', 'communes', 'specialties'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $patientPendingList = new PendingPatient($request->All());
        $patientPendingList->save();
        session()->flash('success', "Se ha ingresado correctamente el paciente");
        return redirect()->back();
//        return view('pending_patient.create', compact('regions', 'communes'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(PendingPatient $pendingPatient)
    {
        $regions = Region::orderBy('id', 'ASC')->get();
        $communes = Commune::orderBy('id', 'ASC')->get();
        $specialties = Specialty::all();
        return view('pending_patient.edit', compact('pendingPatient', 'regions', 'communes', 'specialties'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PendingPatient $pendingPatient)
    {
        $pendingPatient->fill($request->all());
        $pendingPatient->save();
        session()->flash('success', 'Se actualizó correctamente la información del paciente');
        return redirect()->route('pending_patient.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PendingPatient $pendingPatient)
    {
        $pendingPatient->delete();
        session()->flash('success', 'Paciente eliminado exitosamente');
        return redirect()->route('pending_patient.index');
    }

    public function exportExcelByStatus($selectedStatus)
    {
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=patients_$selectedStatus.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $filas = null;
        $filas = $this->getPendingPatients($selectedStatus);

        $columnas = array(
            '#',
            'run',
            'run provisorio',
            'nro. ficha',
            'name',
            'dirección',
            'región',
            'comuna',
            'email',
            'telefono',
            'motivo',
            'citacion con',
            'citacion especialidad',
            'fecha citacion',
            'lugar a presentarse',
        );

        $callback = function () use ($filas, $columnas) {
            $file = fopen('php://output', 'w');
            fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
            fputcsv($file, $columnas, ';');

            foreach ($filas as $fila) {
                fputcsv($file, array(
                    $fila->id,
                    ($fila->run) ? "$fila->run-$fila->dv" : '',
                    $fila->other_id,
                    $fila->file_number,
                    "$fila->name $fila->fathers_family $fila->mothers_family",
                    $fila->address,
                    $fila->commune->region->name,
                    $fila->commune->name,
                    $fila->commune->email,
                    $fila->telephone,
                    $fila->reason,
                    $fila->appointment_with,
                    ($fila->specialty) ? $fila->specialty->name : '',
                    $fila->appointment_at,
                    $fila->appointment_location,
                ), ';');
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Obtiene pacientes según estado
     * @param $selectedStatus
     * @return mixed
     */
    private function getPendingPatients($selectedStatus)
    {
        $communes_ids = Auth::user()->communes();

        if (Auth::user()->can('NotContacted: show all')) {
            $pendingPatients = PendingPatient::where('status', $selectedStatus)
                ->get();
        } else {
            $pendingPatients = PendingPatient::where('status', $selectedStatus)
                ->whereIn('commune_id', $communes_ids)
                ->get();
        }
        return $pendingPatients;
    }

}
