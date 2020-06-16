<!DOCTYPE html>
<html lang="es" dir="ltr">
    <body>
        <strong>{{ env('APP_NAME') }}</strong><br>
        <hr>
        <div class="justify">
            <div class="card">
                <div class="card-body">
                    <h3>Estimado/a: {{ $suspectCase->patient->FullName }}</h3><br>
                    <p>Junto con saludar, a través del presente se informa el resultado de su examen PCR, cuya muestra fue realizada el día {{ $suspectCase->sample_at->format('d-m-Y') }}:</p>
                    <br>
                    <p><strong>Fecha Resultado</strong>: {{ $suspectCase->pscr_sars_cov_2_at->format('d-m-Y') }}</p>
                    <p><strong>Resultado: {{ $suspectCase->Covid19 }}</strong></p>
                    <br>
                    <p>Atte.</p>
                </div>
            </div>

            <br>

            <p class="texto">
                <span class="linea_firma" style="color: #EE3A43">——</span><span class="linea_firma" style="color: #0168B3">———</span><br>
                <br><br>
                Correo Automático generado por: {{ env('APP_NAME') }}
            </p>

        </div>

    </body>
</html>
