@can('Patient: tracing')

@if($patient->tracing)

    <div class="card">
        <div class="card-body">

            <form method="POST" class="form-horizontal mb-3"
                  action="{{ route('patients.tracings.update',$patient->tracing) }}">
                @csrf
                @method('PUT')
                <h4>Notificación</h4>

                <!--**********************************-->
                <div class="form-row">
                    <fieldset class="form-group col-12 col-sm-4 col-md-2">
                        <label for="for_notification_at">Fecha de Notificación</label>
                        <input type="date" class="form-control" name="notification_at"
                               id="for_notification_at"
                               value="{{ ($patient->tracing->notification_at) ? $patient->tracing->notification_at->format('Y-m-d') : '' }}">
                    </fieldset>

                    <fieldset class="form-group col-12 col-sm-4 col-md-3">
                        <label for="for_notification_mechanism">Mecanismo de Notifi.</label>
                        <select name="notification_mechanism" id="for_notification_mechanism" class="form-control">
                            <option></option>
                            <option value="Pendiente"
                                {{ ($patient->tracing->notification_mechanism == 'Pendiente')?'selected':'' }}>
                                Pendiente
                            </option>
                            <option value="Llamada telefónica"
                                {{ ($patient->tracing->notification_mechanism == 'Llamada telefónica')?'selected':'' }}>
                                Llamada telefónica
                            </option>
                            <option value="Correo electrónico"
                                {{ ($patient->tracing->notification_mechanism == 'Correo electrónico')?'selected':'' }}>
                                Correo electrónico
                            </option>
                            <option value="Visita domiciliaria"
                                {{ ($patient->tracing->notification_mechanism == 'Visita domiciliaria')?'selected':'' }}>
                                Visita domiciliaria
                            </option>
                            <option value="Centro de salud"
                                {{ ($patient->tracing->notification_mechanism == 'Centro de salud')?'selected':'' }}>
                                Centro de salud
                            </option>
                        </select>
                    </fieldset>

                    <fieldset class="form-group col-12 col-sm-4 col-md-3">
                        <label for="for_discharged_at">Fecha alta médica</label>
                        <input type="date" class="form-control" name="discharged_at"
                               id="for_discharged_at" value="{{ $patient->tracing->discharged_at }}">
                    </fieldset>

                </div>

                <!--**********************************-->
                <hr>

                <h4 class="mt-4">Ficha de Seguimiento</h4>
                <div class="form-row">
                    <fieldset class="form-group col-5 col-md-1">
                        <label for="for_index">Indice *</label>
                        <select name="index" id="for_index" class="form-control" required>
                            <option value=""></option>
                            <option value="1" {{ ($patient->tracing->index === 1) ? 'selected' : '' }}>Si</option>
                            <option value="0" {{ ($patient->tracing->index === 0) ? 'selected' : '' }}>No</option>
                            <option value="2" {{ ($patient->tracing->index === 2) ? 'selected' : '' }}>Probable</option>
                        </select>
                    </fieldset>

                    <fieldset class="form-group col-7 col-md-3">
                        <label for="for_next_control_at">Próximo seguimiento *</label>
                        <input type="datetime-local" class="form-control" name="next_control_at"
                               id="for_next_control_at" required
                               value="{{ ($patient->tracing->next_control_at) ? $patient->tracing->next_control_at->format('Y-m-d\TH:i:s') : '' }}">
                    </fieldset>

                    <fieldset class="form-group col-12 col-md-3">
                        <label for="for_status">Estado del seguimiento *</label>
                        <select name="status" id="for_status_seguimiento" class="form-control" required>
                            <option value=""></option>
                            <option value="1" {{ ($patient->tracing->status == 1) ? 'selected' : '' }}>En seguimiento
                            </option>
                            <option value="0" {{ ($patient->tracing->status === 0) ? 'selected' : '' }}>Fin
                                seguimiento
                            </option>
                        </select>
                    </fieldset>

                    <fieldset class="form-group col-12 col-md-4">
                        <label for="for_establishment_id">Establecimiento que realiza seguimiento</label>
                        <select name="establishment_id" id="for_establishment_id" class="form-control">
                            @foreach($establishments as $estab)
                                <option
                                    value="{{ $estab->id }}" {{ ($patient->tracing->establishment_id == $estab->id) ? 'selected' : '' }}>{{ $estab->alias }}</option>
                            @endforeach
                        </select>
                    </fieldset>

                    <fieldset class="form-group col-6 col-sm-6 col-md-1">
                        <label for="for_functionary">Func. Salud</label>
                        <select name="functionary" id="for_functionary" class="form-control">
                            <option value=""></option>
                            <option value="1" {{ ($patient->tracing->functionary === 1) ? 'selected' : '' }}>Si</option>
                            <option value="0" {{ ($patient->tracing->functionary === 0) ? 'selected' : '' }}>No</option>
                        </select>
                    </fieldset>
                </div>

                <!--**********************************-->
                <div class="form-row">

                    <fieldset class="form-group col-6 col-sm-6 col-md-1">
                        <label for="for_symptoms">Síntomas</label>
                        <select name="symptoms" id="for_symptoms" class="form-control">
                            <option value=""></option>
                            <option value="0" {{ ($patient->tracing->symptoms === 0) ? 'selected' : '' }}>No</option>
                            <option value="1" {{ ($patient->tracing->symptoms === 1) ? 'selected' : '' }}>Si</option>
                        </select>
                    </fieldset>

                    <fieldset class="form-group col-12 col-sm-6 col-md-3">
                        <label for="for_symptoms_start_at">Inicio de síntomas</label>
                        <input type="datetime-local" class="form-control" name="symptoms_start_at"
                               id="for_symptoms_start_at"
                               value="{{ ($patient->tracing->symptoms_start_at) ? $patient->tracing->symptoms_start_at->format('Y-m-d\TH:i:s') : '' }}">
                    </fieldset>

                    <fieldset class="form-group col-12 col-sm-6 col-md-3">
                        <label for="for_symptoms_end_at">Fin de síntomas</label>
                        <input type="datetime-local" class="form-control" name="symptoms_end_at"
                               id="for_symptoms_end_at"
                               value="{{ ($patient->tracing->symptoms_end_at) ? $patient->tracing->symptoms_end_at->format('Y-m-d\TH:i:s') : '' }}">
                    </fieldset>
                </div>

                <!--**********************************-->

                <div class="form-row">
                    <fieldset class="form-group col-12 col-sm-6 col-md-2">
                        <label for="for_quarantine_start_at">Inicio Cuarentena *</label>
                        <input type="date" class="form-control" name="quarantine_start_at"
                               id="for_quarantine_start_at" required
                               value="{{ ($patient->tracing->quarantine_start_at) ? $patient->tracing->quarantine_start_at->format('Y-m-d') : '' }}">
                    </fieldset>

                    <fieldset class="form-group col-12 col-sm-6 col-md-2">
                        <label for="for_quarantine_end_at">Término de Cuarentena *</label>
                        <input type="date" class="form-control" name="quarantine_end_at"
                               id="for_quarantine_end_at" required
                               value="{{ ($patient->tracing->quarantine_end_at) ? $patient->tracing->quarantine_end_at->format('Y-m-d') : '' }}">
                    </fieldset>

                    <fieldset class="form-group col-12 col-sm-12 col-md-5">
                        <label for="for_cannot_quarantine">No puede realizar cuarentena</label>
                        <input type="text" class="form-control" name="cannot_quarantine"
                               id="for_cannot_quarantine" value="{{ $patient->tracing->cannot_quarantine }}">
                    </fieldset>
                </div>

