<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSequencingCriteriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sequencing_criterias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('suspect_case_id');
            $table->date('send_at')->nullable();;
            $table->string('critery')->nullable();
            $table->date('symptoms_at')->nullable();
            $table->string('vaccination')->nullable();
            $table->date('last_dose_at')->nullable();
            $table->string('hospitalization_status')->nullable();
            $table->boolean('upc')->nullable();
            $table->boolean('fever')->nullable();
            $table->boolean('throat_pain')->nullable();
            $table->boolean('myalgia')->nullable();
            $table->boolean('pneumonia')->nullable();
            $table->boolean('encephalitis')->nullable();
            $table->boolean('cough')->nullable();
            $table->boolean('rhinorrhea')->nullable();
            $table->boolean('respiratory_distress')->nullable();
            $table->boolean('hypotension')->nullable();
            $table->boolean('headache')->nullable();
            $table->boolean('tachypnea')->nullable();
            $table->boolean('hypoxia')->nullable();
            $table->boolean('cyanosis')->nullable();
            $table->boolean('food_refusal')->nullable();
            $table->boolean('hemodynamic_compromise')->nullable();
            $table->boolean('respiratory_condition_deterioration')->nullable();
            $table->boolean('ageusia')->nullable();
            $table->boolean('anosmia')->nullable();
            $table->string('underlying_disease')->nullable();
            $table->text('diagnosis')->nullable();
            
            

            $table->timestamps();
            $table->softDeletes();


            $table->foreign('suspect_case_id')->references('id')->on('suspect_cases');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sequencing_criterias');
    }
}
