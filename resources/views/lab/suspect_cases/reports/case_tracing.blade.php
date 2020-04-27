@extends('layouts.app')

@section('title', 'Reporte COVID-19')

@section('content')

<h3 class="mb-3">Seguimiento de Casos Positivos</h3>

</main><main class="py-4">

<table class="table table-sm table-bordered table-responsive small" >
    <thead>
        <tr class="text-center">
            <th colspan="6"></th>
            <th colspan="1"></th>
            <th colspan="2"></th>
            <th colspan="2">Covid</th>
            <th colspan="2">IFD</th>
            <th colspan="2">Laboratorio</th>
            <th colspan="2" nowrap>Entrega de resultados</th>
            <th colspan="5"></th>
            @for ($i=0; $i < ($cont_casos-1); $i++)
                <th colspan="3" nowrap>Covid salida {{$i+1}}</th>
            @endfor
            <th colspan="2"></th>
        </tr>
        <tr class="text-center">
            <th>°</th>
            <th>Paciente</th>
            <th>Run</th>
            <th>Edad</th>
            <th>Sexo</th>
            <th>Comuna</th>

            <th>Estado</th>

            <th nowrap>Fecha Muestra</th>
            <th>Origen</th>

            <th>Fecha Covid</th>
            <th>Covid</th>

            <th nowrap>Fecha IFD</th>
            <th>IFD</th>

            <th>Fecha envío</th>
            <th>Laboratorio</th>

            <th>Fecha</th>
            <th>Mecanismo</th>

            <th>S.Epidemiológica</th>
            <th>Epivigila</th>
            <th nowrap>PAHO FLU</th>
            <th>Gestante</th>
            <th nowrap>Contacto directo</th>

            @for ($i=0; $i < ($cont_casos-1); $i++)
                <th nowrap>Fecha Muestra</th>
                <th nowrap>Fecha Resultado</th>
                <th>Covid</th>
            @endfor

            <th nowrap>Fecha Alta</th>
            <th>Observación</th>
        </tr>
    </thead>
    <tbody>
        @php $ct=1 @endphp
        @foreach($patients as $patient)
            @php
                $suspectCase = $patient->suspectCases->first();
            @endphp
            <tr>
                <td>{{ $ct++ }}</td>
                <td nowrap>
                    <a href="{{ route('patients.edit', $patient) }}">{{ $patient->fullName }}</a>
                </td>
                <td nowrap>{{ $patient->identifier }}</td>
                <td nowrap>{{ $patient->suspectCases->last()->age }}</td>
                <td nowrap>{{ strtoupper($patient->genderEsp) }}</td>
                <td nowrap>{{ ($patient->demographic) ? $patient->demographic->commune : '' }}</td>
                <td nowrap>{{ $suspectCase->status }}</td>

                <td nowrap>{{ $suspectCase->sample_at->format('Y-m-d') }}</td>
                <td nowrap>{{ $suspectCase->origin }}</td>

                <td nowrap>{{ ($suspectCase->pscr_sars_cov_2_at) ? $suspectCase->pscr_sars_cov_2_at->format('Y-m-d') : '' }}</td>
                <td nowrap>
                    <a href="{{ route('lab.suspect_cases.edit', $suspectCase) }}">{{ $suspectCase->covid19 }}</a>
                </td>

                <td nowrap>{{ ($suspectCase->result_ifd_at) ? $suspectCase->result_ifd_at->format('Y-m-d') : '' }}</td>
                <td nowrap>{{ $suspectCase->result_ifd }}</td>

                <td nowrap>{{ ($suspectCase->sent_isp_at) ? $suspectCase->sent_isp_at->format('Y-m-d') : '' }}</td>
                <td nowrap>{{ $suspectCase->procesingLab }}</td>

                <td nowrap>{{ ($suspectCase->notification_at) ? $suspectCase->notification_at->format('Y-m-d') : '' }}</td>
                <td nowrap>{{ $suspectCase->notification_mechanism }}</td>

                <td nowrap>{{ $suspectCase->epidemiological_week }}</td>
                <td nowrap>{{ $suspectCase->epivigila }}</td>
                <td nowrap>{{ $suspectCase->paho_flu }}</td>
                <td nowrap>{{ ($suspectCase->gestation == 1) ? 'Sí' : '' }}</td>
                <td nowrap>{{ ($suspectCase->close_contact == 1) ? 'Sí':'' }}</td>

                @if($patient->suspectCases->count() == 1)
                    <td></td>
                    <td></td>
                    <td></td>
                @else
                    @foreach ($patient->suspectCases as $key => $suspectCase2)
                        @if($key <> 0) {{-- no se imprime el primer item --}}
                            <td nowrap>{{ $suspectCase2->sample_at->format('Y-m-d') }}</td>
                            <td nowrap>
                                {{ ($suspectCase2->pscr_sars_cov_2_at)? $suspectCase2->pscr_sars_cov_2_at->format('Y-m-d') : '' }}
                            </td>
                            <td nowrap>
                                <a href="{{ route('lab.suspect_cases.edit', $suspectCase2) }}">{{ $suspectCase2->covid19 }}</a>
                                {{ ($suspectCase2->discharge_test)? '' : 'x' }}
                            </td>
                        @endif
                    @endforeach
                @endif

                <td nowrap>{{ $suspectCase->discharged_at }}</td>
                <td nowrap>{{ $suspectCase->observation }}</td>

            </tr>
        @endforeach
    </tbody>
</table>

@endsection

@section('custom_js_head')
<script type="text/javascript">

</script>
@endsection
