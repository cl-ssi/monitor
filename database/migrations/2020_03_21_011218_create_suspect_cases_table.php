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
            $table->smallInteger('age')->nullable();
            $table->enum('gender',['male', 'female', 'other', 'unknown']);
            $table->datetime('sample_at')->nullable();
            $table->unsignedSmallInteger('epidemiological_week')->nullable();

            $table->string('origin')->nullable(); /* Hospital, Clinica Tarapacá, Clinica Iquique, Hector Reyno, Guzmán */
            $table->string('status')->nullable(); /* Fallecido, Alta, Hospitalizado, Fugado */

            $table->datetime('result_ifd_at')->nullable();
            $table->string('result_ifd')->nullable();
            $table->string('subtype')->nullable();

            $table->datetime('pscr_sars_cov_2_at')->nullable();
            $table->string('pscr_sars_cov_2')->nullable();
            $table->string('sample_type')->nullable();
            $table->unsignedBigInteger('validator_id')->nullable();

            $table->datetime('sent_isp_at')->nullable();
            $table->string('external_laboratory')->nullable();

            $table->unsignedInteger('paho_flu')->nullable();
            $table->unsignedInteger('epivigila')->nullable();
            $table->char('gestation',2)->nullable();
            $table->smallInteger('gestation_week')->nullable();

            $table->string('observation')->nullable();

            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('laboratory_id')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('validator_id')->references('id')->on('users');
            $table->foreign('patient_id')->references('id')->on('patients');
            $table->foreign('laboratory_id')->references('id')->on('laboratories');
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
