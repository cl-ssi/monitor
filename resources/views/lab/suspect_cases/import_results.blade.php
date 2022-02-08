@extends('layouts.app')

@section('title', 'Listado de casos')

@section('content')

    <div class="card">
        <div class="card-body">
            <h5>Formato de archivo excel</h5>
            <em>Debe respetar el nombre de las columnas.</em><br>

            <div class="container-fluid">
                <table id="productSizes" class="table table-sm">
                    <thead>
                    <tr class="d-flex">
                        <th class="col-3"></th>
                        <th class="col-2">id esmeralda</th>
                        <th class="col-2">resultado</th>
                        <th class="col-2">fecha resultado</th>
                        <th class="col-3"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="d-flex">
                        <td class="col-3"></td>
                        <td class="col-2">1</td>
                        <td class="col-2">pending</td>
                        <td class="col-2">13-07-2020</td>
                        <td class="col-3"></td>
                    </tr>
                    <tr class="d-flex">
                        <td class="col-3"></td>
                        <td class="col-2">2</td>
                        <td class="col-2">negative</td>
                        <td class="col-2">14-07-2020</td>
                        <td class="col-3"></td>
                    </tr>
                    <tr class="d-flex">
                        <td class="col-3"></td>
                        <td class="col-2">3</td>
                        <td class="col-2">positive</td>
                        <td class="col-2">15-07-2020</td>
                        <td class="col-3"></td>
                    </tr>
                    <tr class="d-flex">
                        <td class="col-3"></td>
                        <td class="col-2">4</td>
                        <td class="col-2">undetermined</td>
                        <td class="col-2">11-07-2020</td>
                        <td class="col-3"></td>
                    </tr>
                    <tr class="d-flex">
                        <td class="col-3"></td>
                        <td class="col-2">5</td>
                        <td class="col-2">rejected</td>
                        <td class="col-2">01-07-2020</td>
                        <td class="col-3"></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div><br/>

    <div class="card">
        <div class="card-body">
            <h5>Carga Masiva Resultados</h5>
            <br>
            <form action="{{ route('lab.suspect_cases.results_import') }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('POST')

                <div class="row">
                    <div class="col-3">
                        <select name="upload_to_pntm" id="for_upload_to_pntm" class="form-control">
                            <option value=1>Cargar a PNTM</option>
                            <option value=0>NO cargar a PNTM</option>
                        </select>
                    </div>
                    <div class="col-3">
                        <select name="generate_pdf" id="for_generate_pdf" class="form-control">
                            <option value=1>Generar pdf laboratorio</option>
                            <option value=0>No generar pdf laboratorio</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <div class="custom-file mb-3">
                            <input type="file" class="custom-file-input" name="file" required>
                            <label class="custom-file-label" for="customFile" data-browse="Elegir">Seleccione el archivo
                                excel...</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <select name="send_email" id="for_send_email" class="form-control">
                            <option value=1>Enviar correo de resultado a pacientes</option>
                            <option value=0>No enviar correos.</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <button class="btn btn-primary float-right mb-3"><i class="fas fa-upload"></i> Cargar</button>
                </div>

            </form>
        </div>
    </div>

@endsection

@section('custom_js')

    <script>
        // Add the following code if you want the name of the file appear on select
        $(".custom-file-input").on("change", function () {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
    </script>

@endsection
