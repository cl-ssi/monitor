<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataToSequencingCriteriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sequencing_criterias', function (Blueprint $table) {
            // llaman local suspicion, result_isp
            $table->string('local_suspicion')->after('underlying_disease')->nullable();
            $table->string('result_isp')->after('local_suspicion')->nullable();
            


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sequencing_criterias', function (Blueprint $table) {
            //
            $table->dropColumn('local_suspicion');
            $table->dropColumn('result_isp');
        });
    }
}
