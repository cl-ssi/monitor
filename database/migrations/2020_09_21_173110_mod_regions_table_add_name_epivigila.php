<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModRegionsTableAddNameEpivigila extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('regions', function (Blueprint $table){
           $table->string('name_epivigila')->after('name')->nullable();
        });

        $results = DB::table('regions')->select('id')->get();

        DB::table('regions')->where('id', 1)->update(['name_epivigila' => 'De Tarapacá']);
        DB::table('regions')->where('id', 2)->update(['name_epivigila' => 'De Antofagasta']);
        DB::table('regions')->where('id', 3)->update(['name_epivigila' => 'De Atacama']);
        DB::table('regions')->where('id', 4)->update(['name_epivigila' => 'De Coquimbo']);
        DB::table('regions')->where('id', 5)->update(['name_epivigila' => 'De Valparaíso']);
        DB::table('regions')->where('id', 6)->update(['name_epivigila' => 'Del Libertador B. O\'Higgins']);
        DB::table('regions')->where('id', 7)->update(['name_epivigila' => 'Del Maule']);
        DB::table('regions')->where('id', 8)->update(['name_epivigila' => 'Del Bíobío']);
        DB::table('regions')->where('id', 9)->update(['name_epivigila' => 'De La Araucanía']);
        DB::table('regions')->where('id', 10)->update(['name_epivigila' => 'De Los Lagos']);
        DB::table('regions')->where('id', 11)->update(['name_epivigila' => 'De Aisén del Gral. C. Ibáñez del Campo']);
        DB::table('regions')->where('id', 12)->update(['name_epivigila' => 'De Magallanes y de La Antártica Chilena']);
        DB::table('regions')->where('id', 13)->update(['name_epivigila' => 'Metropolitana de Santiago']);
        DB::table('regions')->where('id', 14)->update(['name_epivigila' => 'De Los Ríos']);
        DB::table('regions')->where('id', 15)->update(['name_epivigila' => 'De Arica y Parinacota']);
        DB::table('regions')->where('id', 16)->update(['name_epivigila' => 'De Ñuble']);



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
