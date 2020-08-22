<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModEstablishmentsAddAddressMailPhoneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('establishments', function (Blueprint $table) {
            //
            $table->string('address')->after('dependency')->nullable();
            $table->string('telephone')->after('address')->nullable();
            $table->string('email')->after('telephone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('establishments', function (Blueprint $table) {
            //
            $table->dropColumn('address');
            $table->dropColumn('telephone');
            $table->dropColumn('email');
        });
    }
}
