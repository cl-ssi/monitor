<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModRegionsTableIds extends Migration
{
    /**
     * Run the migrations. Se actualizan ids para que corresponda con estándar DEIS
     *
     * @return void
     */
    public function up()
    {
        Schema::table('demographics', function (Blueprint $table) {
            $table->dropForeign(['region_id']);
        });

        Schema::table('communes', function (Blueprint $table) {
            $table->dropForeign(['region_id']);
        });

        Schema::table('demographics', function (Blueprint $table) {
            $table->foreign('region_id')->references('id')->on('regions')->onUpdate('cascade');
        });

        Schema::table('communes', function (Blueprint $table) {
            $table->foreign('region_id')->references('id')->on('regions')->onUpdate('cascade');
        });

        DB::statement(" UPDATE regions SET id = 20 WHERE id = 6 ");
        DB::statement(" UPDATE regions SET id = 21 WHERE id = 7 ");
        DB::statement(" UPDATE regions SET id = 22 WHERE id = 8 ");
        DB::statement(" UPDATE regions SET id = 23 WHERE id = 9 ");
        DB::statement(" UPDATE regions SET id = 24 WHERE id = 10 ");
        DB::statement(" UPDATE regions SET id = 25 WHERE id = 11 ");
        DB::statement(" UPDATE regions SET id = 26 WHERE id = 12 ");
        DB::statement(" UPDATE regions SET id = 27 WHERE id = 13 ");
        DB::statement(" UPDATE regions SET id = 28 WHERE id = 14 ");
        DB::statement(" UPDATE regions SET id = 29 WHERE id = 15 ");
        DB::statement(" UPDATE regions SET id = 30 WHERE id = 16 ");

        DB::statement(" UPDATE regions SET id = 13 WHERE id = 20 ");
        DB::statement(" UPDATE regions SET id = 6 WHERE id = 21 ");
        DB::statement(" UPDATE regions SET id = 7 WHERE id = 22 ");
        DB::statement(" UPDATE regions SET id = 16 WHERE id = 23 ");
        DB::statement(" UPDATE regions SET id = 8 WHERE id = 24 ");
        DB::statement(" UPDATE regions SET id = 9 WHERE id = 25 ");
        DB::statement(" UPDATE regions SET id = 14 WHERE id = 26 ");
        DB::statement(" UPDATE regions SET id = 10 WHERE id = 27 ");
        DB::statement(" UPDATE regions SET id = 11 WHERE id = 28 ");
        DB::statement(" UPDATE regions SET id = 12 WHERE id = 29 ");
        DB::statement(" UPDATE regions SET id = 15 WHERE id = 30 ");


        Schema::table('demographics', function (Blueprint $table) {
            $table->dropForeign(['region_id']);
        });

        Schema::table('communes', function (Blueprint $table) {
            $table->dropForeign(['region_id']);
        });

        Schema::table('demographics', function (Blueprint $table) {
            $table->foreign('region_id')->references('id')->on('regions')->onUpdate('restrict');
        });

        Schema::table('communes', function (Blueprint $table) {
            $table->foreign('region_id')->references('id')->on('regions')->onUpdate('restrict');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('demographics', function (Blueprint $table) {
            $table->dropForeign(['region_id']);
        });

        Schema::table('communes', function (Blueprint $table) {
            $table->dropForeign(['region_id']);
        });

        Schema::table('demographics', function (Blueprint $table) {
            $table->foreign('region_id')->references('id')->on('regions')->onUpdate('cascade');
        });

        Schema::table('communes', function (Blueprint $table) {
            $table->foreign('region_id')->references('id')->on('regions')->onUpdate('cascade');
        });

        DB::statement(" UPDATE regions SET id = 20 WHERE id = 13");
        DB::statement(" UPDATE regions SET id = 21 WHERE id = 6 ");
        DB::statement(" UPDATE regions SET id = 22 WHERE id = 7 ");
        DB::statement(" UPDATE regions SET id = 23 WHERE id = 16");
        DB::statement(" UPDATE regions SET id = 24 WHERE id = 8  ");
        DB::statement(" UPDATE regions SET id = 25 WHERE id = 9  ");
        DB::statement(" UPDATE regions SET id = 26 WHERE id = 14 ");
        DB::statement(" UPDATE regions SET id = 27 WHERE id = 10 ");
        DB::statement(" UPDATE regions SET id = 28 WHERE id = 11 ");
        DB::statement(" UPDATE regions SET id = 29 WHERE id = 12 ");
        DB::statement(" UPDATE regions SET id = 30 WHERE id = 15 ");

        DB::statement(" UPDATE regions SET id = 6 WHERE id = 20 ");
        DB::statement(" UPDATE regions SET id = 7 WHERE id = 21 ");
        DB::statement(" UPDATE regions SET id = 8 WHERE id = 22 ");
        DB::statement(" UPDATE regions SET id = 9 WHERE id = 23 ");
        DB::statement(" UPDATE regions SET id = 10 WHERE id = 24 ");
        DB::statement(" UPDATE regions SET id = 11 WHERE id = 25 ");
        DB::statement(" UPDATE regions SET id = 12 WHERE id = 26 ");
        DB::statement(" UPDATE regions SET id = 13 WHERE id = 27 ");
        DB::statement(" UPDATE regions SET id = 14 WHERE id = 28 ");
        DB::statement(" UPDATE regions SET id = 15 WHERE id = 29 ");
        DB::statement(" UPDATE regions SET id = 16 WHERE id = 30 ");

        Schema::table('demographics', function (Blueprint $table) {
            $table->dropForeign(['region_id']);
        });

        Schema::table('communes', function (Blueprint $table) {
            $table->dropForeign(['region_id']);
        });

        Schema::table('demographics', function (Blueprint $table) {
            $table->foreign('region_id')->references('id')->on('regions')->onUpdate('restrict');
        });

        Schema::table('communes', function (Blueprint $table) {
            $table->foreign('region_id')->references('id')->on('regions')->onUpdate('restrict');
        });

    }
}
