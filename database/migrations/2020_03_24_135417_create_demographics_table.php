<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemographicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demographics', function (Blueprint $table) {
            $table->id();

            $table->enum('street_type',['Calle', 'Pasaje', 'Avenida', 'Camino']);
            $table->string('address')->nullable();
            $table->string('number')->nullable();
            $table->string('department')->nullable();
            $table->string('nationality')->nullable();

            $table->unsignedBigInteger('region_id');
            $table->string('region')->nullable();
            // $table->enum('region',['Arica y Parinacota',
            //                        'Tarapacá',
            //                        'Antofagasta',
            //                        'Atacama',
            //                        'Coquimbo',
            //                        'Valparaíso',
            //                        'Región del Libertador Gral. Bernardo O’Higgins',
            //                        'Región del Maule',
            //                        'Región del Biobío',
            //                        'Región de la Araucanía',
            //                        'Región de Los Ríos',
            //                        'Región de Los Lagos',
            //                        'Región Aisén del Gral. Carlos Ibáñez del Campo',
            //                        'Región de Magallanes y de la AntárVca Chilena',
            //                        'Región Metropolitana de Santiago',
            //                        'Región de Ñuble']);

            $table->unsignedBigInteger('commune_id');
            $table->string('commune')->nullable();
            // $table->enum('commune',['Arica', 'Camarones', 'Putre', 'General Lagos','Iquique', 'Alto Hospicio', 'Pozo Almonte', 'Camiña',
            //                         'Colchane', 'Huara', 'Pica','Antofagasta', 'Mejillones', 'Sierra Gorda', 'Taltal', 'Calama', 'Ollagüe',
            //                         'San Pedro de Atacama', 'Tocopilla', 'María Elena','Copiapó', 'Caldera', 'Tierra Amarilla', 'Chañaral', 'Diego de Almagro',
            //                         'Vallenar', 'Alto del Carmen', 'Freirina', 'Huasco','La Serena', 'Coquimbo', 'Andacollo', 'La Higuera', 'Paiguano', 'Vicuña',
            //                         'Illapel', 'Canela', 'Los Vilos', 'Salamanca', 'Ovalle', 'Combarbalá', 'Monte Patria', 'Punitaqui', 'Río Hurtado','Valparaíso',
            //                         'Casablanca', 'Concón', 'Juan Fernández', 'Puchuncaví', 'Quintero', 'Viña del Mar', 'Isla de Pascua', 'Los Andes', 'Calle Larga',
            //                         'Rinconada', 'San Esteban', 'La Ligua', 'Cabildo', 'Papudo', 'Petorca', 'Zapallar', 'Quillota', 'Calera', 'Hijuelas', 'La Cruz',
            //                         'Nogales', 'San Antonio', 'Algarrobo', 'Cartagena', 'El Quisco', 'El Tabo', 'Santo Domingo', 'San Felipe', 'Catemu', 'Llaillay',
            //                         'Panquehue', 'Putaendo', 'Santa María', 'Quilpué', 'Limache', 'Olmué', 'Villa Alemana','Rancagua', 'Codegua', 'Coinco', 'Coltauco',
            //                         'Doñihue', 'Graneros', 'Las Cabras', 'Machalí', 'Malloa', 'Mostazal', 'Olivar', 'Peumo', 'Pichidegua', 'Quinta de Tilcoco', 'Rengo',
            //                         'Requínoa', 'San Vicente', 'Pichilemu', 'La Estrella', 'Litueche', 'Marchihue', 'Navidad', 'Paredones', 'San Fernando', 'Chépica',
            //                         'Chimbarongo', 'Lolol', 'Nancagua', 'Palmilla', 'Peralillo', 'Placilla', 'Pumanque', 'Santa Cruz','Talca', 'ConsVtución', 'Curepto',
            //                         'Empedrado', 'Maule', 'Pelarco', 'Pencahue', 'Río Claro', 'San Clemente', 'San Rafael', 'Cauquenes', 'Chanco', 'Pelluhue', 'Curicó',
            //                         'Hualañé', 'Licantén', 'Molina', 'Rauco', 'Romeral', 'Sagrada Familia', 'Teno', 'Vichuquén', 'Linares', 'Colbún', 'Longaví', 'Parral',
            //                         'ReVro', 'San Javier', 'Villa Alegre', 'Yerbas Buenas','Concepción', 'Coronel', 'Chiguayante', 'Florida', 'Hualqui', 'Lota', 'Penco',
            //                         'San Pedro de la Paz', 'Santa Juana', 'Talcahuano', 'Tomé', 'Hualpén', 'Lebu', 'Arauco', 'Cañete', 'Contulmo', 'Curanilahue', 'Los Álamos',
            //                         'Tirúa', 'Los Ángeles', 'Antuco', 'Cabrero', 'Laja', 'Mulchén', 'Nacimiento', 'Negrete', 'Quilaco', 'Quilleco', 'San Rosendo', 'Santa Bárbara',
            //                         'Tucapel', 'Yumbel', 'Alto Biobío',
            //                         'Treguaco', 'Temuco', 'Carahue', 'Cunco', 'Curarrehue',
            //                         'Freire', 'Galvarino', 'Gorbea', 'Lautaro', 'Loncoche', 'Melipeuco', 'Nueva Imperial', 'Padre las Casas', 'Perquenco', 'Pitrufquén',
            //                         'Pucón', 'Saavedra', 'Teodoro Schmidt', 'Toltén', 'Vilcún', 'Villarrica', 'Cholchol', 'Angol', 'Collipulli', 'Curacautín', 'Ercilla',
            //                         'Lonquimay', 'Los Sauces', 'Lumaco', 'Purén', 'Renaico', 'Traiguén', 'Victoria','Valdivia', 'Corral', 'Lanco', 'Los Lagos', 'Máfil',
            //                         'Mariquina', 'Paillaco', 'Panguipulli', 'La Unión', 'Futrono', 'Lago Ranco', 'Río Bueno','Puerto Montt', 'Calbuco', 'Cochamó',
            //                         'Fresia', 'FruVllar', 'Los Muermos', 'Llanquihue', 'Maullín', 'Puerto Varas', 'Castro', 'Ancud', 'Chonchi', 'Curaco de Vélez',
            //                         'Dalcahue', 'Puqueldón', 'Queilén', 'Quellón', 'Quemchi', 'Quinchao', 'Osorno', 'Puerto Octay', 'Purranque', 'Puyehue', 'Río Negro',
            //                         'San Juan de la Costa', 'San Pablo', 'Chaitén', 'Futaleufú', 'Hualaihué', 'Palena','Coihaique', 'Lago Verde', 'Aisén', 'Cisnes',
            //                         'Guaitecas', 'Cochrane', 'O’Higgins', 'Tortel', 'Chile Chico', 'Río Ibáñez','Punta Arenas', 'Laguna Blanca', 'Río Verde', 'San Gregorio',
            //                         'Cabo de Hornos (Ex Navarino)', 'AntárVca', 'Porvenir', 'Primavera', 'Timaukel', 'Natales', 'Torres del Paine','Cerrillos', 'Cerro Navia',
            //                         'Conchalí', 'El Bosque', 'Estación Central', 'Huechuraba', 'Independencia', 'La Cisterna', 'La Florida', 'La Granja', 'La Pintana',
            //                         'La Reina', 'Las Condes', 'Lo Barnechea', 'Lo Espejo', 'Lo Prado', 'Macul', 'Maipú', 'Ñuñoa', 'Pedro Aguirre Cerda', 'Peñalolén',
            //                         'Providencia', 'Pudahuel', 'Quilicura', 'Quinta Normal', 'Recoleta', 'Renca', 'San Joaquín', 'San Miguel', 'San Ramón', 'Vitacura',
            //                         'Puente Alto', 'Pirque', 'San José de Maipo', 'Colina', 'Lampa', 'TilVl', 'San Bernardo', 'Buin', 'Calera de Tango', 'Paine', 'Melipilla',
            //                         'Alhué', 'Curacaví', 'María Pinto', 'San Pedro', 'Talagante', 'El Monte', 'Isla de Maipo', 'Padre Hurtado', 'Peñaflor',
            //                         'Cobquecura','Coelemu','Ninhue','Portezuelo','Quirihue','Ránquil','Trehuaco','Bulnes','Chillán Viejo','Chillán','El Carmen','Pemuco','Pinto','Quillón','San Ignacio','Yunga','Coihueco','Ñiquén','San Carlos','San Fabián','San Nicolás']);
            $table->string('town')->nullable();

            $table->decimal('latitude', 11, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            $table->string('telephone')->nullable();
            $table->string('telephone2')->nullable();
            $table->string('email')->nullable();
            $table->unsignedBigInteger('patient_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('patient_id')->references('id')->on('patients');
            $table->foreign('region_id')->references('id')->on('regions');
            $table->foreign('commune_id')->references('id')->on('communes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('demographics');
    }
}
