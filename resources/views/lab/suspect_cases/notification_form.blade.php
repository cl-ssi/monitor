
@extends('layouts.app')
@section('title', 'Crear Paciente')

@section('content')

    <div class="row">
        <div class="col-md-2">
            <img src="/images/256px_logo_isp.png" class="img-fluid" alt="Instituto de Salud Pública de Chile" width="70%">
        </div>
        <div class="col-md-8">
            <h3 class="mb-3">Formulario notificación inmediata y envío de muestras
                a confirmación IRA grave y 2019-nCoV </h3>
        </div>
        <div class="col-md-2"></div>
    </div>

    <hr/>
    <form method="POST" class="form-horizontal" >
        @csrf
        @method('POST')

        <div class="row">
            <div class="col-md-4">
                <h4 class="mb-3">
                    Información del Paciente
                </h4>
            </div>
        </div>

        <div class="form-group row">
            <label for="for_run" class="col-sm-1 col-form-label">Rut</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="for_run" value="{{$suspectCase->patient->run . '-' . $suspectCase->patient->dv}}">
            </div>
            <label for="for_address" class="col-sm-1 col-form-label">Dirección</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="for_address" value="{{$suspectCase->patient->demographic->address . ' '.
                                                                                 $suspectCase->patient->demographic->number . ' '.
                                                                                 $suspectCase->patient->demographic->department}}">
            </div>
        </div>
        <div class="form-group row">
            <label for="for_name" class="col-sm-1 col-form-label">Nombres</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="for_name" value="{{$suspectCase->patient->name}}">
            </div>
            <label for="for_region" class="col-sm-1 col-form-label">Región</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="for_region" value="{{$suspectCase->patient->demographic->region}}" >
            </div>
        </div>
        <div class="form-group row">
            <label for="for_fathers_family" class="col-sm-1 col-form-label">Apellido Paterno</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="for_fathers_family" value="{{$suspectCase->patient->fathers_family}}">
            </div>
            <label for="for_city" class="col-sm-1 col-form-label">Ciudad</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="for_city">
            </div>
        </div>
        <div class="form-group row">
            <label for="for_mothers_family" class="col-sm-1 col-form-label">Apellido Materno</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="for_mothers_family" value="{{$suspectCase->patient->mothers_family}}">
            </div>
            <label for="for_commune" class="col-sm-1 col-form-label">Comuna</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="for_commune" value="{{$suspectCase->patient->demographic->commune}}">
            </div>
        </div>
        <div class="form-group row">
            <label for="for_gender" class="col-sm-1 col-form-label">Sexo</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="for_gender" value="{{$suspectCase->patient->sexEsp}}">
            </div>
            <label for="for_telephone" class="col-sm-1 col-form-label">Teléfono</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="for_telephone" value="{{$suspectCase->patient->demographic->telephone}}">
            </div>
        </div>
        <div class="form-group row">
            <label for="for_birthday" class="col-sm-1 col-form-label">Nacimiento</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="for_birthday" value="{{Carbon\Carbon::parse($suspectCase->patient->birthday)->format('d/m/Y')}}">
            </div>
            <label for="for_prevision" class="col-sm-1 col-form-label">Previsión</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="for_prevision" value="{{($suspectCase->patient->booking) ? $suspectCase->patient->booking->prevision : '' }}">
            </div>
        </div>
        <div class="form-group row">
            <label for="for_age" class="col-sm-1 col-form-label">Edad</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="for_age" value="{{$suspectCase->patient->age}}">
            </div>

        </div>

        <hr/>
        <div class="row">
            <div class="col-md-4">
                <h4 class="mb-3">
                    Datos de Procedencia
                </h4>
            </div>
        </div>
        <div class="form-group row">
            <label for="for_run_medic" class="col-sm-1 col-form-label">Profesional Responsable</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="for_run_medic" value="{{$user->run . '-' .$user->dv}}">
            </div>
            <label for="for_laboratory" class="col-sm-1 col-form-label">Laboratorio</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="for_laboratory">
            </div>
        </div>
        <div class="form-group row">
            <label for="for_region_origin" class="col-sm-1 col-form-label">Región</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="for_region_origin" value="{{$suspectCase->patient->demographic->region}}">
            </div>
            <label for="for_unit_origin" class="col-sm-1 col-form-label">Unidad</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="for_unit_origin" >
            </div>
        </div>
        <div class="form-group row">
            <label for="for_province_origin" class="col-sm-1 col-form-label">Provincia</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="for_province_origin">
            </div>
            <label for="for_email_origin" class="col-sm-1 col-form-label">Correo Electrónico</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="for_email_origin">
            </div>
        </div>
        <div class="form-group row">
            <label for="for_commune_origin" class="col-sm-1 col-form-label">Comuna</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="for_commune_origin" value="{{$suspectCase->laboratory->commune->name}}">
            </div>
            <label for="for_telephone_origin" class="col-sm-1 col-form-label">Fono</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="for_telephone_origin">
            </div>
        </div>
        <div class="form-group row">
            <label for="for_address_origin" class="col-sm-1 col-form-label">Dirección</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="for_address_origin">
            </div>
            <label for="for_fax_origin" class="col-sm-1 col-form-label">Fax</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="for_fax_origin">
            </div>
        </div>

        <hr/>
        <div class="row">
            <div class="col-md-4">
                <h4 class="mb-3">
                    Antecedentes de la Muestra
                </h4>
            </div>
        </div>
        <div class="form-group row">
            <label for="for_sample_at" class="col-sm-1 col-form-label">Obtención</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="for_sample_at" value="{{Carbon\Carbon::parse($suspectCase->sample_at)->format('d/m/Y')  }}">
            </div>
            <div class="col-sm-6">
                <h5>Virus detectado localmente</h5>
            </div>
        </div>


        <hr/>
        <div class="form-group row">
            <label for="for_immuno_fluorescence" class="col-sm-2 col-form-label">Inmuno Fluorescencia</label>
            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck1">
                        Influenza A
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck2">
                        Influenza B
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck2">
                        VRS
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck2">
                        Adenovirus
                    </label>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck1">
                        Parainfluenza
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck2">
                        Metapneumovirus
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck2">
                        Negativo
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck2">
                        Otro
                    </label>
                </div>
            </div>
            <label for="for_establishment" class="col-sm-2 col-form-label">Establecimiento</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="for_establishment">
            </div>
        </div>

        <hr/>
        <div class="form-group row">
            <label for="for_immuno_fluorescence" class="col-sm-2 col-form-label">Test Pack</label>
            <div class="col-sm-4">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                    <label class="form-check-label" for="inlineCheckbox1">Influenza A</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option2">
                    <label class="form-check-label" for="inlineCheckbox2">Influenza B</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="option3">
                    <label class="form-check-label" for="inlineCheckbox3">Negativo</label>
                </div>
            </div>
            <label for="for_establishment" class="col-sm-2 col-form-label">Establecimiento</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="for_establishment">
            </div>
        </div>

        <hr/>
        <div class="form-group row">
            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck1">
                        RT-PCR
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck2">
                        Film Array
                    </label>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck1">
                        Influenza A(H1N1) pdm
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck2">
                        Influenza (H3N2)
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck2">
                        Influenza no subtipificable
                    </label>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck2">
                        Influenza B
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck1">
                        Negativo
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck2">
                        Otro
                    </label>
                </div>
            </div>
            <label for="for_establishment" class="col-sm-2 col-form-label">Establecimiento</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="for_establishment">
            </div>
        </div>

        <hr/>
        <div class="form-group row">
            <label for="for_sample_type" class="col-sm-2 col-form-label">Tipo de muestra</label>
            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" >
                    <label class="form-check-label" for="defaultCheck1">
                        Lavado Broncoalveolar
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" {{($suspectCase->sample_type === 'ESPUTO' or $suspectCase->sample_type === 'TÓRULAS NASOFARÍNGEAS/ESPUTO') ? 'checked' : ''}}>
                    <label class="form-check-label" for="defaultCheck2">
                        Esputo
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck2">
                        Aspirado Traqueal
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" {{($suspectCase->sample_type === 'ASPIRADO NASOFARÍNGEO') ? 'checked' : ''}}>
                    <label class="form-check-label" for="defaultCheck2">
                        Aspirado Nasofaríngeo
                    </label>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" {{($suspectCase->sample_type === 'TÓRULAS NASOFARÍNGEAS' or $suspectCase->sample_type === 'TÓRULAS NASOFARÍNGEAS/ESPUTO') ? 'checked' : ''}}>
                    <label class="form-check-label" for="defaultCheck1">
                        Tórulas Nasofaríngeas
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck2">
                        Biopsia o Tejido Pulmonar
                    </label>
                </div>
            </div>
            <label for="for_sample_type" class="col-sm-2 col-form-label">Otro</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="for_establishment">
            </div>
        </div>

        <hr/>
        <div class="row">
            <div class="col-md-6">
                <h4 class="mb-3">
                    Antecedentes Clínicos/Epidemiológicos
                </h4>
            </div>
        </div>
        <div class="form-group row">
            <label for="for_first_symptoms" class="col-sm-2 col-form-label">Inicio síntomas</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="for_first_symptoms">
            </div>
            <label for="for_first_medical_appointment" class="col-sm-2 col-form-label">Primera consulta</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="for_first_medical_appointment">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-8">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
                    <label class="form-check-label" for="inlineRadio1">Trabajador avícola o granja de cerdos</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                    <label class="form-check-label" for="inlineRadio2">Trabajador</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2" {{($suspectCase->gestation == 1) ? 'checked' : ''}}>
                    <label class="form-check-label" for="inlineRadio2">Embarazo</label>
                </div>
            </div>
            <label for="for_first_medical_appointment" class="col-sm-2 col-form-label">Semanas gestación</label>
            <div class="col-sm-2">
                <input type="text" class="form-control" id="for_establishment" value="{{$suspectCase->gestation_week}}">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-6">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck1">
                        Viajó al extranjero en los 14 días previo al inicio de los sintomas
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="for_travel_country" class="col-sm-1 col-form-label">País</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="for_travel_country">
            </div>
            <label for="for_travel_city" class="col-sm-1 col-form-label">Ciudad</label>
            <div class="col-sm-5">
                <input type="text" class="form-control" id="for_travel_city">
            </div>
        </div>

        <hr/>
        <div class="row">
            <div class="col-md-4">
                <h4 class="mb-3">
                    Síntomas
                </h4>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-6">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck1">
                        Fiebre sobre 38°C
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck2">
                        Dolor de garganta
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck2">
                        Mialgia
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck2">
                        Neumonía
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck2">
                        Encefalitis
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck2">
                        Tos
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck2">
                        Rinorrea/congestión Nasal
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck2">
                        Dificultad respiratoria
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck2">
                        Hipotensión
                    </label>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck1">
                        Cefalea
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck2">
                        Taquipnea
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck2">
                        Hipoxia
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck2">
                        Cianosis
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck2">
                        Deshidratación o rechazo alimentario (lactantes)
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck2">
                        Compromiso hemodinámica
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck2">
                        Consulta repetida por deterioro cuadro respiratorio
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck2">
                        Enfermedad de base
                    </label>
                </div>
                <input type="text" class="form-control" id="for_dv">
            </div>
        </div>

        <hr/>
        <div class="row">
            <div class="col-md-4">
                <h4 class="mb-3">
                    Antecedentes Vacunación
                </h4>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck1">
                        Vacuna contra influenza
                    </label>
                </div>
            </div>
            <label for="for_vaccination_date" class="col-sm-2 col-form-label">Fecha vacunación</label>
            <div class="col-sm-1">
                <input type="text" class="form-control" id="for_vaccination_day" >
            </div>
            <label for="for_vaccination_day" class="col-sm-1 col-form-label">Día</label>
            <div class="col-sm-1">
                <input type="text" class="form-control" id="for_vaccination_month" >
            </div>
            <label for="for_vaccination_month" class="col-sm-1 col-form-label">Mes</label>
            <div class="col-sm-1">
                <input type="text" class="form-control" id="for_vaccination_year" >
            </div>
            <label for="for_vaccination_year" class="col-sm-1 col-form-label">Año</label>
        </div>

        <hr/>
        <div class="row">
            <div class="col-md-4">
                <h4 class="mb-3">
                    Hospitalización
                </h4>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck1">
                        Hospitalizado
                    </label>
                </div>
            </div>
            <label for="for_vaccination_date" class="col-sm-2 col-form-label">Fecha Hospitalización</label>
            <div class="col-sm-1">
                <input type="text" class="form-control" id="for_vaccination_day" >
            </div>
            <label for="for_vaccination_day" class="col-sm-1 col-form-label">Día</label>
            <div class="col-sm-1">
                <input type="text" class="form-control" id="for_vaccination_month" >
            </div>
            <label for="for_vaccination_month" class="col-sm-1 col-form-label">Mes</label>
            <div class="col-sm-1">
                <input type="text" class="form-control" id="for_vaccination_year" >
            </div>
            <label for="for_vaccination_year" class="col-sm-1 col-form-label">Año</label>
        </div>
        <div class="form-group row">
            <div class="col-sm-4"></div>
            <label for="for_diagnosis" class="col-sm-2 col-form-label">Diagnostico de ingreso</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" id="for_diagnosis">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-12">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                    <label class="form-check-label" for="inlineCheckbox1">Grave</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option2">
                    <label class="form-check-label" for="inlineCheckbox2">VM</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="option3">
                    <label class="form-check-label" for="inlineCheckbox3">ECMO</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="option3">
                    <label class="form-check-label" for="inlineCheckbox3">Ingreso UCI</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="option3">
                    <label class="form-check-label" for="inlineCheckbox3">VAFO</label>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck1">
                        Uso Antiviral
                    </label>
                </div>
            </div>
            <label for="for_vaccination_date" class="col-sm-1 col-form-label">Inicio tratamiento</label>
            <div class="col-sm-1">
                <input type="text" class="form-control" id="for_vaccination_day" >
            </div>
            <label for="for_vaccination_day" class="col-sm-1 col-form-label">Día</label>
            <div class="col-sm-1">
                <input type="text" class="form-control" id="for_vaccination_month" >
            </div>
            <label for="for_vaccination_month" class="col-sm-1 col-form-label">Mes</label>
            <div class="col-sm-1">
                <input type="text" class="form-control" id="for_vaccination_year" >
            </div>
            <label for="for_vaccination_year" class="col-sm-1 col-form-label">Año</label>
            <label for="for_vaccination_year" class="col-sm-1 col-form-label">Antiviral</label>
            <div class="col-sm-2">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                    <label class="form-check-label" for="inlineCheckbox1">Oseltamivir</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option2">
                    <label class="form-check-label" for="inlineCheckbox2">Zanamivir</label>
                </div>
            </div>
        </div>

        <hr/>
        <div class="row">
            <div class="col-md-4">
                <h4 class="mb-3">
                    Fallecimiento
                </h4>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                    <label class="form-check-label" for="defaultCheck1">
                        Fallece
                    </label>
                </div>
            </div>
            <label for="for_vaccination_date" class="col-sm-2 col-form-label">Fecha Fallecimiento</label>
            <div class="col-sm-1">
                <input type="text" class="form-control" id="for_vaccination_day" >
            </div>
            <label for="for_vaccination_day" class="col-sm-1 col-form-label">Día</label>
            <div class="col-sm-1">
                <input type="text" class="form-control" id="for_vaccination_month" >
            </div>
            <label for="for_vaccination_month" class="col-sm-1 col-form-label">Mes</label>
            <div class="col-sm-1">
                <input type="text" class="form-control" id="for_vaccination_year" >
            </div>
            <label for="for_vaccination_year" class="col-sm-1 col-form-label">Año</label>
        </div>
        <div class="form-group row">
            <label for="for_diagnosis" class="col-sm-3 col-form-label">Diagnostico de fallecimiento</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="for_diagnosis">
            </div>
        </div>

        <hr/>
        <div class="row">
            <div class="col-md-4">
                <h4 class="mb-3">
                    Instrucciones
                </h4>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <ol>
                    <li>Recepción Lunes a Jueves de 08:00 a 16:00 hrs. Viernes de 08:00 a 12:00 hrs.</li>
                    <li>El transporte debe realizarse según Normativa de transporte de muestras ISP.</li>
                    <li>En caso de dudas consultar a Unidad de Recepción de Muestras (02) 5755187.</li>
                </ol>
            </div>
        </div>
    </form>

@endsection
<style>
    @media print{
        @page {
            size: auto;   /* auto is the initial value */
            size: letter portrait;
            /*margin: 0;  !* this affects the margin in the printer settings *!*/
        }
    }

</style>
