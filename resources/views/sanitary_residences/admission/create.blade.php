@extends('layouts.app')

@section('title', 'Crear Residencia')

@section('content')



<h3 class="mb-3">Crear Pauta de Evaluación Residencia Sanitaria</h3>

<hr>
<form method="POST" class="form-horizontal" action="{{ route('sanitary_residences.admission.store') }}">
    @csrf
    @method('POST')

    <h5 class="mb-6">Identificación de Paciente</h5>
    <input type="hidden" id="patient_id" name="patient_id" value="{{$patient->id}}">

    <div class="form-row">
        <fieldset class="form-group col-8 col-md-8">
            <label for="for_name">Nombre completo</label>
            <input type="text" class="form-control" name="name" id="for_name" required placeholder="" autocomplete="off" value="{{$patient->fullname}}" readonly>
        </fieldset>

        <fieldset class="form-group col-4 col-md-4">
            <label for="for_address">RUT o ID</label>
            <input type="text" class="form-control" name="address" id="for_address" required placeholder="" autocomplete="off" value="{{$patient->identifier}}" readonly>
        </fieldset>
    </div>

    <div class="form-row">
        <fieldset class="form-group col-6 col-md-6">
            <label for="for_name">Domicilio</label>
            <input type="text" class="form-control" name="name" id="for_name" required placeholder="" autocomplete="off" value="{{($patient->demographic)?$patient->demographic->fulladdress:''}}" readonly>
        </fieldset>

        <fieldset class="form-group col-3 col-md-3">
            <label for="for_address">Nacionalidad</label>
            <input type="text" class="form-control" name="address" id="for_address" required placeholder="" autocomplete="off" value="{{ ($patient->demographic)?$patient->demographic->nationality:'' }}" readonly>
        </fieldset>

        <fieldset class="form-group col-3 col-md-3">
            <label for="for_address">Comuna</label>
            <input type="text" class="form-control" name="address" id="for_address" required placeholder="" autocomplete="off" value="{{ ($patient->demographic->commune)?$patient->demographic->commune->name:'' }}" readonly>
        </fieldset>
    </div>


    <div class="form-row">
        

        <fieldset class="form-group col-2 col-md-2">
            <label for="for_name">Telefono</label>
            <input type="text" class="form-control" name="name" id="for_name" required placeholder="" autocomplete="off" value="{{ ($patient->demographic)?$patient->demographic->telephone:'' }}" readonly>
        </fieldset>

        <fieldset class="form-group col-2 col-md-2">
            <label for="for_name">Edad</label>
            <input type="text" class="form-control" name="name" id="for_name" required placeholder="" autocomplete="off" value="{{ $patient->age }}" readonly>
        </fieldset>

        <fieldset class="form-group col-4 col-md-4">
            <label for="for_name">Fecha de Confirmación de COVID 19- Positivo</label>
            <input type="text" class="form-control" name="name" id="for_name" required placeholder="" autocomplete="off" value="{{ ($patient->suspectCases->where('pscr_sars_cov_2', 'positive')->last())? $patient->suspectCases->where('pscr_sars_cov_2', 'positive')->last()->pscr_sars_cov_2_at->format('d-m-Y H:i'):''  }}" readonly>
        </fieldset>

        <fieldset class="form-group col-3 col-md-3">
            <label for="for_address">Centro de Salud de Origen</label>
            <input type="text" class="form-control" name="address" id="for_address" required placeholder="" autocomplete="off" value="{{ ($patient->suspectCases->where('pscr_sars_cov_2', 'positive')->last())? $patient->suspectCases->where('pscr_sars_cov_2', 'positive')->last()->origin:''  }}" readonly>
        </fieldset>


        
    </div>

    <div class="form-row">
        <fieldset class="form-group col-4 col-md-4">
            <label for="for_prevision">Fecha y Hora de Encuesta en Terreno*</label>
            <input type="datetime-local" name="created_at" id="for_prevision" class="form-control" max="{{ date('Y-m-d\TH:i:s') }}" required>                
            </input>
        </fieldset>

        <fieldset class="form-group col-3 col-md-3">
            <label for="for_address">Telefono(s) Contacto Emergencia</label>
            <input type="text" class="form-control" name="contactnumber" id="for_contactnumber" placeholder="" autocomplete="off">
        </fieldset>      

        

    </div>
    <div class="form-row">
        <fieldset class="form-group col-12 col-md-7">
            <label for="for_indications">Antecedentes Morbidos</label>
            <textarea class="form-control" id="for_morbid_history" rows="6" name="morbid_history"></textarea>
        </fieldset>

        <fieldset class="form-group col-12 col-md-5">
            <label for="for_observations">Observaciones</label>
            <textarea type="textarea" class="form-control" rows="6" name="observations" id="for_observations"> </textarea>
        </fieldset>
    </div>

    <hr>
    <h5 class="mb-6">Condiciones de habitabilidad</h5>

    <div class="form-row">
        <fieldset class="form-group col-4 col-md-4">
            <label for="for_name">¿Cuantas personas viven en su domicilio?</label>
            <input type="number" class="form-control" name="people" id="for_people" required placeholder="" autocomplete="off">
        </fieldset>
        <fieldset class="form-group col-4 col-md-4">
            <label for="for_name">¿Con cuantas habitaciones cuenta el hogar?</label>
            <input type="number" class="form-control" name="rooms" id="for_rooms" required placeholder="" autocomplete="off">
        </fieldset>
        <fieldset class="form-group col-4 col-md-4">
            <label for="for_name">¿Cuantos Baños Tiene en la casa?</label>
            <input type="number" class="form-control" name="bathrooms" id="for_bathrooms" required placeholder="" autocomplete="off">
        </fieldset>
    </div>

    <div class="form-check">
        <h5 class="mb-6">¿Tiene Agua en su Domicilio?</h5>
        <input class="form-check-input" type="radio" name="water" id="exampleRadios1" value="1" required>
        <label class="form-check-label" for="exampleRadios1">
            SÍ
        </label>
    </div>

    <div class="form-check">
        <input class="form-check-input" type="radio" name="water" id="exampleRadios2" value="0">
        <label class="form-check-label" for="exampleRadios2">
            NO
        </label>
    </div>
    <br>

    <div class="form-check">
        <h5 class="mb-6">¿ES POSIBLE AISLAR AL PACIENTE? (Seguir con la encuesta si el paciente no puede aislarse correctamente)</h5>
        <input class="form-check-input" type="radio" name="isolate" id="chkYes" value="1" required>
        <label class="form-check-label" for="exampleRadios1">
            SÍ
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="isolate" id="chkNo" value="0">
        <label class="form-check-label" for="exampleRadios2">
            NO
        </label>
    </div>

    <hr>

    <div id="dvPinNo" style="display: none">
    
        <h5 class="mb-6">Criterios de Inclusión/Exclusión</h5>
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-center">PREGUNTA</th>
                    <th class="text-center">SÍ</th>
                    <th class="text-center">NO</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>¿Tiene algún problema respiratorio: ejemplo fiebre alta, dificultad para respirar?</td>
                    <td class="text-center"><input name="respiratory" id="for_respiratory" type="radio" value="1"></td>
                    <td class="text-center"><input name="respiratory" id="for_respiratory" type="radio" value="0"></td>
                </tr>
                <tr>
                    <td>¿Tiene alguna dificultad para realizar sus actividades básicas (o diarias) sin ningún tipo de ayuda?</td>
                    <td class="text-center"><input name="basicactivities" id="for_basicactivities" type="radio" value="1"></td>
                    <td class="text-center"><input name="basicactivities" id="for_basicactivities" type="radio" value="0"></td>
                </tr>
                <tr>
                    <td>¿Actualmente se encuentra o se ha encontrado en tratamiento por alguna adicción (como alcohol, drogas o tabaco)?</td>
                    <td class="text-center"><input name="drugs" id="for_drugs" type="radio" value="1"></td>
                    <td class="text-center"><input name="drugs" id="for_drugs" type="radio" value="0"></td>
                </tr>
                <tr>
                    <td>¿Tiene enfermedades crónicas no compensadas (ej: hipertensión, diabetes)?</td>
                    <td class="text-center"><input name="chronic" id="for_chronic" type="radio" value="1"></td>
                    <td class="text-center"><input name="chronic" id="for_chronic" type="radio" value="0"></td>
                </tr>
                <tr>
                    <td>¿Tiene algún otro problema de salud en este momento?</td>
                    <td class="text-center"><input name="healthnow" id="for_healthnow" type="radio" value="1"></td>
                    <td class="text-center"><input name="healthnow" id="for_healthnow" type="radio" value="0"></td>
                </tr>
                <tr>
                    <td>¿Convive usted con personas portadoras de enfermedades crónicas (ej: Cancer, Hierpertención, Diabetes, Lupus, etc)?</td>
                    <td class="text-center"><input name="risk" type="radio" value="1"></td>
                    <td class="text-center"><input name="risk" type="radio" value="0"></td>
                </tr>
                <tr>
                    <td>¿Hay personas mayores de 60 años en su hogar?</td>
                    <td class="text-center"><input name="old" type="radio" value="1"></td>
                    <td class="text-center"><input name="old" type="radio" value="0"></td>
                </tr>
            </tbody>
        </table>
        <hr>    
    </div>

    
    <div class="form-check">
            <h5 class="mb-6">¿CALIFICA RESIDENCIA?</h5>
            <input class="form-check-input" type="radio" name="residency" id="exampleRadios1" value="1" required>
            <label class="form-check-label" for="exampleRadios1">
                SÍ
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="residency" id="exampleRadios2" value="0">
            <label class="form-check-label" for="exampleRadios2">
                NO
            </label>
        </div>



    <button type="submit" class="btn btn-primary">Guardar</button>

    <a class="btn btn-outline-secondary" href="{{ URL::previous() }}">Cancelar</a>

</form>


@endsection

@section('custom_js')
<script type="text/javascript">
    $(function() {
        $("input[name='isolate']").click(function() {
            if ($("#chkNo").is(":checked")) {
                $("#dvPinNo").show();
            } else {
                $("#dvPinNo").hide();
            }
        });
    });
</script>


@endsection