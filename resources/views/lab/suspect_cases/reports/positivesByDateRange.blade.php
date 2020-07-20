@extends('layouts.app')

@section('title', 'Positivos por fecha')

@section('content')

<h3 class="mb-3">Casos positivos por rango de fechas</h3>

<form method="get" class="form-inline mb-3" action="{{ route('lab.suspect_cases.reports.positivesByDateRange') }}">
    <div class="form-group ml-3">
        <label for="for_from">Desde</label>
        <input type="date" class="form-control mx-sm-3" id="for_from" name="from"
               value="{{ Carbon\Carbon::parse($from)->format('Y-m-d') }}">
    </div>

    <div class="form-group">
        <label for="for_to">Hasta</label>
        <input type="date" class="form-control mx-sm-3" id="for_to" name="to"
               value="{{ Carbon\Carbon::parse($to)->format('Y-m-d') }}">
    </div>

    <div class="form-group ">
        <label for="for_commune">Comuna</label>
        <select name="commune" id="for_commune" class="form-control ml-3">
            <option value="">Todas</option>
            @foreach($communes as $commune)
                <option
                    value="{{ $commune->id }}" {{($selectedCommune == $commune->id) ? 'selected' : '' }}  >{{ $commune->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group ml-3">
        <button type="submit" class="btn btn-primary">Buscar</button>
    </div>

    <div class="form-group ml-3">
        <a class="btn btn-outline-success" id="downloadLink" onclick="download_table_as_csv('tabla_positivos_por_fecha');">Descargar en excel</a>
    </div>
</form>


<div class="table-responsive">
    <table class="table table-sm table-bordered table-responsive small" id="tabla_positivos_por_fecha">
        <thead>
            <td>°</td>
            <th nowrap>id</th>
            <th nowrap>Run</th>
            <th nowrap>Nombre</th>
            <th nowrap>Sexo</th>
            <th nowrap>Edad</th>
            <th nowrap>Estado</th>
            <th nowrap>Epivigila</th>
            <th nowrap>Fecha Resultado</th>
            <th nowrap>Comuna</th>
            <th nowrap>Tipo Calle</th>
            <th nowrap>Dirección</th>
            <th nowrap>Número</th>
            <th nowrap>Departamento</th>
            <th nowrap>Teléfono</th>
            <th nowrap>Latitud</th>
            <th nowrap>Longitud</th>
            <th nowrap>Gestante</th>
            <th nowrap>Establecimiento</th>
        </thead>

        <tbody>
        @foreach($suspectCases->reverse() as $key => $suspectCase)
                <tr>
                    <td>{{ ++$key }}</td>
                    <td nowrap>{{ $suspectCase->id }}</td>
                    <td nowrap>{{ $suspectCase->patient->identifier }}</td>
                    <td nowrap class="text-uppercase">{{ $suspectCase->patient->fullName }}</td>
                    <td nowrap>{{ $suspectCase->patient->genderEsp }}</td>
                    <td nowrap>{{ $suspectCase->patient->age }}</td>
                    <td nowrap>{{ $suspectCase->patient->status }}</td>
                    <td nowrap>{{ $suspectCase->epivigila }}</td>
                    <td nowrap>{{ $suspectCase->pcr_sars_cov_2_at }}</td>
                    <td nowrap>{{ ($suspectCase->patient->demographic AND $suspectCase->patient->demographic->commune)? $suspectCase->patient->demographic->commune->name : '' }}</td>
                    <td nowrap>{{ ($suspectCase->patient->demographic)? $suspectCase->patient->demographic->street_type : '' }}</td>
                    <td nowrap class="text-uppercase">{{ ($suspectCase->patient->demographic)? $suspectCase->patient->demographic->address : '' }}</td>
                    <td nowrap>{{ ($suspectCase->patient->demographic)? $suspectCase->patient->demographic->number : '' }}</td>
                    <td nowrap>{{ ($suspectCase->patient->demographic)? $suspectCase->patient->demographic->department : '' }}</td>
                    <td nowrap>{{ ($suspectCase->patient->demographic)? $suspectCase->patient->demographic->telephone : '' }}</td>
                    <td nowrap>{{ ($suspectCase->patient->demographic)? $suspectCase->patient->demographic->latitude : '' }}</td>
                    <td nowrap>{{ ($suspectCase->patient->demographic)? $suspectCase->patient->demographic->longitude : '' }}</td>
                    <td nowrap>{{ ($suspectCase->gestation == 1) ? 'Sí' : '' }}</td>
                    <td nowrap>{{ ($suspectCase->establishment) ? $suspectCase->establishment->alias : '' }}</td>

                </tr>
        @endforeach
        </tbody>

    </table>
</div>
@endsection


@section('custom_js')
<script type="text/javascript">
// Quick and simple export target #table_id into a csv
function download_table_as_csv(table_id) {
    // Select rows from table_id
    var rows = document.querySelectorAll('table#' + table_id + ' tr');
    // Construct csv
    var csv = [];
    for (var i = 0; i < rows.length; i++) {
        var row = [], cols = rows[i].querySelectorAll('td, th');
        for (var j = 0; j < cols.length; j++) {
            // Clean innertext to remove multiple spaces and jumpline (break csv)
            var data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, '').replace(/(\s\s)/gm, ' ')
            // Escape double-quote with double-double-quote (see https://stackoverflow.com/questions/17808511/properly-escape-a-double-quote-in-csv)
            data = data.replace(/"/g, '""');
            // Push escaped string
            row.push('"' + data + '"');
        }
        csv.push(row.join(';'));
    }
    var csv_string = csv.join('\n');
    // Download it
    var filename = 'export_' + table_id + '_' + new Date().toLocaleDateString() + '.csv';
    var link = document.createElement('a');
    link.style.display = 'none';
    link.setAttribute('target', '_blank');
    link.setAttribute('href', 'data:text/csv;charset=utf-8,%EF%BB%BF' + encodeURIComponent(csv_string));
    link.setAttribute('download', filename);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
@endsection
