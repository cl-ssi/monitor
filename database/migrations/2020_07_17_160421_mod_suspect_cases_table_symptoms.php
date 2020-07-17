<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModSuspectCasesTableSymptoms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(" UPDATE suspect_cases SET symptoms = '1' WHERE symptoms = 'Si' ");
        DB::statement(" UPDATE suspect_cases SET symptoms = '0' WHERE symptoms = 'No' ");
        Schema::table('suspect_cases', function (Blueprint $table){
            $table->boolean('symptoms')->nullable()->change();
            $table->dropColumn('status');
            $table->dropColumn('discharge_test');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('suspect_cases', function (Blueprint $table){
            $table->string('symptoms')->nullable()->change();
            $table->string('status')->nullable()->after('origin');
            $table->boolean('discharge_test')->nullable()->after('observation');
        });

        DB::statement(" UPDATE suspect_cases SET symptoms = 'Si' WHERE symptoms = '1' ");
        DB::statement(" UPDATE suspect_cases SET symptoms = 'No' WHERE symptoms = '0' ");
    }
}
