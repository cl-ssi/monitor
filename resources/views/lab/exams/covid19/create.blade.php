@extends('layouts.app')

@section('title', 'Agregar nueva muestra')

@section('content')
<h3 class="mb-3">Agregar nueva muestra</h3>

<form method="POST" class="form-horizontal" action="{{ route('lab.exams.covid19.store') }}">
    @csrf
    @method('POST')

    <div class="form-row">
        <fieldset class="form-group col-md-2">
            <label for="for_run">Run (sin digito)</label>
            <input type="text" class="form-control" name="run" id="for_run">
        </fieldset>

        <fieldset class="form-group col-md-1">
            <label for="for_dv">Digito</label>
            <input type="text" class="form-control" name="dv" id="for_dv">
        </fieldset>

        <fieldset class="form-group col-1 col-md-1">
            <label for="">&nbsp;</label>
            <button type="button" class="btn btn-outline-success" disabled>Fonasa&nbsp;</button>
        </fieldset>

        <fieldset class="form-group col-1 col-md-1">
            <label for="">&nbsp;</label>
            <span class="form-control-plaintext"> </span>
        </fieldset>

        <fieldset class="form-group col-md-2">
            <label for="for_other_identification">Otra identificación</label>
            <input type="text" class="form-control" name="other_identification"
                id="for_other_identification" placeholder="Extranjeros">
        </fieldset>

    </div>
    <div class="form-row">

        <fieldset class="form-group col-md-3">
            <label for="for_name">Nombre *</label>
            <input type="text" class="form-control" name="name" id="for_name"
                required>
        </fieldset>

        <fieldset class="form-group col-md-2">
            <label for="for_fathers_family">Apellido Paterno *</label>
            <input type="text" class="form-control" name="fathers_family"
                id="for_fathers_family" required>
        </fieldset>

        <fieldset class="form-group col-md-2">
            <label for="for_mothers_family">Apellido Materno</label>
            <input type="text" class="form-control" name="mothers_family"
                id="for_mothers_family">
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_gender">Genero *</label>
            <select name="gender" id="for_gender" class="form-control" required>
                <option value=""></option>
                <option value="male">Masculino</option>
                <option value="female">Femenino</option>
                <option value="other">Otro</option>
                <option value="unknown">Desconocido</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-6 col-md-2">
            <label for="for_birthday">Fecha Nacimiento *</label>
            <input type="date" class="form-control" id="for_birthday"
                name="birthday" required>
        </fieldset>

    </div>
    <div class="form-row">

        <fieldset class="form-group col-md-3">
            <label for="for_email">Email</label>
            <input type="text" class="form-control" name="email" id="for_email">
        </fieldset>

        <fieldset class="form-group col-md-2">
            <label for="for_telephone">Telefono</label>
            <input type="text" class="form-control" name="telephone" id="for_telephone">
        </fieldset>

        <fieldset class="form-group col-md-4">
            <label for="for_address">Dirección</label>
            <input type="text" class="form-control" name="address" id="for_address">
        </fieldset>

        <fieldset class="form-group col">
            <label for="for_commune">Comuna *</label>
            <select name="commune" id="for_commune" class="form-control" required>
                <option value=""></option>
                <option>Algarrobo</option>
                <option>Alhué</option>
                <option>Alto Biobío</option>
                <option>Alto del Carmen</option>
                <option>Alto Hospicio</option>
                <option>Ancud</option>
                <option>Andacollo</option>
                <option>Angol</option>
                <option>Antártica</option>
                <option>Antofagasta</option>
                <option>Antuco</option>
                <option>Arauco</option>
                <option>Arica</option>
                <option>Aysén</option>
                <option>Buin</option>
                <option>Bulnes</option>
                <option>Cabildo</option>
                <option>Cabo de Hornos</option>
                <option>Cabrero</option>
                <option>Calama</option>
                <option>Calbuco</option>
                <option>Caldera</option>
                <option>Calera de Tango</option>
                <option>Calle Larga</option>
                <option>Camarones</option>
                <option>Camiña</option>
                <option>Canela</option>
                <option>Cañete</option>
                <option>Carahue</option>
                <option>Cartagena</option>
                <option>Casablanca</option>
                <option>Castro</option>
                <option>Catemu</option>
                <option>Cauquenes</option>
                <option>Cerrillos</option>
                <option>Cerro Navia</option>
                <option>Chaitén</option>
                <option>Chanco</option>
                <option>Chañaral</option>
                <option>Chépica</option>
                <option>Chiguayante</option>
                <option>Chile Chico</option>
                <option>Chillán</option>
                <option>Chillán Viejo</option>
                <option>Chimbarongo</option>
                <option>Cholchol</option>
                <option>Chonchi</option>
                <option>Cisnes</option>
                <option>Cobquecura</option>
                <option>Cochamó</option>
                <option>Cochrane</option>
                <option>Codegua</option>
                <option>Coelemu</option>
                <option>Coihueco</option>
                <option>Coinco</option>
                <option>Colbún</option>
                <option>Colchane</option>
                <option>Colina</option>
                <option>Collipulli</option>
                <option>Coltauco</option>
                <option>Combarbalá</option>
                <option>Concepción</option>
                <option>Conchalí</option>
                <option>Concón</option>
                <option>Constitución</option>
                <option>Contulmo</option>
                <option>Copiapó</option>
                <option>Coquimbo</option>
                <option>Coronel</option>
                <option>Corral</option>
                <option>Coyhaique</option>
                <option>Cunco</option>
                <option>Curacautín</option>
                <option>Curacaví</option>
                <option>Curaco de Vélez</option>
                <option>Curanilahue</option>
                <option>Curarrehue</option>
                <option>Curepto</option>
                <option>Curicó</option>
                <option>Dalcahue</option>
                <option>Diego de Almagro</option>
                <option>Doñihue</option>
                <option>El Bosque</option>
                <option>El Carmen</option>
                <option>El Monte</option>
                <option>El Quisco</option>
                <option>El Tabo</option>
                <option>Empedrado</option>
                <option>Ercilla</option>
                <option>Estación Central</option>
                <option>Florida</option>
                <option>Freire</option>
                <option>Freirina</option>
                <option>Fresia</option>
                <option>Frutillar</option>
                <option>Futaleufú</option>
                <option>Futrono</option>
                <option>Galvarino</option>
                <option>General Lagos</option>
                <option>Gorbea</option>
                <option>Graneros</option>
                <option>Guaitecas</option>
                <option>Hijuelas</option>
                <option>Hualaihué</option>
                <option>Hualañé</option>
                <option>Hualpén</option>
                <option>Hualqui</option>
                <option>Huara</option>
                <option>Huasco</option>
                <option>Huechuraba</option>
                <option>Illapel</option>
                <option>Independencia</option>
                <option>Iquique</option>
                <option>Isla de Maipo</option>
                <option>Isla de Pascua</option>
                <option>Juan Fernández</option>
                <option>La Calera</option>
                <option>La Cisterna</option>
                <option>La Cruz</option>
                <option>La Estrella</option>
                <option>La Florida</option>
                <option>La Granja</option>
                <option>La Higuera</option>
                <option>La Ligua</option>
                <option>La Pintana</option>
                <option>La Reina</option>
                <option>La Serena</option>
                <option>La Unión</option>
                <option>Lago Ranco</option>
                <option>Lago Verde</option>
                <option>Laguna Blanca</option>
                <option>Laja</option>
                <option>Lampa</option>
                <option>Lanco</option>
                <option>Las Cabras</option>
                <option>Las Condes</option>
                <option>Lautaro</option>
                <option>Lebu</option>
                <option>Licantén</option>
                <option>Limache</option>
                <option>Linares</option>
                <option>Litueche</option>
                <option>Llanquihue</option>
                <option>Llay-Llay</option>
                <option>Lo Barnechea</option>
                <option>Lo Espejo</option>
                <option>Lo Prado</option>
                <option>Lolol</option>
                <option>Loncoche</option>
                <option>Longaví</option>
                <option>Lonquimay</option>
                <option>Los Álamos</option>
                <option>Los Andes</option>
                <option>Los Ángeles</option>
                <option>Los Lagos</option>
                <option>Los Muermos</option>
                <option>Los Sauces</option>
                <option>Los Vilos</option>
                <option>Lota</option>
                <option>Lumaco</option>
                <option>Machalí</option>
                <option>Macul</option>
                <option>Máfil</option>
                <option>Maipú</option>
                <option>Malloa</option>
                <option>Marchihue</option>
                <option>María Elena</option>
                <option>María Pinto</option>
                <option>Mariquina</option>
                <option>Maule</option>
                <option>Maullín</option>
                <option>Mejillones</option>
                <option>Melipeuco</option>
                <option>Melipilla</option>
                <option>Molina</option>
                <option>Monte Patria</option>
                <option>Mostazal</option>
                <option>Mulchén</option>
                <option>Nacimiento</option>
                <option>Nancagua</option>
                <option>Natales</option>
                <option>Navidad</option>
                <option>Negrete</option>
                <option>Ninhue</option>
                <option>Nogales</option>
                <option>Nueva Imperial</option>
                <option>Ñiquén</option>
                <option>Ñuñoa</option>
                <option>O'Higgins</option>
                <option>Olivar</option>
                <option>Ollagüe</option>
                <option>Olmué</option>
                <option>Osorno</option>
                <option>Ovalle</option>
                <option>Padre Hurtado</option>
                <option>Padre Las Casas</option>
                <option>Paihuano</option>
                <option>Paillaco</option>
                <option>Paine</option>
                <option>Palena</option>
                <option>Palmilla</option>
                <option>Panguipulli</option>
                <option>Panquehue</option>
                <option>Papudo</option>
                <option>Paredones</option>
                <option>Parral</option>
                <option>Pedro Aguirre Cerda</option>
                <option>Pelarco</option>
                <option>Pelluhue</option>
                <option>Pemuco</option>
                <option>Pencahue</option>
                <option>Penco</option>
                <option>Peñaflor</option>
                <option>Peñalolén</option>
                <option>Peralillo</option>
                <option>Perquenco</option>
                <option>Petorca</option>
                <option>Peumo</option>
                <option>Pica</option>
                <option>Pichidegua</option>
                <option>Pichilemu</option>
                <option>Pinto</option>
                <option>Pirque</option>
                <option>Pitrufquén</option>
                <option>Placilla</option>
                <option>Portezuelo</option>
                <option>Porvenir</option>
                <option>Pozo Almonte</option>
                <option>Primavera</option>
                <option>Providencia</option>
                <option>Puchuncaví</option>
                <option>Pucón</option>
                <option>Pudahuel</option>
                <option>Puente Alto</option>
                <option>Puerto Montt</option>
                <option>Puerto Octay</option>
                <option>Puerto Varas</option>
                <option>Pumanque</option>
                <option>Punitaqui</option>
                <option>Punta Arenas</option>
                <option>Puqueldón</option>
                <option>Purén</option>
                <option>Purranque</option>
                <option>Putaendo</option>
                <option>Putre</option>
                <option>Puyehue</option>
                <option>Queilén</option>
                <option>Quellón</option>
                <option>Quemchi</option>
                <option>Quilaco</option>
                <option>Quilicura</option>
                <option>Quilleco</option>
                <option>Quillón</option>
                <option>Quillota</option>
                <option>Quilpué</option>
                <option>Quinchao</option>
                <option>Quinta de Tilcoco</option>
                <option>Quinta Normal</option>
                <option>Quintero</option>
                <option>Quirihue</option>
                <option>Rancagua</option>
                <option>Ránquil</option>
                <option>Rauco</option>
                <option>Recoleta</option>
                <option>Renaico</option>
                <option>Renca</option>
                <option>Rengo</option>
                <option>Requínoa</option>
                <option>Retiro</option>
                <option>Rinconada</option>
                <option>Río Bueno</option>
                <option>Río Claro</option>
                <option>Río Hurtado</option>
                <option>Río Ibáñez</option>
                <option>Río Negro</option>
                <option>Río Verde</option>
                <option>Romeral</option>
                <option>Saavedra</option>
                <option>Sagrada Familia</option>
                <option>Salamanca</option>
                <option>San Antonio</option>
                <option>San Bernardo</option>
                <option>San Carlos</option>
                <option>San Clemente</option>
                <option>San Esteban</option>
                <option>San Fabián</option>
                <option>San Felipe</option>
                <option>San Fernando</option>
                <option>San Gregorio</option>
                <option>San Ignacio</option>
                <option>San Javier</option>
                <option>San Joaquín</option>
                <option>San José de Maipo</option>
                <option>San Juan de la Costa</option>
                <option>San Miguel</option>
                <option>San Nicolás</option>
                <option>San Pablo</option>
                <option>San Pedro</option>
                <option>San Pedro de Atacama</option>
                <option>San Pedro de La Paz</option>
                <option>San Rafael</option>
                <option>San Ramón</option>
                <option>San Rosendo</option>
                <option>San Vicente</option>
                <option>Santa Bárbara</option>
                <option>Santa Cruz</option>
                <option>Santa Juana</option>
                <option>Santa María</option>
                <option>Santiago</option>
                <option>Santo Domingo</option>
                <option>Sierra Gorda</option>
                <option>Talagante</option>
                <option>Talca</option>
                <option>Talcahuano</option>
                <option>Taltal</option>
                <option>Temuco</option>
                <option>Teno</option>
                <option>Teodoro Schmidt</option>
                <option>Tierra Amarilla</option>
                <option>Til Til</option>
                <option>Timaukel</option>
                <option>Tirúa</option>
                <option>Tocopilla</option>
                <option>Toltén</option>
                <option>Tomé</option>
                <option>Torres del Paine</option>
                <option>Tortel</option>
                <option>Traiguén</option>
                <option>Treguaco</option>
                <option>Tucapel</option>
                <option>Valdivia</option>
                <option>Vallenar</option>
                <option>Valparaíso</option>
                <option>Vichuquén</option>
                <option>Victoria</option>
                <option>Vicuña</option>
                <option>Vilcún</option>
                <option>Villa Alegre</option>
                <option>Villa Alemana</option>
                <option>Villarrica</option>
                <option>Viña del Mar</option>
                <option>Vitacura</option>
                <option>Yerbas Buenas</option>
                <option>Yumbel</option>
                <option>Yungay</option>
                <option>Zapallar</option>

            </select>
        </fieldset>


    </div>

    <hr>

    <div class="form-row">

        <fieldset class="form-group col-3">
            <label for="for_origin">Origen *</label>
            <input type="text" class="form-control" name="origin" id="for_origin"
                required placeholder="EJ: Hospital Erneso Torres">
        </fieldset>


        <fieldset class="form-group col-3">
            <label for="for_origin_commune">Comuna Origen</label>
            <select name="origin_commune" id="for_origin_commune" class="form-control" readonly>
                <option value="Coquimbo">Coquimbo</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-6 col-md-3">
            <label for="for_sample_type">Tipo de Muestra *</label>
            <select name="sample_type" id="for_sample_type" class="form-control" required>
                <option value=""></option>
                <option value="TÓRULAS NASOFARÍNGEAS">TORULAS NASOFARINGEAS</option>
                <option value="ESPUTO">ESPUTO</option>
                <option value="TÓRULAS NASOFARÍNGEAS/ESPUTO">TÓRULAS NASOFARÍNGEAS/ESPUTO</option>
                <option value="ASPIRADO NASOFARÍNGEO">ASPIRADO NASOFARÍNGEO</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-3">
            <label for="for_sample_at">Fecha de muestra *</label>
            <input type="datetime-local" class="form-control" name="sample_at"
                id="for_sample_at" required value="{{ date('Y-m-d\TH:i:s') }}">
        </fieldset>

    </div>

    <button type="submit" class="btn btn-primary">Guardar</button>

</form>

@endsection

@section('custom_js')

@endsection
