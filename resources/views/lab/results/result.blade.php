<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Resultado de examen</title>
        <style>
        h1{
            text-transform: uppercase;
            font-size: 17px;
            text-decoration: underline;
        }
        h2{
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

        #firma {
            font-size: 13px;
            width: 100%;
            text-align: center;
        }

        .cabecera {
            display:inline-block;
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

        <h2 id="peticion">RESULTADO DE EXAMEN N°: 123</h2>

        <table id="demograficos">
            <tr>
                <th>NOMBRE</th>
                <td>ALVARO RAYMUNDO TORRES FUCHSLOCHER</td>
            </tr>
            <tr>
                <th>RUN</th>
                <td>15287582-7</td>
            </tr>
            <tr>
                <th>FECHA NACIMIENTO</th>
                <td>25-02-1982 - 38 AÑOS</td>
            </tr>
            <tr>
                <th>SEXO</th>
                <td>MASCULINO</td>
            </tr>
            <tr>
                <th>ORIGEN</th>
                <td>INFECTOLOGÍA</td>
            </tr>
            <tr>
                <th>FECHA MUESTRA</th>
                <td>04-04-2020 17:22</td>
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
                        <td>SARS-CoV-2 (COVID-19)</td>
                        <td>Negativo</td>
                        <td>[ Negativo ]</td>
                        <td>PCR en tiempo real</td>
                    </tr>
                </tbody>
            </table>

            <p id="tipomuestra">
                Tipo de muestra:<br>
                - Aspirado Nasofaringeo<br>
                - Esputo
            </p>


            <div style="height: 300px;">
            </div>

            <div id="firma">
                <img src="images/firma.jpg" width="140" alt="Firma tecnólogo">
                <br>
                TM DAVID ORTIZ LEIVA<br>
                DIRECTOR TÉCNICO LABORATORIO
            </div>

            <p id="fecha">Fecha y hora informe: 04-04-2020 17:30:11</p>
        </div>
    </body>
</html>
