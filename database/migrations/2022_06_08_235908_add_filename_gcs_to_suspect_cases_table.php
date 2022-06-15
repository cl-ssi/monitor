<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFilenameGcsToSuspectCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suspect_cases', function (Blueprint $table) {
            $table->uuid('filename_gcs')->after('hl7_result_message_id')->nullable();
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
            $table->dropColumn('filename_gcs');
        });
    }
}
