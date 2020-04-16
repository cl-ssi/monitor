<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->datetime('from')->nullable();
            $table->datetime('to')->nullable();
            $table->datetime('real_to')->nullable();
            $table->text('indications')->nullable();
            $table->text('observations')->nullable();

            // criterio de ingreso, prevision, familiar responzable, parentesco,
            // médico derivador, diagnostivo, antecedentes mórbidos,
            // indicaion tiempo estadía en residencia
            // fecha inicio sintomas, fecha termino sintomas
            // alergias, farmacos de uso comun

            $table->string('entry_criteria')->nullable();
            $table->string('prevision')->nullable();
            $table->string('responsible_family_member')->nullable();
            $table->string('relationship')->nullable();
            $table->string('doctor')->nullable();
            $table->string('diagnostic')->nullable();
            $table->text('morbid_history')->nullable();
            $table->integer('length_of_stay')->nullable();
            $table->date('onset_on_symptoms')->nullable();
            $table->date('end_of_symptoms')->nullable();
            $table->string('allergies')->nullable();
            $table->string('commonly_used_drugs')->nullable();

            $table->string('status')->nullable(); 
            /* Fallecido, Alta, Hospitalizado, Fugado, Residencia */

            $table->string('healthcare_centre')->nullable(); //centro de salud
            $table->boolean('influenza_vaccinated')->default(0); //vacunado influenza
            $table->boolean('covid_exit_test')->default(0); //examen covid al egreso
            $table->string('released_cause')->nullable(); //causal de alta

            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('room_id');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('patient_id')->references('id')->on('patients');
            $table->foreign('room_id')->references('id')->on('rooms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
