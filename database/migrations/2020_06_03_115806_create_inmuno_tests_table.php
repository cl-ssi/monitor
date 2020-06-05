<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInmunoTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inmuno_tests', function (Blueprint $table) {
            $table->id();
            $table->datetime('register_at')->nullable();
            $table->enum('igg_value',['positive', 'negative', 'weak']);
            $table->enum('igm_value',['positive', 'negative', 'weak']);
            $table->enum('control',['yes', 'no']);
            $table->foreignId('patient_id');
            $table->foreignId('user_id');

            $table->softDeletes();
            $table->timestamps();

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
        Schema::dropIfExists('inmuno_tests');
    }
}
