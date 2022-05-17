@extends('layouts.app')

@section('title', 'Bandeja de recepción')

@section('content')


@if ($errors->any())
    <div class="alert alert-warning">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">
    <div class="col-4">
        <h3 class="mb-3"><i class="fas fa-lungs-virus"></i> Bandeja de recepción</h3>
    </div>
    <div class="col-3"></div>
    <div class="col-5">
        <a type="button" class="btn btn-sm btn-success mb-3 float-right" href="{{ route('lab.suspect_cases.exportExcelReceptionInbox', 1) }}"><i class="far fa-file-excel"></i> Descargar</a>
        @can('SuspectCase: reception with barcode')
            <a type="button" class="btn btn-sm btn-primary mb-3 mr-3 float-right"
               href="{{ route('lab.suspect_cases.barcode_reception.index') }}"><i class="fas fa-barcode"></i> Por código
                barra</a>
        @endcan

        <a href="{{route('lab.suspect_cases.reports.cases_by_ids_index')}}" type="button" class="btn btn-sm btn-primary mb-3 mr-3 float-right"> Descargar por Ids </a>

    </div>
</div>

<form method="GET" action="{{ route('lab.suspect_cases.reception_inbox') }}">
<!-------------------------->
<div class="row align-items-end">
    <div class="col-12 col-md-3 col-lg-3">
        <table class="table table-sm table-bordered">
            <thead>
                <tr class="text-center">
                    <th>Exámenes por recepcionar</th>
                </tr>
            </thead>
            <tbody>
                <tr class="text-center">
                    <td>{{ $suspectCases->count() }} pendientes</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="col-12 col-md-4 col-lg-3">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="ID examen" name="search" id="for_search" value="{{$idFilter}}">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit" id="button-addon">Buscar</button>
                </div>
            </div>
    </div>
    <div class="col-12 col-md-5 col-lg-5">
        @if(Auth::user()->laboratory)
        <h3>Tu Laboratorio: {{ Auth::user()->laboratory->alias }}</h3>
        @else
        <h3 class="text-danger">Usuario no tiene laboratorio asignado</h3>
        @endif
    </div>

