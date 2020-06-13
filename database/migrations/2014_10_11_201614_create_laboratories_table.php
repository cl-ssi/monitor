<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaboratoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laboratories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('alias');
            $table->boolean('external')->default(0);
            $table->boolean('minsal_ws')->default(0);
            $table->string('token_ws')->nullable();
            $table->boolean('pdf_generate')->default(0);
            $table->string('cod_deis')->nullable();
            $table->foreignId('commune_id')->nullable();
            $table->foreignId('director_id')->nullable();
            $table->timestamps();

            $table->foreign('commune_id')->references('id')->on('communes');
            $table->foreign('director_id')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laboratories');
    }
}
