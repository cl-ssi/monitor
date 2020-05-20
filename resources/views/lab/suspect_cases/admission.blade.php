@extends('layouts.app')

@section('title', 'Nueva sospecha')

@section('content')
<h3 class="mb-3">Nueva sospecha</h3>

<form method="POST" class="form-horizontal" action="{{ route('lab.suspect_cases.store_admission') }}" enctype="multipart/form-data">
    @csrf
    @method('POST')
    <div class="form-row">
        <fieldset class="form-group col-10 col-md-2">
            <label for="for_run">Run SIN DIGITO VERIF.</label>
            <input type="hidden" class="form-control" id="for_id" name="id">
            <input type="number" class="form-control" id="for_run" name="run">
        </fieldset>

        <fieldset class="form-group col-2 col-md-1">
            <label for="for_dv">Digito</label>
            <input type="text" class="form-control" id="for_dv" name="dv" readonly>
        </fieldset>

        <fieldset class="form-group col-12 col-md-2">
            <label for="for_other_identification">Otra identificación</label>
            <input type="text" class="form-control" id="for_other_identification"
                placeholder="Extranjeros sin run" name="other_identification">
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_gender">Genero</label>
            <select name="gender" id="for_gender" class="form-control">
                <option value="male">Masculino</option>
                <option value="female">Femenino</option>
                <option value="other">Otro</option>
                <option value="unknown">Desconocido</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-6 col-md-3">
            <label for="for_birthday">Fecha Nacimiento</label>
            <input type="date" class="form-control" id="for_birthday"
                name="birthday">
        </fieldset>

    </div>

    <div class="form-row">
        <fieldset class="form-group col-12 col-md-4">
            <label for="for_name">Nombres</label>
            <input type="text" class="form-control" id="for_name" name="name"
                style="text-transform: uppercase;">
        </fieldset>

        <fieldset class="form-group col-12 col-md-3">
            <label for="for_fathers_family">Apellido Paterno</label>
            <input type="text" class="form-control" id="for_fathers_family"
                name="fathers_family" style="text-transform: uppercase;">
        </fieldset>

        <fieldset class="form-group col-12 col-md-3">
            <label for="for_mothers_family">Apellido Materno</label>
            <input type="text" class="form-control" id="for_mothers_family"
                name="mothers_family" style="text-transform: uppercase;">
        </fieldset>


    </div>

    <hr>
    @include('patients.demographic.create')
    <hr>

    <div class="form-row">

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_sample_at">Fecha Muestra</label>
            <input type="date" class="form-control" id="for_sample_at"
                name="sample_at" required min="{{ date('Y-m-d', strtotime("-2 week")) }}" max="{{ date('Y-m-d') }}">
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_sample_type">Tipo de Muestra</label>
            <select name="sample_type" id="for_sample_type" class="form-control">
                <option value=""></option>
                <option value="TÓRULAS NASOFARÍNGEAS">TORULAS NASOFARINGEAS</option>
                <option value="ESPUTO">ESPUTO</option>
                <option value="TÓRULAS NASOFARÍNGEAS/ESPUTO">TÓRULAS NASOFARÍNGEAS/ESPUTO</option>
                <option value="ASPIRADO NASOFARÍNGEO">ASPIRADO NASOFARÍNGEO</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_origin">Origen</label>
            <select name="origin" id="for_origin" class="form-control">
                <option value=""></option>
                @foreach($sampleOrigins as $sampleOrigin)
                    <option value="{{ $sampleOrigin->name }}">{{ $sampleOrigin->alias }}</option>
                @endforeach
            </select>
        </fieldset>

        <fieldset class="form-group col-4 col-md-1">
            <label for="for_age">Edad</label>
            <input type="number" class="form-control" id="for_age" name="age">
        </fieldset>


    </div>

    <div class="form-group">

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="for_gestation"
                name="gestation">
            <label class="form-check-label" for="for_gestation">Gestante</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="for_close_contact"
                name="close_contact">
            <label class="form-check-label" for="for_close_contact">Contacto directo</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="for_discharge_test"
                name="discharge_test">
            <label class="form-check-label" for="for_discharge_test">Test de salida</label>
        </div>

    </div>

    <div class="form-row">

        <fieldset class="form-group col-md-2">
            <label for="for_gestation_week">Semanas de gestación</label>
            <input type="text" class="form-control" name="gestation_week"
                id="for_gestation_week">
        </fieldset>

        <fieldset class="form-group col-4 col-md-1">
            <label for="for_symptoms">Sintomas</label>
            <select name="symptoms" id="for_symptoms" class="form-control">
                <option value=""></option>
                <option value="Si">Si</option>
                <option value="No">No</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-8 col-md-4">
            <label for="for_status">Estado</label>
            <select name="status" id="for_status" class="form-control">
                <option value=""></option>
                <option value="Alta">Alta</option>
                <option value="Ambulatorio">Ambulatorio (domiciliario)</option>
                <option value="Fallecido">Fallecido</option>
                <option value="Fugado">Fugado</option>
                <option value="Hospitalizado Básico">Hospitalizado Básico</option>
                <option value="Hospitalizado Medio">Hospitalizado Medio</option>
                <option value="Hospitalizado UTI">Hospitalizado UTI</option>
                <option value="Hospitalizado UCI">Hospitalizado UCI</option>
                <option value="Residencia Sanitaria">Residencia Sanitaria</option>
            </select>
        </fieldset>

    </div>

    <hr>


    <div class="form-row">

        <fieldset class="form-group col-md-8">
            <label for="for_observation">Observación</label>
            <input type="text" class="form-control" name="observation"
                id="for_observation">
        </fieldset>


        <fieldset class="form-group col-md-2">
            <label for="for_paho_flu">PAHO FLU</label>
            <input type="number" class="form-control" name="paho_flu"
                id="for_paho_flu">
        </fieldset>

        <fieldset class="form-group col-md-2">
            <label for="for_epivigila">Epivigila *</label>
            <input type="number" class="form-control" id="for_epivigila"
                name="epivigila" required>
        </fieldset>


    </div>

    <hr>

    <button type="submit" class="btn btn-primary">Guardar</button>

    <a class="btn btn-outline-secondary" href="{{ route('lab.suspect_cases.index') }}">
        Cancelar
    </a>
