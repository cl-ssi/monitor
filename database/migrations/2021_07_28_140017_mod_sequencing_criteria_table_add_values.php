<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModSequencingCriteriaTableAddValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sequencing_criterias', function (Blueprint $table) {
            //
            $table->string('type_of_vaccine')->after('vaccination')->nullable();
            $table->boolean('diarrhea')->after('anosmia')->nullable();
            $table->boolean('nasal_congestion')->after('diarrhea')->nullable();
            $table->boolean('sickness')->after('nasal_congestion')->nullable();
            $table->boolean('fatigue')->after('sickness')->nullable();
            $table->boolean('vomit')->after('fatigue')->nullable();
            $table->boolean('chest_pain')->after('vomit')->nullable();
            $table->boolean('anorexy')->after('chest_pain')->nullable();
            $table->boolean('asymptomatic')->after('anorexy')->nullable();          
            

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sequencing_criterias', function (Blueprint $table) {
            //
            $table->dropColumn('type_of_vaccine');
            $table->dropColumn('diarrhea');
            $table->dropColumn('nasal_congestion');
            $table->dropColumn('sickness');
            $table->dropColumn('fatigue');
            $table->dropColumn('vomit');
            $table->dropColumn('chest_pain');
            $table->dropColumn('anorexy');
            $table->dropColumn('asymptomatic');       
            
        });
    }
}
