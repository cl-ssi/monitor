<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">



</head>

<body>

<div class="container">
<br/>
    <h3 class="mb-3"><i class="fas fa-lungs-virus"></i> Consulta de Cuarentena</h3>
    <form method="GET" class="form-horizontal" action="{{ action('TracingController@quarantineCheck') }}">
        <div class="input-group mb-sm-0">
            <input class="form-control" type="text" name="run" autocomplete="off" id="run"
                    maxlength="8" placeholder="RUN (sin dígito verificador)" required>
            <div class="input-group-append">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
            </div>
        </div>
    </form>

    <br/>

    <div class="row">
        <div class="col-12">
            @if($run)
                @if($isQuarantined)
                    <div class="alert alert-danger" role="alert">
                        El rut {{$run}} se encuentra en cuarentena.
                    </div>
                @else
                    <div class="alert alert-success" role="alert">
                        El rut {{$run}} no se encuentra en cuarentena.
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>

<script type="text/javascript">
    // Restricts input for the given textbox to the given inputFilter.
    function setInputFilter(textbox, inputFilter) {
        ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function(event) {
            textbox.addEventListener(event, function() {
                if (inputFilter(this.value)) {
                    this.oldValue = this.value;
                    this.oldSelectionStart = this.selectionStart;
                    this.oldSelectionEnd = this.selectionEnd;
                } else if (this.hasOwnProperty("oldValue")) {
                    this.value = this.oldValue;
                    this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
                } else {
                    this.value = "";
                }
            });
        });
    }

    // Install input filters.
    setInputFilter(document.getElementById("run"), function(value) {
        return /^\d*$/.test(value); });
</script>

</body>
</html>








