<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCovid19sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('covid19s', function (Blueprint $table) {
            $table->id();

            $table->integer('run')->nullable();
            $table->char('dv')->nullable();

            $table->string('other_identification')->nullable();

            $table->string('name');
            $table->string('fathers_family');
            $table->string('mothers_family')->nullable();

            $table->string('gender')->nullable();
            $table->date('birthday')->nullable();

            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('commune')->nullable();

            $table->string('origin');
            $table->string('origin_commune');

            $table->string('sample_type');
            $table->datetime('sample_at');

            $table->datetime('reception_at')->nullable();
            $table->datetime('result_at')->nullable();
            $table->string('result')->nullable();

            $table->string('file')->nullable();

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('receptor_id')->nullable();
            $table->unsignedBigInteger('validator_id')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('receptor_id')->references('id')->on('users');
            $table->foreign('validator_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('covid19s');
    }
}
