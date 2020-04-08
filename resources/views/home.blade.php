@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Bienvenido al sistema de monitoreo del Servicio de Salud
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    Sistema desarrollado por el área de Informática del
                    Servicio de Salud de Iquique
                    <a href="mailto:sistemas.ssi@redsalud.gob.cl">
                        sistemas.ssi@redsalud.gob.cl
                    </a>
                    <hr>
                    Whatsapp desarrollador: +56 9 82598059 <br>
                    <a href="mailto:alvaro.torres@redsalud.gob.cl">alvaro.torres@redsalud.gob.cl</a>
                </div>
            </div>

            <p class="mt-3">Registro de cambios: </p>
            <ul class="text-muted small">
                <li>2020-04-08 Agregado calculo de tasa</li>
                <li>2020-04-08 Agregado todos los establecimientos de la region</li>
                <li>2020-04-08 Habilitado para cambiar clave</li>
                <li>2020-04-07 Reordenado los datos demográficos (fix de via local)</li>
                <li>2020-04-07 Grafico de casos positivos en el tiempo</li>
                <li>2020-04-06 Es posible ver el PDF de un examen en la lista de casos</li>
                <li>2020-04-06 Obtención de resultados a través de clave única</li>
                <li>2020-03-31 Reporte ahora necesita logear</li>
                <li>2020-03-31 Filtro por resultado de Covid-19</li>
                <li>2020-03-28 Dirección desglosada en datos demográficos.</li>
                <li>2020-03-27 Agregado desglose por genero en resumen.</li>
                <li>2020-03-27 Agregado filtro de busqueda en listados.</li>
                <li>2020-03-27 Reordenado el ingreso de caso sospecha</li>
                <li>2020-03-26 Agregado georeferencia.</li>
                <li>2020-30-26 Agregado campos de fecha entrega de resultados y envío ISP</li>
                <li>2020-03-25 Busca si el paciente ya está ingresado y pre carga los datos.</li>
                <li>2020-03-25 Renombrados datos de resultado de IFD equivalentes al LIS.</li>
                <li>2020-03-25 Resultados IFD agregado "No procesado".</li>
                <li>2020-03-25 Edad se mantiene en caso sospecha y no en datos del paciente.</li>
                <li>2020-03-25 Traducido resultado covid19.</li>
                <li>2020-03-25 Separados datos demograficos de nuevas sospechas.</li>
                <li>2020-03-25 En editar caso sospecha agregado datos de paciente.</li>
                <li>2020-03-25 Semana epidemiologica se calcula automáticamente.</li>
                <li>2020-03-25 Calcular dígito verificador automaticamente.</li>
                <li>2020-03-24 Agregado campos demograficos al paciente.</li>
            </ul>
        </div>
    </div>
</div>
@endsection
