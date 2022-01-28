<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHl7ErrorMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hl7_error_messages', function (Blueprint $table) {
            $table->id();
            $table->string('alert_id')->nullable();
            $table->string('channel_name')->nullable();
            $table->string('connector_name')->nullable();
            $table->string('message_id')->nullable();
            $table->text('error')->nullable();
            $table->text('error_message')->nullable();
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
        Schema::dropIfExists('hl7_error_messages');
    }
}
