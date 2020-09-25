<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModTracingEventsAddContactType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tracing_events', function (Blueprint $table){
            $table->enum('contact_type',['llamada', 'visita'])->after('event_at')->nullable();

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
            $table->dropColumn('contact_type');
        });

    }
}
