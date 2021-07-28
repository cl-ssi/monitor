<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModSuspectCasesTableCtCanditSequency extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suspect_cases', function (Blueprint $table) {
            //
            $table->double('ct',3,1)->after('ws_pntm_mass_sending')->nullable();
            $table->boolean('candidate_for_sq')->after('ct')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('suspect_cases', function (Blueprint $table) {
            //
            $table->dropColumn('ct');
            $table->dropColumn('candidate_for_sq');
            
        });
    }
}
