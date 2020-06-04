<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHelpBasketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('help_baskets', function (Blueprint $table) {
            $table->increments('id');
            //se pone ahora nullable por si se borra con soft deleted hay error
            $table->integer('run')->nullable();
            $table->char('dv',1)->nullable();

            $table->string('other_identification')->nullable();

            $table->string('name');
            $table->string('fathers_family');
            $table->string('mothers_family')->nullable();

            $table->enum('street_type',['Calle', 'Pasaje', 'Avenida', 'Camino']);
            $table->string('address')->nullable();
            $table->string('number')->nullable();
            $table->string('department')->nullable();

            $table->string('telephone')->nullable();

            $table->decimal('latitude', 11, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            $table->string('photo')->nullable();
            $table->string('photoid')->nullable();
            $table->text('observations')->nullable();

            $table->unsignedBigInteger('commune_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();



            $table->timestamps();
            $table->softDeletes();

            $table->foreign('commune_id')->references('id')->on('communes');
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
        Schema::dropIfExists('help_baskets');
    }
}
