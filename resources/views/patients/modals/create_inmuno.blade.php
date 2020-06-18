<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Test IgG - IgM</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h5>Paciente:</h5>
        <div class="form-row">
            <fieldset class="form-group col-md-3">
                <label for="for_run">Run u Otra identificación:</label>
                <input type="text" class="form-control" value="{{ $patient->identifier }}" style="text-transform: uppercase;" readonly>
            </fieldset>

            <fieldset class="form-group col-md-3">
                <label for="for_name">Nombre:</label>
                <input type="text" class="form-control" value="{{ $patient->name }}" style="text-transform: uppercase;" readonly>
            </fieldset>

            <fieldset class="form-group col-md-3">
                <label for="for_fathers_family">Apellido Paterno:</label>
                <input type="text" class="form-control" value="{{ $patient->fathers_family }}" style="text-transform: uppercase;" readonly>
            </fieldset>

            <fieldset class="form-group col-md-3">
                <label for="for_mothers_family">Apellido Materno:</label>
                <input type="text" class="form-control" value="{{ $patient->mothers_family }}" style="text-transform: uppercase;" readonly>
            </fieldset>
        </div>

        <hr>

        <div class="card mb-3">
            <div class="card-body">
                <h5>Ingreso de resultados:</h5>

                <form method="POST" class="form-horizontal" action="{{ route('lab.inmuno_tests.store', 'modal') }}">
                    @csrf
                    @method('POST')
                    <div class="form-row">
                      <fieldset class="form-group col-md-3">
                          <label for="for_register_at">Fecha de Examen</label>
                          <input type="datetime-local" class="form-control" name="register_at" id="for_register_at" value="">
                      </fieldset>

                      <fieldset class="form-group col-md-3">
                          <label for="for_register_at">IgG Valor</label>
                          <select class="form-control selectpicker" name="igg_value" id="for_igg_value" title="Seleccione..." required>
                              <option value="positive">Positivo</option>
                              <option value="negative">Negativo</option>
                              <option value="weak">Débil</option>
                          </select>
                      </fieldset>

                      <fieldset class="form-group col-md-3">
                          <label for="for_register_at">IgM Valor</label>
                          <select class="form-control selectpicker" name="igm_value" id="for_igm_value" title="Seleccione..." required>
                              <option value="positive">Positivo</option>
                              <option value="negative">Negativo</option>
                              <option value="weak">Débil</option>
                          </select>
                      </fieldset>

                      <fieldset class="form-group col-md-3">
                          <label for="for_control">Control</label>
                          <select class="form-control selectpicker" name="control" id="for_control" title="Seleccione..." required>
                              <option value="yes">Si</option>
                              <option value="no">No</option>
                          </select>
                      </fieldset>

                      <fieldset class="form-group col-md-4" hidden>
                          <input type="text" class="form-control" name="patient_id" id="for_patient_id" value="{{ $patient->id }}">
                      </fieldset>

                      <fieldset class="form-group col-md-4" hidden>
                          <input type="text" class="form-control" name="user_id" id="for_user_id" value="{{ Auth::id() }}">
                      </fieldset>

                      <hr>

                    </div>

            </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary float-right">Guardar</button>
      </div>
      </form>
    </div>
  </div>
</div>
