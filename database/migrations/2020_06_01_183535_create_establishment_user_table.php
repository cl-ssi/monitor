<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstablishmentUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('establishment_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('establishment_id');
            $table->foreignId('user_id');

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('establishment_id')->references('id')->on('establishments');
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
        Schema::dropIfExists('establishment_user');
    }
}
