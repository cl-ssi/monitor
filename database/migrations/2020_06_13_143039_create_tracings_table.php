<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTracingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('patient_id');
            $table->boolean('index')->nullable();

            $table->boolean('functionary')->nullable();

            $table->datetime('next_control_at')->nullable();
            $table->unsignedSmallInteger('status')->nullable();

            $table->string('category')->nullable();

            $table->string('responsible_family_member')->nullable();

            $table->string('prevision')->nullable();
            $table->foreignId('establishment_id')->nullable();

            $table->boolean('gestation',2)->nullable();
            $table->smallInteger('gestation_week')->nullable();

            $table->boolean('symptoms')->nullable();
            $table->datetime('symptoms_start_at')->nullable();
            $table->datetime('symptoms_end_at')->nullable();

            $table->date('quarantine_start_at')->nullable();
            $table->date('quarantine_end_at')->nullable();

            $table->text('allergies')->nullable();
            $table->text('common_use_drugs')->nullable();
            $table->text('morbid_history')->nullable();
            $table->text('family_history')->nullable();

            $table->text('indications')->nullable();
            $table->string('observations')->nullable();

            $table->boolean('help_basket')->nullable();
            $table->boolean('psychological_intervention')->nullable();
            $table->boolean('requires_hospitalization')->nullable();
            $table->boolean('requires_licence')->nullable();

            $table->foreignId('user_id')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('patient_id')->references('id')->on('patients');
            $table->foreign('establishment_id')->references('id')->on('establishments');
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
        Schema::dropIfExists('tracings');
    }
}
