<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReasonSuspectCases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('suspect_cases', function (Blueprint $table) {
          $table->string('reason', 250)->nullable();
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
        $table->dropColumn(['reason']);
      });
    }
}
