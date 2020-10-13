<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModContactPatientsAdd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contact_patients', function (Blueprint $table) {
            $table->string('flight_name')->after('index')->nullable();
            $table->date('flight_date')->after('index')->nullable();
            $table->string('waiting_room_establishment')->after('index')->nullable();
            $table->string('social_meeting_place')->after('index')->nullable();
            $table->date('social_meeting_date')->after('index')->nullable();
            $table->string('company_name')->after('index')->nullable();
            $table->string('functionary_profession')->after('index')->nullable();
            $table->string('institution')->after('index')->nullable();
            $table->enum('mode_of_transport',['terrestre', 'aereo', 'maritimo'])->after('index')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contact_patients', function (Blueprint $table) {
            $table->dropColumn('mode_of_transport');
            $table->dropColumn('flight_name');
            $table->dropColumn('flight_date');
            $table->dropColumn('waiting_room_establishment');
            $table->dropColumn('social_meeting_place');
            $table->dropColumn('social_meeting_date');
            $table->dropColumn('company_name');
            $table->dropColumn('functionary_profession');
            $table->dropColumn('institution');
        });
    }
}
