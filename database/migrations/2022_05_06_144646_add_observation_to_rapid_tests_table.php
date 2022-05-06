<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddObservationToRapidTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rapid_tests', function (Blueprint $table) {
            //
            $table->text('observation')->nullable()->after('value_test');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rapid_tests', function (Blueprint $table) {
            //
            $table->dropColumn('observation');
        });
    }
}
