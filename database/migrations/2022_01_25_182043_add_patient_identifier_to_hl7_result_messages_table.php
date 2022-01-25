<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPatientIdentifierToHl7ResultMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hl7_result_messages', function (Blueprint $table) {
            $table->string('patient_identifier')->nullable()->after('message_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hl7_result_messages', function (Blueprint $table) {
            $table->dropColumn('patient_identifier');
            //
        });
    }
}