</div>
<!-------------------------->
<div class="form-group row">
    <div class="col-12 col-md-6 col-lg-3">
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Filtrar por Nombre" id="for_filter_name_string" name="filter_name_string" value="{{$nameFilter}}">
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3">
        <select name="establishment_id" id="for_establishment_id" class="form-control">
            <option value=""> Seleccione Establecimiento</option>
            @foreach($establishments as $establishment)
                <option value="{{ $establishment->id }}" {{($establishment->id == $selectedEstablishment) ? 'selected' : '' }}>{{ $establishment->alias }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-12 col-md-4 col-lg-3 text-center">
        <button class="btn btn-primary" id="btn_reception" form="mass_reception_form" type="submit" disabled title="Seleccione las muestras a recepcionar."> <i class="fas fa-inbox"></i> Recepcionar</button>
    </div>
{{--    <div class="col-12 col-md-4 col-lg-3"></div>--}}

    <div class="col-12 col-md-4 col-lg-3">
        <div class="input-group mb-3">
            <select name="laboratory_id_derive" form="derive_form" id="for_laboratory_id_derive" class="form-control selectpicker" required>
                <option value="">Selec. Laboratorio</option>
                <optgroup label="Internos">
                    @foreach($laboratories as $laboratory)
                        @if(!$laboratory->external)
                            <option {{(Auth::user()->laboratory->id == $laboratory->id) ? 'disabled' : '' }} value="{{ $laboratory->id }}">{{ $laboratory->alias }}</option>
                        @endif
                    @endforeach
                </optgroup>

                <optgroup label="Externos">
                    @foreach($laboratories as $laboratory)
                        @if($laboratory->external)
                            <option {{(Auth::user()->laboratory->id == $laboratory->id) ? 'disabled' : '' }} value="{{ $laboratory->id }}">{{ $laboratory->alias }}</option>
                        @endif
                    @endforeach
                </optgroup>
            </select>
            <div class="input-group-append">
                <button type="submit" form="derive_form" id="btn_derive" class="btn btn-primary float-right" disabled title="Seleccione las muestras a derivar."><i class="fas fa-reply-all"></i> Derivar</button>
            </div>
        </div>
    </div>

</div>
</form>



<form method="POST" id="derive_form" action="{{ route('lab.suspect_cases.derive') }}">
    @csrf
    @method('POST')
</form>

<form method="POST" id="mass_reception_form" action="{{ route('lab.suspect_cases.mass_reception') }}">
    @csrf
    @method('POST')
</form>


<!-------------------------->





<table class="table table-sm table-bordered table-responsive" id="tabla_casos">
    <thead>
        <tr>
            <th nowrap>° Monitor</th>
            <th></th>
            <th>Fecha Muestra</th>
            <th>Establecimiento</th>
            <th>Nombre</th>
            <th>Identificador</th>
            <th>Edad</th>
            <th>Sexo</th>
            <th class="alert-danger">PCR SARS-Cov2</th>
            <th>Observación</th>
            <th>Epivigila</th>
            <th>WS Minsal</th>
            <th>Impr.</th>
            <th>Selec.</th>
        </tr>
    </thead>
    <tbody id="tableCases">
        @foreach($suspectCases as $case)
        <tr class="row_{{$case->covid19}} {{ ($case->pcr_sars_cov_2 == 'positive')?'table-danger':''}}">
            <td class="text-center">{{ $case->id }}</td>
            <td>
                @if(Auth::user()->laboratory)
                    @can('SuspectCase: reception')
                        <form method="POST" class="form-inline" action="{{ route('lab.suspect_cases.reception', $case) }}">
                            @csrf
                            @method('POST')
                            <button type="submit" class="btn btn-sm btn-primary" title="Recepcionar"><i class="fas fa-inbox"></i></button>
                        </form>

                        @can('Developer')
                            @if(Auth::user()->laboratory->id === 1 && $case->patient->run != null)


                                <form method="POST" class="form-inline" action="{{ route('lab.suspect_cases.send_to_yani', $case) }}">
                                    @csrf
                                    @method('POST')
                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-outline-{{$case->hasSuccessfulWsHetgRequests() ? 'success' : 'primary'}} mt-1"
                                        title="{{$case->hasSuccessfulWsHetgRequests() ? 'Enviado' : 'Enviar'}} a YANI">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </form

                            @endif
                        @endcan
                    @endcan
                @endif
            </td>
            <td nowrap class="small">{{$case->sample_at->toDateString()}} <br> {{$case->sample_at->toTimeString()}} </td>
            <td>{{ ($case->establishment) ? $case->establishment->alias : '' }}</td>
            <td>
                @if($case->patient)
                <a class="link" href="{{ route('patients.edit', $case->patient) }}">
                    {{ $case->patient->fullName }}
                </a>
                @endif
            </td>
            <td class="text-center" nowrap>
                @if($case->patient)
                {{ $case->patient->identifier }}
                @endif
            </td>
            <td>{{ $case->age }}</td>
            <td>{{ strtoupper($case->gender[0]) }}</td>
            <td>{{ $case->covid19 }}</td>
            <td class="text-muted small">{{ $case->observation }}</td>
            <td>{{ $case->epivigila }}</td>
            <td>{{ $case->minsal_ws_id }}</td>
            <td > <a href= '{{route('lab.suspect_cases.notificationFormSmall',$case) }}' class="btn btn-sm btn-outline-primary" href="#"><i class="fas fa-print"></i></a> </td>
            <td style="text-align:center;"><label for="chk_derivacion">{{($case->external_laboratory) ? 'externo' : '' }}</label><input type="checkbox" {{($case->external_laboratory) ? 'visibility: hidden' : '' }} name="casos_seleccionados[]" id="chk_derivacion" class="select_checkboxs" value={{$case->id}} /> </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $suspectCases->appends(request()->query())->links() }}

@endsection

@section('custom_js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){

});

function exportF(elem) {
    var table = document.getElementById("tabla_casos");
    var html = table.outerHTML;
    var html_no_links = html.replace(/<a[^>]*>|<\/a>/g, "");//remove if u want links in your table
    var url = 'data:application/vnd.ms-excel,' + escape(html_no_links); // Set your html table into url
    elem.setAttribute("href", url);
    elem.setAttribute("download", "casos_sospecha_laboratorio_hetg.xls"); // Choose the file name
    return false;
}
</script>

<script>

    $(document).ready(function () {
        document.getElementById("btn_reception").onclick = function () {
            let selectCheckboxs = document.getElementsByClassName("select_checkboxs");
            for (let item of selectCheckboxs) {
                item.setAttribute('form', 'mass_reception_form');
            }
        }

        document.getElementById("btn_derive").onclick = function () {
            let selectCheckboxs = document.getElementsByClassName("select_checkboxs");
            for (let item of selectCheckboxs) {
                item.setAttribute('form', 'derive_form');
            }
        }

        //Seleccionar maximo 10 muestras. Habilita botones derivar recepcionar massivos
        jQuery(function(){
            var max = 10;
            var checkboxes = $('input[type="checkbox"]');
            checkboxes.change(function(){
                var current = checkboxes.filter(':checked').length;
                checkboxes.filter(':not(:checked)').prop('disabled', current >= max);

                if(current > 0){
                    document.getElementById('btn_reception').disabled = false;
                    document.getElementById('btn_derive').disabled = false;
                }else {
                    document.getElementById('btn_reception').disabled = true;
                    document.getElementById('btn_derive').disabled = true;
                }

            });
        });

    });

</script>


@endsection
