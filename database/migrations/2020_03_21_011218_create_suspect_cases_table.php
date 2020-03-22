<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuspectCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suspect_cases', function (Blueprint $table) {
            $table->id();
            $table->datetime('sample_at')->nullable();
            $table->string('origin')->nullable(); /* Hospital, Clinica Tarapacá, Clinica Iquique, Hector Reyno, Guzmán */

            $table->smallInteger('age')->nullable();
            $table->enum('gender',['male', 'female', 'other', 'unknown']);

            $table->string('result_ifd')->nullable();
            $table->string('subtype')->nullable();
            $table->unsignedSmallInteger('epidemiological_week')->nullable();
            $table->unsignedInteger('epivigila')->nullable();
            $table->enum('pscr_sars_cov_2',['negative','positive'])->nullable();
            $table->unsignedInteger('paho_flu')->nullable();
            $table->string('status')->nullable(); /* Fallecido, Alta, Hospitalizado, Fugado */
            $table->string('observation')->nullable();
            $table->unsignedBigInteger('patient_id');

            $table->foreign('patient_id')->references('id')->on('patients');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('suspect_cases');
    }
}
