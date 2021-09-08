@extends('layouts.app')

@section('title', 'Listado de Pacientes Candidatos a Secuenciación')

@section('content')
<ul class="nav nav-tabs mb-3 d-print-none">

    <li class="nav-item">
        <a class="nav-link" href="{{ route('sequencing.index') }}">
            <i class="fas fa-ban"></i> Sin Datos
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('sequencing.indexsend') }}">
            <i class="fas fa-plane-departure"></i> Enviados a Seq.
        </a>
    </li>
</ul>
@if(isset($send))
<h3 class="mb-3"><i class="fas fa-plane-departure"></i> Listado de Pacientes Candidato Enviado a Secuenciación</h3>
<a type="button" class="btn btn-success mb-3" id="downloadLink" onclick="exportF(this)">Descargar Excel
    <i class="far fa-file-excel"></i></a>
@else
<h3 class="mb-3"><i class="fas fa-user-injured"></i> Listado de Pacientes Candidato a Secuenciación Sin Datos</h3>
@endif






<div class="table-responsive">
    <table class="table table-sm table-bordered table-striped small" id="tabla_secuenciacion">
        <thead>
            <tr class="text-center">
                <th>Añadir/Editar Datos Secuenciación</th>
                @if(!isset($send))
                <th>Caso Sospecha</th>
                <th>CT</th>
                @endif
                <th>Run o (ID)</th>
                <th>Nombre</th>
                <th>Sexo</th>
                <th>Edad</th>
                <th>Tipo muestra</th>
                <th>Resultado</th>
                <th>Fecha de toma de muestra</th>
                <th>Fecha de resultado</th>
                <th>Hospital o Establecimiento de origen</th>
                <th>Laboratorio de referencia</th>
                @if(isset($send))
                <th>Criterio</th>
                <th>Fecha envío secuenciación</th>
                <th>Fecha inicio de sintomas</th>
                <th>Vacunación</th>
                <th>Estado Hospitalización</th>
                <th>Diagnostico</th>
                <th>UPC</th>
                @else
                <!-- <th>Enviar a secuenciación</th> -->
                <th>Eliminar</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($sequencingcriterias as $sequencingcriteria)
            <tr>
                <td>

                    <a href="{{ route('sequencing.edit', $sequencingcriteria) }}">
                        Agregar
                    </a>
                </td>
                @if(!isset($send))
                <td>{{$sequencingcriteria->suspect_case_id ?? ''}}</td>
                <td>{{$sequencingcriteria->suspectCase->ct ?? ''}}</td>
                @endif
                <td>{{$sequencingcriteria->suspectCase->patient->identifier ?? ''}}</td>
                <td>{{$sequencingcriteria->suspectCase->patient->fullname ?? ''}}</td>
                <td>{{$sequencingcriteria->suspectCase->patient->genderesp ?? ''}}</td>
                <td>{{$sequencingcriteria->suspectCase->patient->age ?? ''}}</td>
                <td>{{$sequencingcriteria->suspectCase->sample_type ?? ''}}</td>
                <td>{{$sequencingcriteria->suspectCase->covid19 ?? ''}}</td>
                <td>{{$sequencingcriteria->suspectCase->sample_at ? $sequencingcriteria->suspectCase->sample_at->format('d-m-Y H:i:s') : ''}}</td>
                <td>{{$sequencingcriteria->suspectCase->pcr_sars_cov_2_at->format('d-m-Y H:i:s') ?? ''}}</td>
                <td>{{$sequencingcriteria->suspectCase->establishment->name ?? ''}}</td>
                <td>{{$sequencingcriteria->suspectCase->laboratory->name ?? ''}}</td>
                @if(!isset($send))
                <!-- <td>
                    <form method="POST" class="form-horizontal" action="{{ route('sequencing.send', $sequencingcriteria) }}">
                        @csrf
                        @method('PUT')
                        <button type="submit">
                            <i class="fas fa-share-square"></i> Enviar
                        </button>
                    </form>
                </td> -->
                @endif
                @if(isset($send))
                <td>{{$sequencingcriteria->critery ?? '' }}</td>
                <td>
                    {{$sequencingcriteria->send_at?? ''}}
                </td>
                <td>{{$sequencingcriteria->symptoms_at ?? '' }} </td>
                <td>{{$sequencingcriteria->vaccination ?? '' }} (<small>{{$sequencingcriteria->last_dose_at ?? '' }}</small>)</td>
                <td>{{$sequencingcriteria->hospitalization_status ?? '' }}</td>
                <td>{{$sequencingcriteria->diagnosis ?? '' }}</td>
                <td>{{$sequencingcriteria->upcesp ?? '' }}</td>
                @endif
                <td>
                <form method="POST" id="delete_form" action="{{route('sequencing.destroy', $sequencingcriteria)}}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('¿Está seguro que desea eliminar el criterio de secuenciación?')">
                        <i class="fas fa-trash-alt"></i>Eliminar Criterio
                    </button>
                </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>

@endsection

@section('custom_js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script type="text/javascript">
    function exportF(elem) {
        var table = document.getElementById("tabla_secuenciacion");
        var html = table.outerHTML;
        var html_no_links = html.replace(/<a[^>]*>|<\/a>/g, ""); //remove if u want links in your table
        var url = 'data:application/vnd.ms-excel,' + escape(html_no_links); // Set your html table into url
        elem.setAttribute("href", url);
        elem.setAttribute("download", "tabla_secuenciacion.xls"); // Choose the file name
        return false;
    }
</script>

@endsection