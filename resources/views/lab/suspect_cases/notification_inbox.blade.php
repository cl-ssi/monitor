@extends('layouts.app')

@section('title', 'Notificacion')

@section('content')

<div class="row mb-3">
    <div class="col-8">
        <h3><i class="fas fa-lungs-virus"></i>
            Examenes por notificar, excluye positivos y pendientes
        </h3>
    </div>
    <div class="col-4">
        <select class="form-control form-sm" id="ddlCountry">
            <option value="all">Todos los Establecimiento</option>
            @foreach($suspectCases->reverse()->unique('establishment') as $case)
            <option value="{{ $case->establishment->alias }}">{{ $case->establishment->alias }}</option>
            @endforeach
        </select>
    </div>
</div>


<div class="table-responsive">

<table class="table table-sm table-bordered table-responsive small text-uppercase" id="tabla_casos">
    <thead>
        <tr>
            <th nowrap>Id pac.</th>
            <th>Nombre</th>
            <th>Comuna</th>
            <th>Dirección</th>
            <th>Teléfono</th>
            <th>Email</th>
            <th>Establecimiento muestra</th>
            <th>F. muestra</th>
            <th>F. Resultado</th>
            <th>Resultado</th>
            <th>Fecha Notificación</th>
            <th nowrap>Mecanismo de Notificación</th>
            <th></th>
        </tr>
    </thead>
    <tbody id="tableCases">
        @foreach($suspectCases->reverse() as $case)
        <tr class="row_{{$case->covid19}} {{ ($case->pscr_sars_cov_2 == 'positive')?'table-danger':''}}">
            <td class="text-center">{{ $case->patient->id }}</td>
            <td>
                @if($case->patient)
                <a class="link" href="{{ route('patients.edit', $case->patient) }}">
                    {{ $case->patient->fullName }}
                    @if($case->gestation == "1") <img align="center" src="{{ asset('images/pregnant.png') }}" width="24"> @endif
                    @if($case->close_contact == "1") <img align="center" src="{{ asset('images/contact.png') }}" width="24"> @endif
                 </a>
                 @endif
            </td>
            <td>{{ $case->patient->demographic->commune->name }}</td>
            <td>{{ $case->patient->demographic->getFullAddressAttribute() }}</td>
            <td>{{ $case->patient->demographic->getFullTelephonesAttribute() }}</td>
            <td class="small">{{ $case->patient->demographic->email }}</td>
            <td>{{ $case->establishment->alias }}</td>
            <td nowrap class="small">{{ (isset($case->sample_at))? $case->sample_at->format('Y-m-d'):'' }}</td>
            <td nowrap class="small">{{ (isset($case->pscr_sars_cov_2_at))? $case->pscr_sars_cov_2_at->format('Y-m-d'):'' }}</td>

            <td nowrap>
                @if($case->laboratory)
                    {{ $case->covid19 }}
                    @if($case->files->first())
                    <a href="{{ route('lab.suspect_cases.download', $case->files->first()->id) }}"
                        target="_blank"><i class="fas fa-paperclip"></i>&nbsp
                    </a>
                    @endif

                    @if ($case->laboratory->pdf_generate == 1 && $case->pscr_sars_cov_2 <> 'pending')
                    <a href="{{ route('lab.print', $case) }}"
                        target="_blank"><i class="fas fa-paperclip"></i>&nbsp
                    </a>
                    @endif
                @else
                    No recepcionado
                @endif
            </td>
            <form method="POST" action="{{ route('lab.updateNotification', $case) }}" class="d-inline">
                @csrf
    			@method('POST')
                @if($case->notification_at)
                    <td>{{ ($case->notification_at)? $case->notification_at->format('Y-m-d') : '' }}</td>
                    <td>{{ $case->notification_mechanism }}</td>
                @else
                    <td><input type="date" class="form-control form-control-sm" name="notification_at"
                        id="for_notification_at" value="{{ ($case->notification_at)?$case->notification_at->format('Y-m-d'):'' }}"></td>
                    <td><select name="notification_mechanism" id="for_notification_mechanism" class="form-control form-control-sm">
                        <option></option>
                        <option value="Pendiente"
                            {{ ($case->notification_mechanism == 'Pendiente')?'selected':'' }}>
                            Pendiente</option>
                        <option value="Llamada telefónica"
                            {{ ($case->notification_mechanism == 'Llamada telefónica')?'selected':'' }}>
                            Llamada telefónica</option>
                        <option value="Correo electrónico"
                            {{ ($case->notification_mechanism == 'Correo electrónico')?'selected':'' }}>
                            Correo electrónico</option>
                        <option value="Visita domiciliaria"
                            {{ ($case->notification_mechanism == 'Visita domiciliaria')?'selected':'' }}>
                            Visita domiciliaria</option>
                        <option value="Centro de salud"
                            {{ ($case->notification_mechanism == 'Centro de salud')?'selected':'' }}>
                            Centro de salud</option>
                    </select></td>
                @endif

                @if(!$case->notification_at)

                    <td nowrap class="small">
                        <button type="submit" class="btn btn-outline-primary btn-sm">
							  <i class="far fa-save"></i>
						</button>
                    </td>
                @else
                    <td></td>
                @endif
            </form>
        </tr>
        @endforeach
    </tbody>
</table>
</div>


@endsection

@section('custom_js')
<script type="text/javascript">
$(document).ready(function(){
    //$("main").removeClass("container");
    $("#ddlCountry").on("change", function () {
            var country = $('#ddlCountry').find("option:selected").val();
            SearchData(country)
        });

});
function SearchData(country) {
        if (country.toUpperCase() == 'ALL') {
            $('#tabla_casos tbody tr').show();
        } else {
            $('#tabla_casos tbody tr:has(td)').each(function () {
                var rowCountry = $.trim($(this).find('td:eq(6)').text());
                if (country.toUpperCase() != 'ALL' ) {
                    if (rowCountry.toUpperCase() == country.toUpperCase()) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                } else if ($(this).find('td:eq(6)').text() != '' || $(this).find('td:eq(6)').text() != '') {
                    if (country != 'all') {
                        if (rowCountry.toUpperCase() == country.toUpperCase()) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    }
                }

            });
        }
    }
</script>
@endsection
