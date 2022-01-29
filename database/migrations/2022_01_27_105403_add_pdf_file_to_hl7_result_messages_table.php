<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddPdfFileToHl7ResultMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hl7_result_messages', function (Blueprint $table) {
            $table->binary('pdf_file')->after('url')->nullable();

        });
        DB::statement("ALTER TABLE hl7_result_messages modify column pdf_file MEDIUMBLOB");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hl7_result_messages', function (Blueprint $table) {
            $table->dropColumn('pdf_file');
        });
    }
}
