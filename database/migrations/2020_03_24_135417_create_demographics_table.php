<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemographicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demographics', function (Blueprint $table) {
            $table->id();

            $table->enum('street_type',['Calle', 'Pasaje', 'Avenida', 'Camino']);
            $table->string('address')->nullable();
            $table->string('number')->nullable();
            $table->string('department')->nullable();
            $table->string('suburb')->nullable();

            $table->unsignedBigInteger('region_id')->nullable();
            //$table->string('region')->nullable();

            $table->unsignedBigInteger('commune_id')->nullable();
            //$table->string('commune')->nullable();

            $table->string('city')->nullable();

            $table->string('nationality')->nullable();

            $table->decimal('latitude', 11, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            $table->string('telephone')->nullable();
            $table->string('telephone2')->nullable();
            $table->string('email')->nullable();
            $table->unsignedBigInteger('patient_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('region_id')->references('id')->on('regions');
            $table->foreign('commune_id')->references('id')->on('communes');
            $table->foreign('patient_id')->references('id')->on('patients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('demographics');
    }
}
