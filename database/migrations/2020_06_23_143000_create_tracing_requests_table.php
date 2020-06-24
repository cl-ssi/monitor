<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTracingRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracing_requests', function (Blueprint $table) {
            $table->id();
            $table->datetime('request_at');
            $table->foreignId('request_type_id');
            $table->text('details')->nullable();
            $table->datetime('validity_at')->nullable();
            $table->foreignId('tracing_id');
            $table->foreignId('user_id'); //USUARIO QUE REGISTRA
            $table->datetime('request_complete_at')->nullable();
            $table->boolean('rejection')->nullable();
            $table->text('rejection_details')->nullable();
            $table->foreignId('user_complete_request_id')->nullable(); //USUARIO QUE COMPLETA EL REGISTRO

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tracing_id')->references('id')->on('tracings');
            $table->foreign('request_type_id')->references('id')->on('tracing_request_types');
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
        Schema::dropIfExists('tracing_requests');
    }
}
