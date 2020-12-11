@extends('layouts.app')

@section('title', 'Reporte por ids de casos')

@section('content')


    <div class="row">
        <div class="col">

            <h5 class="mb-3">Reporte por ids de casos</h5>

            <form method="POST" id="exportExcelByCasesId"
                  action="{{ route('lab.suspect_cases.reports.export_excel_by_cases_ids') }}">
                @csrf
                @method('POST')

                <div class="form-group row">
                    <div class="col-12 col-sm-4">
                        <label for="for_id_lab_report">Destino</label>
                        <select class="form-control" name="id_lab_report" id="for_id_lab_report">
                                <option value="id_lab_calama">Hospital Calama</option>
                                <option value="id_lab_con">CENTRO ONCOLOGICO DEL NORTE</option>
                                <option value="id_lab_ucn">UCN Antofagasta</option>
                                <option value="id_lab_hosp_antof">Hospital Antofagasta</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-12 col-sm-12">
                        <label for="for_id_lab_report">Ids de muestras</label>
                        <textarea class="form-control" cols="30" rows="10" id="forSuspectCasesId" name="suspectCasesId" placeholder="Ids de muestras separadas por un espacio..."
                                  required></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-12 col-sm-12">
                        <button type="submit" class="btn btn-primary">Descargar</button>
                    </div>
                </div>

            </form>


        </div>

    </div>

    {{-- <br /><hr /> --}}

@endsection

@section('custom_js_head')
    <script type="text/javascript">

    </script>
@endsection
