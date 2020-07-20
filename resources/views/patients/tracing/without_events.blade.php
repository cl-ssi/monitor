@extends('layouts.app')

@section('title', 'Seguimientos sin eventos')

@section('content')
<h3 class="mb-3">Pacientes en cuarentena sin seguimiento (ningún evento)</h3>
<a class="btn btn-outline-success btn-sm mb-3" id="downloadLink" onclick="exportF(this)">Descargar en excel</a>

<table class="table table-sm small" id="tabla_pacientes_sin_seguimiento">
    <thead>
        <tr>
            <th>°</th>
            <th>ID</th>
            <th>Identificador</th>
            <th>Nombre</th>
            <th>Comuna Residencia</th>
            <th>Estab. Toma de muestra</th>
            <th>Inicio Cuarentena</th>
            <th>Termino Cuarentena</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tracingsWithoutEvents->reverse() as $key => $tracing)
        <tr>
            <td>{{ ++$key }}- {{ $tracing->id }}</td>
            @if($tracing->patient)
            <td>

                <a href="{{ route('patients.edit', $tracing->patient) }}">
                    {{ $tracing->patient->id }}
                </a>

            </td>
            <td>{{ $tracing->patient->identifier }}</td>
            <td>{{ $tracing->patient->fullName }}</td>
            <td>{{ $tracing->patient->demographic->commune->name }}</td>
            <td>{{ ($tracing->establishment) ? $tracing->establishment->alias : '' }}</td>
            <td>{{ $tracing->quarantine_start_at->format('Y-m-d') }}</td>
            <td nowrap>{{ $tracing->quarantine_end_at->format('Y-m-d') }} ({{ $tracing->quarantine_start_at->diffInDays(now()) }})</td>
            <td>{{ ($tracing->patient->status == '') ? 'Ambulatorio' : $tracing->patient->status }}</td>
            @endif


        </tr>
        @endforeach
    </tbody>
</table>


@endsection

@section('custom_js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script type="text/javascript">
    function exportF(elem) {
        let date = new Date()
        let day = date.getDate()
        let month = date.getMonth() + 1
        let year = date.getFullYear()
        let hour = date.getHours()
        let minute = date.getMinutes()
        var table = document.getElementById("tabla_pacientes_sin_seguimiento");
        var html = table.outerHTML;
        var html_no_links = html.replace(/<a[^>]*>|<\/a>/g, ""); //remove if u want links in your table
        var url = 'data:application/vnd.ms-excel,' + escape(html_no_links); // Set your html table into url
        elem.setAttribute("href", url);
        elem.setAttribute("download", "Pacientes_en_cuarentena_sin_seguimiento_(ningún evento)_"+day+"_"+month+"_"+year+"_"+hour+"_"+minute+".xls"); // Choose the file name
        return false;
    }
</script>

@endsection
