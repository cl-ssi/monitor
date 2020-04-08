<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrmDispatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frm_dispatches', function (Blueprint $table) {
          //cabecera
          $table->bigIncrements('id');
          $table->dateTime('date'); //fecha xfecha
          $table->BigInteger('pharmacy_id'); //origen
          $table->string('pharmacy');
          $table->BigInteger('establishment_id');
          $table->string('establishment');
          $table->longText('notes')->nullable(); //notas
          $table->Integer('user_id');
          $table->string('user');

          $table->timestamps();
          $table->softDeletes();
        });

        Schema::create('frm_dispatch_items', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->bigInteger('barcode')->nullable();
          $table->unsignedBigInteger('dispatch_id');
          $table->unsignedBigInteger('product_id');
          $table->string('product');
          $table->double('amount', 8, 2); //cantidad
          $table->string('unity');
          $table->dateTime('due_date')->nullable(); //fecha vencimiento
          $table->longText('batch'); //lote

          $table->foreign('dispatch_id')->references('id')->on('frm_dispatches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('frm_dispatch_items');
      Schema::dropIfExists('frm_dispatches');
    }
}