</form>

@endsection

@section('custom_js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src='{{asset("js/jquery.rut.chileno.js")}}'></script>
<script type="text/javascript">
jQuery(document).ready(function($){
    //obtiene digito verificador
    $('input[name=run]').keyup(function(e) {
        var str = $("#for_run").val();
        $('#for_dv').val($.rut.dv(str));
    });

$('input[name=run]').change(function() {
    var str = $("#for_run").val();
    $.get('{{ route('patients.get')}}/'+str, function(data) {
        if(data){
            document.getElementById("for_id").value = data.id;
            document.getElementById("for_other_identification").value = data.other_identification;
            document.getElementById("for_gender").value = data.gender;
            document.getElementById("for_birthday").value = data.birthday;
            document.getElementById("for_name").value = data.name;
            document.getElementById("for_fathers_family").value = data.fathers_family;
            document.getElementById("for_mothers_family").value = data.mothers_family;
            document.getElementById("for_status").value = data.status;
        } else {
            document.getElementById("for_id").value = "";
            document.getElementById("for_other_identification").value = "";
            document.getElementById("for_gender").value = "";
            document.getElementById("for_birthday").value = "";
            document.getElementById("for_name").value = "";
            document.getElementById("for_fathers_family").value = "";
            document.getElementById("for_mothers_family").value = "";
        }
    });
});

$('input[name=other_identification]').change(function() {
    var str = $("#for_other_identification").val();
    $.get('{{ route('patients.get')}}/'+str, function(data) {
        if(data){
            document.getElementById("for_id").value = data.id;
            // document.getElementById("for_other_identification").value = data.other_identification;
            document.getElementById("for_gender").value = data.gender;
            document.getElementById("for_birthday").value = data.birthday;
            document.getElementById("for_name").value = data.name;
            document.getElementById("for_fathers_family").value = data.fathers_family;
            document.getElementById("for_mothers_family").value = data.mothers_family;
            document.getElementById("for_status").value = data.status;
        } else {
            document.getElementById("for_id").value = "";
            // document.getElementById("for_other_identification").value = "";
            document.getElementById("for_gender").value = "";
            document.getElementById("for_birthday").value = "";
            document.getElementById("for_name").value = "";
            document.getElementById("for_fathers_family").value = "";
            document.getElementById("for_mothers_family").value = "";
        }
    });
});


});

</script>

<script src="https://js.api.here.com/v3/3.1/mapsjs-core.js" type="text/javascript" charset="utf-8"></script>
<script src="https://js.api.here.com/v3/3.1/mapsjs-service.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
var RegionesYcomunas = {

	"regiones": [{
			"NombreRegion": "Arica y Parinacota",
			"comunas": ["Arica", "Camarones", "Putre", "General Lagos"]
	},
		{
			"NombreRegion": "Tarapacá",
			"comunas": ["Iquique", "Alto Hospicio", "Pozo Almonte", "Camiña", "Colchane", "Huara", "Pica"]
	},
		{
			"NombreRegion": "Antofagasta",
			"comunas": ["Antofagasta", "Mejillones", "Sierra Gorda", "Taltal", "Calama", "Ollagüe", "San Pedro de Atacama", "Tocopilla", "María Elena"]
	},
		{
			"NombreRegion": "Atacama",
			"comunas": ["Copiapó", "Caldera", "Tierra Amarilla", "Chañaral", "Diego de Almagro", "Vallenar", "Alto del Carmen", "Freirina", "Huasco"]
	},
		{
			"NombreRegion": "Coquimbo",
			"comunas": ["La Serena", "Coquimbo", "Andacollo", "La Higuera", "Paiguano", "Vicuña", "Illapel", "Canela", "Los Vilos", "Salamanca", "Ovalle", "Combarbalá", "Monte Patria", "Punitaqui", "Río Hurtado"]
	},
		{
			"NombreRegion": "Valparaíso",
			"comunas": ["Valparaíso", "Casablanca", "Concón", "Juan Fernández", "Puchuncaví", "Quintero", "Viña del Mar", "Isla de Pascua", "Los Andes", "Calle Larga", "Rinconada", "San Esteban", "La Ligua", "Cabildo", "Papudo", "Petorca", "Zapallar", "Quillota", "Calera", "Hijuelas", "La Cruz", "Nogales", "San Antonio", "Algarrobo", "Cartagena", "El Quisco", "El Tabo", "Santo Domingo", "San Felipe", "Catemu", "Llaillay", "Panquehue", "Putaendo", "Santa María", "Quilpué", "Limache", "Olmué", "Villa Alemana"]
	},
		{
			"NombreRegion": "Región del Libertador Gral. Bernardo O’Higgins",
			"comunas": ["Rancagua", "Codegua", "Coinco", "Coltauco", "Doñihue", "Graneros", "Las Cabras", "Machalí", "Malloa", "Mostazal", "Olivar", "Peumo", "Pichidegua", "Quinta de Tilcoco", "Rengo", "Requínoa", "San Vicente", "Pichilemu", "La Estrella", "Litueche", "Marchihue", "Navidad", "Paredones", "San Fernando", "Chépica", "Chimbarongo", "Lolol", "Nancagua", "Palmilla", "Peralillo", "Placilla", "Pumanque", "Santa Cruz"]
	},
		{
			"NombreRegion": "Región del Maule",
			"comunas": ["Talca", "ConsVtución", "Curepto", "Empedrado", "Maule", "Pelarco", "Pencahue", "Río Claro", "San Clemente", "San Rafael", "Cauquenes", "Chanco", "Pelluhue", "Curicó", "Hualañé", "Licantén", "Molina", "Rauco", "Romeral", "Sagrada Familia", "Teno", "Vichuquén", "Linares", "Colbún", "Longaví", "Parral", "ReVro", "San Javier", "Villa Alegre", "Yerbas Buenas"]
	},
		{
			"NombreRegion": "Región del Biobío",
			"comunas": ["Concepción", "Coronel", "Chiguayante", "Florida", "Hualqui", "Lota", "Penco", "San Pedro de la Paz", "Santa Juana", "Talcahuano", "Tomé", "Hualpén", "Lebu", "Arauco", "Cañete", "Contulmo", "Curanilahue", "Los Álamos", "Tirúa", "Los Ángeles", "Antuco", "Cabrero", "Laja", "Mulchén", "Nacimiento", "Negrete", "Quilaco", "Quilleco", "San Rosendo", "Santa Bárbara", "Tucapel", "Yumbel", "Alto Biobío", "Treguaco"]
	},
		{
			"NombreRegion": "Región de la Araucanía",
			"comunas": ["Temuco", "Carahue", "Cunco", "Curarrehue", "Freire", "Galvarino", "Gorbea", "Lautaro", "Loncoche", "Melipeuco", "Nueva Imperial", "Padre las Casas", "Perquenco", "Pitrufquén", "Pucón", "Saavedra", "Teodoro Schmidt", "Toltén", "Vilcún", "Villarrica", "Cholchol", "Angol", "Collipulli", "Curacautín", "Ercilla", "Lonquimay", "Los Sauces", "Lumaco", "Purén", "Renaico", "Traiguén", "Victoria", ]
	},
		{
			"NombreRegion": "Región de Los Ríos",
			"comunas": ["Valdivia", "Corral", "Lanco", "Los Lagos", "Máfil", "Mariquina", "Paillaco", "Panguipulli", "La Unión", "Futrono", "Lago Ranco", "Río Bueno"]
	},
		{
			"NombreRegion": "Región de Los Lagos",
			"comunas": ["Puerto Montt", "Calbuco", "Cochamó", "Fresia", "FruVllar", "Los Muermos", "Llanquihue", "Maullín", "Puerto Varas", "Castro", "Ancud", "Chonchi", "Curaco de Vélez", "Dalcahue", "Puqueldón", "Queilén", "Quellón", "Quemchi", "Quinchao", "Osorno", "Puerto Octay", "Purranque", "Puyehue", "Río Negro", "San Juan de la Costa", "San Pablo", "Chaitén", "Futaleufú", "Hualaihué", "Palena"]
	},
		{
			"NombreRegion": "Región Aisén del Gral. Carlos Ibáñez del Campo",
			"comunas": ["Coihaique", "Lago Verde", "Aisén", "Cisnes", "Guaitecas", "Cochrane", "O’Higgins", "Tortel", "Chile Chico", "Río Ibáñez"]
	},
		{
			"NombreRegion": "Región de Magallanes y de la AntárVca Chilena",
			"comunas": ["Punta Arenas", "Laguna Blanca", "Río Verde", "San Gregorio", "Cabo de Hornos (Ex Navarino)", "AntárVca", "Porvenir", "Primavera", "Timaukel", "Natales", "Torres del Paine"]
	},
		{
			"NombreRegion": "Región Metropolitana de Santiago",
			"comunas": ["Cerrillos", "Cerro Navia", "Conchalí", "El Bosque", "Estación Central", "Huechuraba", "Independencia", "La Cisterna", "La Florida", "La Granja", "La Pintana", "La Reina", "Las Condes", "Lo Barnechea", "Lo Espejo", "Lo Prado", "Macul", "Maipú", "Ñuñoa", "Pedro Aguirre Cerda", "Peñalolén", "Providencia", "Pudahuel", "Quilicura", "Quinta Normal", "Recoleta", "Renca", "San Joaquín", "San Miguel", "San Ramón", "Vitacura", "Puente Alto", "Pirque", "San José de Maipo", "Colina", "Lampa", "TilVl", "San Bernardo", "Buin", "Calera de Tango", "Paine", "Melipilla", "Alhué", "Curacaví", "María Pinto", "San Pedro", "Talagante", "El Monte", "Isla de Maipo", "Padre Hurtado", "Peñaflor"]
	},
		{
			"NombreRegion": "Región de Ñuble",
			"comunas": ["Cobquecura","Coelemu","Ninhue","Portezuelo","Quirihue","Ránquil","Trehuaco","Bulnes","Chillán Viejo","Chillán","El Carmen","Pemuco","Pinto","Quillón","San Ignacio","Yunga","Coihueco","Ñiquén","San Carlos","San Fabián","San Nicolás"]
	}]


}


jQuery(document).ready(function () {

	var iRegion = 0;
	var htmlRegion = '<option value="">Seleccione región</option>';
	var htmlComunas = '<option value="">Seleccione comuna</option>';


	jQuery.each(RegionesYcomunas.regiones, function () {
    htmlRegion = htmlRegion + '<option value="' + RegionesYcomunas.regiones[iRegion].NombreRegion + '">' + RegionesYcomunas.regiones[iRegion].NombreRegion + '</option>';
		iRegion++;
	});
  jQuery('#regiones').html(htmlRegion);
	jQuery('#comunas').html(htmlComunas);
  //si existe demographic


  // caso cuando se cambie manualmente
	jQuery('#regiones').change(function () {
		var iRegiones = 0;
		var valorRegion = jQuery(this).val();
		var htmlComuna = '<option value="sin-comuna">Seleccione comuna</option><option value="sin-comuna">--</option>';
		jQuery.each(RegionesYcomunas.regiones, function () {
			if (RegionesYcomunas.regiones[iRegiones].NombreRegion == valorRegion) {
				var iComunas = 0;
				jQuery.each(RegionesYcomunas.regiones[iRegiones].comunas, function () {
					htmlComuna = htmlComuna + '<option value="' + RegionesYcomunas.regiones[iRegiones].comunas[iComunas] + '">' + RegionesYcomunas.regiones[iRegiones].comunas[iComunas] + '</option>';
					iComunas++;
				});
			}
			iRegiones++;
		});
		jQuery('#comunas').html(htmlComuna);
	});

  //obtener coordenadas
  jQuery('#comunas').change(function () {
    // Instantiate a map and platform object:
    var platform = new H.service.Platform({
      'apikey': '5mKawERqnzL1KMnNIt4n42gAV8eLomjQPKf5S5AAcZg'
    });

    var address = jQuery('#for_address').val();
    var number = jQuery('#for_number').val();
    var regiones = jQuery('#regiones').val();
    var comunas = jQuery('#comunas').val();

    if (address != "" && number != "" && regiones != "Seleccione región" && comunas != "Seleccione comuna") {
      // Create the parameters for the geocoding request:
        var geocodingParams = {
            searchText: address + ' ' + number + ', ' + comunas + ', chile'
          };console.log(geocodingParams);

      // Define a callback function to process the geocoding response:

      jQuery('#for_latitude').val("");
      jQuery('#for_longitude').val("");
      var onResult = function(result) {
        console.log(result);
        var locations = result.Response.View[0].Result;

        // Add a marker for each location found
        for (i = 0;  i < locations.length; i++) {
          //alert(locations[i].Location.DisplayPosition.Latitude);
          jQuery('#for_latitude').val(locations[i].Location.DisplayPosition.Latitude);
          jQuery('#for_longitude').val(locations[i].Location.DisplayPosition.Longitude);
        }
      };

      // Get an instance of the geocoding service:
      var geocoder = platform.getGeocodingService();

      // Error
      geocoder.geocode(geocodingParams, onResult, function(e) {
        alert(e);
      });
    }

  });

});
</script>

@endsection
