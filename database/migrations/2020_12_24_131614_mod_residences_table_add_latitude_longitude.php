<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModResidencesTableAddLatitudeLongitude extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('residences', function (Blueprint $table) {
            //
            $table->decimal('latitude', 11, 8)->after('height')->nullable();
            $table->decimal('longitude', 11, 8)->after('latitude')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('residences', function (Blueprint $table) {
            //
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
        });
    }
}
