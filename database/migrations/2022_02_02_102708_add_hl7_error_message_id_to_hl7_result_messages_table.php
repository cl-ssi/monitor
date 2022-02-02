<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddHl7ErrorMessageIdToHl7ResultMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hl7_result_messages', function (Blueprint $table) {
            $table->foreignId('hl7_error_message_id')->nullable()->after('status');
            $table->foreign('hl7_error_message_id')->references('id')->on('hl7_error_messages');
            
            DB::statement("ALTER TABLE hl7_result_messages MODIFY COLUMN status ENUM('pending', 'assigned_to_case', 'case_not_found', 'too_many_cases', 'monitor_error')");
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
            $table->dropForeign('hl7_error_message_id');
            $table->dropconst('hl7_error_message_id');

            DB::statement("ALTER TABLE hl7_result_messages MODIFY COLUMN status ENUM('pending', 'assigned_to_case', 'case_not_found', 'too_many_cases')");
        });
    }
}
