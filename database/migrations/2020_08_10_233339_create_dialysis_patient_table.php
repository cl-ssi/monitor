<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDialysisPatientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dialysis_patient', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id');
            $table->foreignId('dialysis_center_id');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('patient_id')->references('id')->on('patients');
            $table->foreign('dialysis_center_id')->references('id')->on('dialysis_centers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dialysis_patient');
    }
}
