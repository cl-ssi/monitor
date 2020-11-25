<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModPendingPatientsTableAdd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('specialties', function (Blueprint $table){
            $table->id();
            $table->string('name')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('pending_patients', function (Blueprint $table){
            $table->foreignId('appointment_specialty')->after('appointment_with')->nullable();
            $table->string('appointment_location')->after('appointment_at')->nullable();
            $table->foreign('appointment_specialty')->references('id')->on('specialties');
        });
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pending_patients', function (Blueprint $table){
            $table->dropColumn('appointment_specialty');
            $table->dropColumn('appointment_location');
        });

        Schema::dropIfExists('specialties');
    }
}
