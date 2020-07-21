<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModSuspectCasesTablePscrSarsCov2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suspect_cases', function (Blueprint $table){
           $table->renameColumn('pscr_sars_cov_2_at', 'pcr_sars_cov_2_at');
           $table->renameColumn('pscr_sars_cov_2', 'pcr_sars_cov_2');
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
            $table->renameColumn('pcr_sars_cov_2_at', 'pscr_sars_cov_2_at');
            $table->renameColumn('pcr_sars_cov_2', 'pscr_sars_cov_2');
        });
    }
}
