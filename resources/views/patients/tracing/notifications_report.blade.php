@extends('layouts.app')

@section('title', 'Notificados')

@section('content')
<h3 class="mb-3">Notificados en mis establecimientos</h3>
<a class="btn btn-outline-success btn-sm mb-3" id="downloadLink" onclick="exportF(this)">Descargar en excel</a>

<table class="table table-sm small" id="pacientes_notificados">
    <thead>
        <tr>
            <th>°</th>
            <th>ID Pcte.</th>
            <th>Fecha notificación</th>
            <th>Identificador</th>
            <th>Nombre</th>
            <th>Comuna Residencia</th>
            <th>Estab. Seguimiento</th>
            <th>Inicio Cuarentena</th>
            <th>Termino Cuarentena</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tracings->reverse() as $key => $tracing)
        <tr>
            <td>{{ ++$key }}</td>
            <td>
                <a href="{{ route('patients.edit', $tracing->patient) }}">
                    {{ $tracing->patient->id }}
                </a>
            </td>
            <td>{{ $tracing->notification_at->format('Y-m-d') }}</td>
            <td>{{ $tracing->patient->identifier }}</td>
            <td>{{ $tracing->patient->fullName }}</td>
            <td>{{ $tracing->patient->demographic->commune->name }}</td>
            <td>{{ ($tracing->establishment) ? $tracing->establishment->alias : '' }}</td>
            <td>{{ $tracing->quarantine_start_at->format('Y-m-d') }}</td>
            <td nowrap>{{ $tracing->quarantine_end_at->format('Y-m-d') }} ({{ $tracing->quarantine_start_at->diffInDays(now()) }})</td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $tracings->links() }}

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
        var table = document.getElementById("pacientes_notificados");
        var html = table.outerHTML;
        var html_no_links = html.replace(/<a[^>]*>|<\/a>/g, ""); //remove if u want links in your table
        var url = 'data:application/vnd.ms-excel,' + escape(html_no_links); // Set your html table into url
        elem.setAttribute("href", url);
        elem.setAttribute("download", "pacientes_notificados_"+day+"_"+month+"_"+year+"_"+hour+"_"+minute+".xls"); // Choose the file name
        return false;
    }
</script>

@endsection
