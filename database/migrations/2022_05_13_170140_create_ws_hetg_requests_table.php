<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWsHetgRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ws_hetg_requests', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->text('sent_data');
            $table->text('response_data');
            $table->text('token');
            $table->string('status');
            $table->foreignId('suspect_case_id')->nullable()->constrained('suspect_cases');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ws_hetg_requests');
    }
}
