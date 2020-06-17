@extends('layouts.app')

@section('title', 'Reporte COVID-19')

@section('content')

<h3 class="">Seguimiento de Casos Positivos</h3>

</main><main class="">

    <h4>Casos sin domicilio</h4>
    <table class="table table-sm table-bordered table-responsive small" id="tabla_casos_no_demographic">
        <thead>
        <tr class="text-center">
            <th colspan="7"></th>
            <th colspan="1"></th>

            @for ($i=1; $i <= $max_cases_no_demographic; $i++)
                <th colspan="5" nowrap>Covid {{ $i }}</th>
            @endfor

            @for ($i=1; $i <= $max_cases_inmuno_no_demographic; $i++)
                <th colspan="5" nowrap>Inmunoglobulinas {{ $i }}</th>
            @endfor

            <th colspan="2">IFD</th>

            <th colspan="6"></th>

            <th colspan="2">Laboratorio</th>

            <th colspan="2" nowrap>Entrega de resultados</th>

            <th colspan="3"></th>

        </tr>
        <tr class="text-center">
            <th>°</th>
            <th>Paciente</th>
            <th>Run</th>
            <th>Edad</th>
            <th>Sexo</th>
            <th>Comuna</th>
            <th>Nacionalidad</th>

            <th>Estado</th>

            @for ($i=1; $i <= $max_cases_no_demographic; $i++)
                <th class="table-active">ID</th>
                <th nowrap class="table-active">Fecha Muestra</th>
                <th nowrap class="table-active">Fecha Resultado</th>
                <th class="active">Covid</th>
                <th title='Sintomas'>S</th>
            @endfor

            @for ($i=1; $i <= $max_cases_inmuno_no_demographic; $i++)
                <th class="table-active">ID</th>
                <th nowrap class="table-active">Fecha Examen</th>
                <th>IgG</th>
                <th class="active">IgM</th>
                <th>Control</th>
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
        @php $ct = $patientsNoDemographic->count(); @endphp
        @foreach($patientsNoDemographic->reverse() as $patient)
            <tr class="
            @switch($patient->status)
            @case ('Alta') alert-success @break
            @case ('Fallecido') alert-danger @break
            @case ('Hospitalizado UCI') alert-warning @break
            @case ('Hospitalizado UCI (Ventilador)') alert-warning @break
            @endswitch
                ">
                <td>{{ $ct-- }}</td>
                <td nowrap>
                    @can('Patient: edit')
                        <a href="{{ route('patients.edit', $patient) }}">
                            @endcan
                            {{ $patient->fullName }}
                            @can('Patient: edit')
                        </a>
                    @endcan

                    @if($patient->suspectCases->first()->gestation == "1")
                        <i class="fas fa-baby" title="Gestante"></i>
                    @endif
                    @if($patient->suspectCases->first()->close_contact == "1")
                        <i class="fas fa-user-friends" title="Contacto Estrecho"></i>
                    @endif
                    @if($patient->suspectCases->first()->functionary == "1")
                        <i class="fas fa-user-nurse" title="Funcionario de Salud"></i>
                    @endif
                </td>
                <td nowrap>{{ $patient->identifier }}</td>
                <td nowrap>{{ $patient->age }}</td>
                <td nowrap>{{ strtoupper($patient->sexEsp) }}</td>
                <td nowrap>{{ ($patient->demographic AND $patient->demographic->commune) ? $patient->demographic->commune->name : '' }}</td>
                <td>
                    @if($patient->demographic AND $patient->demographic->nationality != "Chile")
                        {{ $patient->demographic->nationality }}
                    @endif
                </td>
                <td nowrap>{{ $patient->status }}</td>


                @foreach ($patient->suspectCases as $suspectCase)
                    <td>
                        @can('SuspectCase: edit')
                            <a href="{{ route('lab.suspect_cases.edit', $suspectCase) }}">
                                @endcan

                                {{ $suspectCase->id }}

                                @can('SuspectCase: edit')
                            </a>
                        @endcan
                    </td>
                    <td nowrap>{{ $suspectCase->sample_at->format('Y-m-d') }}</td>
                    <td nowrap>{{ ($suspectCase->pscr_sars_cov_2_at) ? $suspectCase->pscr_sars_cov_2_at->format('Y-m-d') : '' }}</td>
                    <td nowrap>
                        {{ $suspectCase->covid19 }}
                        {{ ($suspectCase->discharge_test)? 'x' : '' }}
                    </td>
                    <td title="Síntomas">{{ $suspectCase->symptoms }}</td>
                @endforeach

                @for($i = $patient->suspectCases->count(); $i < $max_cases_no_demographic; $i++)
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                @endfor

                @foreach ($patient->inmunoTests as $inmunoTest)
                    <td>
                        @can('Inmuno Test: edit')
                            <a href="{{ route('lab.inmuno_tests.edit', $inmunoTest) }}">
                                @endcan

                                {{ $inmunoTest->id }}

                                @can('$inmunoTest: edit')
                            </a>
                        @endcan
                    </td>
                    <td nowrap>{{ $inmunoTest->register_at->format('Y-m-d H:i:s') }}</td>
                    <td nowrap>{{ strtoupper(($inmunoTest->IgValue) ? $inmunoTest->IgValue : '') }}</td>
                    <td nowrap>{{ strtoupper(($inmunoTest->ImValue) ? $inmunoTest->ImValue : '') }}</td>
                    <td nowrap>{{ strtoupper($inmunoTest->ControlValue) }}</td>
                @endforeach

                @for($i = $patient->inmunoTests->count(); $i < $max_cases_inmuno_no_demographic; $i++)
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                @endfor

                <td nowrap>{{ ($patient->suspectCases->first()->result_ifd_at) ? $patient->suspectCases->first()->result_ifd_at->format('Y-m-d') : '' }}</td>
                <td nowrap>{{ $patient->suspectCases->first()->result_ifd }}</td>

                <td nowrap>
                    {{ ($patient->suspectCases->first()->establishment) ? $patient->suspectCases->first()->establishment->alias : '' }}
                    - {{ $patient->suspectCases->first()->origin }}
                </td>

                <td nowrap>{{ $patient->suspectCases->first()->epidemiological_week }}</td>
                <td nowrap>{{ $patient->suspectCases->first()->epivigila }}</td>
                <td nowrap>{{ $patient->suspectCases->first()->paho_flu }}</td>
                <td nowrap>{{ ($patient->suspectCases->first()->gestation == 1) ? 'Sí' : '' }}</td>
                <td nowrap>{{ ($patient->suspectCases->first()->close_contact == 1) ? 'Sí':'' }}</td>

                <td nowrap>{{ ($patient->suspectCases->first()->sent_isp_at) ? $patient->suspectCases->first()->sent_isp_at->format('Y-m-d') : '' }}</td>
                <td nowrap>{{ $patient->suspectCases->first()->procesingLab }}</td>


                <td nowrap>{{ ($patient->suspectCases->first()->notification_at) ? $patient->suspectCases->first()->notification_at->format('Y-m-d') : '' }}</td>
                <td nowrap>{{ $patient->suspectCases->first()->notification_mechanism }}</td>

                <td nowrap>{{ ($patient->suspectCases->first()->discharged_at) ? $patient->suspectCases->first()->discharged_at->format('Y-m-d') : '' }}</td>
                <td nowrap>{{ $patient->suspectCases->first()->observation }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <br/>

    <div class="form-inline">
        <h4 style="padding-right:10px">Casos con domicilio</h4>
        <a class="btn btn-outline-success btn-sm mb-3" id="downloadLink" style="line" href="{{ route('lab.suspect_cases.reports.case_tracing.export') }}" >Descargar en excel</a>
    </div>

    <table class="table table-sm table-bordered table-responsive small" id="tabla_casos">
        <thead>
            <tr class="text-center">
                <th colspan="7"></th>
                <th colspan="1"></th>

                @for ($i=1; $i <= $max_cases; $i++)
                    <th colspan="5" nowrap>Covid {{ $i }}</th>
                @endfor

                @for ($i=1; $i <= $max_cases_inmuno; $i++)
                    <th colspan="5" nowrap>Inmunoglobulinas {{ $i }}</th>
                @endfor

                <th colspan="2">IFD</th>

                <th colspan="6"></th>

                <th colspan="2">Laboratorio</th>

                <th colspan="2" nowrap>Entrega de resultados</th>

                <th colspan="3"></th>

            </tr>
            <tr class="text-center">
                <th>°</th>
                <th>Paciente</th>
                <th>Run</th>
                <th>Edad</th>
                <th>Sexo</th>
                <th>Comuna</th>
                <th>Nacionalidad</th>

                <th>Estado</th>

                @for ($i=1; $i <= $max_cases; $i++)
                    <th class="table-active">ID</th>
                    <th nowrap class="table-active">Fecha Muestra</th>
                    <th nowrap class="table-active">Fecha Resultado</th>
                    <th class="active">Covid</th>
                    <th title='Sintomas'>S</th>
                @endfor

                @for ($i=1; $i <= $max_cases_inmuno; $i++)
                    <th class="table-active">ID</th>
                    <th nowrap class="table-active">Fecha Examen</th>
                    <th>IgG</th>
                    <th class="active">IgM</th>
                    <th>Control</th>
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
                <tr class="
                @switch($patient->status)
                    @case ('Alta') alert-success @break
                    @case ('Fallecido') alert-danger @break
                    @case ('Hospitalizado UCI') alert-warning @break
                    @case ('Hospitalizado UCI (Ventilador)') alert-warning @break
                @endswitch
                ">
                    <td>{{ $ct-- }}</td>
                    <td nowrap>
                        @can('Patient: edit')
                        <a href="{{ route('patients.edit', $patient) }}">
                        @endcan
                        {{ $patient->fullName }}
                        @can('Patient: edit')
                        </a>
                        @endcan

                        @if($patient->suspectCases->first()->gestation == "1")
                            <i class="fas fa-baby" title="Gestante"></i>
                        @endif
                        @if($patient->suspectCases->first()->close_contact == "1")
                            <i class="fas fa-user-friends" title="Contacto Estrecho"></i>
                        @endif
                        @if($patient->suspectCases->first()->functionary == "1")
                            <i class="fas fa-user-nurse" title="Funcionario de Salud"></i>
                        @endif
                    </td>
                    <td nowrap>{{ $patient->identifier }}</td>
                    <td nowrap>{{ $patient->age }}</td>
                    <td nowrap>{{ strtoupper($patient->sexEsp) }}</td>
                    <td nowrap>{{ ($patient->demographic AND $patient->demographic->commune) ? $patient->demographic->commune->name : '' }}</td>
                    <td>
                        @if($patient->demographic AND $patient->demographic->nationality != "Chile")
                                {{ $patient->demographic->nationality }}
                        @endif
                    </td>
                    <td nowrap>{{ $patient->status }}</td>


                    @foreach ($patient->suspectCases as $suspectCase)
                        <td>
                            @can('SuspectCase: edit')
                            <a href="{{ route('lab.suspect_cases.edit', $suspectCase) }}">
                            @endcan

                            {{ $suspectCase->id }}

                            @can('SuspectCase: edit')
                            </a>
                            @endcan
                        </td>
                        <td nowrap>{{ $suspectCase->sample_at->format('Y-m-d') }}</td>
                        <td nowrap>{{ ($suspectCase->pscr_sars_cov_2_at) ? $suspectCase->pscr_sars_cov_2_at->format('Y-m-d') : '' }}</td>
                        <td nowrap>
                            {{ $suspectCase->covid19 }}
                            {{ ($suspectCase->discharge_test)? 'x' : '' }}
                        </td>
                        <td title="Síntomas">{{ $suspectCase->symptoms }}</td>
                    @endforeach

                    @for($i = $patient->suspectCases->count(); $i < $max_cases; $i++)
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    @endfor

                    @foreach ($patient->inmunoTests as $inmunoTest)
                        <td>
                            @can('Inmuno Test: edit')
                            <a href="{{ route('lab.inmuno_tests.edit', $inmunoTest) }}">
                            @endcan

                            {{ $inmunoTest->id }}

                            @can('$inmunoTest: edit')
                            </a>
                            @endcan
                        </td>
                        <td nowrap>{{ $inmunoTest->register_at->format('Y-m-d H:i:s') }}</td>
                        <td nowrap>{{ strtoupper(($inmunoTest->IgValue) ? $inmunoTest->IgValue : '') }}</td>
                        <td nowrap>{{ strtoupper(($inmunoTest->ImValue) ? $inmunoTest->ImValue : '') }}</td>
                        <td nowrap>{{ strtoupper($inmunoTest->ControlValue) }}</td>
                    @endforeach

                    @for($i = $patient->inmunoTests->count(); $i < $max_cases_inmuno; $i++)
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    @endfor

                    <td nowrap>{{ ($patient->suspectCases->first()->result_ifd_at) ? $patient->suspectCases->first()->result_ifd_at->format('Y-m-d') : '' }}</td>
                    <td nowrap>{{ $patient->suspectCases->first()->result_ifd }}</td>

                    <td nowrap>
                        {{ ($patient->suspectCases->first()->establishment) ? $patient->suspectCases->first()->establishment->alias : '' }}
                        - {{ $patient->suspectCases->first()->origin }}
                    </td>

                    <td nowrap>{{ $patient->suspectCases->first()->epidemiological_week }}</td>
                    <td nowrap>{{ $patient->suspectCases->first()->epivigila }}</td>
                    <td nowrap>{{ $patient->suspectCases->first()->paho_flu }}</td>
                    <td nowrap>{{ ($patient->suspectCases->first()->gestation == 1) ? 'Sí' : '' }}</td>
                    <td nowrap>{{ ($patient->suspectCases->first()->close_contact == 1) ? 'Sí':'' }}</td>

                    <td nowrap>{{ ($patient->suspectCases->first()->sent_isp_at) ? $patient->suspectCases->first()->sent_isp_at->format('Y-m-d') : '' }}</td>
                    <td nowrap>{{ $patient->suspectCases->first()->procesingLab }}</td>


                    <td nowrap>{{ ($patient->suspectCases->first()->notification_at) ? $patient->suspectCases->first()->notification_at->format('Y-m-d') : '' }}</td>
                    <td nowrap>{{ $patient->suspectCases->first()->notification_mechanism }}</td>

                    <td nowrap>{{ ($patient->suspectCases->first()->discharged_at) ? $patient->suspectCases->first()->discharged_at->format('Y-m-d') : '' }}</td>
                    <td nowrap>{{ $patient->suspectCases->first()->observation }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection
