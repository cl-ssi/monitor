<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('run')->nullable();
            $table->char('dv',1)->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');            
            $table->rememberToken();
            $table->string('telephone')->nullable();
            $table->string('function')->nullable();

            $table->unsignedBigInteger('laboratory_id')->nullable()->default(NULL);
            // $table->unsignedBigInteger('establishment_id')->nullable()->default(NULL);
            $table->timestamps();

            $table->foreign('laboratory_id')->references('id')->on('laboratories');
            // $table->foreign('establishment_id')->references('id')->on('establishments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
