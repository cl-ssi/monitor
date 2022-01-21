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
            $table->text('full_message')->nullable();
            $table->string('message_id')->nullable();
            $table->string('patient_names')->nullable();
            $table->string('patient_family_father')->nullable();
            $table->string('patient_family_mother')->nullable();
            $table->string('observation_datetime')->nullable();
            $table->string('observation_value')->nullable();
            $table->string('sample_observation_datetime')->nullable();
            $table->string('url')->nullable();
            $table->enum('status', ['pending', 'assigned_to_case', 'case_not_found', 'too_many_cases'])->nullable();
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
