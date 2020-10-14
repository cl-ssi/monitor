<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModTracingEventsAddQuarantineFullfilled extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tracing_events', function (Blueprint $table){
            $table->boolean('sars_cov_2_result')->after('symptoms')->nullable();
            $table->boolean('quarantine_fulfilled')->after('symptoms')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tracing_events', function (Blueprint $table) {
            $table->dropColumn('quarantine_fulfilled');
            $table->dropColumn('sars_cov_2_result');
        });
    }
}
