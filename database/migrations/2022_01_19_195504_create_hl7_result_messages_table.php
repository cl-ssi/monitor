<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHl7ResultMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hl7_result_messages', function (Blueprint $table) {
            $table->id();
            $table->text('full_message');
            $table->string('message_id');
            $table->string('patient_names');
            $table->string('patient_family_father');
            $table->string('patient_family_mother');
            $table->string('observation_datetime');
            $table->string('observation_value');
            $table->string('sample_observation_datetime');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hl7_result_messages');
    }
}
