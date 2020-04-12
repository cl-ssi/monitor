<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVitalSignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vital_signs', function (Blueprint $table) {
            $table->id();
            $table->string('temperature')->nullable();
            $table->string('heart_rate')->nullable();
            $table->string('blood_pressure')->nullable();
            $table->string('respiratory_rate')->nullable();
            $table->string('oxygen_saturation')->nullable();
            $table->string('hgt')->nullable();
            $table->string('pain_scale')->nullable();

            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('user_id');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('booking_id')->references('id')->on('bookings');
            $table->foreign('patient_id')->references('id')->on('patients');
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
        Schema::dropIfExists('vital_signs');
    }
}
