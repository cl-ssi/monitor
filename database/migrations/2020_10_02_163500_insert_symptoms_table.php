<?php

use App\Tracing\Symptom;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertSymptomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Symptom::create(['id'=>13, 'name' => 'Cianosis']);
        Symptom::create(['id'=>14, 'name' => 'Dolor abdominal']);
        Symptom::create(['id'=>15, 'name' => 'Postracion']);
        Symptom::create(['id'=>16, 'name' => 'Taquipnea']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Symptom::destroy(13, 14, 15, 16);
    }
}