<!--**********************************-->

                <div class="form-row">
                    <fieldset class="form-group col-12 col-sm-6 col-md-6">
                        <label for="for_responsible_family_member">Familiar responsable / teléfono</label>
                        <input type="text" class="form-control" name="responsible_family_member"
                               id="for_responsible_family_member"
                               value="{{ $patient->tracing->responsible_family_member }}">
                    </fieldset>

                    <fieldset class="form-group col-12 col-sm-6 col-md-2">
                        <label for="for_prevision">Previsión</label>
                        <select name="prevision" id="for_prevision" class="form-control">
                            <option value=""></option>
                            <option value="Sin prevision"
                                {{ ($patient->tracing->prevision == 'Sin prevision') ? 'selected' : '' }}>Sin prevision
                            </option>
                            <option value="Fonasa A"
                                {{ ($patient->tracing->prevision == 'Fonasa A') ? 'selected' : '' }}>Fonasa A
                            </option>
                            <option value="Fonasa B"
                                {{ ($patient->tracing->prevision == 'Fonasa B') ? 'selected' : '' }}>Fonasa B
                            </option>
                            <option value="Fonasa C"
                                {{ ($patient->tracing->prevision == 'Fonasa C') ? 'selected' : '' }}>Fonasa C
                            </option>
                            <option value="Fonasa D"
                                {{ ($patient->tracing->prevision == 'Fonasa D') ? 'selected' : '' }}>Fonasa D
                            </option>
                            <option value="Isapre"
                                {{ ($patient->tracing->prevision == 'Isapre') ? 'selected' : '' }}>Isapre
                            </option>
                            <option value="Otro"
                                {{ ($patient->tracing->prevision == 'O') ? 'selected' : '' }}>Otro
                            </option>
                        </select>
                    </fieldset>

                    <fieldset class="form-group col-12 col-sm-6 col-md-1">
                        <label for="for_gestation">Gestante</label>
                        <select name="gestation" id="for_gestation" class="form-control">
                            <option value=""></option>
                            <option value="0" {{ ($patient->tracing->gestation === 0) ? 'selected' : '' }}>No</option>
                            <option value="1" {{ ($patient->tracing->gestation == 1) ? 'selected' : '' }}>Si</option>
                        </select>
                    </fieldset>

                    <fieldset class="form-group col-12 col-sm-6 col-md-2">
                        <label for="for_gestation_week">Semanas de gestación</label>
                        <input type="text" class="form-control" name="gestation_week"
                               id="for_gestation_week" value="{{ $patient->tracing->gestation_week }}">
                    </fieldset>

                </div>
