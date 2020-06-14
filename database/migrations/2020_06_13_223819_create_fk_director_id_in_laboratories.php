<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFkDirectorIdInLaboratories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('laboratories', function (Blueprint $table) {
            //
            $table->foreignId('director_id')->after('commune_id')->nullable();
            $table->foreign('director_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {   //drop the key
        Schema::table('laboratories', function (Blueprint $table) {
            //
            $table->dropForeign(['director_id']);

        });

        //drop the column
        Schema::table('laboratories', function (Blueprint $table) {
            //
            $table->dropColumn('director_id');

        });


    }
}
