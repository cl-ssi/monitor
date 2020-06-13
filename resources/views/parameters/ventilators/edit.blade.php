@extends('layouts.app')

@section('title', 'Editar Ventiladores')

@section('content')
<h3 class="mb-3">Ventiladores</h3>

<form method="POST" class="form-horizontal" action="{{ route('parameters.ventilators.update') }}">
    @csrf
    @method('PUT')

    <div class="form-row">
        <fieldset class="form-group col-12 col-md-2">
            <label for="static_covid" class="form-label">Cantidas de ventiladores Covi19 +</label>
            <div>
                <input type="text" readonly class="form-control-plaintext"
                    id="static_covid" value="{{ $ct_covid }}" style="color: red;">
            </div>
        </fieldset>

        <fieldset class="form-group col-12 col-md-2">
            <label for="for_no_covid">Cantidad de ventiladores NO Covid19 +</label>
            <input type="number" class="form-control" name="no_covid" id="for_no_covid"
                required value="{{ $ventilator->no_covid }}">
        </fieldset>

        <fieldset class="form-group col-12 col-md-2">
            <label for="for_total">Total de ventiladores(para el público)</label>
            <input type="number" class="form-control" name="total" id="for_total"
                required value="{{ $ventilator->total }}">
        </fieldset>

        <fieldset class="form-group col-12 col-md-2">
            <label for="for_total_real">Total Real (máximo que se pueden ocupar)</label>
            <input type="number" class="form-control" name="total_real" id="for_total_real"
                required value="{{ $ventilator->total_real }}">
        </fieldset>

    </div>

    <button type="submit" class="btn btn-primary">Guardar</button>

</form>

@endsection

@section('custom_js')

@endsection
