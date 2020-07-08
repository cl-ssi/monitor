@extends('layouts.app')

@section('title', 'Listado de casos')

@section('content')

<h3 class="mb-3"><i class="fas fa-lungs-virus"></i>
    Exámenes ingresados en las comunas del usuario.
</h3>

{{-- <table class="table table-sm table-bordered">
    <thead>
        <tr class="text-center">
            <th>Exámenes enviados a análisis</th>
            <th>Exámenes positivos</th>
            <th>Exámenes negativos</th>
            <th>Exámenes pendientes</th>
            <th>Exámenes rechazados</th>
            <th>Exámenes indeterminados</th>
        </tr>
    </thead>
    <tbody>
        <tr class="text-center">
            <td>{{ $suspectCasesTotal->count() }}</td>
            <th class="text-danger">{{ $suspectCasesTotal->where('pscr_sars_cov_2','positive')->count() }}</th>
            <td>{{ $suspectCasesTotal->where('pscr_sars_cov_2','negative')->count() }}</td>
            <td>{{ $suspectCasesTotal->where('pscr_sars_cov_2','pending')->count() }}</td>
            <td>{{ $suspectCasesTotal->where('pscr_sars_cov_2','rejected')->count() }}</td>
            <td>{{ $suspectCasesTotal->where('pscr_sars_cov_2','undetermined')->count() }}</td>
        </tr>
    </tbody>
</table> --}}


<div class="table-responsive">
{{-- <table class="table table-sm table-bordered" id="tabla_casos"> --}}
<table class="table table-sm table-bordered table-responsive small text-uppercase" id="tabla_casos">
    <thead>
        <tr>
            <th nowrap>Id pac.</th>
            {{-- <th>Origen</th> --}}
            <th>Nombre</th>
            <th>Comuna</th>
            <th>Dirección</th>
            <th>Teléfono</th>
            <th style="width: 50%">Email</th>
            <th>Establecimiento muestra</th>
            <th>Fecha muestra</th>
            <th>Fecha Resultado</th>
            <th>Resultado</th>
            <th>Fecha Notificación</th>
            <th nowrap>Mecanismo de Notificación</th>
            <th></th>
        </tr>
    </thead>
    <tbody id="tableCases">
        @foreach($suspectCases as $case)
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
            <td>{{ $case->establishment->name }}</td>
            <td nowrap class="small">{{ (isset($case->sample_at))? $case->sample_at->format('Y-m-d'):'' }}</td>
            <td nowrap class="small">{{ (isset($case->pscr_sars_cov_2_at))? $case->pscr_sars_cov_2_at->format('Y-m-d'):'' }}</td>
            {{--
            <td class="text-center" nowrap>
                @if($case->patient)
                {{ $case->patient->identifier }}
                @endif
            </td> --}}
            {{-- <td>{{ $case->age }}</td> --}}
            {{-- <td>{{ strtoupper($case->gender[0]) }}</td> --}}
            <td>
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
            <form method="POST" action="{{ route('lab.updateNotification', $case) }}" class="d-inline" enctype="multipart/form-data">
                @csrf
    			@method('POST')
                @if($case->notification_at)
                    <td>{{ $case->notification_at }}</td>
                    <td>{{ $case->notification_mechanism }}</td>
                @else
                    <td><input type="date" class="form-control" name="notification_at"
                        id="for_notification_at" value="{{ ($case->notification_at)?$case->notification_at->format('Y-m-d'):'' }}"></td>
                    <td><select name="notification_mechanism" id="for_notification_mechanism" class="form-control">
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
                    {{-- <td nowrap class="small"> <a href="{{ route('lab.updateNotification', $case) }}"><span><i class="far fa-edit"></i></span> Guardar</a></td> --}}
                    <td nowrap class="small">
                        {{-- <input type="submit"  value="guardar"/> --}}
                        <button type="submit" class="btn btn-outline-secondary btn-sm">
                         {{-- onclick="return confirm('¿Está seguro de subir la información?');"> --}}
							<span style="font-size: 1em; color: #C9C8C8;">
							  <i class="far fa-save"></i>
							</span>
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

{{ $suspectCases->appends(request()->query())->links() }}

@endsection

@section('custom_js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    // $("#inputSearch").on("keyup", function() {
    //     var value = $(this).val().toLowerCase();
    //     $("#tableCases tr").filter(function() {
    //         $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    //     });
    // });
    // //oculta segun checkbox
    // $("#chk_positivos").change(function(){
    //     $(".row_Positivo").toggle();
    // });
    // $("#chk_negativos").change(function(){
    //     var self = this;
    //     $(".row_Negativo").toggle();
    // });
    // $("#chk_pendientes").change(function(){
    //     var self = this;
    //     $(".row_Pendiente").toggle();
    // });
    // $("#chk_rechazados").change(function(){
    //     var self = this;
    //     $(".row_Rechazado").toggle();
    // });
    // $("#chk_indeterminados").change(function(){
    //     var self = this;
    //     $(".row_Indeterminado").toggle();
    // });
});

function exportF(elem) {
    var table = document.getElementById("tabla_casos");
    var html = table.outerHTML;
    var html_no_links = html.replace(/<a[^>]*>|<\/a>/g, "");//remove if u want links in your table
    var url = 'data:application/vnd.ms-excel,' + escape(html_no_links); // Set your html table into url
    elem.setAttribute("href", url);
    elem.setAttribute("download", "casos_sospecha.xls"); // Choose the file name
    return false;
}
</script>
@endsection
