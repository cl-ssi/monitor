<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendingPatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pending_patients', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('run')->nullable();
            $table->char('dv')->nullable();
            $table->string('other_identification')->nullable();
            $table->string('file_number')->nullable();
            $table->string('name')->nullable();
            $table->string('fathers_family')->nullable();
            $table->string('mothers_family')->nullable();
            $table->string('address')->nullable();
            $table->unsignedBigInteger('region_id')->nullable();
            $table->unsignedBigInteger('commune_id')->nullable();
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->enum('status',['not_contacted', 'updated_information', 'contacted']);
            $table->enum('reason',['ges', 'le', 'control', 'procedure']);
            $table->string('appointment_with')->nullable();
            $table->dateTime('appointment_at')->nullable();
            $table->string('responsible_name')->nullable();
            $table->string('responsible_run')->nullable();
            $table->string('responsible_phone')->nullable();
            $table->string('visit_observation')->nullable();
            $table->dateTime('visit_delivery_at')->nullable();
            $table->string('visit_appointment_functionary')->nullable();
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
        Schema::dropIfExists('pending_patients');
    }
}
