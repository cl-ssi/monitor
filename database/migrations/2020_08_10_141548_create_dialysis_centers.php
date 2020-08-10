<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDialysisCenters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dialysis_centers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('commune_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('commune_id')->references('id')->on('communes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dialysis_centers');
    }
}
