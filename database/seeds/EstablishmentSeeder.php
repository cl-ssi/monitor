<?php

use App\Establishment;
use Illuminate\Database\Seeder;

class EstablishmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      Establishment::Create(['name'=>'Ernesto Torres Galdames','type'=>'HOSPITAL','deis'=>'102-100','commune_id' => 6]);
      Establishment::Create(['name'=>'Cirujano Aguirre','type'=>'CESFAM','deis'=>'102-300','commune_id' => 6]);
      Establishment::Create(['name'=>'Cirujano Videla','type'=>'CESFAM','deis'=>'102-301','commune_id' => 6]);
      Establishment::Create(['name'=>'Cirujano Guzmán','type'=>'CESFAM','deis'=>'102-302','commune_id' => 6]);
      Establishment::Create(['name'=>'Sur de Iquique','type'=>'CESFAM','deis'=>'102-306','commune_id' => 6]);
      Establishment::Create(['name'=>'Cerro Esmeralda','type'=>'CECOSF','deis'=>'102-701','commune_id' => 6]);
      Establishment::Create(['name'=>'Chanavayita','type'=>'PSR','deis'=>'102-412','commune_id' => 6]);
      Establishment::Create(['name'=>'San Marcos','type'=>'PSR','deis'=>'102-413','commune_id' => 6]);
      Establishment::Create(['name'=>'Iquique','type'=>'PRAIS','deis'=>'102-011','commune_id' => 6]);
      Establishment::Create(['name'=>'Pedro Pulgar','type'=>'CESFAM','deis'=>'102-305','commune_id' => 7]);
      Establishment::Create(['name'=>'Pedro Pulgar','type'=>'SAPU','deis'=>'102-805','commune_id' => 7]);
      Establishment::Create(['name'=>'Dr. Héctor Reyno G.','type'=>'CESFAM','deis'=>'102-307','commune_id' => 7]);
      Establishment::Create(['name'=>'El Boro','type'=>'CECOSF','deis'=>'102-705','commune_id' => 7]);
      Establishment::Create(['name'=>'La Tortuga','type'=>'CECOSF','deis'=>'200-335','commune_id' => 7]);
      Establishment::Create(['name'=>'Camiña','type'=>'CGR','deis'=>'102-309','commune_id' => 3]);
      Establishment::Create(['name'=>'Moquella','type'=>'PSR','deis'=>'102-408','commune_id' => 3]);
      Establishment::Create(['name'=>'Colchane','type'=>'CGR','deis'=>'102-310','commune_id' => 1]);
      Establishment::Create(['name'=>'Enquelga','type'=>'PSR','deis'=>'102-409','commune_id' => 1]);
      Establishment::Create(['name'=>'Cariquima','type'=>'PSR','deis'=>'102-415','commune_id' => 1]);
      Establishment::Create(['name'=>'Huara','type'=>'CGR','deis'=>'102-308','commune_id' => 2]);
      Establishment::Create(['name'=>'Pisagua','type'=>'PSR','deis'=>'102-401','commune_id' => 2]);
      Establishment::Create(['name'=>'Tarapacá','type'=>'PSR','deis'=>'102-402','commune_id' => 2]);
      Establishment::Create(['name'=>'Pachica','type'=>'PSR','deis'=>'','commune_id' => 2]);
      Establishment::Create(['name'=>'Chiapa','type'=>'PSR','deis'=>'102-407','commune_id' => 2]);
      Establishment::Create(['name'=>'Sibaya','type'=>'PSR','deis'=>'102-410','commune_id' => 2]);
      Establishment::Create(['name'=>'Pica','type'=>'CESFAM','deis'=>'102-304','commune_id' => 5]);
      Establishment::Create(['name'=>'Cancosa','type'=>'PSR','deis'=>'102-411','commune_id' => 5]);
      Establishment::Create(['name'=>'Matilla','type'=>'PSR','deis'=>'102-416','commune_id' => 5]);
      Establishment::Create(['name'=>'Pozo Almonte','type'=>'CESFAM','deis'=>'102-303','commune_id' => 4]);
      Establishment::Create(['name'=>'Mamiña','type'=>'PSR','deis'=>'102-403','commune_id' => 4]);
      Establishment::Create(['name'=>'La Tirana','type'=>'PSR','deis'=>'102-406','commune_id' => 4]);
      Establishment::Create(['name'=>'La Huayca','type'=>'PSR','deis'=>'102-414','commune_id' => 4]);
      Establishment::Create(['name'=>'Pozo Almonte','type'=>'SAPU','deis'=>'102-803','commune_id' => 4]);
      Establishment::Create(['name'=>'Jorge Seguel C.','type'=>'COSAM','deis'=>'102-600','commune_id' => 6]);
      Establishment::Create(['name'=>'Salvador Allende','type'=>'COSAM','deis'=>'102-601','commune_id' => 6]);
      Establishment::Create(['name'=>'Dr. Enrique Paris','type'=>'COSAM','deis'=>'102-602','commune_id' => 7]);
    }
}
