<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModSarsCov2ExternalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('SARS_CoV_2_External', function (Blueprint $table){

            $table->foreignId('commune_id')->nullable()->change();
            $table->foreignId('establishment_id')->change();

            $table->unsignedBigInteger('minsal_ws_id')->after('validator_id')->nullable();
            $table->string('run_medic')->after('minsal_ws_id')->nullable();
            $table->string('run_responsible')->after('run_medic')->nullable();
            $table->dropColumn('origin_commune');

            $table->foreign('commune_id')->references('id')->on('communes');
            $table->foreign('establishment_id')->references('id')->on('establishments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('SARS_CoV_2_External', function (Blueprint $table){

            $table->dropForeign('sars_cov_2_external_commune_id_foreign');
            $table->dropForeign('sars_cov_2_external_establishment_id_foreign');

            $table->dropColumn('minsal_ws_id');
            $table->dropColumn('run_medic');
            $table->dropColumn('run_responsible');
            $table->string('origin_commune');

        });

        Schema::table('SARS_CoV_2_External', function (Blueprint $table){

            $table->string('commune_id')->nullable()->change();
            $table->string('establishment_id')->change();

        });
    }
}
