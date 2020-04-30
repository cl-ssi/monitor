@extends('layouts.app')

@section('title', 'Reporte COVID-19')

@section('content')

<h3 class="">Seguimiento de Casos Positivos</h3>

</main><main class="">

<table class="table table-sm table-bordered table-responsive small" >
    <thead>
        <tr class="text-center">
            <th colspan="6"></th>
            <th colspan="1"></th>

            @for ($i=1; $i <= $max_cases; $i++)
                <th colspan="3" nowrap>Covid {{ $i }}</th>
            @endfor

            <th colspan="2">IFD</th>

            <th colspan="6"></th>

            <th colspan="2">Laboratorio</th>

            <th colspan="2" nowrap>Entrega de resultados</th>

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

            @for ($i=1; $i <= $max_cases; $i++)
                <th nowrap class="table-active">Fecha Muestra</th>
                <th nowrap class="table-active">Fecha Resultado</th>
                <th class="active">Covid</th>
            @endfor

            <th nowrap>Fecha IFD</th>
            <th>IFD</th>

            <th>Origen</th>

            <th>S.Epidemiológica</th>
            <th>Epivigila</th>
            <th nowrap>PAHO FLU</th>
            <th>Gestante</th>
            <th nowrap>Contacto directo</th>

            <th>Fecha envío</th>
            <th>Laboratorio</th>

            <th>Fecha</th>
            <th>Mecanismo</th>

            <th nowrap>Fecha Alta</th>
            <th>Observación</th>
        </tr>
    </thead>
    <tbody>
        @php $ct = $patients->count(); @endphp
        @foreach($patients->reverse() as $patient)
            <tr>
                <td>{{ $ct-- }}</td>
                <td nowrap>
                    <a href="{{ route('patients.edit', $patient) }}">{{ $patient->fullName }}</a>
                </td>
                <td nowrap>{{ $patient->identifier }}</td>
                <td nowrap>{{ $patient->suspectCases->last()->age }}</td>
                <td nowrap>{{ strtoupper($patient->genderEsp) }}</td>
                <td nowrap>{{ ($patient->demographic) ? $patient->demographic->commune : '' }}</td>
                <td nowrap>{{ $patient->suspectCases->first()->status }}</td>


                @foreach ($patient->suspectCases as $suspectCase)
                    <td nowrap>{{ $suspectCase->sample_at->format('Y-m-d') }}</td>
                    <td nowrap>{{ ($suspectCase->pscr_sars_cov_2_at) ? $suspectCase->pscr_sars_cov_2_at->format('Y-m-d') : '' }}</td>
                    <td nowrap>
                        <a href="{{ route('lab.suspect_cases.edit', $suspectCase) }}">{{ $suspectCase->covid19 }}</a>
                        {{ ($suspectCase->discharge_test)? '' : 'x' }}
                    </td>
                @endforeach

                @for($i = $patient->suspectCases->count(); $i < $max_cases; $i++)
                    <td></td>
                    <td></td>
                    <td></td>
                @endfor

                <td nowrap>{{ ($patient->suspectCases->first()->result_ifd_at) ? $patient->suspectCases->first()->result_ifd_at->format('Y-m-d') : '' }}</td>
                <td nowrap>{{ $patient->suspectCases->first()->result_ifd }}</td>

                <td nowrap>{{ $patient->suspectCases->first()->origin }}</td>

                <td nowrap>{{ $patient->suspectCases->first()->epidemiological_week }}</td>
                <td nowrap>{{ $patient->suspectCases->first()->epivigila }}</td>
                <td nowrap>{{ $patient->suspectCases->first()->paho_flu }}</td>
                <td nowrap>{{ ($patient->suspectCases->first()->gestation == 1) ? 'Sí' : '' }}</td>
                <td nowrap>{{ ($patient->suspectCases->first()->close_contact == 1) ? 'Sí':'' }}</td>

                <td nowrap>{{ ($patient->suspectCases->first()->sent_isp_at) ? $patient->suspectCases->first()->sent_isp_at->format('Y-m-d') : '' }}</td>
                <td nowrap>{{ $patient->suspectCases->first()->procesingLab }}</td>


                <td nowrap>{{ ($patient->suspectCases->first()->notification_at) ? $patient->suspectCases->first()->notification_at->format('Y-m-d') : '' }}</td>
                <td nowrap>{{ $patient->suspectCases->first()->notification_mechanism }}</td>

                <td nowrap>{{ $patient->suspectCases->first()->discharged_at }}</td>
                <td nowrap>{{ $patient->suspectCases->first()->observation }}</td>

            </tr>
        @endforeach
    </tbody>
</table>

@endsection

@section('custom_js_head')

@endsection
