<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModTracingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tracings', function (Blueprint $table) {
            $table->datetime('notification_at')->after('functionary')->nullable();
            $table->string('notification_mechanism')->after('functionary')->nullable();
            $table->datetime('discharged_at')->after('status')->nullable();
            $table->text('chronic_diseases')->after('morbid_history')->nullable();
            $table->string('cannot_quarantine')->after('quarantine_end_at')->nullable();
        });

        Schema::table('tracing_events', function (Blueprint $table) {
            $table->text('symptoms')->after('details')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tracings', function (Blueprint $table) {
            $table->dropColumn('notification_at');
            $table->dropColumn('notification_mechanism');
            $table->dropColumn('discharged_at');
            $table->dropColumn('chronic_diseases');
            $table->dropColumn('cannot_quarantine');
        });
        Schema::table('tracing_events', function (Blueprint $table) {
            $table->dropColumn('symptoms');
        });
    }
}
