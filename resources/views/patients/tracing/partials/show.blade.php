@can('Developer')
<h4 class="mt-4">Seguimiento</h4>

@if($patient->tracing)

    <form method="POST" class="form-horizontal mb-3" action="{{ route('patients.tracings.update',$patient->tracing) }}">
        @csrf
        @method('PUT')

        <div class="form-row">
            <fieldset class="form-group col-md-3">
                <label for="for_next_control_at">Próximo Control *</label>
                <input type="datetime-local" class="form-control" name="next_control_at"
                    id="for_next_control_at" required value="{{ $patient->tracing->next_control_at->format('Y-m-d\TH:i:s') }}">
            </fieldset>

            <fieldset class="form-group col-md-3">
                <label for="for_status">Estado del seguimiento *</label>
                <select name="status" id="for_status" class="form-control" required>
                    <option value=""></option>
                    <option value="1" {{ ($patient->tracing->status == 1) ? 'selected' : '' }}>En seguimiento</option>
                    <option value="0" {{ ($patient->tracing->status === 0) ? 'selected' : '' }}>Fin seguimiento</option>
                </select>
            </fieldset>

            <fieldset class="form-group col-md-5">
                <label for="for_responsible_family_member">Familiar responsable / teléfono</label>
                <input type="text" class="form-control" name="responsible_family_member"
                    id="for_responsible_family_member" value="{{ $patient->tracing->responsible_family_member }}">
            </fieldset>


        </div>

        <div class="form-row">
            <fieldset class="form-group col-md-1">
                <label for="for_symptoms">Síntomas *</label>
                <select name="symptoms" id="for_symptoms" class="form-control" required>
                    <option value=""></option>
                    <option value="0" {{ ($patient->tracing->symptoms === 0) ? 'selected' : '' }}>No</option>
                    <option value="1" {{ ($patient->tracing->symptoms === 1) ? 'selected' : '' }}>Si</option>
                </select>
            </fieldset>


            <fieldset class="form-group col-md-3">
                <label for="for_symptoms_start_at">Inicio de síntomas</label>
                <input type="datetime-local" class="form-control" name="symptoms_start_at"
                    id="for_symptoms_start_at"
                    value="{{ ($patient->tracing->symptoms_start_at) ? $patient->tracing->symptoms_start_at->format('Y-m-d\TH:i:s') : '' }}">
            </fieldset>

            <fieldset class="form-group col-md-3">
                <label for="for_symptoms_end_at">Fin de síntomas</label>
                <input type="datetime-local" class="form-control" name="symptoms_end_at"
                    id="for_symptoms_end_at"
                    value="{{ ($patient->tracing->symptoms_end_at) ? $patient->tracing->symptoms_end_at->format('Y-m-d\TH:i:s') : '' }}">
            </fieldset>

            <fieldset class="form-group col-md-2">
                <label for="for_quarantine_start_at">Inicio Cuarentena *</label>
                <input type="date" class="form-control" name="quarantine_start_at"
                    id="for_quarantine_start_at" required
                    value="{{ ($patient->tracing->quarantine_start_at) ? $patient->tracing->quarantine_start_at->format('Y-m-d') : '' }}">
            </fieldset>

            <fieldset class="form-group col-md-2">
                <label for="for_quarantine_end_at">Término de Cuarentena</label>
                <input type="date" class="form-control" name="quarantine_end_at"
                    id="for_quarantine_end_at"
                    value="{{ ($patient->tracing->quarantine_end_at) ? $patient->tracing->quarantine_end_at->format('Y-m-d') : '' }}">
            </fieldset>

        </div>

        <div class="form-row">
            <fieldset class="form-group col-md-2">
                <label for="for_prevision">Previsión</label>
                <select name="prevision" id="for_prevision" class="form-control">
                    <option value=""></option>
                </select>
            </fieldset>

            <fieldset class="form-group col-md-3">
                <label for="for_establishment_id">Establecimiento</label>
                <select name="establishment_id" id="for_establishment_id" class="form-control">
                    @foreach($establishments as $estab)
                    <option value="{{ $estab->id }}" {{ ($patient->tracing->establishment_id == $estab->id) ? 'selected' : '' }}>{{ $estab->alias }}</option>
                    @endforeach
                </select>
            </fieldset>


        </div>

        <div class="form-row">
            <fieldset class="form-group col">
                <label for="for_allergies">Alergias</label>
                <input type="text" class="form-control" name="allergies"
                    id="for_allergies">
            </fieldset>

            <fieldset class="form-group col">
                <label for="for_common_use_drugs">Farmacos de uso común</label>
                <input type="text" class="form-control" name="common_use_drugs"
                    id="for_common_use_drugs">
            </fieldset>
        </div>

        <div class="form-row">
            <fieldset class="form-group col">
                <label for="for_morbid_history">Antecedentes Mórbidos</label>
                <input type="text" class="form-control" name="morbid_history"
                    id="for_morbid_history">
            </fieldset>
        </div>

        <div class="form-row">
            <fieldset class="form-group col">
                <label for="for_family_history">Antecedentes Familiares</label>
                <input type="text" class="form-control" name="family_history"
                    id="for_family_history">
            </fieldset>
        </div>

        <div class="form-row">

            <fieldset class="form-group col">
                <label for="for_indications">Indicaciones</label>
                <textarea class="form-control" name="indications"
                    id="for_indications" rows="5"></textarea>
            </fieldset>

            <fieldset class="form-group col">
                <label for="for_observations">Observaciones</label>
                <textarea class="form-control" name="observations"
                    id="for_observations" rows="5"></textarea>
            </fieldset>


        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>

    </form>

    @if($patient->tracing->status)
        @include('patients.tracing.partials.events')
        @include('patients.tracing.partials.event_create')
    @endif

@else
<form method="POST" class="form-horizontal" action="{{ route('patients.tracings.store') }}">
    @csrf
    @method('POST')

        <input type="hidden" class="form-control" name="patient_id"
                id="for_patient_id" value="{{ $patient->id }}">

    <button type="submit" class="btn btn-primary">Crear Seguimiento</button>

</form>
@endif
<hr>
@endcan
