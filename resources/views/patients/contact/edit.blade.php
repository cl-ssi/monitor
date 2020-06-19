@extends('layouts.app')

@section('title', 'Contacto Pacientes')

@section('content')

<h3>Editar Contacto de Pacientes</h3>

<h5>Paciente:</h5>


<div class="form-row">
    <fieldset class="form-group col-md-4">
        <label for="for_register_at">Nombre</label>
        <input type="text" class="form-control" name="register_at" id="for_register_at" value="{{ $contactPatient->self_patient->name }}" style="text-transform: uppercase;" readonly>
    </fieldset>

    <fieldset class="form-group col-md-4">
        <label for="for_fathers_family">Apellido Paterno</label>
        <input type="text" class="form-control" value="{{ $contactPatient->self_patient->fathers_family }}" style="text-transform: uppercase;" readonly>
    </fieldset>

    <fieldset class="form-group col-md-4">
        <label for="for_mothers_family">Apellido Materno</label>
        <input type="text" class="form-control" value="{{ $contactPatient->self_patient->mothers_family }}" style="text-transform: uppercase;" readonly>
    </fieldset>
</div>

<hr>

<h5>Contacto:</h5>


<div class="form-row">
    <fieldset class="form-group col-md-4">
        <label for="for_register_at">Nombre</label>
        <input type="text" class="form-control" name="register_at" id="for_register_at" value="{{ $contactPatient->patient->name }}" style="text-transform: uppercase;" readonly>
    </fieldset>

    <fieldset class="form-group col-md-4">
        <label for="for_fathers_family">Apellido Paterno</label>
        <input type="text" class="form-control" value="{{ $contactPatient->patient->fathers_family }}" style="text-transform: uppercase;" readonly>
    </fieldset>

    <fieldset class="form-group col-md-4">
        <label for="for_mothers_family">Apellido Materno</label>
        <input type="text" class="form-control" value="{{ $contactPatient->patient->mothers_family }}" style="text-transform: uppercase;" readonly>
    </fieldset>
</div>

