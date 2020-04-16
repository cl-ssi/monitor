<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Resultado de examen</title>
    <style>
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
            <img src="https://i.saludiquique.cl/images/SSI_RGB_200.png" width="150" alt="logo servicio">
        </div>
        <div class="cabecera" style="padding-left: 10px;">
            <h1>SERVICIO DE SALUD IQUIQUE / UNAP</h1>
            <h2>LABORATORIO DE BIOLOGÍA MOLECULAR</h2>
        </div>

    </div>

    <h2 id="peticion">RESULTADO DE EXAMEN N°: {{$case->id}} </h2>

    <table id="demograficos">
        <tr>
            <th>NOMBRE COMPLETO</th>
            <td>{{$case->patient->fullName }}</td>
        </tr>
        <tr>
            <th>RUN</th>
            <td>{{$case->patient->identifier}}</td>
        </tr>
        <tr>
            <th>EDAD</th>
            <td>{{$case->age }} AÑOS</td>
        </tr>
        <tr>
            <th>SEXO</th>
            <td>{{strtoupper($case->patient->genderEsp)}}</td>
        </tr>
        <tr>
            <th>ORIGEN</th>
            <td>{{strtoupper($case->origin)}}</td>
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


        <div style="height: 250px;">

        </div>

        <table width="100%">
            <tr>
                <td>
                    <div class="firma">
                        <img src="images/firma_juan_moreno.png" width="140" alt="Firma tecnólogo">
                    </div>
                </td>

                <td>
                    <div class="firma">
                        <!--img src="images/firma.jpg" width="140" alt="Firma tecnólogo"-->
                    </div>
                </td>

            </tr>
            <tr>
                <td class="firma">
                    DR. JUAN MORENO SAAVEDRA
                    <br>
                    DIRECTOR TÉCNICO LABORATORIO
                </td>
                <td class="firma">
                    @if($case->validator)
                    TM. {{ strtoupper($case->validator->name) }}
                    @endif
                    <br>
                    VALIDADOR
                </td>
            </tr>
        </table>


        <p id="fecha">Fecha y hora informe: {{ ($case->updated_at)? $case->updated_at->format('d-m-Y H:i'): '' }}</p>
    </div>
</body>

</html>
