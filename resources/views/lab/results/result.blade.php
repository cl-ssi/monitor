<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>Resultado de examen</title>
    <style>

        body { font-family: futural; }

        h1 {
            text-transform: uppercase;
            font-size: 17px;
            text-decoration: underline;

        }

        h2 {
            text-transform: uppercase;
            font-size: 15px;
        }

        #demograficos {
            font-size: 13px;
            width: 100%;
        }

        th {
            text-align: left;
        }

        #peticion {
            text-align: center;
        }

        #resultados {
            font-size: 13px;
            width: 100%;
        }

        .firma {
            font-size: 13px;
            width: 100%;
            text-align: center;
            text-transform: uppercase;
        }

        .cabecera {
            display: inline-block;
            vertical-align: top;
        }

        #tipomuestra {
            font-size: 13px;
        }

        #fecha {
            font-size: 13px;
        }
    </style>
</head>

<body>
    <div class="row">
        <div class="cabecera" style="padding-top: 22px;">
            @if(File::exists(public_path("images/lab_{$case->laboratory->id}.png")))
                <img src="{{"images/lab_{$case->laboratory->id}.png"}}" width="150" alt="logo servicio">
            @else
                <img src="images/lab_1.png" width="150" alt="logo servicio">
            @endif
        </div>
        <div class="cabecera" style="padding-left: 10px; @if($case->laboratory->id == 13) padding-top: 18px; @endif ">
            <h1>{{$case->laboratory->name}}</h1>
            <h2 style="line-height: 1px;">LABORATORIO DE BIOLOGÍA MOLECULAR</h2>
        </div>

    </div>

    <h2 id="peticion">RESULTADO DE EXAMEN N°: {{ $case->id }} </h2>

    <table id="demograficos">
        <tr>
            <th>NOMBRE COMPLETO</th>
            <td>{{ $case->patient->fullName }}</td>
        </tr>
        <tr>
            <th>RUN</th>
            <td>{{ $case->patient->identifier }}</td>
        </tr>
        <tr>
            <th>EDAD</th>
            <td>{{$case->age }} AÑOS</td>
        </tr>
        <tr>
            <th>SEXO</th>
            <td>{{ strtoupper($case->patient->sexEsp) }}</td>
        </tr>
        <tr>
            <th>ESTABLECIMIENTO</th>
            <td>{{ strtoupper(($case->establishment)?$case->establishment->alias.' - '.$case->origin: '') }}</td>
        </tr>
        <tr>
            <th>FECHA MUESTRA</th>
            <td>{{ $case->sample_at->format('d-m-Y') }}</td>
        </tr>
    </table>
    <hr>
    <div class="contenido">
        <p>BIOLOGÍA MOLECULAR</p>

        <table id="resultados">
            <thead>
                <tr>
                    <th>PRUEBA</th>
                    <th>RESULTADO</th>
                    <th>R. REFERENCIA</th>
                    <th>MÉTODO</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>SARS-CoV-2</td>
                    <td>{{ $case->covid19 }}</td>
                    <td>[ Negativo ]</td>
                    <td>PCR en tiempo real</td>
                </tr>
            </tbody>
        </table>

        <br>
        <p id="tipomuestra">
            Tipo de muestra:<br>
            - {{ $case->sample_type }}<br>
        </p>


        <div style="height: 240px;">

        </div>

        <table width="100%">
            <tr>
                <td>
                    <div class="firma">
                        <img src="images/firma_user_{{ $case->laboratory->director->id}}.png" width="140" alt="Firma Director Técnico">
                    </div>
                </td>

                @if($case->laboratory->id == 1)
                    <td>
                        <div class="firma">
                            <img src="images/firma_user_{{ $case->validator->id}}.png" width="140"
                                 alt="Firma Validador">
                        </div>
                    </td>
                @endif

                {{-- Lab Arauco --}}
                @if($case->laboratory->id == 9)
                    <td>
                        <div class="firma">
                            <img src="images/firma_tec_arauco.png" width="140"
                                 alt="Firma Validador">
                        </div>
                    </td>
                @endif

            </tr>
            <tr>
                <td class="firma">
                    {{ $case->laboratory->director->name}}
                    <br>
                    DIRECTOR TÉCNICO LABORATORIO
                </td>

                @if($case->laboratory->id == 1)
                    <td class="firma">
                        @if($case->validator)
                            {{ $case->validator->name }}
                        @endif
                        <br>
                        VALIDADOR
                    </td>
                @endif

                {{-- Lab Arauco --}}
                @if($case->laboratory->id == 9)
                    <td class="firma">
                        {{-- @if($case->validator) --}}
                        Mauricio Fuentes Aviles
                            {{-- {{ $case->validator->name }} --}}
                        {{-- @endif --}}
                        <br>
                        VALIDADOR
                    </td>
                @endif

            </tr>
        </table>


        <p id="fecha">Fecha y hora informe: {{ ($case->updated_at)? $case->updated_at->format('d-m-Y H:i'): '' }}</p>
    </div>
</body>

</html>
