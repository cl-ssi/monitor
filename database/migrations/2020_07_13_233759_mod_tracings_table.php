<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModTracingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tracings', function (Blueprint $table) {
            /* Primero drop de los campos antiguos */
            $table->dropColumn('help_basket');
            $table->dropColumn('psychological_intervention');
            $table->dropColumn('requires_hospitalization');
            $table->dropColumn('requires_licence');

            /* Nuevos campos */
            $table->unsignedSmallInteger('risk_rating')->after('status')->nullable();
            $table->string('employer_name')->after('observations')->nullable();
            $table->date('last_day_worked')->after('employer_name')->nullable();
            $table->string('employer_contact')->after('last_day_worked')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tracings', function (Blueprint $table) {
            /* Se crean las 4 columnas eliminadas */
            $table->boolean('help_basket')->nullable();
            $table->boolean('psychological_intervention')->nullable();
            $table->boolean('requires_hospitalization')->nullable();
            $table->boolean('requires_licence')->nullable();

            /* Eliminar las nuevas */
            $table->dropColumn('risk_rating');
            $table->dropColumn('employer_name');
            $table->dropColumn('last_day_worked');
            $table->dropColumn('employer_contact');
        });
    }
}