<div class="card mb-3">
    <div class="card-body">
        <h5>Ingreso los datos del contacto:</h5>
        <form method="POST" class="form-horizontal" action="{{ route('patients.contacts.update', $contactPatient) }}">
            @csrf
            @method('PUT')
            <div class="form-row">
              <fieldset class="form-group col-md-3">
                  <label for="for_last_contact_at">Fecha último contacto</label>
                  <input type="datetime-local" class="form-control" name="last_contact_at" id="for_last_contact_at" value="{{ ($contactPatient->LastContactDate) ? $contactPatient->LastContactDate : '' }}">
              </fieldset>

              <fieldset class="form-group col-md-3">
                  <label for="for_category">Categoría</label>
                  <select class="form-control selectpicker" name="category" id="for_category" title="Seleccione..." data-live-search="true" data-size="5" required>
                      <option value="institutional" {{ ($contactPatient->category == 'institutional') ? 'selected' : '' }}>Institucional</option>
                      <option value="ocupational" {{ ($contactPatient->category == 'ocupational') ? 'selected' : '' }}>Laboral</option>
                      <option value="passenger" {{ ($contactPatient->category == 'passenger') ? 'selected' : '' }}>Pasajero</option>
                      <option value="social" {{ ($contactPatient->category == 'social') ? 'selected' : '' }}>Social</option>
                      <option value="waiting room" {{ ($contactPatient->category == 'waiting room') ? 'selected' : '' }}>Sala de espera</option>
                      <option value="family" {{ ($contactPatient->category == 'family') ? 'selected' : '' }}>Familiar</option>
                      <option value="intradomiciliary" {{ ($contactPatient->category == 'intradomiciliary') ? 'selected' : '' }}>Intradomiciliario</option>
                  </select>
              </fieldset>

              <fieldset class="form-group col-md-3">
                  <label for="for_register_at">Parentesco</label>
                  <select class="form-control selectpicker" name="relationship" id="for_relationship" title="Seleccione..." data-live-search="true" data-size="5" required>
                      @if($contactPatient->self_patient->sexEsp == 'Femenino')
                        <option value="grandmother" {{ ($contactPatient->relationship == 'grandmother') ? 'selected' : '' }}>Abuela</option>
                        <option value="coworker" {{ ($contactPatient->relationship == "coworker") ? 'selected' : '' }}>Compañera de Trabajo</option>
                        <option value="sister in law" {{ ($contactPatient->relationship == 'sister in law') ? 'selected' : '' }}>Cuñada</option>
                        <option value="wife" {{ ($contactPatient->relationship == 'wife') ? 'selected' : '' }}>Esposa</option>
                        <option value="sister" {{ ($contactPatient->relationship == 'sister') ? 'selected' : '' }}>Hermana</option>
                        <option value="daughter" {{ ($contactPatient->relationship == 'daughter') ? 'selected' : '' }}>Hija</option>
                        <option value="mother" {{ ($contactPatient->relationship == 'mother') ? 'selected' : '' }}>Madre</option>
                        <option value="cousin" {{ ($contactPatient->relationship == 'cousin') ? 'selected' : '' }}>Primo/a</option>
                        <option value="niece" {{ ($contactPatient->relationship == 'niece') ? 'selected' : '' }}>Sobrina</option>
                        <option value="mother in law" {{ ($contactPatient->relationship == 'mother in law') ? 'selected' : '' }}>Suegra</option>
                        <option value="aunt" {{ ($contactPatient->relationship == 'aunt') ? 'selected' : '' }}>Tía</option>
                        <option value="grandchild" {{ ($contactPatient->relationship == 'grandchild') ? 'selected' : '' }}>Nieta</option>
                        <option value="daughter in law" {{ ($contactPatient->relationship == 'daughter in law') ? 'selected' : '' }}>Nuera</option>
                        <option value="girlfriend" {{ ($contactPatient->relationship == 'girlfriend') ? 'selected' : '' }}>Pareja</option>
                        <option value="neighbour" {{ ($contactPatient->relationship == 'neighbour') ? 'selected' : '' }}>Vecina</option>
                        <option value="other" {{ ($contactPatient->relationship == 'other') ? 'selected' : '' }}>Otro</option>
                      @elseif($contactPatient->self_patient->sexEsp == 'Masculino')
                        <option value="grandfather" {{ ($contactPatient->relationship == 'grandfather') ? 'selected' : '' }}>Abuelo</option>
                        <option value="coworker" {{ ($contactPatient->relationship == 'coworker') ? 'selected' : '' }}>Compañero de Trabajo</option>
                        <option value="brother in law" {{ ($contactPatient->relationship == 'brother in law') ? 'selected' : '' }}>Cuñado</option>
                        <option value="husband" {{ ($contactPatient->relationship == 'husband') ? 'selected' : '' }}>Esposo</option>
                        <option value="brother" {{ ($contactPatient->relationship == 'brother') ? 'selected' : '' }}>Hermano</option>
                        <option value="son" {{ ($contactPatient->relationship == 'son') ? 'selected' : '' }}>Hijo</option>
                        <option value="grandchild" {{ ($contactPatient->relationship == 'grandchild') ? 'selected' : '' }}>Nieto</option>
                        <option value="father" {{ ($contactPatient->relationship == 'father') ? 'selected' : '' }}>Padre</option>
                        <option value="boyfriend" {{ ($contactPatient->relationship == 'boyfriend') ? 'selected' : '' }}>Pareja</option>
                        <option value="cousin" {{ ($contactPatient->relationship == 'cousin') ? 'selected' : '' }}>Primo/a</option>
                        <option value="nephew" {{ ($contactPatient->relationship == 'nephew') ? 'selected' : '' }}>Sobrino</option>
                        <option value="father in law" {{ ($contactPatient->relationship == 'father in law') ? 'selected' : '' }}>Suegro</option>
                        <option value="uncle" {{ ($contactPatient->relationship == 'uncle') ? 'selected' : '' }}>Tío</option>
                        <option value="neighbour" {{ ($contactPatient->relationship == 'neighbour') ? 'selected' : '' }}>Vecino</option>
                        <option value="son in law" {{ ($contactPatient->relationship == 'son in law') ? 'selected' : '' }}>Yerno</option>
                        <option value="other" {{ ($contactPatient->relationship == 'other') ? 'selected' : '' }}>Otro</option>
                      @else
                        <option value="grandmother" {{ ($contactPatient->relationship == 'grandmother') ? 'selected' : '' }}>Abuela</option>
                        <option value="grandfather" {{ ($contactPatient->relationship == 'grandfather') ? 'selected' : '' }}>Abuelo</option>
                        <option value="sister in law" {{ ($contactPatient->relationship == 'sister in law') ? 'selected' : '' }}>Cuñada</option>
                        <option value="brother in law" {{ ($contactPatient->relationship == 'brother in law') ? 'selected' : '' }}>Cuñado</option>
                        <option value="wife" {{ ($contactPatient->relationship == 'wife') ? 'selected' : '' }}>Esposa</option>
                        <option value="husband" {{ ($contactPatient->relationship == 'husband') ? 'selected' : '' }}>Esposo</option>
                        <option value="sister" {{ ($contactPatient->relationship == 'sister') ? 'selected' : '' }}>Hermana</option>
                        <option value="brother" {{ ($contactPatient->relationship == 'brother') ? 'selected' : '' }}>Hermano</option>
                        <option value="daughter" {{ ($contactPatient->relationship == 'daughter') ? 'selected' : '' }}>Hija</option>
                        <option value="son" {{ ($contactPatient->relationship == 'son') ? 'selected' : '' }}>Hijo</option>
                        <option value="mother" {{ ($contactPatient->relationship == 'mother') ? 'selected' : '' }}>Madre</option>
                        <option value="father" {{ ($contactPatient->relationship == 'father') ? 'selected' : '' }}>Padre</option>
                        <option value="cousin" {{ ($contactPatient->relationship == 'cousin') ? 'selected' : '' }}>Primo/a</option>
                        <option value="niece" {{ ($contactPatient->relationship == 'niece') ? 'selected' : '' }}>Sobrina</option>
                        <option value="nephew" {{ ($contactPatient->relationship == 'nephew') ? 'selected' : '' }}>Sobrino</option>
                        <option value="mother in law" {{ ($contactPatient->relationship == 'mother in law') ? 'selected' : '' }}>Suegra</option>
                        <option value="father in law" {{ ($contactPatient->relationship == 'father in law') ? 'selected' : '' }}>Suegro</option>
                        <option value="aunt" {{ ($contactPatient->relationship == 'aunt') ? 'selected' : '' }}>Tía</option>
                        <option value="uncle" {{ ($contactPatient->relationship == 'uncle') ? 'selected' : '' }}>Tío</option>
                        <option value="grandchild" {{ ($contactPatient->relationship == 'grandchild') ? 'selected' : '' }}>Nieta/o</option>
                        <option value="daughter in law" {{ ($contactPatient->relationship == 'daughter in law') ? 'selected' : '' }}>Nuera</option>
                        <option value="son in law" {{ ($contactPatient->relationship == 'son in law') ? 'selected' : '' }}>Yerno</option>
                        <option value="girlfriend" {{ ($contactPatient->relationship == 'girlfriend') ? 'selected' : '' }}>Pareja (Femenino)</option>
                        <option value="boyfriend" {{ ($contactPatient->relationship == 'boyfriend') ? 'selected' : '' }}>Pareja (Masculino)</option>
                        <option value="other" {{ ($contactPatient->relationship == 'other') ? 'selected' : '' }}>Otro</option>
                      @endif
                  </select>
              </fieldset>

              <fieldset class="form-group col-md-3">
                  <label for="for_live_together">¿Viven Juntos?</label>
                  <select class="form-control selectpicker" name="live_together" id="for_live_together" title="Seleccione..." data-size="2" required>
                      <option value="1" {{ ($contactPatient->live_together == 1) ? 'selected' : '' }}>Si</option>
                      <option value="0" {{ ($contactPatient->live_together == 0) ? 'selected' : '' }}>No</option>
                  </select>
              </fieldset>


              </div>
              <hr>
              <button type="submit" class="btn btn-primary float-right">Guardar</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('custom_js')
<link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-select.min.css') }}">

<script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('js/defaults-es_CL.min.js') }}"></script>

@endsection