<!--**********************************-->
                <div class="form-row">
                    <fieldset class="form-group col-12 col-sm-6">
                        <label for="for_allergies">Alergias</label>
                        <input type="text" class="form-control" name="allergies"
                               id="for_allergies" value="{{ $patient->tracing->allergies }}">
                    </fieldset>

                    <fieldset class="form-group col-12 col-sm-6">
                        <label for="for_common_use_drugs">Farmacos de uso común</label>
                        <input type="text" class="form-control" name="common_use_drugs"
                               id="for_common_use_drugs" value="{{ $patient->tracing->common_use_drugs }}">
                    </fieldset>

                    <fieldset class="form-group col-12 col-sm-12">
                        <label for="for_morbid_history">Antecedentes Mórbidos</label>
                        <input type="text" class="form-control" name="morbid_history"
                               id="for_morbid_history" value="{{ $patient->tracing->morbid_history }}">
                    </fieldset>

                    <fieldset class="form-group col-12 col-sm-12">
                        <label for="for_family_history">Antecedentes Familiares</label>
                        <input type="text" class="form-control" name="family_history"
                               id="for_family_history" value="{{ $patient->tracing->family_history }}">
                    </fieldset>
                </div>
<!--**********************************-->
                <div class="form-row">

                <!-- <fieldset class="form-group col-6 col-md-1">
                <label for="for_help_basket">Canasta</label>
                <select name="help_basket" id="for_help_basket" class="form-control">
                    <option value=""></option>
                    <option value="1" {{ ($patient->tracing->help_basket === 1) ? 'selected' : '' }}>Si</option>
                    <option value="0" {{ ($patient->tracing->help_basket === 0) ? 'selected' : '' }}>No</option>
                </select>
            </fieldset>

            <fieldset class="form-group col-6 col-md-1">
                <label for="for_psychological_intervention">Psícológica</label>
                <select name="psychological_intervention" id="for_psychological_intervention" class="form-control">
                    <option value=""></option>
                    <option value="1" {{ ($patient->tracing->psychological_intervention === 1) ? 'selected' : '' }}>Si</option>
                    <option value="0" {{ ($patient->tracing->psychological_intervention === 0) ? 'selected' : '' }}>No</option>
                </select>
            </fieldset>

            <fieldset class="form-group col-6 col-md-1">
                <label for="for_requires_hospitalization">Hospitaliza.</label>
                <select name="requires_hospitalization" id="for_requires_hospitalization" class="form-control">
                    <option value=""></option>
                    <option value="1" {{ ($patient->tracing->requires_hospitalization === 1) ? 'selected' : '' }}>Si</option>
                    <option value="0" {{ ($patient->tracing->requires_hospitalization === 0) ? 'selected' : '' }}>No</option>
                </select>
            </fieldset>

            <fieldset class="form-group col-6 col-md-1">
                <label for="for_requires_licence">Licencia</label>
                <select name="requires_licence" id="for_requires_licence" class="form-control">
                    <option value=""></option>
                    <option value="1" {{ ($patient->tracing->requires_licence === 1) ? 'selected' : '' }}>Si</option>
                    <option value="0" {{ ($patient->tracing->requires_licence === 0) ? 'selected' : '' }}>No</option>
                </select>
            </fieldset> -->
                </div>
<!--**********************************-->
                <div class="form-row">

                    <fieldset class="form-group col-12 col-sm-6">
                        <label for="for_indications">Indicaciones</label>
                        <textarea class="form-control" name="indications"
                                  id="for_indications" rows="4">{{ $patient->tracing->indications }}</textarea>
                    </fieldset>

                    <fieldset class="form-group col-12 col-sm-6">
                        <label for="for_observations">Observaciones</label>
                        <textarea class="form-control" name="observations"
                                  id="for_observations" rows="4">{{ $patient->tracing->observations }}</textarea>
                    </fieldset>

                </div>
<!--**********************************-->
                <button type="submit" class="btn btn-primary ">Guardar</button>

            </form>

        </div>
    </div>

    <hr>

    <div class="card">
        <div class="card-body">
            @include('patients.tracing.partials.events')

            @if($patient->tracing->status)
                @include('patients.tracing.partials.event_create')
            @endif
        </div>
    </div>

    <hr>

    <div class="card">
        <div class="card-body">
            @include('patients.tracing.partials.requests')
            @if($patient->tracing->status)
                @include('patients.tracing.partials.request_create')
            @endif

        </div>
    </div>

    @include('partials.audit_simple', ['audits' => $patient->tracing->audits] )

@else

    <div class="card mb-3">
        <div class="card-body">

            <form method="POST" class="form-horizontal" action="{{ route('patients.tracings.store') }}">
                @csrf
                @method('POST')

                <input type="hidden" class="form-control" name="patient_id"
                       id="for_patient_id" value="{{ $patient->id }}">

                <button type="submit" class="btn btn-primary">Crear Seguimiento</button>

            </form>

        </div>
    </div>

@endif
@endcan
