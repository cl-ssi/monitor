<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->integer('floor')->nullable();
            $table->text('description')->nullable();
            $table->string('type')->nullable();
            $table->integer('single')->nullable();
            $table->integer('double')->nullable();
            
            $table->unsignedBigInteger('residence_id');
            
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('residence_id')->references('id')->on('residences');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rooms');
    }
}
