<!DOCTYPE html>
<html lang="es" dir="ltr">
    <body>
        <div class="justify">
            <div class="card">
                <div class="card-body">
                    <h3>Estimado/a: {{ $suspectCase->patient->FullName }}</h3>

                    <br>

                    <p>A través del presente, se informa el resultado de exámen
                        PCR para el virus SARS CoV-2, causante de la enfermedad
                        Covid-19, realizado el día:
                        {{ $suspectCase->sample_at->format('d-m-Y') }}:</p>

                    <br>

                    <p><strong>Resultado: {{ $suspectCase->Covid19 }}</strong></p>
                    <p><strong>Fecha de resultado</strong>: {{ $suspectCase->pcr_sars_cov_2_at->format('d-m-Y') }}</p>
                    <br>

                    @if($suspectCase->Covid19 == 'Positivo')
                        <p>
                            <!-- <strong>Deberá cumplir una cuarentena por 11 días. </strong> -->
                            La duración de la licencia médica es definida por la autoridad sanitaria
                            <!-- Sin perjuicio de lo anterior, dicho tiempo puede
                            extenderse si no se ha recuperado totalmente de la
                            enfermedad. -->
                        </p>
                        <br>

                        <p>Se le realizará seguimiento telefónico diario por parte
                            del equipo de la unidad de epidemiología de su
                            Establecimiento de Salud.</p>
                        <br>

                        <p>Si requiere licencia médica deberá tomar contacto
                            con el establecimiento que lo diagnosticó.</p>
                    @endif

                    @if($suspectCase->Covid19 == 'Negativo'
                        OR $suspectCase->Covid19 == 'Indeterminado'
                        OR $suspectCase->Covid19 == 'Rechazado')

                        <p>De haber sido calificado como contacto de alto riesgo de
                            algún caso positivo, debe seguir todas las instrucciones
                            que la autoridad sanitaria le indique.</p>

                    @endif

                    <p>De ser necesario licencia médica de quienes sean
                        designados como contacto de alto riesgo, esta será
                        emitida por la SEREMI de Salud.</p>

                    <p>Ante la aparición de síntomas tales como:</p>
                    <ul>
                        <li>Fiebre sobre 37,8 °C.</li>
                        <li>Dolor de cabeza.</li>
                        <li>Tos.</li>
                        <li>Dolor de garganta.</li>
                        <li>Ausencia del gusto.</li>
                    </ul>
                    <p>Debe consultar en su establecimiento de Salud más cercano
                        o llamar al 800 123 766.</p>

                  <hr>
                </div>
            </div>

            <br>

            <p class="texto">
                <span class="linea_firma" style="color: #EE3A43">——</span><span class="linea_firma" style="color: #0168B3">———</span><br>
                <br><br>
                No responder. Correo generado automáticamente a través del {{env('APP_NAME')}}.
            </p>

        </div>

    </body>
</html>
