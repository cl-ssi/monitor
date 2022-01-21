<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHl7ResultMessageIdToSuspectCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suspect_cases', function (Blueprint $table) {
            $table->foreignId('hl7_result_message_id')->nullable()->after('candidate_for_sq');
            $table->foreign('hl7_result_message_id')->references('id')->on('hl7_result_messages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('suspect_cases', function (Blueprint $table) {
            $table->dropForeign('hl7_result_message_id');
            $table->dropColumn('hl7_result_message_id');
        });
    }
}
