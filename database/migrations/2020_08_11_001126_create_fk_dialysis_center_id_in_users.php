<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFkDialysisCenterIdInUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->foreignId('dialysis_center_id')->after('laboratory_id')->nullable();
            $table->foreign('dialysis_center_id')->references('id')->on('dialysis_centers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropForeign(['dialysis_center_id']);
            $table->dropColumn('dialysis_center_id');

        });
    }
}
