<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdmissionSurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admission_surveys', function (Blueprint $table) {
            $table->id();
            $table->integer('people')->nullable();
            $table->integer('rooms')->nullable();
            $table->boolean('residency')->nullable();
            $table->text('observations')->nullable();



            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('user_id');

            
            $table->foreign('patient_id')->references('id')->on('patients');
            $table->foreign('user_id')->references('id')->on('users');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admission_surveys');
    }
}
