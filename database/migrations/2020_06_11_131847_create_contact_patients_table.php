<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactPatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id');
            $table->foreignId('contact_id');
            $table->datetime('last_contact_at')->nullable();
            $table->longText('comment')->nullable();
            $table->string('relationship')->nullable();
            $table->boolean('live_together')->nullable();
            $table->datetime('notification_contact_at')->nullable();
            $table->integer('index')->nullable();
            $table->foreignId('user_id');

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('patient_id')->references('id')->on('patients');
            $table->foreign('contact_id')->references('id')->on('patients');
            $table->foreign('user_id')->references('id')->on('users');

            // $table->primary(array('patient_id', 'contact_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contact_patients');
    }
}
