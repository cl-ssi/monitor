<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Test Rápido</h5>
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
            <h5>Ingreso de resultados Examen Rápido:</h5>

            <form method="POST" class="form-horizontal" action="{{ route('lab.rapid_tests.store', 'modal') }}">
              @csrf
              @method('POST')

              <div class="form-row">

                <fieldset class="form-group col-md-3">
                  <label for="for_control">Tipo de Examen</label>
                  <select class="form-control" name="type" id="for_type" required>
                    <option value="">Seleccione</option>
                    <option value="Antígeno">Antígeno</option>
                    <option value="IgG">IgG</option>
                    <option value="IgM">IgM</option>
                  </select>
                </fieldset>



                <fieldset class="form-group col-md-3">
                  <label for="for_register_at">Fecha de Examen</label>
                  <input type="datetime-local" class="form-control" name="register_at" id="for_register_at" value="{{ date('Y-m-d\TH:i:s') }}" max="{{ date('Y-m-d\TH:i:s') }}">
                </fieldset>

                <fieldset class="form-group col-md-3">
                  <label for="for_register_at">Valor del Examen</label>
                  <select class="form-control" name="value_test" id="for_value_test" required>
                    <option value="">Seleccione</option>
                    <option value="Positive">Positivo</option>
                    <option value="Negative">Negativo</option>
                    <option value="Weak Positive">Positivo Débil</option>
                  </select>
                </fieldset>

                <fieldset class="form-group col-md-3">
                  <label for="for_epivigila">Epivigila:</label>
                  <input type="number" class="form-control" name="epivigila">
                </fieldset>

                <fieldset class="form-group col-md-12">
                  <label for="for_epivigila">Observación:</label>
                  <input type="text" class="form-control" name="observation" autocomplete="off">
                </fieldset>

                <fieldset class="form-group col-md-4" hidden>
                  <input type="text" class="form-control" name="patient_id" id="for_patient_id" value="{{ $patient->id }}">
                </fieldset>

                <hr>

              </div>

          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary float-right">Guardar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
      </form>
    </div>
  </div>
</div>