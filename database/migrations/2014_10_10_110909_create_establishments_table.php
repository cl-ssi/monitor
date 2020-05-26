<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstablishmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('regions', function (Blueprint $table) {
          $table->increments('id');
          $table->string('name');

          $table->timestamps();
          $table->softDeletes();
      });

      Schema::create('communes', function (Blueprint $table) {
          $table->id('id');
          $table->string('name');
          $table->string('code_deis');
          $table->unsignedInteger('region_id');

          $table->foreign('region_id')->references('id')->on('regions');

          $table->timestamps();
          $table->softDeletes();
      });

      Schema::create('establishments', function (Blueprint $table) {
            $table->id('id');
            $table->string('name');
            $table->string('alias');
            $table->string('type');
            $table->string('old_code_deis');
            $table->string('new_code_deis');
            $table->string('service');
            $table->string('dependency');
            // $table->string('commune');
            // $table->string('commune_code_deis');

            $table->unsignedInteger('commune_id');

            $table->foreign('commune_id')->references('id')->on('communes');

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
        Schema::dropIfExists('establishments');
        Schema::dropIfExists('communes');
        Schema::dropIfExists('regions');
    }
}
