<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWsTimestampToSuspectCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suspect_cases', function (Blueprint $table) {
            //
            $table->dateTime('ws_crea_muestra_added_at')->after('minsal_ws_id')->nullable();
            $table->dateTime('ws_recepciona_muestra_added_at')->after('ws_crea_muestra_added_at')->nullable();
            $table->dateTime('ws_resultado_muestra_added_at')->after('ws_recepciona_muestra_added_at')->nullable();
            $table->dateTime('ws_cambia_laboratorio_added_at')->after('ws_resultado_muestra_added_at')->nullable();
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
            //
            $table->dropColumn('ws_crea_muestra_added_at');
            $table->dropColumn('recepciona_muestra');
            $table->dropColumn('resultado_muestra');
            $table->dropColumn('cambia_laboratorio');
        });
    }
}
