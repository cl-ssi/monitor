<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTracingEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracing_events', function (Blueprint $table) {
            $table->id();
            $table->datetime('event_at');
            $table->foreignId('event_type_id');
            $table->text('details')->nullable();
            $table->foreignId('tracing_id');
            $table->foreignId('user_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tracing_id')->references('id')->on('tracings');
            $table->foreign('event_type_id')->references('id')->on('tracing_event_types');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tracing_events');
    }
}
