<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameCovid19sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('covid19s', 'SARS_CoV_2_External');

        Schema::table('SARS_CoV_2_External', function (Blueprint $table){
            $table->renameColumn('commune', 'commune_id');
        });

        Schema::table('SARS_CoV_2_External', function (Blueprint $table){
            $table->renameColumn('origin', 'establishment_id');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('SARS_CoV_2_External', 'covid19s');

        Schema::table('covid19s', function (Blueprint $table){
            $table->renameColumn('commune_id', 'commune');
        });

        Schema::table('covid19s', function (Blueprint $table){
            $table->renameColumn('establishment_id', 'origin');
        });
    }
}
